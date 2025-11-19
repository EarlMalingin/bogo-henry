<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

class WebRTCController extends Controller
{
    public function offer(Request $request)
    {
        $request->validate([
            'roomId' => 'required|string',
            'offer' => 'required',
            'from' => 'required|integer'
        ]);

        try {
            broadcast(new \App\Events\WebRTCOffer([
                'roomId' => $request->roomId,
                'offer' => $request->offer,
                'from' => $request->from
            ]));

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error sending WebRTC offer: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function answer(Request $request)
    {
        $request->validate([
            'roomId' => 'required|string',
            'answer' => 'required',
            'from' => 'required|integer'
        ]);

        try {
            broadcast(new \App\Events\WebRTCAnswer([
                'roomId' => $request->roomId,
                'answer' => $request->answer,
                'from' => $request->from
            ]));

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function iceCandidate(Request $request)
    {
        $request->validate([
            'roomId' => 'required|string',
            'candidate' => 'required',
            'from' => 'required|integer'
        ]);

        try {
            broadcast(new \App\Events\WebRTCIceCandidate([
                'roomId' => $request->roomId,
                'candidate' => $request->candidate,
                'from' => $request->from
            ]));

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}

