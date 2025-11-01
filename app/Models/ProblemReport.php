<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProblemReport extends Model
{
    protected $fillable = [
        'student_id',
        'tutor_id',
        'problem_type',
        'subject',
        'description',
        'status',
        'admin_response',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    /**
     * Get the student that owns the problem report.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the tutor that owns the problem report.
     */
    public function tutor(): BelongsTo
    {
        return $this->belongsTo(Tutor::class);
    }
    
    /**
     * Get the reporter name (student or tutor)
     */
    public function getReporterNameAttribute()
    {
        if ($this->student_id) {
            return $this->student->first_name . ' ' . $this->student->last_name;
        }
        if ($this->tutor_id) {
            return $this->tutor->first_name . ' ' . $this->tutor->last_name;
        }
        return 'Unknown';
    }
    
    /**
     * Get the reporter type
     */
    public function getReporterTypeAttribute()
    {
        if ($this->student_id) {
            return 'Student';
        }
        if ($this->tutor_id) {
            return 'Tutor';
        }
        return 'Unknown';
    }
}
