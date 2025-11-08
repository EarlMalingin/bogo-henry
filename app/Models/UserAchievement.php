<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserAchievement extends Model
{
    protected $fillable = [
        'achievement_id',
        'user_type',
        'user_id',
        'unlocked_at',
        'progress',
        'is_unlocked',
    ];

    protected $casts = [
        'unlocked_at' => 'datetime',
        'is_unlocked' => 'boolean',
    ];

    public function achievement(): BelongsTo
    {
        return $this->belongsTo(Achievement::class);
    }

    public function user(): MorphTo
    {
        return $this->morphTo();
    }
}

