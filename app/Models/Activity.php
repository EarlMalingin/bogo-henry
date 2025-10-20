<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    protected $fillable = [
        'tutor_id',
        'student_id',
        'session_id',
        'title',
        'description',
        'type',
        'status',
        'instructions',
        'questions',
        'attachments',
        'due_date',
        'total_points',
        'time_limit',
        'feedback',
        'score',
        'submitted_at',
        'graded_at'
    ];

    protected $casts = [
        'questions' => 'array',
        'attachments' => 'array',
        'due_date' => 'datetime',
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime'
    ];

    public function tutor(): BelongsTo
    {
        return $this->belongsTo(Tutor::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class, 'session_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(ActivitySubmission::class);
    }

    public function studentSubmission($studentId): ?ActivitySubmission
    {
        return $this->submissions()->where('student_id', $studentId)->first();
    }

    // Helper methods
    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && $this->status !== 'completed';
    }

    public function getProgressPercentage(): int
    {
        if ($this->status === 'completed' || $this->status === 'graded') {
            return 100;
        }
        
        if ($this->status === 'in_progress') {
            return 50;
        }
        
        if ($this->status === 'sent') {
            return 25;
        }
        
        return 0;
    }

    public function getGradeLetter(): string
    {
        if (!$this->score || !$this->total_points) {
            return 'N/A';
        }

        $percentage = ($this->score / $this->total_points) * 100;

        if ($percentage >= 90) return 'A';
        if ($percentage >= 80) return 'B';
        if ($percentage >= 70) return 'C';
        if ($percentage >= 60) return 'D';
        return 'F';
    }

    public function isQuiz(): bool
    {
        return $this->type === 'quiz';
    }

    public function getQuizResults($submission): array
    {
        if (!$this->isQuiz() || !$submission || !$submission->answers) {
            return [
                'correct_count' => 0,
                'total_questions' => 0,
                'percentage' => 0,
                'details' => []
            ];
        }

        $correctCount = 0;
        $totalQuestions = count($this->questions);
        $details = [];

        foreach ($this->questions as $index => $question) {
            $studentAnswer = $submission->answers[$index] ?? null;
            $correctAnswer = $question['correct_answer'] ?? 0;
            $isCorrect = $studentAnswer === $correctAnswer;

            if ($isCorrect) {
                $correctCount++;
            }

            $details[] = [
                'question_index' => $index,
                'question_text' => $question['question'] ?? $question,
                'student_answer' => $studentAnswer,
                'correct_answer' => $correctAnswer,
                'is_correct' => $isCorrect
            ];
        }

        $percentage = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100) : 0;

        return [
            'correct_count' => $correctCount,
            'total_questions' => $totalQuestions,
            'percentage' => $percentage,
            'details' => $details
        ];
    }
}
