<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\ActivitySubmission;
use App\Models\Tutor;
use App\Models\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        // Get all tutor IDs that the student has already rated
        $ratedTutorIds = \App\Models\Review::where('student_id', $student->id)
            ->pluck('tutor_id')
            ->toArray();

        return view('student.my-sessions', compact('activities', 'tutors', 'student', 'ratedTutorIds'));
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

        // Check if already submitted
        $existingSubmission = $activity->studentSubmission($student->id);
        if ($existingSubmission && in_array($existingSubmission->status, ['submitted', 'graded'])) {
            return response()->json([
                'success' => false,
                'message' => 'This activity has already been submitted. You cannot submit it again.'
            ], 400);
        }

        $request->validate([
            'answers' => 'nullable|array',
            'notes' => 'nullable|string|max:1000',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,txt,jpg,jpeg,png|max:10240',
            'student_attachments' => 'nullable|array',
            'student_attachments.*' => 'file|mimes:pdf,doc,docx,txt,jpg,jpeg,png|max:10240'
        ]);

        $submission = $existingSubmission;
        if (!$submission) {
            $submission = new ActivitySubmission([
                'activity_id' => $activity->id,
                'student_id' => $student->id
            ]);
            $submission->save();
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

        // Create notification for tutor
        $tutor = $activity->tutor;
        if ($tutor) {
            \App\Models\Notification::create([
                'user_id' => $tutor->id,
                'user_type' => 'tutor',
                'type' => 'activity_submitted',
                'title' => 'Activity Submitted',
                'message' => $student->first_name . ' ' . $student->last_name . ' has submitted the activity "' . $activity->title . '".',
            ]);
        }

        // Check achievements for student
        $achievementService = new \App\Services\AchievementNotificationService();
        $achievementService->checkAndNotifyProgress($student, 'student', 'activities_submitted');

        return response()->json(['success' => true, 'message' => 'Activity submitted successfully!']);
    }

    // Get student's progress statistics
    public function getProgressStats()
    {
        $student = Auth::guard('student')->user();
        
        // Get all activities assigned to this student
        $activities = Activity::where('student_id', $student->id)->get();
        
        // Total activities
        $totalActivities = $activities->count();
        
        // Get all activity IDs
        $activityIds = $activities->pluck('id')->toArray();
        
        // Get all submissions for these activities and this student in one query
        $submissions = ActivitySubmission::whereIn('activity_id', $activityIds)
            ->where('student_id', $student->id)
            ->get()
            ->keyBy('activity_id');
        
        // Count submitted and graded activities
        $submittedActivities = 0;
        $gradedActivities = 0;
        $pendingActivities = 0;
        
        foreach ($activities as $activity) {
            // Get submission for this activity
            $submission = $submissions->get($activity->id);
            
            if ($submission) {
                if ($submission->status === 'graded') {
                    $gradedActivities++;
                } elseif ($submission->status === 'submitted') {
                    $submittedActivities++;
                }
            } else {
                // No submission means pending
                $pendingActivities++;
            }
        }
        
        // Average score from graded submissions
        $averageScore = ActivitySubmission::where('student_id', $student->id)
            ->where('status', 'graded')
            ->avg('score') ?? 0;
        
        $stats = [
            'total_activities' => $totalActivities,
            'submitted_activities' => $submittedActivities,
            'graded_activities' => $gradedActivities,
            'pending_activities' => $pendingActivities,
            'average_score' => round($averageScore, 2),
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

        // Check if already rated - prevent re-rating
        $existingReview = \App\Models\Review::where('student_id', $student->id)
            ->where('tutor_id', $request->tutor_id)
            ->first();

        if ($existingReview) {
            // Student has already rated this tutor - prevent re-rating
            return response()->json([
                'success' => false,
                'message' => 'You have already rated this tutor. You can only rate once.'
            ], 403);
        }

        // Create new review (we'll use the first session_id as a reference)
        $session = \App\Models\Session::where('student_id', $student->id)
            ->where('tutor_id', $request->tutor_id)
            ->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'No session found for this tutor.'
            ], 404);
        }

        \App\Models\Review::create([
            'session_id' => $session->id,
            'student_id' => $student->id,
            'tutor_id' => $request->tutor_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Check achievements for both student and tutor when rating is 5 stars
        if ($request->rating == 5) {
            $achievementService = new \App\Services\AchievementNotificationService();
            $tutor = \App\Models\Tutor::find($request->tutor_id);
            
            // Check student achievements (for giving perfect ratings)
            $achievementService->checkAndNotifyProgress($student, 'student', 'perfect_ratings');
            
            // Check tutor achievements (for receiving perfect ratings)
            if ($tutor) {
                $achievementService->checkAndNotifyProgress($tutor, 'tutor', 'perfect_ratings');
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your rating!'
        ]);
    }

    /**
     * Download activity attachment
     */
    public function downloadAttachment(Activity $activity, $attachment)
    {
        $student = Auth::guard('student')->user();
        
        // Ensure the activity is assigned to this student
        if ($activity->student_id !== $student->id) {
            abort(403, 'Unauthorized access to this activity.');
        }

        // Decode the attachment path
        $attachmentPath = base64_decode($attachment);
        
        if (!$attachmentPath || !in_array($attachmentPath, $activity->attachments ?? [])) {
            abort(404, 'Attachment not found.');
        }

        // Check if file exists
        if (!Storage::disk('public')->exists($attachmentPath)) {
            abort(404, 'Attachment file not found.');
        }

        $filePath = Storage::disk('public')->path($attachmentPath);
        $fileName = basename($attachmentPath);
        
        // Determine content type
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $contentTypes = [
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'txt' => 'text/plain',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
        ];
        
        $contentType = $contentTypes[$extension] ?? 'application/octet-stream';

        return response()->download($filePath, $fileName, [
            'Content-Type' => $contentType,
        ]);
    }
}