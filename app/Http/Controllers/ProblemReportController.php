<?php

namespace App\Http\Controllers;

use App\Models\ProblemReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProblemReportController extends Controller
{
    /**
     * Store a new problem report from student
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'problem_type' => 'required|string',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $report = ProblemReport::create([
            'student_id' => Auth::guard('student')->id(),
            'problem_type' => $validated['problem_type'],
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Your problem report has been submitted successfully. We will review it shortly.');
    }

    /**
     * Store a new problem report from tutor
     */
    public function storeTutor(Request $request)
    {
        $validated = $request->validate([
            'problem_type' => 'required|string',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $report = ProblemReport::create([
            'tutor_id' => Auth::guard('tutor')->id(),
            'problem_type' => $validated['problem_type'],
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Your problem report has been submitted successfully. We will review it shortly.');
    }

    /**
     * Display all problem reports for admin
     */
    public function adminIndex()
    {
        $reports = ProblemReport::with(['student', 'tutor'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.problem-reports.index', compact('reports'));
    }

    /**
     * Show a specific problem report for admin
     */
    public function adminShow($id)
    {
        $report = ProblemReport::with(['student', 'tutor'])->findOrFail($id);
        return view('admin.problem-reports.show', compact('report'));
    }

    /**
     * Update problem report status and add admin response
     */
    public function adminUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,resolved,closed',
            'admin_response' => 'nullable|string',
        ]);

        $report = ProblemReport::findOrFail($id);
        $report->status = $validated['status'];
        
        if (isset($validated['admin_response'])) {
            $report->admin_response = $validated['admin_response'];
        }

        if ($validated['status'] === 'resolved' && !$report->resolved_at) {
            $report->resolved_at = now();
        }

        $report->save();

        return redirect()->back()->with('success', 'Problem report updated successfully.');
    }
}
