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