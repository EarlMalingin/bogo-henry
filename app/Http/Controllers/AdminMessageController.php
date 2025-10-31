<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Tutor;

class AdminMessageController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'tutor_id' => 'required|exists:tutors,id',
            'message' => 'required|string|min:1',
        ]);

        try {
            $tutor = Tutor::findOrFail($request->tutor_id);

            Message::create([
                'chat_room_id' => null,
                'conversation_id' => 'admin_' . $tutor->id,
                'sender_id' => 0, // Admin doesn't have an ID in the system
                'sender_type' => 'admin',
                'receiver_id' => $tutor->id,
                'receiver_type' => 'tutor',
                'message' => $request->message,
                'is_read' => false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully to ' . $tutor->getFullName(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message: ' . $e->getMessage(),
            ], 500);
        }
    }
}
