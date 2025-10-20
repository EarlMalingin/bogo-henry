<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Student;
use App\Models\Tutor;
use App\Mail\VerificationCodeMail;

class EmailVerificationController extends Controller
{
    public function showVerificationForm(Request $request)
    {
        $email = $request->query('email');
        $type = $request->query('type'); // 'student' or 'tutor'
        
        if (!$email || !$type) {
            return redirect()->route('home')->with('error', 'Invalid verification link.');
        }

        return view('auth.verify-email', compact('email', 'type'));
    }

    public function verifyCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'verification_code' => 'required|string|size:6|regex:/^[0-9]{6}$/',
            'email' => 'required|email',
            'type' => 'required|in:student,tutor',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $email = $request->input('email');
        $code = $request->input('verification_code');
        $type = $request->input('type');

        // Find the user based on type
        if ($type === 'student') {
            $user = Student::where('email', $email)->first();
        } else {
            $user = Tutor::where('email', $email)->first();
        }

        if (!$user) {
            return redirect()->back()
                ->with('error', 'User not found.')
                ->withInput();
        }

        // Check if verification code matches and is not expired
        if ($user->verification_code !== $code) {
            return redirect()->back()
                ->with('error', 'Invalid verification code.')
                ->withInput();
        }

        if ($user->verification_code_expires_at && Carbon::now()->gt($user->verification_code_expires_at)) {
            return redirect()->back()
                ->with('error', 'Verification code has expired. Please request a new one.')
                ->withInput();
        }

        // Mark user as verified
        $user->update([
            'is_verified' => true,
            'verification_code' => null,
            'verification_code_expires_at' => null,
        ]);

        // Show success popup on verification page before redirecting
        return redirect()->back()
            ->with('verification_success', 'Email verified successfully! Your account has been activated.');
    }

    public function resendCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'type' => 'required|in:student,tutor',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $email = $request->input('email');
        $type = $request->input('type');

        // Find the user based on type
        if ($type === 'student') {
            $user = Student::where('email', $email)->first();
        } else {
            $user = Tutor::where('email', $email)->first();
        }

        if (!$user) {
            return redirect()->back()
                ->with('error', 'User not found.')
                ->withInput();
        }

        // Generate 6-digit verification code
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = Carbon::now()->addMinutes(15); // Code expires in 15 minutes

        $user->update([
            'verification_code' => $verificationCode,
            'verification_code_expires_at' => $expiresAt,
        ]);

        // Send verification email
        try {
            Mail::to($email)->send(new VerificationCodeMail($verificationCode, $type));
            
            return redirect()->back()
                ->with('success', 'A new verification code has been sent to your email.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to send verification email. Please try again.')
                ->withInput();
        }
    }

    public static function sendVerificationCode($user, $type)
    {
        // Generate 6-digit verification code
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = Carbon::now()->addMinutes(15); // Code expires in 15 minutes

        $user->update([
            'verification_code' => $verificationCode,
            'verification_code_expires_at' => $expiresAt,
        ]);

        // Send verification email
        try {
            Mail::to($user->email)->send(new VerificationCodeMail($verificationCode, $type));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}