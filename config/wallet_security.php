<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Wallet Security Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains security settings for the wallet system including
    | transaction limits, rate limiting, and validation rules.
    |
    */

    'limits' => [
        'daily_cash_in' => 100000, // ₱100,000 per day
        'daily_cash_out' => 100000, // ₱100,000 per day
        'max_transaction' => 50000, // ₱50,000 per transaction
        'min_transaction' => 1, // ₱1 minimum
    ],

    'rate_limiting' => [
        'cash_in_attempts' => 5, // 5 attempts per minute
        'cash_out_attempts' => 3, // 3 attempts per 5 minutes
        'balance_checks' => 30, // 30 checks per minute
        'general_requests' => 30, // 30 requests per minute
    ],

    'validation' => [
        'account_number_pattern' => '/^[0-9]{10,12}$/', // GCash format
        'account_name_pattern' => '/^[a-zA-Z\s\.\-\']+$/', // Letters, spaces, dots, hyphens, apostrophes
        'amount_pattern' => '/^\d+(\.\d{1,2})?$/', // Valid decimal with max 2 decimal places
    ],

    'security' => [
        'require_webhook_verification' => env('WALLET_REQUIRE_WEBHOOK_VERIFICATION', true),
        'log_all_attempts' => env('WALLET_LOG_ALL_ATTEMPTS', true),
        'mask_sensitive_data' => env('WALLET_MASK_SENSITIVE_DATA', true),
        'require_https' => env('WALLET_REQUIRE_HTTPS', true),
    ],

    'audit' => [
        'retention_days' => 365, // Keep audit logs for 1 year
        'log_failed_attempts' => true,
        'log_successful_transactions' => true,
        'log_balance_checks' => false, // Too frequent
    ],
];
