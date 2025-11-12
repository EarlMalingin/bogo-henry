<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tutor;
use App\Models\Session;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Message;
use App\Services\AchievementNotificationService;

class StudentSessionController extends Controller
{
    // Show all tutors and booking form
    public function index()
    {
        // Only show approved tutors that are active
        $tutors = Tutor::where('registration_status', 'approved')
            ->where('is_active', true)
            ->get();
        return view('student.book-session', compact('tutors'));
    }

    // Handle booking submission
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'tutor_id' => 'required|exists:tutors,id',
                'session_type' => 'required|in:face_to_face,online',
                'date' => 'required|date|after_or_equal:today',
                'start_time' => 'required',
                'end_time' => 'required|after:start_time',
                'notes' => 'nullable|string|max:500',
            ], [
                'end_time.after' => 'The end time must be after the start time.',
            ]);

            // Additional validation to ensure end_time is after start_time
            if ($request->start_time >= $request->end_time) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['end_time' => 'The end time must be after the start time.']);
            }

            // Get tutor to get their rate
            $tutor = Tutor::findOrFail($request->tutor_id);
            $sessionRate = $tutor->hourly_rate ?? $tutor->session_rate ?? 0;
            $studentId = Auth::guard('student')->id();

            if (!$studentId) {
                return redirect()->route('login.student')->with('error', 'Please log in to book a session.');
            }

            // Check if tutor is approved and active
            if ($tutor->registration_status !== 'approved') {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['error' => 'This tutor is not yet available for booking.']);
            }

            if (!$tutor->is_active) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['error' => 'This tutor is currently inactive.']);
            }

            // Get or create student's wallet
            $wallet = Wallet::where('user_id', $studentId)
                ->where('user_type', 'student')
                ->first();

            if (!$wallet) {
                $wallet = Wallet::create([
                    'user_id' => $studentId,
                    'user_type' => 'student',
                    'balance' => 0.00,
                    'currency' => 'PHP'
                ]);
            }

            // Check if student has sufficient balance
            if (!$wallet->canAfford($sessionRate)) {
                DB::rollBack();
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['error' => 'Insufficient balance. Please add funds to your wallet first.']);
            }

            // Deduct payment from wallet
            $transaction = $wallet->deductFunds($sessionRate, 'session_booking', [
                'tutor_id' => $tutor->id,
                'tutor_name' => $tutor->first_name . ' ' . $tutor->last_name,
                'session_type' => $request->session_type,
                'date' => $request->date,
            ]);

            if (!$transaction) {
                DB::rollBack();
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['error' => 'Failed to process payment. Please try again.']);
            }

            // Create session booking
            $session = Session::create([
                'student_id' => $studentId,
                'tutor_id' => $request->tutor_id,
                'session_type' => $request->session_type,
                'date' => $request->date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'notes' => $request->notes,
                'rate' => $sessionRate,
                'status' => 'pending',
            ]);

            // Check achievements for student
            $achievementService = new AchievementNotificationService();
            $student = Auth::guard('student')->user();
            $achievementService->checkAndNotifyProgress($student, 'student', 'sessions_booked');

            DB::commit();
            return redirect()->route('student.book-session')->with('success', 'Session booking request sent successfully! Payment of ₱' . number_format($sessionRate, 2) . ' has been deducted from your wallet.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'An error occurred while creating your booking. Please try again.']);
        }
    }

    // Get tutor details for modal
    public function getTutorDetails($id)
    {
        $tutor = Tutor::findOrFail($id);
        return response()->json($tutor);
    }

    // Get student's booking history
    public function myBookings()
    {
        $bookings = Session::where('student_id', Auth::guard('student')->id())
            ->with('tutor')
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        return view('student.my-bookings', compact('bookings'));
    }

    public function getUpcomingSessions()
    {
        try {
            $studentId = Auth::guard('student')->id();
            if (!$studentId) {
                return response()->json(['error' => 'Student not authenticated'], 401);
            }

            $sessions = Session::where('student_id', $studentId)
                ->where('status', 'accepted')
                ->where('date', '>=', now()->toDateString())
                ->with('tutor')
                ->orderBy('date', 'asc')
                ->orderBy('start_time', 'asc')
                ->limit(5)
                ->get();

            return response()->json($sessions);

        } catch (\Exception $e) {
            \Log::error('Error in getUpcomingSessions for student: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while loading upcoming sessions.'], 500);
        }
    }

    public function messages()
    {
        return view('student.chat.student-messages');
    }

    // Show student schedule with calendar
    public function schedule(Request $request)
    {
        $student = Auth::guard('student')->user();
        
        // Get the requested month/year or default to current
        $year = (int) $request->get('year', now()->year);
        $month = (int) $request->get('month', now()->month);
        
        // Validate month and year ranges
        if ($month < 1 || $month > 12) {
            $month = now()->month;
        }
        if ($year < 2020 || $year > 2030) {
            $year = now()->year;
        }
        
        // Handle month overflow/underflow
        if ($month > 12) {
            $year += 1;
            $month = 1;
        } elseif ($month < 1) {
            $year -= 1;
            $month = 12;
        }
        
        // Get all accepted sessions for the student in the requested month
        $sessions = Session::where('student_id', $student->id)
            ->where('status', 'accepted')
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->with(['tutor'])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();
        
        // Group sessions by date
        $sessionsByDate = $sessions->groupBy(function($session) {
            return $session->date->format('Y-m-d');
        });
        
        // Get calendar data
        $calendarData = $this->generateCalendarData($year, $month, $sessionsByDate);
        
        return view('student.schedule.index', compact('student', 'sessions', 'sessionsByDate', 'calendarData', 'year', 'month'));
    }

    // Generate calendar data for the month
    private function generateCalendarData($year, $month, $sessionsByDate)
    {
        $firstDay = now()->setYear((int)$year)->setMonth((int)$month)->startOfMonth();
        $lastDay = $firstDay->copy()->endOfMonth();
        $startOfWeek = $firstDay->copy()->startOfWeek();
        $endOfWeek = $lastDay->copy()->endOfWeek();
        
        $calendar = [];
        $current = $startOfWeek->copy();
        
        while ($current->lte($endOfWeek)) {
            $week = [];
            for ($i = 0; $i < 7; $i++) {
                $date = $current->copy();
                $dateString = $date->format('Y-m-d');
                
                $dayData = [
                    'date' => $date,
                    'isCurrentMonth' => $date->month == $month,
                    'isToday' => $date->isToday(),
                    'sessions' => $sessionsByDate->get($dateString, collect()),
                    'sessionCount' => $sessionsByDate->get($dateString, collect())->count(),
                ];
                
                $week[] = $dayData;
                $current->addDay();
            }
            $calendar[] = $week;
        }
        
        return $calendar;
    }
} 