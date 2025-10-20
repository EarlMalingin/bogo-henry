<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\Wallet;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));
        $type = $request->get('type', 'all'); // all|student|tutor

        $students = Student::query();
        $tutors = Tutor::query();

        if ($q !== '') {
            $students->where(function($w) use ($q){
                $w->where('first_name','like',"%$q%")
                  ->orWhere('last_name','like',"%$q%")
                  ->orWhere('email','like',"%$q%");
            });
            $tutors->where(function($w) use ($q){
                $w->where('first_name','like',"%$q%")
                  ->orWhere('last_name','like',"%$q%")
                  ->orWhere('email','like',"%$q%");
            });
        }

        $users = collect();
        if ($type === 'all' || $type === 'student') {
            $users = $users->merge(
                $students->latest()->get()->map(function($s){
                    $wallet = Wallet::where('user_id', $s->id)->where('user_type','student')->first();
                    return [
                        'id' => $s->id,
                        'name' => $s->getFullName(),
                        'email' => $s->email,
                        'type' => 'student',
                        'active' => (bool)($s->is_active ?? true),
                        'balance' => $wallet?->balance ?? 0,
                    ];
                })
            );
        }
        if ($type === 'all' || $type === 'tutor') {
            $users = $users->merge(
                $tutors->latest()->get()->map(function($t){
                    $wallet = Wallet::where('user_id', $t->id)->where('user_type','tutor')->first();
                    return [
                        'id' => $t->id,
                        'name' => $t->getFullName(),
                        'email' => $t->email,
                        'type' => 'tutor',
                        'active' => (bool)($t->is_active ?? true),
                        'balance' => $wallet?->balance ?? 0,
                    ];
                })
            );
        }

        $users = $users->sortBy('type')->values();
        return view('admin.users.index', compact('users', 'q', 'type'));
    }

    public function toggleActive(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'type' => 'required|in:student,tutor',
        ]);

        if ($request->type === 'student') {
            $user = Student::findOrFail($request->id);
        } else {
            $user = Tutor::findOrFail($request->id);
        }

        $current = (bool)($user->is_active ?? true);
        $user->is_active = !$current;
        $user->save();

        return back()->with('status', ($user->is_active ? 'Activated ' : 'Deactivated ') . $user->getFullName());
    }

    public function show($type, $id)
    {
        abort_unless(in_array($type, ['student','tutor']), 404);

        if ($type === 'student') {
            $user = Student::findOrFail($id);
        } else {
            $user = Tutor::findOrFail($id);
        }

        $wallet = Wallet::where('user_id', $user->id)->where('user_type', $type)->first();
        $balance = $wallet?->balance ?? 0;

        return view('admin.users.show', [
            'type' => $type,
            'user' => $user,
            'balance' => $balance
        ]);
    }

    public function detail($type, $id)
    {
        abort_unless(in_array($type, ['student','tutor']), 404);

        if ($type === 'student') {
            $u = Student::findOrFail($id);
            $extra = [
                'student_id' => $u->student_id,
                'course' => $u->course,
                'year_level' => $u->year_level,
            ];
        } else {
            $u = Tutor::findOrFail($id);
            $extra = [
                'tutor_id' => $u->tutor_id,
                'specialization' => $u->specialization,
                'session_rate' => $u->session_rate,
            ];
        }

        $wallet = Wallet::where('user_id', $u->id)->where('user_type', $type)->first();

        return response()->json([
            'id' => $u->id,
            'type' => $type,
            'name' => $u->getFullName(),
            'email' => $u->email,
            'phone' => $u->phone,
            'active' => (bool) ($u->is_active ?? true),
            'balance' => $wallet?->balance ?? 0,
            'extra' => $extra,
        ]);
    }
}


