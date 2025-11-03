<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentAnswer;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class StudentAssignmentController extends Controller
{
    /**
     * Show the page to post assignments
     */
    public function create()
    {
        $student = Auth::guard('student')->user();
        
        // Get recent assignments posted by the student
        $recentAssignments = Assignment::where('student_id', $student->id)
            ->with(['answers.tutor'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('student.post-assignment', compact('recentAssignments'));
    }

    /**
     * Store a new assignment
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'question' => 'required|string|min:10',
            'description' => 'nullable|string|max:1000',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
        ]);

        $student = Auth::guard('student')->user();

        $filePath = null;
        $fileName = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->store('assignments', 'public');
        }

        $assignment = Assignment::create([
            'student_id' => $student->id,
            'subject' => $request->subject,
            'question' => $request->question,
            'description' => $request->description,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'status' => 'pending',
            'price' => 70.00,
        ]);

        return redirect()->route('student.assignments.post')
            ->with('success', 'Assignment posted successfully! Wait for tutors to answer.');
    }

    /**
     * Show all assignments posted by the student
     */
    public function myAssignments()
    {
        $student = Auth::guard('student')->user();
        
        $assignments = Assignment::where('student_id', $student->id)
            ->with(['answers.tutor'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('student.my-assignments', compact('student', 'assignments'));
    }

    /**
     * Show assignment details and allow payment to view answer
     */
    public function show($id)
    {
        $student = Auth::guard('student')->user();
        $assignment = Assignment::with(['answers.tutor'])
            ->where('student_id', $student->id)
            ->findOrFail($id);

        // Get wallet to check balance
        $wallet = Wallet::where('user_id', $student->id)
            ->where('user_type', 'student')
            ->first();

        if (!$wallet) {
            $wallet = Wallet::create([
                'user_id' => $student->id,
                'user_type' => 'student',
                'balance' => 0.00,
                'currency' => 'PHP',
            ]);
        }

        $canAfford = $wallet->canAfford(70.00);
        
        // If assignment is paid, get the selected answer
        $answer = null;
        if ($assignment->status === 'paid' && $assignment->selected_answer_id) {
            $answer = $assignment->selectedAnswer()->with('tutor')->first();
        }
        
        $answers = $assignment->answers()->with('tutor')->get()->map(function($answerItem) {
            $tutor = $answerItem->tutor;
            return [
                'id' => $answerItem->id,
                'tutor_id' => $tutor->id,
                'tutor_name' => $tutor->getFullName(),
                'tutor_specialization' => $tutor->specialization,
                'answer_preview' => 'This answer is locked. Pay to view the full solution.',
                'rating' => $tutor->getAverageRating(),
                'rating_count' => $tutor->getRatingCount(),
                'created_at' => $answerItem->created_at,
            ];
        })->sortByDesc('rating')->values(); // Sort by highest rating

        return view('student.assignment-detail', compact('student', 'assignment', 'wallet', 'canAfford', 'answer', 'answers'));
    }

    /**
     * Process payment to view answer
     */
    public function payAndView($id, Request $request)
    {
        $student = Auth::guard('student')->user();
        $assignment = Assignment::where('student_id', $student->id)
            ->where('status', 'answered')
            ->findOrFail($id);

        // Get or create wallet
        $wallet = Wallet::where('user_id', $student->id)
            ->where('user_type', 'student')
            ->first();

        if (!$wallet) {
            $wallet = Wallet::create([
                'user_id' => $student->id,
                'user_type' => 'student',
                'balance' => 0.00,
                'currency' => 'PHP',
            ]);
        }

        // Check if already paid
        if ($assignment->status === 'paid') {
            return redirect()->route('student.assignments.show', $assignment->id)
                ->with('info', 'You have already purchased an answer for this assignment.');
        }

        // Check balance
        if (!$wallet->canAfford((float)$assignment->price)) {
            return redirect()->route('student.assignments.show', $assignment->id)
                ->with('error', 'Insufficient wallet balance. Please add funds to your wallet.');
        }

        // Get the specific answer if provided, otherwise get the latest
        $answerId = $request->input('answer_id');
        if ($answerId) {
            $answer = $assignment->answers()->where('id', $answerId)->first();
        } else {
            $answer = $assignment->answers()->latest()->first();
        }
        
        if (!$answer) {
            return redirect()->route('student.assignments.show', $assignment->id)
                ->with('error', 'No answer available for this assignment yet.');
        }

        DB::beginTransaction();
        try {
            // Deduct from student wallet
            $transaction = $wallet->deductFunds(
                (float)$assignment->price,
                'assignment_payment',
                [
                    'assignment_id' => $assignment->id,
                    'answer_id' => $answer->id,
                    'description' => 'Payment to view assignment answer',
                ]
            );

            if (!$transaction) {
                throw new \Exception('Payment failed. Insufficient funds.');
            }

            // Update assignment status and save selected answer
            $assignment->update([
                'status' => 'paid',
                'selected_answer_id' => $answer->id
            ]);

            // Credit tutor's wallet (70% to tutor, 30% platform fee)
            $tutorWallet = Wallet::where('user_id', $answer->tutor_id)
                ->where('user_type', 'tutor')
                ->first();

            if (!$tutorWallet) {
                $tutorWallet = Wallet::create([
                    'user_id' => $answer->tutor_id,
                    'user_type' => 'tutor',
                    'balance' => 0.00,
                    'currency' => 'PHP',
                ]);
            }

            $tutorEarnings = (float)$assignment->price; // 100% to tutor
            $tutorWallet->addFunds(
                $tutorEarnings,
                'assignment_earnings',
                [
                    'assignment_id' => $assignment->id,
                    'answer_id' => $answer->id,
                    'student_id' => $student->id,
                    'description' => 'Earnings from assignment answer',
                ]
            );

            // Create notification for tutor
            Notification::create([
                'user_id' => $answer->tutor_id,
                'user_type' => 'tutor',
                'type' => 'payment_received',
                'title' => 'Payment Received',
                'message' => 'You received ₱' . number_format($tutorEarnings, 2) . ' for your assignment answer.',
            ]);

            DB::commit();

            return redirect()->route('student.assignments.show', $assignment->id)
                ->with('success', 'Payment successful! You can now view the answer.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('student.assignments.show', $assignment->id)
                ->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }

    /**
     * Download assignment file
     */
    public function downloadFile($id)
    {
        $student = Auth::guard('student')->user();
        $assignment = Assignment::where('student_id', $student->id)
            ->findOrFail($id);

        if (!$assignment->file_path) {
            abort(404, 'File not found');
        }

        $filePath = Storage::disk('public')->path($assignment->file_path);
        return response()->download($filePath, $assignment->file_name ?? 'assignment-file');
    }
}
