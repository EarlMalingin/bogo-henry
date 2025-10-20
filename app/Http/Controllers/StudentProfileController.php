<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class StudentProfileController extends Controller
{
    public function edit()
    {
        $student = Auth::guard('student')->user();
        return view('student.profile.edit', compact('student'));
    }

    public function update(Request $request)
    {
        $student = Auth::guard('student')->user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'phone' => 'nullable|string|max:20',
            'student_id' => 'required|string|unique:students,student_id,' . $student->id,
            'year_level' => 'required|string',
            'course' => 'required|string',
            'subjects_interest' => 'nullable|string',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|confirmed|min:8',
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            if ($student->profile_picture) {
                \Storage::disk('public')->delete($student->profile_picture);
            }
            $path = $request->file('profile_picture')->store('profile-pictures', 'public');
            $validated['profile_picture'] = $path;
        }

        // Handle password change
        if ($request->filled('current_password') && $request->filled('new_password')) {
            if (\Hash::check($request->input('current_password'), $student->password)) {
                $validated['password'] = bcrypt($request->input('new_password'));
            } else {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
        }

        $student->update($validated);
        // Refresh the authenticated user in the session
        Auth::guard('student')->setUser($student->fresh());

        return redirect()->route('student.profile.edit')
            ->with('success', 'Profile updated successfully');
    }
} 