<?php

namespace App\Http\Controllers\uiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class homeController extends Controller
{
    function homePage () {
        return view('homePage');
    }

    function signupPage (Request $request) {
        $role = $request->query('role');
        if ($role === 'tutor') {
            return view('tutor-signup');
        }
        return view('signup');
    }

    public function signupSubmit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'year_level' => 'required|string',
            'course' => 'required|string',
            'subjects_interest' => 'nullable|string',
            'phone' => 'nullable|string',
            'terms' => 'accepted',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $studentId = Student::generateStudentId();

        $user = User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'), // hashed automatically by model cast
            'student_id' => $studentId,
            'year_level' => $request->input('year_level'),
            'course' => $request->input('course'),
            'subjects_interest' => $request->input('subjects_interest'),
            'phone' => $request->input('phone'),
        ]);

        return redirect()->back()->with('success', 'Registration successful! You can now log in.');
    }

    function loginPage () {
        return view('loginStudent');
    }

    function selectRolePage () {
        return view('select-role');
    }
}
