<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Student;
use App\Models\Tutor;

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
            // student_id is automatically generated and cannot be changed
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

        // Remove student_id from validated data if present (should not be updated)
        unset($validated['student_id']);
        
        $student->update($validated);
        // Refresh the authenticated user in the session
        Auth::guard('student')->setUser($student->fresh());

        return redirect()->route('student.profile.edit')
            ->with('success', 'Profile updated successfully');
    }

    public function profilePicture()
    {
        $student = Auth::guard('student')->user();

        if (!$student->profile_picture) {
            abort(404, 'Profile picture not found');
        }

        // Check if file exists
        if (!Storage::disk('public')->exists($student->profile_picture)) {
            abort(404, 'Profile picture file not found');
        }

        $filePath = Storage::disk('public')->path($student->profile_picture);
        $fileName = basename($student->profile_picture);
        
        // Determine content type
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $contentTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
        ];
        
        $contentType = $contentTypes[$extension] ?? 'image/jpeg';

        return response()->file($filePath, [
            'Content-Type' => $contentType,
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
        ]);
    }

    public function viewStudentPicture($id)
    {
        $student = Student::findOrFail($id);

        if (!$student->profile_picture) {
            abort(404, 'Profile picture not found');
        }

        // Check if file exists
        if (!Storage::disk('public')->exists($student->profile_picture)) {
            abort(404, 'Profile picture file not found');
        }

        $filePath = Storage::disk('public')->path($student->profile_picture);
        $fileName = basename($student->profile_picture);
        
        // Determine content type
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $contentTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
        ];
        
        $contentType = $contentTypes[$extension] ?? 'image/jpeg';

        return response()->file($filePath, [
            'Content-Type' => $contentType,
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
        ]);
    }

    public function viewTutorPicture($id)
    {
        $tutor = Tutor::findOrFail($id);

        if (!$tutor->profile_picture) {
            abort(404, 'Profile picture not found');
        }

        // Check if file exists
        if (!Storage::disk('public')->exists($tutor->profile_picture)) {
            abort(404, 'Profile picture file not found');
        }

        $filePath = Storage::disk('public')->path($tutor->profile_picture);
        $fileName = basename($tutor->profile_picture);
        
        // Determine content type
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $contentTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
        ];
        
        $contentType = $contentTypes[$extension] ?? 'image/jpeg';

        return response()->file($filePath, [
            'Content-Type' => $contentType,
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
        ]);
    }
} 