<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;

class NewMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $senderId = $this->message->sender_id;
        $receiverId = $this->message->receiver_id;
        $roomId = 'chat-' . min($senderId, $receiverId) . '-' . max($senderId, $receiverId);
        
        return [
            new PrivateChannel("private-{$roomId}"),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'new-message';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'senderId' => $this->message->sender_id,
            'senderType' => $this->message->sender_type,
            'receiverId' => $this->message->receiver_id,
            'receiverType' => $this->message->receiver_type,
            'message' => $this->message->message,
            'fileData' => $this->message->file_path ? [
                'path' => $this->message->file_path,
                'name' => $this->message->file_name,
                'type' => $this->message->file_type
            ] : null,
            'timestamp' => $this->message->created_at->toIso8601String()
        ];
    }
}
