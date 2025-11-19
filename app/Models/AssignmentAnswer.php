<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssignmentAnswer extends Model
{
    protected $fillable = [
        'assignment_id',
        'tutor_id',
        'answer',
        'file_path',
        'file_name',
    ];

    // Relationships
    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function tutor(): BelongsTo
    {
        return $this->belongsTo(Tutor::class);
    }

    public function ratings()
    {
        return $this->hasMany(AnswerRating::class, 'answer_id');
    }

    // Helper methods
    public function getFileUrlAttribute()
    {
        if ($this->file_path) {
            return asset('storage/' . $this->file_path);
        }
        return null;
    }

    public function getAverageRatingAttribute()
    {
        $ratings = $this->ratings;
        if ($ratings->isEmpty()) {
            return 0;
        }
        return round($ratings->avg('rating'), 1);
    }

    public function getRatingCountAttribute()
    {
        return $this->ratings()->count();
    }

    public function hasRatingFromStudent($studentId)
    {
        return $this->ratings()->where('student_id', $studentId)->exists();
    }

    public function getStudentRating($studentId)
    {
        return $this->ratings()->where('student_id', $studentId)->first();
    }
}
