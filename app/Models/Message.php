<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_room_id',
        'sender_id',
        'sender_type',
        'receiver_id',
        'receiver_type',
        'message',
        'file_path',
        'file_name',
        'file_type',
        'conversation_id',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // Relationships
    public function sender()
    {
        if ($this->sender_type === 'student') {
            return $this->belongsTo(Student::class, 'sender_id');
        } elseif ($this->sender_type === 'admin') {
            // Admin doesn't have a model, return null or handle differently
            return null;
        }
        return $this->belongsTo(Tutor::class, 'sender_id');
    }

    public function receiver()
    {
        if ($this->receiver_type === 'student') {
            return $this->belongsTo(Student::class, 'receiver_id');
        }
        return $this->belongsTo(Tutor::class, 'receiver_id');
    }

    // Scopes
    public function scopeBetweenUsers($query, $user1Id, $user1Type, $user2Id, $user2Type)
    {
        return $query->where(function ($q) use ($user1Id, $user1Type, $user2Id, $user2Type) {
            $q->where(function ($subQ) use ($user1Id, $user1Type, $user2Id, $user2Type) {
                $subQ->where('sender_id', $user1Id)
                      ->where('sender_type', $user1Type)
                      ->where('receiver_id', $user2Id)
                      ->where('receiver_type', $user2Type);
            })->orWhere(function ($subQ) use ($user1Id, $user1Type, $user2Id, $user2Type) {
                $subQ->where('sender_id', $user2Id)
                      ->where('sender_type', $user2Type)
                      ->where('receiver_id', $user1Id)
                      ->where('receiver_type', $user1Type);
            });
        });
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    // Helper methods
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => Carbon::now()
        ]);
    }

    public function getFormattedTimeAttribute()
    {
        return $this->created_at->format('g:i A');
    }

    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('M j, Y');
    }

    public function isFile()
    {
        return !empty($this->file_path);
    }

    public function getFileUrlAttribute()
    {
        if ($this->file_path) {
            return asset('storage/' . $this->file_path);
        }
        return null;
    }

    public function isImage()
    {
        if (!$this->file_type) return false;
        return in_array($this->file_type, ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
    }
}
