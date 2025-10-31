<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\Wallet;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\EmailVerificationController;

class RegisterController extends Controller
{
    public function studentRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\']+$/',
            'last_name' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\']+$/',
            'email' => 'required|string|email|max:255|unique:students',
            'password' => 'required|string|confirmed|min:8',
            'student_id' => 'required|string|unique:students',
            'year_level' => 'required|string',
            'course' => 'required|string',
            'subjects_interest' => 'nullable|string',
            'phone' => 'nullable|string',
            'terms' => 'accepted',
        ], [
            'first_name.regex' => 'First name can only contain letters, spaces, hyphens, and apostrophes.',
            'last_name.regex' => 'Last name can only contain letters, spaces, hyphens, and apostrophes.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $student = Student::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'student_id' => $request->input('student_id'),
            'year_level' => $request->input('year_level'),
            'course' => $request->input('course'),
            'subjects_interest' => $request->input('subjects_interest'),
            'phone' => $request->input('phone'),
            'is_verified' => false,
        ]);

        // Create wallet for the student
        Wallet::create([
            'user_id' => $student->id,
            'user_type' => 'student',
            'balance' => 0.00,
            'currency' => 'PHP'
        ]);

        // Send verification email
        if (EmailVerificationController::sendVerificationCode($student, 'student')) {
            return redirect()->route('verify.email', [
                'email' => $student->email,
                'type' => 'student'
            ])->with('success', 'Registration successful! Please check your email for verification code.');
        } else {
            return redirect()->back()->with('error', 'Registration successful but failed to send verification email. Please contact support.');
        }
    }

    
}
