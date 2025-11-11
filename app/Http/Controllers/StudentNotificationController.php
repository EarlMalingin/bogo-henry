<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class StudentNotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::guard('student')->id())
            ->where('user_type', 'student')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('student.notifications', compact('notifications'));
    }

    public function destroy($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::guard('student')->id())
            ->where('user_type', 'student')
            ->firstOrFail();

        $notification->delete();

        return response()->json(['success' => true]);
    }

    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::guard('student')->id())
            ->where('user_type', 'student')
            ->firstOrFail();

        $notification->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }
}
