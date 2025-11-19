<?php
/**
 * Diagnostic script to check view compilation
 * Run this on your Hostinger server: php check-view-compilation.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== View Compilation Diagnostic ===\n\n";

// Check if view file exists
$viewPath = resource_path('views/livewire/call-manager.blade.php');
echo "1. View file exists: " . (file_exists($viewPath) ? "YES ✓\n" : "NO ✗\n");
if (file_exists($viewPath)) {
    echo "   File size: " . filesize($viewPath) . " bytes\n";
    echo "   File permissions: " . substr(sprintf('%o', fileperms($viewPath)), -4) . "\n";
}

// Check storage directory
$storagePath = storage_path('framework/views');
echo "\n2. Storage directory exists: " . (is_dir($storagePath) ? "YES ✓\n" : "NO ✗\n");
if (is_dir($storagePath)) {
    echo "   Directory permissions: " . substr(sprintf('%o', fileperms($storagePath)), -4) . "\n";
    echo "   Is writable: " . (is_writable($storagePath) ? "YES ✓\n" : "NO ✗\n");
    echo "   Files in directory: " . count(glob($storagePath . '/*')) . "\n";
}

// Check Livewire component class first
echo "\n3. Checking CallManager component class:\n";
try {
    // Check if file exists
    $classFile = app_path('Livewire/CallManager.php');
    echo "   Class file exists: " . (file_exists($classFile) ? "YES ✓\n" : "NO ✗\n");
    
    if (file_exists($classFile)) {
        echo "   File size: " . filesize($classFile) . " bytes\n";
        echo "   File permissions: " . substr(sprintf('%o', fileperms($classFile)), -4) . "\n";
    }
    
    // Try to load the class
    $componentExists = class_exists('App\Livewire\CallManager', false);
    if (!$componentExists) {
        // Try requiring the file directly
        if (file_exists($classFile)) {
            require_once $classFile;
            $componentExists = class_exists('App\Livewire\CallManager', false);
        }
    }
    
    echo "   Class exists: " . ($componentExists ? "YES ✓\n" : "NO ✗\n");
    
    if ($componentExists) {
        try {
            $component = new \App\Livewire\CallManager();
            echo "   Component instantiated successfully! ✓\n";
            
            // Test rendering through Livewire
            echo "\n4. Testing Livewire view rendering:\n";
            try {
                $rendered = $component->render();
                echo "   View rendered successfully! ✓\n";
                echo "   Rendered size: " . strlen($rendered) . " bytes\n";
                echo "   First 100 chars: " . substr($rendered, 0, 100) . "...\n";
            } catch (\Exception $e) {
                echo "   ERROR rendering view: " . $e->getMessage() . " ✗\n";
                echo "   File: " . $e->getFile() . "\n";
                echo "   Line: " . $e->getLine() . "\n";
            }
        } catch (\Exception $e) {
            echo "   ERROR instantiating: " . $e->getMessage() . " ✗\n";
            echo "   File: " . $e->getFile() . "\n";
            echo "   Line: " . $e->getLine() . "\n";
        }
    } else {
        echo "\n4. Cannot test view - class not found ✗\n";
        echo "   SOLUTION: Run 'composer dump-autoload --optimize' to regenerate autoloader\n";
    }
} catch (\Exception $e) {
    echo "   ERROR: " . $e->getMessage() . " ✗\n";
    echo "   File: " . $e->getFile() . "\n";
    echo "   Line: " . $e->getLine() . "\n";
}

echo "\n=== End Diagnostic ===\n";

