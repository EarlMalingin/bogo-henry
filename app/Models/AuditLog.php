<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_type',
        'action',
        'details',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'details' => 'array'
    ];

    public function user()
    {
        if ($this->user_type === 'student') {
            return $this->belongsTo(Student::class, 'user_id');
        }
        
        return $this->belongsTo(Tutor::class, 'user_id');
    }
}
