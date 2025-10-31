<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function studentLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Do not clear the other guard's session to avoid logging out another user in the same browser

        if (Auth::guard('student')->attempt($credentials)) {
            $student = Auth::guard('student')->user();
            
            // Check if email is verified
            if (!$student->is_verified) {
                Auth::guard('student')->logout();
                return back()->withErrors([
                    'email' => 'Please verify your email address before logging in. Check your email for the verification code.',
                ])->withInput($request->only('email'));
            }
            
            $request->session()->regenerate();
            return redirect()->intended(route('student.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    public function studentLogout(Request $request)
    {
        Auth::guard('student')->logout();
        // Only regenerate the session token, don't invalidate the entire session
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    public function tutorLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Do not clear the other guard's session to avoid logging out another user in the same browser

        if (Auth::guard('tutor')->attempt($credentials)) {
            $tutor = Auth::guard('tutor')->user();
            
            // Check if email is verified
            if (!$tutor->is_verified) {
                Auth::guard('tutor')->logout();
                return back()->withErrors([
                    'email' => 'Please verify your email address before logging in. Check your email for the verification code.',
                ])->withInput($request->only('email'));
            }
            
            // Check if registration is approved
            if ($tutor->registration_status !== 'approved') {
                Auth::guard('tutor')->logout();
                $statusMessage = $tutor->registration_status === 'pending' 
                    ? 'Your registration is pending admin approval. Please wait for approval before logging in.'
                    : 'Your registration has been rejected. Please contact support for assistance.';
                
                return back()->withErrors([
                    'email' => $statusMessage,
                ])->withInput($request->only('email'));
            }
            
            $request->session()->regenerate();
            return redirect()->intended('/tutor/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    public function tutorLogout(Request $request)
    {
        Auth::guard('tutor')->logout();
        // Only regenerate the session token, don't invalidate the entire session
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
} 