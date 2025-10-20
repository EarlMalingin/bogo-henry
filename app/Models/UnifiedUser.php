<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UnifiedUser extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'profile_picture',
        'user_type',
        'student_id',
        'year_level',
        'course',
        'subjects_interest',
        'tutor_id',
        'specialization',
        'bio',
        'session_rate',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'session_rate' => 'decimal:2',
    ];

    // Helper methods
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function isStudent()
    {
        return in_array($this->user_type, ['student', 'both']);
    }

    public function isTutor()
    {
        return in_array($this->user_type, ['tutor', 'both']);
    }

    public function isBoth()
    {
        return $this->user_type === 'both';
    }

    // Relationships
    public function sessionsAsStudent()
    {
        return $this->hasMany(Session::class, 'student_id');
    }

    public function sessionsAsTutor()
    {
        return $this->hasMany(Session::class, 'tutor_id');
    }

    public function messagesSent()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function messagesReceived()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }
}
