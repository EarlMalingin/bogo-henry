<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\Session;
use App\Models\WalletTransaction;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    public function dashboard()
    {
        $studentCount = Student::count();
        $tutorCount = Tutor::count();
        $totalUsers = $studentCount + $tutorCount;

        $upcomingToday = Session::where('status', 'accepted')
            ->whereBetween('date', [now()->startOfDay(), now()->endOfDay()])
            ->count();

        $pendingPayouts = WalletTransaction::where('type', 'cash_out')
            ->where('status', 'pending')
            ->count();

        $recentSessions = Session::with(['student', 'tutor'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentWallet = WalletTransaction::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Compose a simple recent users list (latest created first)
        $recentStudents = Student::orderBy('created_at', 'desc')->limit(5)->get()->map(function($s){
            return [
                'name' => $s->getFullName(),
                'type' => 'Student',
                'email' => $s->email,
                'created_at' => $s->created_at,
            ];
        });
        $recentTutors = Tutor::orderBy('created_at', 'desc')->limit(5)->get()->map(function($t){
            return [
                'name' => $t->getFullName(),
                'type' => 'Tutor',
                'email' => $t->email,
                'created_at' => $t->created_at,
            ];
        });
        $recentUsers = $recentStudents->merge($recentTutors)->sortByDesc('created_at')->values()->take(8);

        return view('admin.dashboard', compact(
            'totalUsers', 'upcomingToday', 'pendingPayouts', 'recentSessions', 'recentWallet', 'recentUsers'
        ));
    }
}


