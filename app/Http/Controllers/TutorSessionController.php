<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Session;
use App\Models\Wallet;
use App\Models\Notification;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Message;
use App\Services\AchievementNotificationService;

class TutorSessionController extends Controller
{
    // Show all bookings for the tutor
    public function index()
    {
        try {
            $tutorId = Auth::guard('tutor')->id();
            
            if (!$tutorId) {
                return redirect()->route('login.tutor')->with('error', 'Please log in to view your bookings.');
            }

            // Get the tutor information
            $tutor = Auth::guard('tutor')->user();

            $bookings = Session::where('tutor_id', $tutorId)
                ->with('student')
                ->orderBy('date', 'desc')
                ->orderBy('start_time', 'desc')
                ->get();

            $pendingBookings = $bookings->where('status', 'pending');
            $acceptedBookings = $bookings->where('status', 'accepted');
            $rejectedBookings = $bookings->where('status', 'rejected');
            $completedBookings = $bookings->where('status', 'completed');

            return view('tutor.bookings.index', compact('tutor', 'pendingBookings', 'acceptedBookings', 'rejectedBookings', 'completedBookings'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while loading your bookings. Please try again.');
        }
    }

    // Show booking details
    public function show($id)
    {
        $tutor = Auth::guard('tutor')->user();
        
        $booking = Session::where('tutor_id', Auth::guard('tutor')->id())
            ->where('id', $id)
            ->with('student')
            ->firstOrFail();

        return view('tutor.bookings.show', compact('tutor', 'booking'));
    }

    // Accept booking
    public function accept(Request $request, $id)
    {
        $tutor = Auth::guard('tutor')->user();
        
        // Check if tutor is approved
        if ($tutor->registration_status !== 'approved') {
            return redirect()->route('tutor.bookings.index')
                ->with('error', 'Your account must be approved by an admin before you can accept bookings.');
        }

        $booking = Session::where('tutor_id', Auth::guard('tutor')->id())
            ->where('id', $id)
            ->where('status', 'pending')
            ->firstOrFail();

        DB::beginTransaction();
        try {
            // Update booking status
            $booking->update([
                'status' => 'accepted',
                'notes' => $request->input('notes', $booking->notes)
            ]);

            // Credit the tutor's wallet (70% of session rate)
            $tutorWallet = Wallet::where('user_id', $tutor->id)
                ->where('user_type', 'tutor')
                ->first();

            if (!$tutorWallet) {
                $tutorWallet = Wallet::create([
                    'user_id' => $tutor->id,
                    'user_type' => 'tutor',
                    'balance' => 0.00,
                    'currency' => 'PHP'
                ]);
            }

            $tutorEarnings = $booking->rate; // 100% to tutor
            $tutorWallet->addFunds($tutorEarnings, 'session_earnings', [
                'session_id' => $booking->id,
                'student_id' => $booking->student_id,
                'description' => 'Session booking accepted - Earnings',
            ]);

            // Create notification for tutor
            Notification::create([
                'user_id' => $tutor->id,
                'user_type' => 'tutor',
                'type' => 'payment_received',
                'title' => 'Payment Received',
                'message' => 'You received ₱' . number_format($tutorEarnings, 2) . ' for the accepted session booking.',
            ]);

            DB::commit();
            return redirect()->route('tutor.bookings.index')->with('success', 'Booking accepted successfully! Earnings of ₱' . number_format($tutorEarnings, 2) . ' have been added to your wallet.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('tutor.bookings.index')->with('error', 'Failed to accept booking: ' . $e->getMessage());
        }
    }

    // Reject booking
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $booking = Session::where('tutor_id', Auth::guard('tutor')->id())
            ->where('id', $id)
            ->where('status', 'pending')
            ->firstOrFail();

        $booking->update([
            'status' => 'rejected',
            'notes' => $request->input('rejection_reason')
        ]);

        return redirect()->route('tutor.bookings.index')->with('success', 'Booking rejected successfully!');
    }

    // Complete booking
    public function complete($id)
    {
        $booking = Session::where('tutor_id', Auth::guard('tutor')->id())
            ->where('id', $id)
            ->where('status', 'accepted')
            ->with('student')
            ->firstOrFail();

        $booking->update(['status' => 'completed']);

        // Check achievements for both tutor and student
        $achievementService = new AchievementNotificationService();
        $tutor = Auth::guard('tutor')->user();
        $student = $booking->student;
        
        // Check tutor achievements
        $achievementService->checkAndNotifyProgress($tutor, 'tutor', 'sessions_completed');
        
        // Check student achievements
        if ($student) {
            $achievementService->checkAndNotifyProgress($student, 'student', 'sessions_completed');
        }

        return redirect()->route('tutor.bookings.index')->with('success', 'Session marked as completed!');
    }

    // Cancel booking
    public function cancel(Request $request, $id)
    {
        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);

        $booking = Session::where('tutor_id', Auth::guard('tutor')->id())
            ->where('id', $id)
            ->whereIn('status', ['pending', 'accepted'])
            ->firstOrFail();

        $booking->update([
            'status' => 'cancelled',
            'notes' => $request->input('cancellation_reason')
        ]);

        return redirect()->route('tutor.bookings.index')->with('success', 'Booking cancelled successfully!');
    }

    // Get today's sessions for dashboard
    public function getTodaysSessions(Request $request)
    {
        try {
            $request->validate([
                'date' => 'required|date_format:Y-m-d',
            ]);

            $tutorId = Auth::guard('tutor')->id();
            
            if (!$tutorId) {
                return response()->json(['error' => 'Tutor not authenticated'], 401);
            }

            $sessions = Session::where('tutor_id', $tutorId)
                ->where('date', $request->date)
                ->where('status', 'accepted')
                ->with('student')
                ->orderBy('start_time')
                ->get();

            return response()->json($sessions);
        } catch (\Exception $e) {
            \Log::error('Error in getTodaysSessions: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while loading sessions.'], 500);
        }
    }

    // Get upcoming sessions for dashboard
    public function getUpcomingSessions()
    {
        $sessions = Session::where('tutor_id', Auth::guard('tutor')->id())
            ->where('date', '>=', today())
            ->whereIn('status', ['accepted', 'pending'])
            ->with('student')
            ->orderBy('date')
            ->orderBy('start_time')
            ->limit(5)
            ->get();

        return response()->json($sessions);
    }

    // Get pending bookings for notifications
    public function getPendingBookings()
    {
        try {
            $tutorId = Auth::guard('tutor')->id();
            
            if (!$tutorId) {
                return response()->json(['error' => 'Tutor not authenticated'], 401);
            }

            $bookings = Session::where('tutor_id', $tutorId)
                ->where('status', 'pending')
                ->with('student')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function($booking) {
                    return [
                        'id' => $booking->id,
                        'student_name' => $booking->student->first_name . ' ' . $booking->student->last_name,
                        'subject' => $booking->notes ? substr($booking->notes, 0, 50) : 'Session Request',
                        'date' => $booking->date->format('Y-m-d'),
                        'start_time' => $booking->start_time,
                        'session_type' => $booking->session_type,
                        'created_at' => $booking->created_at->diffForHumans(),
                    ];
                });

            return response()->json($bookings);
        } catch (\Exception $e) {
            \Log::error('Error in getPendingBookings: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while loading notifications.'], 500);
        }
    }

    public function messages()
    {
        return view('tutor.messages');
    }
} 