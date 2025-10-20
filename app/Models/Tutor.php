<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Session; // Added this import
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tutor extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'tutor_id',
        'email',
        'password',
        'specialization',
        'phone',
        'bio',
        'profile_picture',
        'cv',
        'session_rate',
        'verification_code',
        'verification_code_expires_at',
        'is_verified',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function chatRooms()
    {
        return $this->hasMany(ChatRoom::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id')->where('sender_type', 'tutor');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    // Helper methods
    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getInitials()
    {
        return strtoupper(substr($this->first_name, 0, 1) . substr($this->last_name, 0, 1));
    }

    public function getAvatar()
    {
        if ($this->profile_picture) {
            return asset('storage/' . $this->profile_picture);
        }
        return $this->getInitials();
    }

    public function getCvUrl()
    {
        if ($this->cv) {
            return asset('storage/' . $this->cv);
        }
        return null;
    }
}
