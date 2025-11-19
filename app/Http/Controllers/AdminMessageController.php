<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Tutor;
use App\Models\Student;

class AdminMessageController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'user_type' => 'required|in:student,tutor',
            'message' => 'required|string|min:1',
        ]);

        try {
            if ($request->user_type === 'tutor') {
                $user = Tutor::findOrFail($request->user_id);
            } else {
                $user = Student::findOrFail($request->user_id);
            }

            // Create notification instead of message
            Notification::create([
                'user_id' => $user->id,
                'user_type' => $request->user_type,
                'type' => 'admin_message',
                'title' => 'Message from MentorHub Admin',
                'message' => $request->message,
                'is_read' => false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully to ' . $user->getFullName(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message: ' . $e->getMessage(),
            ], 500);
        }
    }
}
