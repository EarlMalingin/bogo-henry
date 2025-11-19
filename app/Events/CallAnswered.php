<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CallAnswered implements ShouldBroadcast
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
        $roomId = $this->data['roomId'];
        return [
            new PrivateChannel("private-call-{$roomId}"),
            new PrivateChannel("private-user-{$this->data['receiverType']}-{$this->data['receiverId']}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'call-answered';
    }

    public function broadcastWith(): array
    {
        return $this->data;
    }
}
