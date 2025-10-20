<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CHECKING USERS IN DATABASE ===\n\n";

try {
    // Check students
    echo "STUDENTS:\n";
    $students = DB::table('students')->select('id', 'first_name', 'last_name', 'email')->get();
    if ($students->count() > 0) {
        foreach ($students as $student) {
            echo "ID: {$student->id}, Name: {$student->first_name} {$student->last_name}, Email: {$student->email}\n";
        }
    } else {
        echo "No students found\n";
    }
    
    echo "\nTUTORS:\n";
    $tutors = DB::table('tutors')->select('id', 'first_name', 'last_name', 'email')->get();
    if ($tutors->count() > 0) {
        foreach ($tutors as $tutor) {
            echo "ID: {$tutor->id}, Name: {$tutor->first_name} {$tutor->last_name}, Email: {$tutor->email}\n";
        }
    } else {
        echo "No tutors found\n";
    }
    
    echo "\nUSERS:\n";
    $users = DB::table('users')->select('id', 'name', 'email')->get();
    if ($users->count() > 0) {
        foreach ($users as $user) {
            echo "ID: {$user->id}, Name: {$user->name}, Email: {$user->email}\n";
        }
    } else {
        echo "No users found\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
