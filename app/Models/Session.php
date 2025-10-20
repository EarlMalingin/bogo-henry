<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\Tutor;

class Session extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'tutor_id',
        'session_type',
        'date',
        'start_time',
        'end_time',
        'status',
        'notes',
        'rate',
    ];

    protected $table = 'tutoring_sessions';

    protected $casts = [
        'date' => 'date',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    // Accessors
    public function getFormattedDateAttribute()
    {
        return $this->date->format('F j, Y');
    }

    public function getFormattedStartTimeAttribute()
    {
        return date('g:i A', strtotime($this->start_time));
    }

    public function getFormattedEndTimeAttribute()
    {
        return date('g:i A', strtotime($this->end_time));
    }

    public function getDurationAttribute()
    {
        $start = strtotime($this->start_time);
        $end = strtotime($this->end_time);
        $duration = ($end - $start) / 60; // in minutes
        
        if ($duration < 60) {
            return $duration . ' minutes';
        } else {
            $hours = floor($duration / 60);
            $minutes = $duration % 60;
            return $hours . 'h ' . $minutes . 'm';
        }
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeToday($query)
    {
        return $query->where('date', today());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', today());
    }
} 