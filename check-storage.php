<?php
/**
 * Storage Diagnostic Script
 * Run this via: php check-storage.php
 * 
 * This script helps diagnose storage and profile picture issues
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Storage;
use App\Models\Student;
use App\Models\Tutor;

echo "=== Storage Diagnostic Tool ===\n\n";

// Check storage directory
$storagePath = storage_path('app/public');
$publicStoragePath = public_path('storage');

echo "1. Storage Paths:\n";
echo "   Storage: {$storagePath}\n";
echo "   Public Storage: {$publicStoragePath}\n";
echo "   Storage exists: " . (is_dir($storagePath) ? "YES" : "NO") . "\n";
echo "   Public storage symlink exists: " . (is_link($publicStoragePath) || is_dir($publicStoragePath) ? "YES" : "NO") . "\n\n";

// Check profile pictures directory
$profilePicturesPath = $storagePath . '/profile-pictures';
echo "2. Profile Pictures Directory:\n";
echo "   Path: {$profilePicturesPath}\n";
echo "   Exists: " . (is_dir($profilePicturesPath) ? "YES" : "NO") . "\n";
if (is_dir($profilePicturesPath)) {
    $files = glob($profilePicturesPath . '/*');
    echo "   Files count: " . count($files) . "\n";
}
echo "\n";

// Check students with profile pictures
echo "3. Students with Profile Pictures:\n";
$students = Student::whereNotNull('profile_picture')->get();
echo "   Total: " . $students->count() . "\n";
foreach ($students->take(5) as $student) {
    $exists = Storage::disk('public')->exists($student->profile_picture);
    echo "   - Student ID {$student->id}: {$student->profile_picture} - " . ($exists ? "EXISTS" : "MISSING") . "\n";
}
echo "\n";

// Check tutors with profile pictures
echo "4. Tutors with Profile Pictures:\n";
$tutors = Tutor::whereNotNull('profile_picture')->get();
echo "   Total: " . $tutors->count() . "\n";
foreach ($tutors->take(5) as $tutor) {
    $exists = Storage::disk('public')->exists($tutor->profile_picture);
    echo "   - Tutor ID {$tutor->id}: {$tutor->profile_picture} - " . ($exists ? "EXISTS" : "MISSING") . "\n";
}
echo "\n";

// Recommendations
echo "=== Recommendations ===\n";
if (!is_link($publicStoragePath) && !is_dir($publicStoragePath)) {
    echo "⚠️  Run: php artisan storage:link\n";
}
if (!is_dir($profilePicturesPath)) {
    echo "⚠️  Profile pictures directory doesn't exist. Creating...\n";
    mkdir($profilePicturesPath, 0755, true);
    echo "✓ Created profile pictures directory\n";
}

echo "\nDone!\n";

