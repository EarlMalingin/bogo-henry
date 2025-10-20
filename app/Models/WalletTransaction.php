<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTransaction extends Model
{
    protected $fillable = [
        'wallet_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'status',
        'payment_method',
        'reference_number',
        'paymongo_payment_intent_id',
        'paymongo_source_id',
        'description',
        'metadata'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'metadata' => 'array'
    ];

    /**
     * Set the amount attribute with validation
     */
    public function setAmountAttribute($value)
    {
        // Ensure amount is never negative
        $this->attributes['amount'] = max(0, (float) $value);
    }

    /**
     * Set the balance_before attribute with validation
     */
    public function setBalanceBeforeAttribute($value)
    {
        $this->attributes['balance_before'] = max(0, (float) $value);
    }

    /**
     * Set the balance_after attribute with validation
     */
    public function setBalanceAfterAttribute($value)
    {
        $this->attributes['balance_after'] = max(0, (float) $value);
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function markAsCompleted(): void
    {
        $this->update(['status' => 'completed']);
    }

    public function markAsFailed(): void
    {
        $this->update(['status' => 'failed']);
    }
}
