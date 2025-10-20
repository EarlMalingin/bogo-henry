<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'created_by',
        'created_by_type'
    ];

    // Relationships
    public function students()
    {
        return $this->belongsToMany(Student::class);
    }

    public function tutors()
    {
        return $this->belongsToMany(Tutor::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
