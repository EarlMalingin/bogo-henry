<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Session;
use App\Models\Review;
use App\Models\AssignmentAnswer;
use App\Models\AnswerRating;
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
        'hourly_rate',
        'verification_code',
        'verification_code_expires_at',
        'is_verified',
        'is_active',
        'registration_status',
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

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function assignmentAnswers()
    {
        return $this->hasMany(AssignmentAnswer::class);
    }

    // Get all ratings from assignment answers
    public function getAssignmentAnswerRatings()
    {
        return AnswerRating::whereHas('answer', function($query) {
            $query->where('tutor_id', $this->id);
        })->get();
    }

    // Helper methods
    public function getAverageRating()
    {
        // Get ratings from sessions (reviews)
        $sessionRatings = $this->reviews()->pluck('rating');
        
        // Get ratings from assignment answers
        $answerRatings = AnswerRating::whereHas('answer', function($query) {
            $query->where('tutor_id', $this->id);
        })->pluck('rating');
        
        // Combine all ratings
        $allRatings = $sessionRatings->merge($answerRatings);
        
        if ($allRatings->isEmpty()) {
            return 0;
        }
        
        return round($allRatings->avg(), 2);
    }

    public function getRatingCount()
    {
        // Count ratings from sessions
        $sessionRatingCount = $this->reviews()->count();
        
        // Count ratings from assignment answers
        $answerRatingCount = AnswerRating::whereHas('answer', function($query) {
            $query->where('tutor_id', $this->id);
        })->count();
        
        return $sessionRatingCount + $answerRatingCount;
    }
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

    public static function generateTutorId(): string
    {
        do {
            // Generate an 8-digit number
            $number = str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
            $id = 'TUT-' . $number;
        } while (self::where('tutor_id', $id)->exists());

        return $id;
    }
}
