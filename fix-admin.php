<?php
/**
 * Quick Admin Account Fix Script
 * 
 * This script creates or resets the admin account.
 * Run: php fix-admin.php
 * 
 * IMPORTANT: Delete this file after use for security!
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Admin;

echo "========================================\n";
echo "MentorHub Admin Account Fix\n";
echo "========================================\n\n";

// Check if admins table exists
try {
    $admin = Admin::where('email', 'admin@mentorhub.com')->first();
    
    if (!$admin) {
        echo "Creating admin account...\n";
        Admin::create([
            'name' => 'Administrator',
            'email' => 'admin@mentorhub.com',
            'password' => 'earlgwapo123'
        ]);
        echo "✓ Admin account created successfully!\n\n";
    } else {
        echo "Admin account exists. Resetting password...\n";
        $admin->password = 'earlgwapo123';
        $admin->save();
        echo "✓ Admin password reset successfully!\n\n";
    }
    
    echo "========================================\n";
    echo "Admin Credentials:\n";
    echo "========================================\n";
    echo "Email: admin@mentorhub.com\n";
    echo "Password: earlgwapo123\n";
    echo "========================================\n\n";
    echo "⚠️  IMPORTANT: Change this password after first login!\n";
    echo "⚠️  Delete this file (fix-admin.php) for security!\n\n";
    
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n\n";
    echo "Possible issues:\n";
    echo "1. Database connection failed - check .env file\n";
    echo "2. Admins table doesn't exist - run: php artisan migrate\n";
    echo "3. Wrong directory - make sure you're in the project root\n";
    exit(1);
}

