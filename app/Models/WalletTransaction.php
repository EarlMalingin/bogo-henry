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
        'metadata',
        'payment_proof_path',
        'payment_proof_description',
        'payment_proof_uploaded_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'metadata' => 'array',
        'payment_proof_uploaded_at' => 'datetime'
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

    public function isPendingApproval(): bool
    {
        return $this->status === 'pending_approval';
    }

    public function hasPaymentProof(): bool
    {
        // Check if payment_proof_path exists and is not empty/null
        return !empty($this->payment_proof_path);
    }

    public function getPaymentProofUrl(): ?string
    {
        if ($this->payment_proof_path) {
            return asset('storage/' . $this->payment_proof_path);
        }
        return null;
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
