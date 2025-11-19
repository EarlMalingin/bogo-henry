<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserTyping implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;

    /**
     * Create a new event instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        $roomId = 'chat-' . min($this->data['senderId'], $this->data['receiverId']) . '-' . max($this->data['senderId'], $this->data['receiverId']);
        return [
            new PrivateChannel("private-{$roomId}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'user-typing';
    }

    public function broadcastWith(): array
    {
        return $this->data;
    }
}
