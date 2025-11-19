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

    /**
     * Get all notifications for tutor dashboard (API endpoint)
     */
    public function getAll()
    {
        $tutor = Auth::guard('tutor')->user();
        
        $notifications = Notification::where('user_id', $tutor->id)
            ->where('user_type', 'tutor')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'is_read' => $notification->is_read,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'created_at_full' => $notification->created_at->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json($notifications);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::guard('tutor')->id())
            ->where('user_type', 'tutor')
            ->firstOrFail();

        $notification->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::guard('tutor')->id())
            ->where('user_type', 'tutor')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
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
