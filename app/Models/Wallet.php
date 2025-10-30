<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    protected $fillable = [
        'user_id',
        'user_type',
        'balance',
        'currency',
        'is_active'
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'is_active' => 'boolean',
        'metadata' => 'array'
    ];

    /**
     * Get the encrypted balance attribute
     */
    public function getBalanceAttribute($value)
    {
        return $value; // Already stored as decimal, no encryption needed for display
    }

    /**
     * Set the balance attribute with validation
     */
    public function setBalanceAttribute($value)
    {
        // Ensure balance is never negative
        $this->attributes['balance'] = max(0, (float) $value);
    }

    public function user(): BelongsTo
    {
        if ($this->user_type === 'student') {
            return $this->belongsTo(Student::class, 'user_id');
        }
        
        return $this->belongsTo(Tutor::class, 'user_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function addFunds(float $amount, string $type = 'cash_in', array $metadata = []): WalletTransaction
    {
        $balanceBefore = $this->balance;
        $this->balance = $this->balance + $amount;
        $this->save();

        return $this->transactions()->create([
            'type' => $type,
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->balance,
            'status' => 'completed',
            'metadata' => $metadata
        ]);
    }

    public function deductFunds(float $amount, string $type = 'cash_out', array $metadata = []): ?WalletTransaction
    {
        if ($this->balance < $amount) {
            return null; // Insufficient funds
        }

        $balanceBefore = $this->balance;
        $this->balance = $this->balance - $amount;
        $this->save();

        return $this->transactions()->create([
            'type' => $type,
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->balance,
            'status' => 'completed',
            'metadata' => $metadata
        ]);
    }

    public function canAfford(float $amount): bool
    {
        return $this->balance >= $amount;
    }

    /**
     * Get the user's full name based on user_type
     */
    public function getUserFullNameAttribute()
    {
        if ($this->user_type === 'student') {
            $student = \App\Models\Student::find($this->user_id);
            return $student ? $student->first_name . ' ' . $student->last_name : null;
        } elseif ($this->user_type === 'tutor') {
            $tutor = \App\Models\Tutor::find($this->user_id);
            return $tutor ? $tutor->first_name . ' ' . $tutor->last_name : null;
        }
        return null;
    }
}
