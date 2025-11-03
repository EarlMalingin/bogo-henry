<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class TutorNotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::guard('tutor')->id())
            ->where('user_type', 'tutor')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tutor.notifications', compact('notifications'));
    }

    public function destroy($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::guard('tutor')->id())
            ->where('user_type', 'tutor')
            ->firstOrFail();

        $notification->delete();

        return response()->json(['success' => true]);
    }
}
