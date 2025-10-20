<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetCode;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Test email sending
try {
    $testCode = '123456';
    $testEmail = 'test@example.com'; // Replace with your email for testing
    
    Mail::to($testEmail)->send(new PasswordResetCode($testCode, $testEmail));
    
    echo "Test email sent successfully!\n";
    echo "Check your email at: $testEmail\n";
    echo "Test code: $testCode\n";
    
} catch (Exception $e) {
    echo "Error sending email: " . $e->getMessage() . "\n";
    echo "Make sure you have configured your email settings in .env file\n";
    echo "See EMAIL_CONFIGURATION.md for setup instructions\n";
} 