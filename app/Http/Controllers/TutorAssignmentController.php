<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentAnswer;
use App\Services\AIValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TutorAssignmentController extends Controller
{
    /**
     * Show all assignments that can be answered
     */
    public function index()
    {
        $tutor = Auth::guard('tutor')->user();

        // Show assignments that are pending or answered (multiple tutors can still answer)
        // But exclude those this tutor has already answered
        $assignments = Assignment::whereIn('status', ['pending', 'answered'])
            ->whereDoesntHave('answers', function($query) use ($tutor) {
                $query->where('tutor_id', $tutor->id);
            })
            ->with(['student', 'answers'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('tutor.assignments.index', compact('tutor', 'assignments'));
    }

    /**
     * Show assignment details and answer form
     */
    public function show($id)
    {
        $tutor = Auth::guard('tutor')->user();
        $assignment = Assignment::with(['student', 'answers.tutor'])
            ->findOrFail($id);

        // Check if tutor already answered this assignment
        $hasAnswered = $assignment->answers()
            ->where('tutor_id', $tutor->id)
            ->exists();

        return view('tutor.assignments.answer', compact('tutor', 'assignment', 'hasAnswered'));
    }

    /**
     * Store answer for an assignment
     */
    public function storeAnswer(Request $request, $id)
    {
        $request->validate([
            'answer' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
        ]);

        $tutor = Auth::guard('tutor')->user();
        $assignment = Assignment::findOrFail($id);

        // Check if tutor is approved
        if ($tutor->registration_status !== 'approved') {
            return back()->with('error', 'Your account must be approved by an admin before you can answer assignments.');
        }

        // Check if tutor already answered this assignment
        $hasAnswered = $assignment->answers()
            ->where('tutor_id', $tutor->id)
            ->exists();

        if ($hasAnswered) {
            return back()->with('error', 'You have already submitted an answer for this assignment.');
        }

        // AI Validation: Check if answer is relevant AND correct
        try {
            $aiService = new AIValidationService();
            $validation = $aiService->validateAnswerRelevance(
                $assignment->question,
                $request->answer,
                $assignment->subject
            );

            // Log validation result for debugging
            \Log::info('AI validation result', [
                'assignment_id' => $assignment->id,
                'tutor_id' => $tutor->id,
                'question' => substr($assignment->question, 0, 100),
                'answer_preview' => substr($request->answer, 0, 100),
                'is_relevant' => $validation['is_relevant'],
                'confidence' => $validation['confidence'],
                'reason' => $validation['reason']
            ]);

            // Reject if answer is not relevant/correct with confidence >= 0.6
            // This will catch wrong answers like "36" for "1+1" or "400" for "1+1"
            if (!$validation['is_relevant'] && $validation['confidence'] >= 0.6) {
                // Always use generic message - never reveal correct answers
                $rejectionMessage = 'Answer rejected. Please take the answer more seriously or we will take immediate action.';
                return back()
                    ->with('error', $rejectionMessage)
                    ->withInput();
            }
            
            // Accept answers with low confidence if they passed basic checks (API unavailable but answer seems valid)
            // Only reject if confidence is very low (< 0.3) which indicates clear problems
            if ($validation['is_relevant'] && $validation['confidence'] < 0.3) {
                // Very low confidence - likely a problem with the answer
                return back()
                    ->with('error', 'Answer rejected. Please provide a more complete and accurate answer.')
                    ->withInput();
            }
        } catch (\Exception $e) {
            // Log error and reject answer if validation fails
            \Log::error('AI validation error in assignment answer submission', [
                'assignment_id' => $assignment->id,
                'tutor_id' => $tutor->id,
                'error' => $e->getMessage(),
                'trace' => substr($e->getTraceAsString(), 0, 500)
            ]);
            
            // Reject answer if validation service fails - require proper validation
            return back()
                ->with('error', 'Answer rejected. AI validation service encountered an error. Please try again later or contact support.')
                ->withInput();
        }

        $filePath = null;
        $fileName = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->store('assignment-answers', 'public');
        }

        $answer = AssignmentAnswer::create([
            'assignment_id' => $assignment->id,
            'tutor_id' => $tutor->id,
            'answer' => $request->answer,
            'file_path' => $filePath,
            'file_name' => $fileName,
        ]);

        // Only update status to 'answered' if this is the first answer
        if ($assignment->status === 'pending') {
            $assignment->update(['status' => 'answered']);
        }

        return redirect()->route('tutor.assignments.index')
            ->with('success', 'Answer submitted successfully! The student will be notified.');
    }

    /**
     * Show assignments answered by this tutor
     */
    public function myAnswers()
    {
        $tutor = Auth::guard('tutor')->user();

        $answers = AssignmentAnswer::where('tutor_id', $tutor->id)
            ->with(['assignment.student'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('tutor.assignments.my-answers', compact('tutor', 'answers'));
    }

    /**
     * Download assignment file
     */
    public function downloadFile($id)
    {
        $assignment = Assignment::findOrFail($id);

        if (!$assignment->file_path) {
            abort(404, 'File not found');
        }

        $filePath = Storage::disk('public')->path($assignment->file_path);
        return response()->download($filePath, $assignment->file_name ?? 'assignment-file');
    }

    /**
     * Download answer file
     */
    public function downloadAnswerFile($id)
    {
        $tutor = Auth::guard('tutor')->user();
        $answer = AssignmentAnswer::where('tutor_id', $tutor->id)
            ->findOrFail($id);

        if (!$answer->file_path) {
            abort(404, 'File not found');
        }

        $filePath = Storage::disk('public')->path($answer->file_path);
        return response()->download($filePath, $answer->file_name ?? 'answer-file');
    }
}
