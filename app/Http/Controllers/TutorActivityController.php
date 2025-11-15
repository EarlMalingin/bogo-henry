<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Student;
use App\Models\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TutorActivityController extends Controller
{
    // Show all activities for the tutor
    public function index()
    {
        $tutor = Auth::guard('tutor')->user();
        
        $activities = Activity::where('tutor_id', $tutor->id)
            ->with(['student', 'session'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get students who have sessions with this tutor
        $students = Student::whereHas('sessions', function($query) use ($tutor) {
            $query->where('tutor_id', $tutor->id);
        })->get();

        return view('tutor.my-sessions', compact('activities', 'students', 'tutor'));
    }

    // Show create activity form
    public function create(Request $request)
    {
        $tutor = Auth::guard('tutor')->user();
        
        $students = Student::whereHas('sessions', function($query) use ($tutor) {
            $query->where('tutor_id', $tutor->id);
        })->get();

        // Get selected student ID from query parameter
        $selectedStudentId = $request->query('student_id');
        
        // If student_id is provided, verify the student has sessions with this tutor
        if ($selectedStudentId) {
            $hasSessions = Session::where('tutor_id', $tutor->id)
                ->where('student_id', $selectedStudentId)
                ->exists();
            
            if (!$hasSessions) {
                $selectedStudentId = null; // Reset if invalid
            }
        }

        $sessions = Session::where('tutor_id', $tutor->id)
            ->where('status', 'accepted')
            ->with('student')
            ->get();

        return view('tutor.activities.create', compact('students', 'sessions', 'tutor', 'selectedStudentId'));
    }

    // Store new activity
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:activity,exam,assignment,quiz',
            'student_id' => 'required|exists:students,id',
            'session_id' => 'nullable|exists:sessions,id',
            'instructions' => 'nullable|string',
            'due_date' => 'nullable|date|after:now',
            'total_points' => 'required|integer|min:1',
            'time_limit' => 'nullable|integer|min:1',
            'questions' => 'nullable|array',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,txt,jpg,jpeg,png|max:10240'
        ]);

        $tutor = Auth::guard('tutor')->user();

        // Handle file uploads
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                // Preserve original filename but handle duplicates
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $filename = pathinfo($originalName, PATHINFO_FILENAME);
                
                // Check if file already exists and add timestamp if needed
                $fullPath = 'activities/attachments/' . $originalName;
                if (Storage::disk('public')->exists($fullPath)) {
                    $timestamp = time();
                    $originalName = $filename . '_' . $timestamp . '.' . $extension;
                }
                
                $path = $file->storeAs('activities/attachments', $originalName, 'public');
                $attachments[] = $path;
            }
        }

        $activity = Activity::create([
            'tutor_id' => $tutor->id,
            'student_id' => $request->student_id,
            'session_id' => $request->session_id,
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'status' => 'sent',
            'instructions' => $request->instructions,
            'questions' => $request->questions,
            'attachments' => $attachments,
            'due_date' => $request->due_date,
            'total_points' => $request->total_points,
            'time_limit' => $request->time_limit,
        ]);

        // Check achievements for tutor
        $achievementService = new \App\Services\AchievementNotificationService();
        $achievementService->checkAndNotifyProgress($tutor, 'tutor', 'activities_created');

        return redirect()->route('tutor.my-sessions')->with('success', 'Activity sent successfully!');
    }

    // Show activity details
    public function show(Activity $activity)
    {
        $tutor = Auth::guard('tutor')->user();
        
        // Ensure the tutor owns this activity
        if ($activity->tutor_id !== $tutor->id) {
            abort(403);
        }

        $activity->load(['student', 'session']);

        return view('tutor.activities.show', compact('activity', 'tutor'));
    }

    // Grade an activity
    public function grade(Request $request, Activity $activity)
    {
        $tutor = Auth::guard('tutor')->user();
        
        // Ensure the tutor owns this activity
        if ($activity->tutor_id !== $tutor->id) {
            abort(403);
        }

        $request->validate([
            'score' => 'required|integer|min:0|max:' . $activity->total_points,
            'feedback' => 'nullable|string|max:1000'
        ]);

        // Get the submission
        $submission = $activity->submissions()->where('student_id', $activity->student_id)->first();
        
        if (!$submission || $submission->status !== 'submitted') {
            return redirect()->route('tutor.activities.show', $activity)
                ->with('error', 'No submitted activity found to grade.');
        }

        // Update the submission with grade and feedback
        $submission->update([
            'score' => $request->score,
            'feedback' => $request->feedback,
            'status' => 'graded',
            'graded_at' => now()
        ]);

        // Also update activity status
        $activity->update([
            'status' => 'graded',
            'graded_at' => now()
        ]);

        // Create notification for student
        \App\Models\Notification::create([
            'user_id' => $activity->student_id,
            'user_type' => 'student',
            'type' => 'activity_graded',
            'title' => 'Activity Graded',
            'message' => 'Your activity "' . $activity->title . '" has been graded. Score: ' . $request->score . '/' . $activity->total_points,
        ]);

        // If AJAX request, return JSON response
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Activity graded successfully! The student has been notified.',
                'redirect' => route('tutor.my-sessions')
            ]);
        }

        return redirect()->route('tutor.my-sessions')
            ->with('success', 'Activity graded successfully! The student has been notified.');
    }

    // Get progress statistics
    public function getProgressStats()
    {
        $tutor = Auth::guard('tutor')->user();
        
        $stats = [
            'total_activities' => Activity::where('tutor_id', $tutor->id)->count(),
            'completed_activities' => Activity::where('tutor_id', $tutor->id)
                ->whereIn('status', ['completed', 'graded'])->count(),
            'pending_grading' => Activity::where('tutor_id', $tutor->id)
                ->where('status', 'completed')->count(),
            'overdue_activities' => Activity::where('tutor_id', $tutor->id)
                ->where('due_date', '<', now())
                ->whereNotIn('status', ['completed', 'graded'])->count(),
        ];

        return response()->json($stats);
    }

    // Get student progress
    public function getStudentProgress($studentId)
    {
        $tutor = Auth::guard('tutor')->user();
        
        // Get the student
        $student = Student::findOrFail($studentId);
        
        // Verify the student has sessions with this tutor
        $hasSessions = Session::where('tutor_id', $tutor->id)
            ->where('student_id', $studentId)
            ->exists();
        
        if (!$hasSessions) {
            abort(403, 'You do not have access to this student\'s progress.');
        }
        
        // Get all activities for this student
        $activities = Activity::where('tutor_id', $tutor->id)
            ->where('student_id', $studentId)
            ->with(['submissions' => function($query) use ($studentId) {
                $query->where('student_id', $studentId);
            }])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calculate statistics
        $totalActivities = $activities->count();
        $submittedActivities = $activities->filter(function($activity) use ($studentId) {
            $submission = $activity->studentSubmission($studentId);
            return $submission && $submission->status === 'submitted';
        })->count();
        
        $gradedActivities = $activities->filter(function($activity) use ($studentId) {
            $submission = $activity->studentSubmission($studentId);
            return $submission && $submission->status === 'graded';
        });
        
        $gradedCount = $gradedActivities->count();
        
        // Calculate average score from submissions
        $averageScore = $gradedActivities->avg(function($activity) use ($studentId) {
            $submission = $activity->studentSubmission($studentId);
            if ($submission && $submission->score && $activity->total_points) {
                return ($submission->score / $activity->total_points) * 100;
            }
            return 0;
        }) ?? 0;
        
        $totalPoints = $gradedActivities->sum(function($activity) use ($studentId) {
            $submission = $activity->studentSubmission($studentId);
            return $submission ? $submission->score : 0;
        });
        
        $maxPoints = $gradedActivities->sum('total_points');
        
        // Get sessions count
        $sessionsCount = Session::where('tutor_id', $tutor->id)
            ->where('student_id', $studentId)
            ->count();
        
        $completedSessions = Session::where('tutor_id', $tutor->id)
            ->where('student_id', $studentId)
            ->where('status', 'completed')
            ->count();

        return view('tutor.students.progress', compact(
            'student',
            'tutor',
            'activities',
            'totalActivities',
            'submittedActivities',
            'gradedCount',
            'averageScore',
            'totalPoints',
            'maxPoints',
            'sessionsCount',
            'completedSessions'
        ));
    }

    // Show all students for the tutor
    public function students()
    {
        $tutor = Auth::guard('tutor')->user();
        
        // Get current students (with active sessions - accepted or pending)
        // A student is "current" if they have at least one accepted or pending session
        $currentStudents = Student::whereHas('sessions', function($query) use ($tutor) {
            $query->where('tutor_id', $tutor->id)
                  ->whereIn('status', ['accepted', 'pending']);
        })->with(['sessions' => function($query) use ($tutor) {
            $query->where('tutor_id', $tutor->id)
                  ->orderBy('date', 'desc')
                  ->orderBy('start_time', 'desc');
        }])->distinct()->get();

        // Get past students (with completed sessions only, and no active sessions)
        $pastStudents = Student::whereHas('sessions', function($query) use ($tutor) {
            $query->where('tutor_id', $tutor->id)
                  ->where('status', 'completed');
        })->whereDoesntHave('sessions', function($query) use ($tutor) {
            $query->where('tutor_id', $tutor->id)
                  ->whereIn('status', ['accepted', 'pending']);
        })->with(['sessions' => function($query) use ($tutor) {
            $query->where('tutor_id', $tutor->id)
                  ->orderBy('date', 'desc')
                  ->orderBy('start_time', 'desc');
        }])->distinct()->get();

        // Get rejected students (students whose sessions were rejected)
        $rejectedStudents = Student::whereHas('sessions', function($query) use ($tutor) {
            $query->where('tutor_id', $tutor->id)
                  ->where('status', 'rejected');
        })->whereDoesntHave('sessions', function($query) use ($tutor) {
            $query->where('tutor_id', $tutor->id)
                  ->whereIn('status', ['accepted', 'pending', 'completed']);
        })->with(['sessions' => function($query) use ($tutor) {
            $query->where('tutor_id', $tutor->id);
        }])->get();

        // Calculate stats for each student
        $currentStudents = $currentStudents->map(function($student) use ($tutor) {
            $sessions = $student->sessions->where('tutor_id', $tutor->id);
            $activities = Activity::where('tutor_id', $tutor->id)
                ->where('student_id', $student->id)
                ->get();
            
            $student->stats = [
                'total_sessions' => $sessions->count(),
                'completed_sessions' => $sessions->where('status', 'completed')->count(),
                'total_activities' => $activities->count(),
                'completed_activities' => $activities->whereIn('status', ['completed', 'graded'])->count(),
                'average_score' => $activities->where('status', 'graded')->avg('score') ?? 0,
                'last_session' => $sessions->sortByDesc('created_at')->first()?->created_at,
                'online_sessions' => $sessions->where('session_type', 'online')->count(),
                'face_to_face_sessions' => $sessions->where('session_type', 'face_to_face')->count(),
                'last_session_type' => $sessions->sortByDesc('created_at')->first()?->session_type,
            ];
            
            return $student;
        });

        $pastStudents = $pastStudents->map(function($student) use ($tutor) {
            $sessions = $student->sessions->where('tutor_id', $tutor->id);
            $activities = Activity::where('tutor_id', $tutor->id)
                ->where('student_id', $student->id)
                ->get();
            
            $student->stats = [
                'total_sessions' => $sessions->count(),
                'completed_sessions' => $sessions->where('status', 'completed')->count(),
                'total_activities' => $activities->count(),
                'completed_activities' => $activities->whereIn('status', ['completed', 'graded'])->count(),
                'average_score' => $activities->where('status', 'graded')->avg('score') ?? 0,
                'last_session' => $sessions->sortByDesc('created_at')->first()?->created_at,
                'online_sessions' => $sessions->where('session_type', 'online')->count(),
                'face_to_face_sessions' => $sessions->where('session_type', 'face_to_face')->count(),
                'last_session_type' => $sessions->sortByDesc('created_at')->first()?->session_type,
            ];
            
            return $student;
        });

        $rejectedStudents = $rejectedStudents->map(function($student) use ($tutor) {
            $sessions = $student->sessions->where('tutor_id', $tutor->id);
            $activities = Activity::where('tutor_id', $tutor->id)
                ->where('student_id', $student->id)
                ->get();
            
            $student->stats = [
                'total_sessions' => $sessions->count(),
                'rejected_sessions' => $sessions->where('status', 'rejected')->count(),
                'total_activities' => $activities->count(),
                'completed_activities' => $activities->whereIn('status', ['completed', 'graded'])->count(),
                'average_score' => $activities->where('status', 'graded')->avg('score') ?? 0,
                'last_session' => $sessions->sortByDesc('created_at')->first()?->created_at,
                'online_sessions' => $sessions->where('session_type', 'online')->count(),
                'face_to_face_sessions' => $sessions->where('session_type', 'face_to_face')->count(),
                'last_session_type' => $sessions->sortByDesc('created_at')->first()?->session_type,
            ];
            
            return $student;
        });

        return view('tutor.students.index', compact('currentStudents', 'pastStudents', 'rejectedStudents', 'tutor'));
    }

    // Show tutor schedule with calendar
    public function schedule(Request $request)
    {
        $tutor = Auth::guard('tutor')->user();
        
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
        
        // Get all accepted sessions for the tutor in the requested month
        $sessions = Session::where('tutor_id', $tutor->id)
            ->where('status', 'accepted')
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->with(['student'])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();
        
        // Group sessions by date
        $sessionsByDate = $sessions->groupBy(function($session) {
            return $session->date->format('Y-m-d');
        });
        
        // Get calendar data
        $calendarData = $this->generateCalendarData($year, $month, $sessionsByDate);
        
        return view('tutor.schedule.index', compact('tutor', 'sessions', 'sessionsByDate', 'calendarData', 'year', 'month'));
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