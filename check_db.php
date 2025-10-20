<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Checking tutors table structure...\n";

// Get all columns in the tutors table
$columns = Schema::getColumnListing('tutors');
echo "Current columns in tutors table:\n";
foreach ($columns as $column) {
    echo "- $column\n";
}

// Check if profile_picture column exists
if (!in_array('profile_picture', $columns)) {
    echo "\nprofile_picture column does not exist. Adding it...\n";
    
    Schema::table('tutors', function (Blueprint $table) {
        $table->string('profile_picture')->nullable()->after('bio');
    });
    
    echo "profile_picture column added successfully!\n";
} else {
    echo "\nprofile_picture column already exists.\n";
}

echo "\nFinal columns in tutors table:\n";
$finalColumns = Schema::getColumnListing('tutors');
foreach ($finalColumns as $column) {
    echo "- $column\n";
} 