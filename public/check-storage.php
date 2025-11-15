<?php
/**
 * Storage Checker Script for Hostinger
 * 
 * This script helps diagnose storage issues when exec() is disabled.
 * Access via: https://yourdomain.com/check-storage.php
 * 
 * IMPORTANT: Delete this file after fixing the issue for security!
 */

// Security: Only allow access from localhost or specific IP (uncomment and set your IP)
// $allowedIPs = ['127.0.0.1', '::1', 'YOUR_IP_HERE'];
// if (!in_array($_SERVER['REMOTE_ADDR'], $allowedIPs)) {
//     die('Access denied');
// }

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Storage Checker</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        h1 { color: #333; }
        .success { color: green; padding: 10px; background: #d4edda; border-radius: 4px; margin: 10px 0; }
        .error { color: red; padding: 10px; background: #f8d7da; border-radius: 4px; margin: 10px 0; }
        .warning { color: orange; padding: 10px; background: #fff3cd; border-radius: 4px; margin: 10px 0; }
        .info { color: blue; padding: 10px; background: #d1ecf1; border-radius: 4px; margin: 10px 0; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto; }
        .path { font-family: monospace; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Storage Configuration Checker</h1>
        
        <?php
        $basePath = dirname(__DIR__);
        $publicPath = __DIR__;
        $storagePath = $basePath . '/storage/app/public';
        $publicStoragePath = $publicPath . '/storage';
        $profilePicturesPath = $storagePath . '/profile-pictures';
        
        echo "<h2>Paths Check</h2>";
        echo "<div class='info'>";
        echo "<strong>Base Path:</strong> <span class='path'>{$basePath}</span><br>";
        echo "<strong>Public Path:</strong> <span class='path'>{$publicPath}</span><br>";
        echo "<strong>Storage Path:</strong> <span class='path'>{$storagePath}</span><br>";
        echo "<strong>Public Storage Path:</strong> <span class='path'>{$publicStoragePath}</span><br>";
        echo "</div>";
        
        echo "<h2>Directory Status</h2>";
        
        // Check storage/app/public
        if (is_dir($storagePath)) {
            echo "<div class='success'>✓ Storage directory exists: {$storagePath}</div>";
            if (is_writable($storagePath)) {
                echo "<div class='success'>✓ Storage directory is writable</div>";
            } else {
                echo "<div class='error'>✗ Storage directory is NOT writable (permissions issue)</div>";
            }
        } else {
            echo "<div class='error'>✗ Storage directory does NOT exist: {$storagePath}</div>";
        }
        
        // Check profile-pictures folder
        if (is_dir($profilePicturesPath)) {
            echo "<div class='success'>✓ Profile pictures directory exists</div>";
            $files = glob($profilePicturesPath . '/*');
            $fileCount = count($files);
            echo "<div class='info'>Found {$fileCount} file(s) in profile-pictures folder</div>";
            if ($fileCount > 0) {
                echo "<div class='info'><strong>Sample files:</strong><br>";
                foreach (array_slice($files, 0, 5) as $file) {
                    echo "&nbsp;&nbsp;• " . basename($file) . " (" . number_format(filesize($file)) . " bytes)<br>";
                }
                if ($fileCount > 5) {
                    echo "&nbsp;&nbsp;... and " . ($fileCount - 5) . " more<br>";
                }
                echo "</div>";
            }
        } else {
            echo "<div class='error'>✗ Profile pictures directory does NOT exist: {$profilePicturesPath}</div>";
        }
        
        // Check public/storage symlink
        echo "<h2>Symlink Status</h2>";
        if (is_link($publicStoragePath)) {
            $linkTarget = readlink($publicStoragePath);
            echo "<div class='success'>✓ Symlink exists and points to: <span class='path'>{$linkTarget}</span></div>";
            if (file_exists($linkTarget)) {
                echo "<div class='success'>✓ Symlink target exists and is accessible</div>";
            } else {
                echo "<div class='error'>✗ Symlink target does NOT exist (broken symlink)</div>";
            }
        } elseif (is_dir($publicStoragePath)) {
            echo "<div class='warning'>⚠ public/storage exists but is NOT a symlink (it's a regular directory)</div>";
            echo "<div class='info'>This might work, but symlink is recommended for better performance</div>";
        } else {
            echo "<div class='error'>✗ public/storage does NOT exist</div>";
            echo "<div class='info'><strong>Action needed:</strong> Create symlink manually via cPanel File Manager</div>";
        }
        
        // Test file access
        echo "<h2>File Access Test</h2>";
        if (is_dir($profilePicturesPath)) {
            $testFiles = glob($profilePicturesPath . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
            if (!empty($testFiles)) {
                $testFile = $testFiles[0];
                $relativePath = 'profile-pictures/' . basename($testFile);
                
                echo "<div class='info'>Testing access to: <span class='path'>{$relativePath}</span></div>";
                
                // Test via storage path
                $storageTestPath = $storagePath . '/' . $relativePath;
                if (file_exists($storageTestPath)) {
                    echo "<div class='success'>✓ File accessible via storage path</div>";
                } else {
                    echo "<div class='error'>✗ File NOT accessible via storage path</div>";
                }
                
                // Test via public storage (if symlink exists)
                if (is_link($publicStoragePath) || is_dir($publicStoragePath)) {
                    $publicTestPath = $publicStoragePath . '/' . $relativePath;
                    if (file_exists($publicTestPath)) {
                        echo "<div class='success'>✓ File accessible via public/storage path</div>";
                    } else {
                        echo "<div class='warning'>⚠ File NOT accessible via public/storage (symlink may be broken)</div>";
                    }
                }
            } else {
                echo "<div class='warning'>⚠ No image files found in profile-pictures directory</div>";
            }
        }
        
        // PHP Configuration
        echo "<h2>PHP Configuration</h2>";
        echo "<div class='info'>";
        echo "<strong>exec() function:</strong> " . (function_exists('exec') ? '<span style="color:green">Enabled</span>' : '<span style="color:red">Disabled</span>') . "<br>";
        echo "<strong>symlink() function:</strong> " . (function_exists('symlink') ? '<span style="color:green">Enabled</span>' : '<span style="color:red">Disabled</span>') . "<br>";
        echo "<strong>PHP Version:</strong> " . PHP_VERSION . "<br>";
        echo "</div>";
        
        // Recommendations
        echo "<h2>Recommendations</h2>";
        if (!is_link($publicStoragePath) && !is_dir($publicStoragePath)) {
            echo "<div class='warning'>";
            echo "<strong>Action Required:</strong><br>";
            echo "1. Log into cPanel File Manager<br>";
            echo "2. Navigate to your <code>public</code> folder<br>";
            echo "3. Create a symbolic link named <code>storage</code> pointing to <code>../storage/app/public</code><br>";
            echo "4. Or manually copy files from <code>storage/app/public</code> to <code>public/storage</code><br>";
            echo "</div>";
        }
        
        if (function_exists('exec') && function_exists('symlink')) {
            echo "<div class='info'>You can try running: <code>php artisan storage:link</code> via SSH</div>";
        } else {
            echo "<div class='info'>Since exec() is disabled, you must create the symlink manually via cPanel</div>";
        }
        ?>
        
        <hr>
        <p style="color: #999; font-size: 12px;">
            <strong>Security Note:</strong> Delete this file after fixing the storage issue!
        </p>
    </div>
</body>
</html>

