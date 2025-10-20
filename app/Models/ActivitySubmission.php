<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivitySubmission extends Model
{
    protected $fillable = [
        'activity_id',
        'student_id',
        'answers',
        'attachments',
        'notes',
        'status',
        'score',
        'feedback',
        'submitted_at',
        'graded_at',
        'time_spent'
    ];

    protected $casts = [
        'answers' => 'array',
        'attachments' => 'array',
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime'
    ];

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    // Helper methods
    public function getGradePercentage(): float
    {
        if (!$this->score || !$this->activity->total_points) {
            return 0;
        }
        return ($this->score / $this->activity->total_points) * 100;
    }

    public function getGradeLetter(): string
    {
        $percentage = $this->getGradePercentage();
        
        if ($percentage >= 90) return 'A';
        if ($percentage >= 80) return 'B';
        if ($percentage >= 70) return 'C';
        if ($percentage >= 60) return 'D';
        return 'F';
    }

    public function isOverdue(): bool
    {
        return $this->activity->due_date && 
               $this->activity->due_date->isPast() && 
               $this->status !== 'submitted';
    }
}
