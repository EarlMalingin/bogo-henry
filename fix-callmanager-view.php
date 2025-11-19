<?php
/**
 * Fix script for CallManager view compilation issues
 * Run this on your Hostinger server: php fix-callmanager-view.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Fixing CallManager View Compilation ===\n\n";

// Step 1: Clear all caches
echo "1. Clearing all caches...\n";
try {
    \Artisan::call('optimize:clear');
    echo "   ✓ Caches cleared\n";
} catch (\Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

// Step 2: Remove compiled views
echo "\n2. Removing compiled views...\n";
$viewPath = storage_path('framework/views');
if (is_dir($viewPath)) {
    $files = glob($viewPath . '/*');
    $count = 0;
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
            $count++;
        }
    }
    echo "   ✓ Removed {$count} compiled view files\n";
} else {
    echo "   ✗ View directory not found\n";
}

// Step 3: Regenerate autoloader
echo "\n3. Regenerating autoloader...\n";
$autoloaderRegenerated = false;

// Try multiple methods since exec() might be disabled
try {
    // Method 1: Try using Symfony Process component (if available)
    if (class_exists('\Symfony\Component\Process\Process')) {
        $process = new \Symfony\Component\Process\Process(['composer', 'dump-autoload', '--optimize'], getcwd());
        $process->setTimeout(60);
        $process->run();
        
        if ($process->isSuccessful()) {
            echo "   ✓ Autoloader regenerated (using Process)\n";
            $autoloaderRegenerated = true;
        } else {
            echo "   ⚠ Process method failed, trying alternatives...\n";
        }
    }
} catch (\Exception $e) {
    echo "   ⚠ Process method not available: " . $e->getMessage() . "\n";
}

// Method 2: Try shell_exec if available
if (!$autoloaderRegenerated && function_exists('shell_exec')) {
    try {
        $output = shell_exec('composer dump-autoload --optimize 2>&1');
        if ($output !== null) {
            echo "   ✓ Autoloader regenerated (using shell_exec)\n";
            $autoloaderRegenerated = true;
        }
    } catch (\Exception $e) {
        echo "   ⚠ shell_exec failed: " . $e->getMessage() . "\n";
    }
}

// Method 3: Try exec if available
if (!$autoloaderRegenerated && function_exists('exec')) {
    try {
        $output = [];
        $returnVar = 0;
        exec('composer dump-autoload --optimize 2>&1', $output, $returnVar);
        if ($returnVar === 0) {
            echo "   ✓ Autoloader regenerated (using exec)\n";
            $autoloaderRegenerated = true;
        }
    } catch (\Exception $e) {
        echo "   ⚠ exec failed: " . $e->getMessage() . "\n";
    }
}

// If all methods failed, provide manual instructions
if (!$autoloaderRegenerated) {
    echo "   ⚠ Cannot regenerate autoloader automatically\n";
    echo "   ⚠ Please run this command manually in SSH:\n";
    echo "      composer dump-autoload --optimize\n";
    echo "\n";
}

// Step 4: Verify CallManager class exists
echo "\n4. Verifying CallManager class...\n";
$classFile = app_path('Livewire/CallManager.php');
if (file_exists($classFile)) {
    echo "   ✓ Class file exists\n";
    
    // Try to load it
    require_once $classFile;
    if (class_exists('App\Livewire\CallManager', false)) {
        echo "   ✓ Class can be loaded\n";
    } else {
        echo "   ✗ Class cannot be loaded - check syntax\n";
    }
} else {
    echo "   ✗ Class file not found at: {$classFile}\n";
}

// Step 5: Verify view file exists
echo "\n5. Verifying view file...\n";
$viewFile = resource_path('views/livewire/call-manager.blade.php');
if (file_exists($viewFile)) {
    echo "   ✓ View file exists\n";
    echo "   File size: " . filesize($viewFile) . " bytes\n";
} else {
    echo "   ✗ View file not found at: {$viewFile}\n";
}

// Step 6: Test component instantiation
echo "\n6. Testing component instantiation...\n";
try {
    if (class_exists('App\Livewire\CallManager', false)) {
        $component = new \App\Livewire\CallManager();
        echo "   ✓ Component instantiated successfully\n";
        
        // Try to render
        try {
            $rendered = $component->render();
            echo "   ✓ Component rendered successfully\n";
            echo "   Rendered size: " . strlen($rendered) . " bytes\n";
        } catch (\Exception $e) {
            echo "   ✗ Error rendering: " . $e->getMessage() . "\n";
        }
    } else {
        echo "   ✗ Class not found - run 'composer dump-autoload --optimize'\n";
    }
} catch (\Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

// Step 7: Fix permissions
echo "\n7. Fixing permissions...\n";
$paths = [
    storage_path('framework/views'),
    storage_path('framework/cache'),
    storage_path('framework/sessions'),
    storage_path('logs'),
];

foreach ($paths as $path) {
    if (is_dir($path)) {
        chmod($path, 0775);
        echo "   ✓ Fixed permissions for: " . basename($path) . "\n";
    }
}

echo "\n=== Fix Complete ===\n";

if (!$autoloaderRegenerated) {
    echo "\n⚠ IMPORTANT: Autoloader was NOT regenerated automatically.\n";
    echo "You MUST run this command manually in SSH:\n";
    echo "   composer dump-autoload --optimize\n";
    echo "\nWithout this step, the CallManager class will not be found!\n";
}

echo "\nNext steps:\n";
echo "1. If autoloader wasn't regenerated, run: composer dump-autoload --optimize\n";
echo "2. Clear your browser cache (Ctrl+F5)\n";
echo "3. Test the call functionality\n";
echo "4. If still not working, check server logs: storage/logs/laravel.log\n";

