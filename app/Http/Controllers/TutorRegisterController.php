<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tutor;
use App\Models\Wallet;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Http\Controllers\EmailVerificationController;
use App\Mail\VerificationCodeMail;

class TutorRegisterController extends Controller
{
    public function tutorRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\']+$/',
            'last_name' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\']+$/',
            'email' => 'required|string|email|max:255|unique:tutors',
            'password' => 'required|string|confirmed|min:8',
            'specialization' => 'required|string|max:1000',
            'phone' => 'nullable|string',
            'bio' => 'nullable|string',
            'rate' => 'required|numeric|min:0',
            'hourly_rate' => 'required|numeric|min:0',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB max, required
            'terms' => 'accepted',
        ], [
            'first_name.regex' => 'First name can only contain letters, spaces, hyphens, and apostrophes.',
            'last_name.regex' => 'Last name can only contain letters, spaces, hyphens, and apostrophes.',
            'cv.required' => 'Please upload your CV/Resume.',
            'cv.mimes' => 'The CV must be a PDF, DOC, or DOCX file.',
            'cv.max' => 'The CV file size must not exceed 5MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if email already exists (even if not verified)
        if (Tutor::where('email', $request->input('email'))->exists()) {
            return redirect()->back()
                ->withErrors(['email' => 'This email is already registered.'])
                ->withInput();
        }

        $tutorId = Tutor::generateTutorId();

        // Handle CV file upload (required) - store temporarily
        $cvPath = $request->file('cv')->store('temp-cvs', 'public');

        // Generate a unique registration token
        $registrationToken = Str::random(64);
        
        // Generate verification code
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = now()->addMinutes(15);

        // Store registration data in cache (expires in 30 minutes)
        Cache::put('tutor_registration_' . $registrationToken, [
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'tutor_id' => $tutorId,
            'specialization' => $request->input('specialization'),
            'phone' => $request->input('phone'),
            'bio' => $request->input('bio'),
            'cv' => $cvPath,
            'session_rate' => $request->input('rate'),
            'hourly_rate' => $request->input('hourly_rate'),
            'verification_code' => $verificationCode,
            'verification_code_expires_at' => $expiresAt,
        ], 1800); // 30 minutes

        // Store verification code mapping
        Cache::put('tutor_verification_' . $request->input('email'), [
            'token' => $registrationToken,
            'code' => $verificationCode,
            'expires_at' => $expiresAt,
        ], 1800);

        // Send verification email
        try {
            Mail::to($request->input('email'))->send(new \App\Mail\VerificationCodeMail($verificationCode, 'tutor'));
            
            return redirect()->route('verify.email', [
                'email' => $request->input('email'),
                'type' => 'tutor',
                'token' => $registrationToken
            ])->with('success', 'Registration information received! Please check your email for the verification code to complete your registration.');
        } catch (\Exception $e) {
            // Clean up on failure
            Cache::forget('tutor_registration_' . $registrationToken);
            Cache::forget('tutor_verification_' . $request->input('email'));
            Storage::disk('public')->delete($cvPath);
            
            return redirect()->back()->with('error', 'Failed to send verification email. Please try again.');
        }
    }
}
