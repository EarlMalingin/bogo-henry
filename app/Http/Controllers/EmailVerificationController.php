<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\Wallet;
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

        if ($type === 'tutor') {
            // For tutors, check cache for registration data
            $verificationData = Cache::get('tutor_verification_' . $email);
            
            if (!$verificationData) {
                return redirect()->back()
                    ->with('error', 'Registration session expired. Please register again.')
                    ->withInput();
            }

            // Check if verification code matches
            if ($verificationData['code'] !== $code) {
                return redirect()->back()
                    ->with('error', 'Invalid verification code.')
                    ->withInput();
            }

            // Check if code is expired
            if (Carbon::now()->gt($verificationData['expires_at'])) {
                return redirect()->back()
                    ->with('error', 'Verification code has expired. Please request a new one.')
                    ->withInput();
            }

            // Get registration data from cache
            $registrationToken = $verificationData['token'];
            $registrationData = Cache::get('tutor_registration_' . $registrationToken);

            if (!$registrationData) {
                return redirect()->back()
                    ->with('error', 'Registration data expired. Please register again.')
                    ->withInput();
            }

            // Move CV from temp to permanent storage
            $oldCvPath = $registrationData['cv'];
            $newCvPath = str_replace('temp-cvs/', 'cvs/', $oldCvPath);
            
            if (Storage::disk('public')->exists($oldCvPath)) {
                Storage::disk('public')->move($oldCvPath, $newCvPath);
            }

            // Create tutor account
            $tutor = Tutor::create([
                'first_name' => $registrationData['first_name'],
                'last_name' => $registrationData['last_name'],
                'email' => $registrationData['email'],
                'password' => $registrationData['password'],
                'tutor_id' => $registrationData['tutor_id'],
                'specialization' => $registrationData['specialization'],
                'phone' => $registrationData['phone'],
                'bio' => $registrationData['bio'],
                'cv' => $newCvPath,
                'session_rate' => $registrationData['session_rate'],
                'hourly_rate' => $registrationData['hourly_rate'] ?? null,
                'is_verified' => true, // Verified since they entered the code
                'registration_status' => 'pending', // Set to pending for admin approval
            ]);

            // Create wallet for the tutor
            Wallet::create([
                'user_id' => $tutor->id,
                'user_type' => 'tutor',
                'balance' => 0.00,
                'currency' => 'PHP'
            ]);

            // Clean up cache
            Cache::forget('tutor_registration_' . $registrationToken);
            Cache::forget('tutor_verification_' . $email);

            return redirect()->back()
                ->with('verification_success', 'Email verified successfully! Your account has been created and is now pending admin approval. You will be notified once your account is approved.');
        } else {
            // Student verification (existing flow)
            $user = Student::where('email', $email)->first();

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

        if ($type === 'tutor') {
            // For tutors, check cache for registration data
            $verificationData = Cache::get('tutor_verification_' . $email);
            
            if (!$verificationData) {
                return redirect()->back()
                    ->with('error', 'Registration session expired. Please register again.')
                    ->withInput();
            }

            // Generate new verification code
            $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $expiresAt = Carbon::now()->addMinutes(15);

            // Update cache with new code
            $verificationData['code'] = $verificationCode;
            $verificationData['expires_at'] = $expiresAt;
            Cache::put('tutor_verification_' . $email, $verificationData, 1800);

            // Update registration data cache
            $registrationToken = $verificationData['token'];
            $registrationData = Cache::get('tutor_registration_' . $registrationToken);
            if ($registrationData) {
                $registrationData['verification_code'] = $verificationCode;
                $registrationData['verification_code_expires_at'] = $expiresAt;
                Cache::put('tutor_registration_' . $registrationToken, $registrationData, 1800);
            }

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
        } else {
            // Student resend (existing flow)
            $user = Student::where('email', $email)->first();

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