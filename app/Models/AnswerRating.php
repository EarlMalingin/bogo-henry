<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnswerRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'answer_id',
        'student_id',
        'rating',
        'comment',
    ];

    // Relationships
    public function answer(): BelongsTo
    {
        return $this->belongsTo(AssignmentAnswer::class, 'answer_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
