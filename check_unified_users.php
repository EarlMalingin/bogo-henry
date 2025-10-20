<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== UNIFIED USERS TABLE ===\n";
$users = DB::table('unified_users')->get();
foreach ($users as $user) {
    echo "ID: {$user->id}, Name: {$user->first_name} {$user->last_name}, Email: {$user->email}, Type: {$user->user_type}\n";
    if ($user->student_id) {
        echo "  Student ID: {$user->student_id}\n";
    }
    if ($user->tutor_id) {
        echo "  Tutor ID: {$user->tutor_id}\n";
    }
    echo "\n";
}

echo "\n=== ORIGINAL STUDENTS TABLE ===\n";
$students = DB::table('students')->get();
foreach ($students as $student) {
    echo "ID: {$student->id}, Name: {$student->first_name} {$student->last_name}, Email: {$student->email}\n";
}

echo "\n=== ORIGINAL TUTORS TABLE ===\n";
$tutors = DB::table('tutors')->get();
foreach ($tutors as $tutor) {
    echo "ID: {$tutor->id}, Name: {$tutor->first_name} {$tutor->last_name}, Email: {$tutor->email}\n";
}
