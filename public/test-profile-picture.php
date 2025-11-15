<?php
/**
 * Test Profile Picture Paths
 * 
 * Access via: https://yourdomain.com/test-profile-picture.php?tutor_id=1
 * 
 * IMPORTANT: Delete this file after testing!
 */

// Security: Only allow in development or with specific IP
if (env('APP_ENV') === 'production' && $_SERVER['REMOTE_ADDR'] !== 'YOUR_IP') {
    die('Access denied');
}

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Profile Picture Path Test</title>
    <style>
        body { font-family: monospace; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        .success { color: green; padding: 10px; background: #d4edda; border-radius: 4px; margin: 10px 0; }
        .error { color: red; padding: 10px; background: #f8d7da; border-radius: 4px; margin: 10px 0; }
        .info { color: blue; padding: 10px; background: #d1ecf1; border-radius: 4px; margin: 10px 0; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Profile Picture Path Test</h1>
        
        <?php
        $tutorId = $_GET['tutor_id'] ?? 1;
        
        try {
            $tutor = \App\Models\Tutor::find($tutorId);
            
            if (!$tutor) {
                echo "<div class='error'>Tutor ID {$tutorId} not found</div>";
                exit;
            }
            
            echo "<h2>Tutor: {$tutor->first_name} {$tutor->last_name} (ID: {$tutor->id})</h2>";
            echo "<div class='info'><strong>Profile Picture Path in DB:</strong> " . ($tutor->profile_picture ?: 'NULL') . "</div>";
            
            if (!$tutor->profile_picture) {
                echo "<div class='error'>No profile picture set in database</div>";
                exit;
            }
            
            $diskName = env('FILESYSTEM_DISK', 'public');
            echo "<div class='info'><strong>FILESYSTEM_DISK:</strong> {$diskName}</div>";
            
            echo "<h3>Path Information</h3>";
            echo "<table>";
            echo "<tr><th>Path Type</th><th>Value</th></tr>";
            echo "<tr><td>base_path()</td><td>" . base_path() . "</td></tr>";
            echo "<tr><td>public_path()</td><td>" . public_path() . "</td></tr>";
            echo "<tr><td>storage_path()</td><td>" . storage_path() . "</td></tr>";
            echo "<tr><td>profile_picture (DB)</td><td>" . $tutor->profile_picture . "</td></tr>";
            echo "</table>";
            
            echo "<h3>Testing Paths</h3>";
            $testPaths = [
                'Storage Disk (public_html_storage)' => function() use ($tutor) {
                    try {
                        return \Storage::disk('public_html_storage')->path($tutor->profile_picture);
                    } catch (\Exception $e) {
                        return "ERROR: " . $e->getMessage();
                    }
                },
                'Storage Disk (public)' => function() use ($tutor) {
                    try {
                        return \Storage::disk('public')->path($tutor->profile_picture);
                    } catch (\Exception $e) {
                        return "ERROR: " . $e->getMessage();
                    }
                },
                'Hardcoded Hostinger Path' => '/home/u394503238/domains/uclm-mentorhub.com/public_html/storage/' . $tutor->profile_picture,
                'base_path + public_html' => base_path() . '/public_html/storage/' . $tutor->profile_picture,
                'dirname(base_path) + public_html' => dirname(base_path()) . '/public_html/storage/' . $tutor->profile_picture,
                'public_path + storage' => public_path('storage/' . $tutor->profile_picture),
                'storage_path + app/public' => storage_path('app/public/' . $tutor->profile_picture),
            ];
            
            echo "<table>";
            echo "<tr><th>Path Name</th><th>Full Path</th><th>Exists</th><th>Is File</th></tr>";
            
            $foundPath = null;
            foreach ($testPaths as $name => $path) {
                if (is_callable($path)) {
                    $fullPath = $path();
                } else {
                    $fullPath = $path;
                }
                
                $exists = file_exists($fullPath);
                $isFile = $exists && is_file($fullPath);
                
                if ($exists && $isFile && !$foundPath) {
                    $foundPath = $fullPath;
                }
                
                $status = $exists && $isFile ? "<span style='color:green'>✓</span>" : "<span style='color:red'>✗</span>";
                
                echo "<tr>";
                echo "<td>{$name}</td>";
                echo "<td><pre style='margin:0;'>{$fullPath}</pre></td>";
                echo "<td>{$status}</td>";
                echo "<td>" . ($isFile ? 'Yes' : 'No') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            if ($foundPath) {
                echo "<div class='success'>✓ Found file at: <pre>{$foundPath}</pre></div>";
                echo "<div class='info'>File size: " . number_format(filesize($foundPath)) . " bytes</div>";
                echo "<div class='info'>File permissions: " . substr(sprintf('%o', fileperms($foundPath)), -4) . "</div>";
            } else {
                echo "<div class='error'>✗ File not found in any tested path</div>";
            }
            
        } catch (\Exception $e) {
            echo "<div class='error'>Error: " . $e->getMessage() . "</div>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
        }
        ?>
        
        <hr>
        <p style="color: #999; font-size: 12px;">
            <strong>Security Note:</strong> Delete this file after testing!
        </p>
    </div>
</body>
</html>

