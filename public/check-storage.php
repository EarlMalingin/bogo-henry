<?php
/**
 * Storage Diagnostic Script - Web Accessible Version
 * Access via: https://yourdomain.com/check-storage.php
 * 
 * This script helps diagnose storage and profile picture issues
 * 
 * IMPORTANT: Delete this file after running it for security reasons!
 */

// Only allow access if APP_DEBUG is true or if you add a secret key
// For security, you can add: ?key=YOUR_SECRET_KEY
$secretKey = 'CHANGE_THIS_SECRET_KEY_BEFORE_USE';
$providedKey = $_GET['key'] ?? '';

// Uncomment the line below to require a secret key
// if ($providedKey !== $secretKey) { die('Unauthorized'); }

// Set content type to HTML for better readability
header('Content-Type: text/html; charset=utf-8');

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Storage;
use App\Models\Student;
use App\Models\Tutor;

?>
<!DOCTYPE html>
<html>
<head>
    <title>Storage Diagnostic Tool</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: #252526;
            padding: 20px;
            border-radius: 8px;
        }
        h1 {
            color: #4ec9b0;
            border-bottom: 2px solid #4ec9b0;
            padding-bottom: 10px;
        }
        h2 {
            color: #569cd6;
            margin-top: 30px;
        }
        .success { color: #4ec9b0; }
        .error { color: #f48771; }
        .warning { color: #dcdcaa; }
        .info { color: #9cdcfe; }
        pre {
            background: #1e1e1e;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
            border-left: 3px solid #007acc;
        }
        .section {
            margin: 20px 0;
            padding: 15px;
            background: #1e1e1e;
            border-radius: 4px;
        }
        .file-list {
            margin-left: 20px;
        }
        .file-list li {
            margin: 5px 0;
        }
        .command {
            background: #1e1e1e;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
            border-left: 3px solid #dcdcaa;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Storage Diagnostic Tool</h1>
        
        <?php
        echo "<div class='section'>";
        echo "<h2>1. Storage Paths</h2>";
        
        $storagePath = storage_path('app/public');
        $publicStoragePath = public_path('storage');
        
        echo "<p><strong>Storage Path:</strong> <span class='info'>{$storagePath}</span></p>";
        echo "<p><strong>Public Storage Path:</strong> <span class='info'>{$publicStoragePath}</span></p>";
        echo "<p><strong>Storage Directory Exists:</strong> " . 
             (is_dir($storagePath) ? "<span class='success'>✓ YES</span>" : "<span class='error'>✗ NO</span>") . "</p>";
        echo "<p><strong>Public Storage Symlink/Directory:</strong> " . 
             (is_link($publicStoragePath) || is_dir($publicStoragePath) ? 
             "<span class='success'>✓ EXISTS</span> " . (is_link($publicStoragePath) ? "(Symlink)" : "(Directory)") : 
             "<span class='error'>✗ MISSING</span>") . "</p>";
        echo "</div>";
        
        echo "<div class='section'>";
        echo "<h2>2. Profile Pictures Directory</h2>";
        
        $profilePicturesPath = $storagePath . '/profile-pictures';
        echo "<p><strong>Path:</strong> <span class='info'>{$profilePicturesPath}</span></p>";
        echo "<p><strong>Exists:</strong> " . 
             (is_dir($profilePicturesPath) ? "<span class='success'>✓ YES</span>" : "<span class='error'>✗ NO</span>") . "</p>";
        
        if (is_dir($profilePicturesPath)) {
            $files = glob($profilePicturesPath . '/*');
            echo "<p><strong>Files Count:</strong> <span class='info'>" . count($files) . "</span></p>";
            
            if (count($files) > 0) {
                echo "<p><strong>Sample Files (first 10):</strong></p><ul class='file-list'>";
                foreach (array_slice($files, 0, 10) as $file) {
                    $filename = basename($file);
                    $size = filesize($file);
                    echo "<li><span class='info'>{$filename}</span> (" . number_format($size / 1024, 2) . " KB)</li>";
                }
                echo "</ul>";
            }
        } else {
            echo "<p class='warning'>⚠️ Profile pictures directory doesn't exist!</p>";
        }
        echo "</div>";
        
        echo "<div class='section'>";
        echo "<h2>3. Students with Profile Pictures</h2>";
        
        $students = Student::whereNotNull('profile_picture')->get();
        echo "<p><strong>Total Students with Profile Pictures:</strong> <span class='info'>{$students->count()}</span></p>";
        
        if ($students->count() > 0) {
            echo "<table style='width:100%; border-collapse: collapse; margin-top: 10px;'>";
            echo "<tr style='background: #007acc;'><th style='padding: 8px; text-align: left;'>ID</th><th style='padding: 8px; text-align: left;'>Name</th><th style='padding: 8px; text-align: left;'>Path</th><th style='padding: 8px; text-align: left;'>Status</th></tr>";
            
            foreach ($students->take(10) as $index => $student) {
                $exists = Storage::disk('public')->exists($student->profile_picture);
                $status = $exists ? "<span class='success'>✓ EXISTS</span>" : "<span class='error'>✗ MISSING</span>";
                $bgColor = $index % 2 == 0 ? '#1e1e1e' : '#252526';
                
                echo "<tr style='background: {$bgColor};'>";
                echo "<td style='padding: 8px;'>{$student->id}</td>";
                echo "<td style='padding: 8px;'>{$student->getFullName()}</td>";
                echo "<td style='padding: 8px;'><span class='info'>{$student->profile_picture}</span></td>";
                echo "<td style='padding: 8px;'>{$status}</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        echo "</div>";
        
        echo "<div class='section'>";
        echo "<h2>4. Tutors with Profile Pictures</h2>";
        
        $tutors = Tutor::whereNotNull('profile_picture')->get();
        echo "<p><strong>Total Tutors with Profile Pictures:</strong> <span class='info'>{$tutors->count()}</span></p>";
        
        if ($tutors->count() > 0) {
            echo "<table style='width:100%; border-collapse: collapse; margin-top: 10px;'>";
            echo "<tr style='background: #007acc;'><th style='padding: 8px; text-align: left;'>ID</th><th style='padding: 8px; text-align: left;'>Name</th><th style='padding: 8px; text-align: left;'>Path</th><th style='padding: 8px; text-align: left;'>Status</th></tr>";
            
            foreach ($tutors->take(10) as $index => $tutor) {
                $exists = Storage::disk('public')->exists($tutor->profile_picture);
                $status = $exists ? "<span class='success'>✓ EXISTS</span>" : "<span class='error'>✗ MISSING</span>";
                $bgColor = $index % 2 == 0 ? '#1e1e1e' : '#252526';
                
                echo "<tr style='background: {$bgColor};'>";
                echo "<td style='padding: 8px;'>{$tutor->id}</td>";
                echo "<td style='padding: 8px;'>{$tutor->getFullName()}</td>";
                echo "<td style='padding: 8px;'><span class='info'>{$tutor->profile_picture}</span></td>";
                echo "<td style='padding: 8px;'>{$status}</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        echo "</div>";
        
        echo "<div class='section'>";
        echo "<h2>5. Recommendations</h2>";
        
        $recommendations = [];
        
        if (!is_link($publicStoragePath) && !is_dir($publicStoragePath)) {
            $recommendations[] = "Run: <code>php artisan storage:link</code> to create the storage symlink";
        }
        
        if (!is_dir($profilePicturesPath)) {
            $recommendations[] = "Profile pictures directory doesn't exist. It should be created automatically when uploading, but you can create it manually if needed.";
        }
        
        // Check for missing files
        $missingStudents = $students->filter(function($student) {
            return !Storage::disk('public')->exists($student->profile_picture);
        });
        
        $missingTutors = $tutors->filter(function($tutor) {
            return !Storage::disk('public')->exists($tutor->profile_picture);
        });
        
        if ($missingStudents->count() > 0) {
            $recommendations[] = "{$missingStudents->count()} student(s) have profile picture paths in database but files are missing. They may need to re-upload their profile pictures.";
        }
        
        if ($missingTutors->count() > 0) {
            $recommendations[] = "{$missingTutors->count()} tutor(s) have profile picture paths in database but files are missing. They may need to re-upload their profile pictures.";
        }
        
        if (empty($recommendations)) {
            echo "<p class='success'>✓ Everything looks good! No issues detected.</p>";
        } else {
            echo "<ul>";
            foreach ($recommendations as $rec) {
                echo "<li class='warning'>⚠️ {$rec}</li>";
            }
            echo "</ul>";
        }
        
        echo "<div class='command'>";
        echo "<strong>SSH Commands (if you have SSH access):</strong><br>";
        echo "<code>php artisan storage:link</code><br>";
        echo "<code>ls -la storage/app/public/profile-pictures/</code><br>";
        echo "<code>ls -la public/storage</code>";
        echo "</div>";
        
        echo "</div>";
        
        echo "<div class='section'>";
        echo "<p class='warning'><strong>⚠️ SECURITY WARNING:</strong> Delete this file after running it!</p>";
        echo "</div>";
        ?>
    </div>
</body>
</html>

