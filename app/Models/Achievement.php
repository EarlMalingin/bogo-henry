<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Achievement extends Model
{
    protected $fillable = [
        'name',
        'description',
        'icon',
        'badge_color',
        'type',
        'category',
        'points',
        'requirement_value',
        'requirement_type',
        'is_active',
    ];

    public function userAchievements(): HasMany
    {
        return $this->hasMany(UserAchievement::class);
    }
}
