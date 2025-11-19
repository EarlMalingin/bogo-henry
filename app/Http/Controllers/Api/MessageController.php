<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'senderId' => 'required|integer',
            'senderType' => 'required|in:student,tutor',
            'receiverId' => 'required|integer',
            'receiverType' => 'required|in:student,tutor',
            'message' => 'required|string',
            'fileData' => 'nullable|array'
        ]);

        try {
            // Create message in database
            $message = Message::create([
                'chat_room_id' => 1, // Default or calculate based on users
                'conversation_id' => 1,
                'sender_id' => $request->senderId,
                'sender_type' => $request->senderType,
                'receiver_id' => $request->receiverId,
                'receiver_type' => $request->receiverType,
                'message' => $request->message,
                'file_path' => $request->fileData['path'] ?? null,
                'file_name' => $request->fileData['name'] ?? null,
                'file_type' => $request->fileData['type'] ?? null,
            ]);

            // Broadcast via Pusher
            broadcast(new \App\Events\NewMessage($message))->toOthers();

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            Log::error('Error sending message: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function typing(Request $request)
    {
        $request->validate([
            'senderId' => 'required|integer',
            'senderType' => 'required|in:student,tutor',
            'receiverId' => 'required|integer',
            'receiverType' => 'required|in:student,tutor',
            'isTyping' => 'required|boolean'
        ]);

        try {
            $roomId = 'chat-' . min($request->senderId, $request->receiverId) . '-' . max($request->senderId, $request->receiverId);
            
            broadcast(new \App\Events\UserTyping([
                'senderId' => $request->senderId,
                'senderType' => $request->senderType,
                'receiverId' => $request->receiverId,
                'receiverType' => $request->receiverType,
                'isTyping' => $request->isTyping
            ]))->toOthers();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function markRead(Request $request)
    {
        $request->validate([
            'messageId' => 'required|integer',
            'readerId' => 'required|integer',
            'readerType' => 'required|in:student,tutor'
        ]);

        try {
            $message = Message::findOrFail($request->messageId);
            // Update read status if needed
            
            broadcast(new \App\Events\MessageRead([
                'messageId' => $request->messageId,
                'readerId' => $request->readerId,
                'readerType' => $request->readerType
            ]))->toOthers();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}

