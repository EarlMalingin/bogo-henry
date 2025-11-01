<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    protected $fillable = [
        'student_id',
        'subject',
        'question',
        'description',
        'file_path',
        'file_name',
        'status',
        'price',
        'selected_answer_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    // Relationships
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(AssignmentAnswer::class);
    }

    public function selectedAnswer(): BelongsTo
    {
        return $this->belongsTo(AssignmentAnswer::class, 'selected_answer_id');
    }

    public function paidAnswer()
    {
        return $this->answers()->latest()->first();
    }

    // Helper methods
    public function isAnswered(): bool
    {
        return $this->status === 'answered' || $this->status === 'paid';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }
}
