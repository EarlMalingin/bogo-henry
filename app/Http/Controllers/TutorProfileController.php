<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Tutor;

class TutorProfileController extends Controller
{
    public function edit()
    {
        $tutor = Auth::guard('tutor')->user();
        return view('tutor.profile.edit', compact('tutor'));
    }

    public function update(Request $request)
    {
        $tutor = Auth::guard('tutor')->user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:tutors,email,' . $tutor->id,
            'tutor_id' => 'required|string|unique:tutors,tutor_id,' . $tutor->id,
            'specialization' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            if ($tutor->profile_picture) {
                Storage::disk('public')->delete($tutor->profile_picture);
            }
            $path = $request->file('profile_picture')->store('profile-pictures', 'public');
            $tutor->profile_picture = $path;
        }

        // Update details
        $tutor->first_name = $validated['first_name'];
        $tutor->last_name = $validated['last_name'];
        $tutor->email = $validated['email'];
        $tutor->tutor_id = $validated['tutor_id'];
        $tutor->specialization = $validated['specialization'] ?? null;
        $tutor->phone = $validated['phone'] ?? null;
        $tutor->bio = $validated['bio'] ?? null;

        // Handle password change
        if (!empty($validated['current_password']) && !empty($validated['new_password'])) {
            if (Hash::check($validated['current_password'], $tutor->password)) {
                $tutor->password = Hash::make($validated['new_password']);
            } else {
                return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
            }
        }

        $tutor->save();
        // Refresh session
        Auth::guard('tutor')->setUser($tutor);

        return back()->with('success', 'Profile updated successfully!');
    }
} 