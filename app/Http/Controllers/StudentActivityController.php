<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\ActivitySubmission;
use App\Models\Tutor;
use App\Models\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentActivityController extends Controller
{
    // Show all activities assigned to the student
    public function index()
    {
        $student = Auth::guard('student')->user();
        
        $activities = Activity::where('student_id', $student->id)
            ->with(['tutor', 'submissions' => function($query) use ($student) {
                $query->where('student_id', $student->id);
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get tutors who have sessions with this student that haven't ended yet
        // Sessions are considered "active" if they have at least one upcoming or recent session (within last month)
        $tutors = Tutor::whereHas('sessions', function($query) use ($student) {
            $query->where('student_id', $student->id)
                  ->where('status', 'accepted')
                  ->where(function($q) {
                      // Show tutors with future sessions OR sessions within the last 30 days
                      $q->where('date', '>=', now()->subDays(30));
                  });
        })
        ->with(['activities' => function($query) use ($student) {
            $query->where('student_id', $student->id);
        }])
        ->with(['sessions' => function($query) use ($student) {
            $query->where('student_id', $student->id)
                  ->where('status', 'accepted')
                  ->orderBy('date', 'desc');
        }])
        ->get();

        return view('student.my-sessions', compact('activities', 'tutors', 'student'));
    }

    // Show activities from a specific tutor
    public function tutorActivities($tutorId)
    {
        $student = Auth::guard('student')->user();
        $tutor = Tutor::findOrFail($tutorId);
        
        $activities = Activity::where('student_id', $student->id)
            ->where('tutor_id', $tutorId)
            ->with(['submissions' => function($query) use ($student) {
                $query->where('student_id', $student->id);
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('student.tutor-activities', compact('activities', 'tutor', 'student'));
    }

    // Show activity details and allow student to answer
    public function show(Activity $activity)
    {
        $student = Auth::guard('student')->user();
        
        // Ensure the activity is assigned to this student
        if ($activity->student_id !== $student->id) {
            abort(403);
        }

        $activity->load(['tutor', 'session']);
        
        // Get or create submission
        $submission = $activity->studentSubmission($student->id);
        if (!$submission) {
            $submission = ActivitySubmission::create([
                'activity_id' => $activity->id,
                'student_id' => $student->id,
                'status' => 'draft'
            ]);
        }

        return view('student.activity-details', compact('activity', 'submission', 'student'));
    }

    // Save student's answers (draft)
    public function saveDraft(Request $request, Activity $activity)
    {
        $student = Auth::guard('student')->user();
        
        // Ensure the activity is assigned to this student
        if ($activity->student_id !== $student->id) {
            abort(403);
        }

        $request->validate([
            'answers' => 'nullable|array',
            'notes' => 'nullable|string|max:1000',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,txt,jpg,jpeg,png|max:10240',
            'student_attachments' => 'nullable|array',
            'student_attachments.*' => 'file|mimes:pdf,doc,docx,txt,jpg,jpeg,png|max:10240'
        ]);

        $submission = $activity->studentSubmission($student->id);
        if (!$submission) {
            $submission = new ActivitySubmission([
                'activity_id' => $activity->id,
                'student_id' => $student->id,
                'status' => 'draft'
            ]);
        }

        // Handle file uploads
        $attachments = $submission->attachments ?? [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $filename = pathinfo($originalName, PATHINFO_FILENAME);
                
                // Check if file already exists and add timestamp if needed
                $fullPath = 'activity-submissions/' . $originalName;
                if (Storage::disk('public')->exists($fullPath)) {
                    $timestamp = time();
                    $originalName = $filename . '_' . $timestamp . '.' . $extension;
                }
                
                $path = $file->storeAs('activity-submissions', $originalName, 'public');
                $attachments[] = $path;
            }
        }

        // Handle student attachments
        if ($request->hasFile('student_attachments')) {
            foreach ($request->file('student_attachments') as $file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $filename = pathinfo($originalName, PATHINFO_FILENAME);
                
                // Check if file already exists and add timestamp if needed
                $fullPath = 'activity-submissions/' . $originalName;
                if (Storage::disk('public')->exists($fullPath)) {
                    $timestamp = time();
                    $originalName = $filename . '_' . $timestamp . '.' . $extension;
                }
                
                $path = $file->storeAs('activity-submissions', $originalName, 'public');
                $attachments[] = $path;
            }
        }

        $submission->update([
            'answers' => $request->answers,
            'notes' => $request->notes,
            'attachments' => $attachments,
            'status' => 'draft'
        ]);

        return response()->json(['success' => true, 'message' => 'Draft saved successfully']);
    }

    // Submit activity
    public function submit(Request $request, Activity $activity)
    {
        $student = Auth::guard('student')->user();
        
        // Ensure the activity is assigned to this student
        if ($activity->student_id !== $student->id) {
            abort(403);
        }

        $request->validate([
            'answers' => 'nullable|array',
            'notes' => 'nullable|string|max:1000',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,txt,jpg,jpeg,png|max:10240',
            'student_attachments' => 'nullable|array',
            'student_attachments.*' => 'file|mimes:pdf,doc,docx,txt,jpg,jpeg,png|max:10240'
        ]);

        $submission = $activity->studentSubmission($student->id);
        if (!$submission) {
            $submission = new ActivitySubmission([
                'activity_id' => $activity->id,
                'student_id' => $student->id
            ]);
        }

        // Handle file uploads
        $attachments = $submission->attachments ?? [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $filename = pathinfo($originalName, PATHINFO_FILENAME);
                
                // Check if file already exists and add timestamp if needed
                $fullPath = 'activity-submissions/' . $originalName;
                if (Storage::disk('public')->exists($fullPath)) {
                    $timestamp = time();
                    $originalName = $filename . '_' . $timestamp . '.' . $extension;
                }
                
                $path = $file->storeAs('activity-submissions', $originalName, 'public');
                $attachments[] = $path;
            }
        }

        // Handle student attachments
        if ($request->hasFile('student_attachments')) {
            foreach ($request->file('student_attachments') as $file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $filename = pathinfo($originalName, PATHINFO_FILENAME);
                
                // Check if file already exists and add timestamp if needed
                $fullPath = 'activity-submissions/' . $originalName;
                if (Storage::disk('public')->exists($fullPath)) {
                    $timestamp = time();
                    $originalName = $filename . '_' . $timestamp . '.' . $extension;
                }
                
                $path = $file->storeAs('activity-submissions', $originalName, 'public');
                $attachments[] = $path;
            }
        }

        $submission->update([
            'answers' => $request->answers,
            'notes' => $request->notes,
            'attachments' => $attachments,
            'status' => 'submitted',
            'submitted_at' => now()
        ]);

        // Update activity status
        $activity->update(['status' => 'completed']);

        return response()->json(['success' => true, 'message' => 'Activity submitted successfully!']);
    }

    // Get student's progress statistics
    public function getProgressStats()
    {
        $student = Auth::guard('student')->user();
        
        $stats = [
            'total_activities' => Activity::where('student_id', $student->id)->count(),
            'submitted_activities' => ActivitySubmission::where('student_id', $student->id)
                ->where('status', 'submitted')->count(),
            'graded_activities' => ActivitySubmission::where('student_id', $student->id)
                ->where('status', 'graded')->count(),
            'pending_activities' => Activity::where('student_id', $student->id)
                ->where('status', 'sent')
                ->whereDoesntHave('submissions', function($query) use ($student) {
                    $query->where('student_id', $student->id)->where('status', 'submitted');
                })->count(),
            'average_score' => ActivitySubmission::where('student_id', $student->id)
                ->where('status', 'graded')
                ->avg('score') ?? 0,
        ];

        return response()->json($stats);
    }

    // Get progress for a specific tutor
    public function getTutorProgress($tutorId)
    {
        $student = Auth::guard('student')->user();
        
        $activities = Activity::where('student_id', $student->id)
            ->where('tutor_id', $tutorId)
            ->with('submissions')
            ->get();

        $progress = [
            'total_activities' => $activities->count(),
            'submitted' => $activities->filter(function($activity) use ($student) {
                $submission = $activity->studentSubmission($student->id);
                return $submission && $submission->status === 'submitted';
            })->count(),
            'graded' => $activities->filter(function($activity) use ($student) {
                $submission = $activity->studentSubmission($student->id);
                return $submission && $submission->status === 'graded';
            })->count(),
            'average_score' => $activities->filter(function($activity) use ($student) {
                $submission = $activity->studentSubmission($student->id);
                return $submission && $submission->status === 'graded';
            })->avg(function($activity) use ($student) {
                $submission = $activity->studentSubmission($student->id);
                return $submission ? $submission->score : 0;
            }) ?? 0,
        ];

        return response()->json($progress);
    }

    // Rate a tutor
    public function rateTutor(Request $request)
    {
        $request->validate([
            'tutor_id' => 'required|exists:tutors,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $student = Auth::guard('student')->user();

        // Check if student has a session with this tutor
        $hasSession = \App\Models\Session::where('student_id', $student->id)
            ->where('tutor_id', $request->tutor_id)
            ->exists();

        if (!$hasSession) {
            return response()->json([
                'success' => false,
                'message' => 'You can only rate tutors you have had sessions with.'
            ], 403);
        }

        // Check if already rated
        $existingReview = \App\Models\Review::where('student_id', $student->id)
            ->where('tutor_id', $request->tutor_id)
            ->first();

        if ($existingReview) {
            // Update existing review
            $existingReview->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
        } else {
            // Create new review (we'll use the first session_id as a reference)
            $session = \App\Models\Session::where('student_id', $student->id)
                ->where('tutor_id', $request->tutor_id)
                ->first();

            \App\Models\Review::create([
                'session_id' => $session->id,
                'student_id' => $student->id,
                'tutor_id' => $request->tutor_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your rating!'
        ]);
    }
}