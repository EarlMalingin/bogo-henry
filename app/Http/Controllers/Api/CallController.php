<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

class CallController extends Controller
{
    public function initiate(Request $request)
    {
        $request->validate([
            'callType' => 'required|in:audio,video',
            'callerId' => 'required|integer',
            'callerName' => 'required|string',
            'receiverId' => 'required|integer',
            'receiverType' => 'required|in:student,tutor',
            'roomId' => 'required|string'
        ]);

        try {
            broadcast(new \App\Events\IncomingCall([
                'callType' => $request->callType,
                'callerId' => $request->callerId,
                'callerName' => $request->callerName,
                'receiverId' => $request->receiverId,
                'receiverType' => $request->receiverType,
                'roomId' => $request->roomId
            ]));

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error initiating call: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function answer(Request $request)
    {
        $request->validate([
            'roomId' => 'required|string',
            'receiverId' => 'required|integer',
            'receiverType' => 'required|in:student,tutor'
        ]);

        try {
            broadcast(new \App\Events\CallAnswered([
                'roomId' => $request->roomId,
                'receiverId' => $request->receiverId,
                'receiverType' => $request->receiverType
            ]));

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function end(Request $request)
    {
        $request->validate([
            'roomId' => 'required|string',
            'endedBy' => 'required|integer'
        ]);

        try {
            broadcast(new \App\Events\CallEnded([
                'roomId' => $request->roomId,
                'endedBy' => $request->endedBy
            ]));

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}

