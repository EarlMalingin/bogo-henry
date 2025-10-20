<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tutor;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\EmailVerificationController;

class TutorRegisterController extends Controller
{
    public function tutorRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:tutors',
            'password' => 'required|string|confirmed|min:8',
            'tutor_id' => 'required|string|unique:tutors',
            'specialization' => 'required|string|max:1000',
            'phone' => 'nullable|string',
            'bio' => 'nullable|string',
            'rate' => 'required|numeric|min:0',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:5120', // 5MB max
            'terms' => 'accepted',
        ], [
            'cv.mimes' => 'The CV must be a PDF, DOC, or DOCX file.',
            'cv.max' => 'The CV file size must not exceed 5MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle CV file upload
        $cvPath = null;
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('cvs', 'public');
        }

        $tutor = Tutor::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'tutor_id' => $request->input('tutor_id'),
            'specialization' => $request->input('specialization'),
            'phone' => $request->input('phone'),
            'bio' => $request->input('bio'),
            'cv' => $cvPath,
            'session_rate' => $request->input('rate'),
            'is_verified' => false,
        ]);

        // Send verification email
        if (EmailVerificationController::sendVerificationCode($tutor, 'tutor')) {
            return redirect()->route('verify.email', [
                'email' => $tutor->email,
                'type' => 'tutor'
            ])->with('success', 'Registration successful! Please check your email for verification code.');
        } else {
            return redirect()->back()->with('error', 'Registration successful but failed to send verification email. Please contact support.');
        }
    }
}
