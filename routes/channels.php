<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Chat room channels - allow authenticated users
Broadcast::channel('private-chat-{roomId}', function ($user, $roomId) {
    // Allow authenticated users to access chat rooms
    // You can add more specific logic here if needed
    return ['id' => $user->id ?? null];
});

// User personal channels - users can only access their own
Broadcast::channel('private-user-{userType}-{userId}', function ($user, $userType, $userId) {
    // Check if user matches the channel
    if ($userType === 'student') {
        $student = \App\Models\Student::find($user->id ?? null);
        return $student && $student->id == $userId ? ['id' => $student->id] : null;
    } elseif ($userType === 'tutor') {
        $tutor = \App\Models\Tutor::find($user->id ?? null);
        return $tutor && $tutor->id == $userId ? ['id' => $tutor->id] : null;
    }
    return null;
});

// Call room channels - allow authenticated users
Broadcast::channel('private-call-{roomId}', function ($user, $roomId) {
    // Allow authenticated users in call rooms
    return ['id' => $user->id ?? null];
});

