<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TutorAssignmentController extends Controller
{
    /**
     * Show all pending assignments that need answers
     */
    public function index()
    {
        $tutor = Auth::guard('tutor')->user();

        $assignments = Assignment::where('status', 'pending')
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
            'answer' => 'required|string|min:10',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
        ]);

        $tutor = Auth::guard('tutor')->user();
        $assignment = Assignment::findOrFail($id);

        // Check if assignment is still pending
        if ($assignment->status !== 'pending') {
            return back()->with('error', 'This assignment has already been answered.');
        }

        // Check if tutor already answered
        $hasAnswered = $assignment->answers()
            ->where('tutor_id', $tutor->id)
            ->exists();

        if ($hasAnswered) {
            return back()->with('error', 'You have already submitted an answer for this assignment.');
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

        // Update assignment status to answered
        $assignment->update(['status' => 'answered']);

        return redirect()->route('tutor.assignments.index')
            ->with('success', 'Answer submitted successfully! The student can now purchase your answer.');
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
