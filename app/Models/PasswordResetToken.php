<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PasswordResetToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'token',
        'code',
        'expires_at',
        'used'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used' => 'boolean'
    ];

    /**
     * Generate a new password reset token and code
     */
    public static function generateToken($email)
    {
        // Delete any existing tokens for this email
        self::where('email', $email)->delete();

        // Generate new token and code
        $token = Str::random(60);
        $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT); // 6-digit code
        $expiresAt = Carbon::now()->addMinutes(15); // 15 minutes expiry

        return self::create([
            'email' => $email,
            'token' => $token,
            'code' => $code,
            'expires_at' => $expiresAt,
            'used' => false
        ]);
    }

    /**
     * Verify the code for a given email
     */
    public static function verifyCode($email, $code)
    {
        $token = self::where('email', $email)
            ->where('code', $code)
            ->where('used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        return $token;
    }

    /**
     * Mark token as used
     */
    public function markAsUsed()
    {
        $this->update(['used' => true]);
    }
}
