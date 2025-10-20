<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\PasswordResetToken;
use App\Models\Student;
use App\Models\Tutor;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetCode;

class PasswordResetController extends Controller
{
    /**
     * Show the forgot password form
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset code
     */
    public function sendResetCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $email = $request->email;

        // Check if email exists in either students or tutors table
        $student = Student::where('email', $email)->first();
        $tutor = Tutor::where('email', $email)->first();

        if (!$student && !$tutor) {
            // Don't reveal if email exists or not for security
            return back()->with('status', 'If an account exists with that email, we have sent a password reset code.');
        }

        // Generate reset token and code
        $resetToken = PasswordResetToken::generateToken($email);
        $code = $resetToken->code;

        try {
            // Send email with the reset code
            Mail::to($email)->send(new PasswordResetCode($code, $email));
            
            // Store email in session for the next step
            session(['reset_email' => $email]);

            return redirect()->route('password.verify')->with('status', 'Password reset code has been sent to your email address. Please check your inbox and enter the 6-digit code.');
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Failed to send password reset email: ' . $e->getMessage());
            
            // Delete the token since email failed
            $resetToken->delete();
            
            return back()->withErrors(['email' => 'Failed to send reset code. Please try again later.'])->withInput();
        }
    }

    /**
     * Show the code verification form
     */
    public function showVerifyCode()
    {
        if (!session('reset_email')) {
            return redirect()->route('password.request');
        }

        return view('auth.verify-code');
    }

    /**
     * Verify the reset code
     */
    public function verifyCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|size:6'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $email = session('reset_email');
        $code = $request->code;

        if (!$email) {
            return redirect()->route('password.request');
        }

        $resetToken = PasswordResetToken::verifyCode($email, $code);

        if (!$resetToken) {
            return back()->withErrors(['code' => 'Invalid or expired code.'])->withInput();
        }

        // Store token in session for password reset
        session(['reset_token' => $resetToken->token]);

        return redirect()->route('password.reset');
    }

    /**
     * Show the password reset form
     */
    public function showResetPassword()
    {
        if (!session('reset_token')) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password');
    }

    /**
     * Reset the password
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $token = session('reset_token');
        $email = session('reset_email');

        if (!$token || !$email) {
            return redirect()->route('password.request');
        }

        $resetToken = PasswordResetToken::where('token', $token)
            ->where('email', $email)
            ->where('used', false)
            ->first();

        if (!$resetToken) {
            return redirect()->route('password.request')->withErrors(['email' => 'Invalid reset token.']);
        }

        // Update password in the appropriate table
        $student = Student::where('email', $email)->first();
        $tutor = Tutor::where('email', $email)->first();

        if ($student) {
            $student->update(['password' => Hash::make($request->password)]);
        } elseif ($tutor) {
            $tutor->update(['password' => Hash::make($request->password)]);
        }

        // Mark token as used
        $resetToken->markAsUsed();

        // Clear session
        session()->forget(['reset_email', 'reset_token']);

        return redirect()->route('select-role-login')->with('status', 'Your password has been reset successfully. You can now log in with your new password.');
    }
}
