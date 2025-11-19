# Complete Fix for CallManager Component Issues

Based on your diagnostic output, there are two main issues:

1. **View compilation error**: `Undefined variable $isInCall`
2. **Class not found**: `CallManager` class is not being detected

## Root Cause

The diagnostic shows that when Laravel tries to compile the view directly (not through Livewire), it doesn't have access to the Livewire component properties like `$isInCall`. This is normal - Livewire views should only be compiled through Livewire components.

However, the real issue is that the `CallManager` class is not being found by PHP's autoloader, which means:
- The class file might not be in the correct location
- The Composer autoloader needs to be regenerated
- There might be a namespace or syntax error in the class file

## Solution Steps

### Step 1: Upload the Fix Script

Upload `fix-callmanager-view.php` to your Hostinger server (same location as `artisan`).

### Step 2: Run the Fix Script

SSH into your Hostinger server and run:

```bash
cd /home/u394503238/domains/uclm-mentorhub.com/laravel_project
php fix-callmanager-view.php
```

This script will:
- Clear all Laravel caches
- Remove compiled views
- Regenerate the Composer autoloader
- Verify the CallManager class exists
- Fix file permissions
- Test component instantiation

### Step 3: Verify the Class File Exists

Check that the file exists at:
```
/home/u394503238/domains/uclm-mentorhub.com/laravel_project/app/Livewire/CallManager.php
```

If it doesn't exist, you need to upload it.

### Step 4: Regenerate Autoloader Manually (if needed)

If the fix script doesn't work, run these commands manually:

```bash
cd /home/u394503238/domains/uclm-mentorhub.com/laravel_project

# Remove all compiled views
rm -rf storage/framework/views/*

# Clear all caches
php artisan optimize:clear

# Regenerate autoloader
composer dump-autoload --optimize

# Fix permissions
chmod -R 775 storage/framework/views
chmod -R 775 storage/framework/cache
chmod -R 775 storage/logs
```

### Step 5: Verify the Fix

Run the updated diagnostic script:

```bash
php check-view-compilation.php
```

You should now see:
- ✓ Class file exists
- ✓ Class exists
- ✓ Component instantiated successfully
- ✓ View rendered successfully

### Step 6: Clear Browser Cache

After fixing on the server:
1. Clear your browser cache (Ctrl+Shift+Delete)
2. Do a hard refresh (Ctrl+F5)
3. Test the call functionality

## If Still Not Working

### Check 1: Verify File Upload

Make sure `app/Livewire/CallManager.php` is uploaded to the server and has the correct content.

### Check 2: Check for Syntax Errors

Run this to check for PHP syntax errors:

```bash
php -l app/Livewire/CallManager.php
```

Should output: `No syntax errors detected`

### Check 3: Check Namespace

The file should start with:
```php
<?php

namespace App\Livewire;

use Livewire\Component;
```

### Check 4: Verify Composer Autoload

Check if the class is in the autoloader:

```bash
grep -r "CallManager" vendor/composer/autoload_classmap.php
```

Or check the PSR-4 autoload:

```bash
grep -r "Livewire" vendor/composer/autoload_psr4.php
```

Should show: `"App\\\\": "app/"`

### Check 5: Check Server Logs

Check for errors in:
```bash
tail -f storage/logs/laravel.log
```

## Expected Behavior After Fix

1. The `/student/messages` page should load without errors
2. The `/tutor/messages` page should load without errors
3. When you click the video/voice call button, the call modal should appear
4. The call modal should show proper HTML (not raw Blade syntax like `{{ $callType }}`)

## Troubleshooting

### Error: "Class 'App\Livewire\CallManager' not found"

**Solution:**
```bash
composer dump-autoload --optimize
```

### Error: "Undefined variable $isInCall"

This is normal when testing the view directly. The view should only be accessed through Livewire. If you see this in the browser, it means the component isn't being loaded properly.

**Solution:**
1. Make sure `@livewire('call-manager')` is in your Blade template
2. Check that the component is registered in Livewire
3. Clear browser cache and do a hard refresh

### Error: View shows raw Blade syntax

**Solution:**
1. Remove compiled views: `rm -rf storage/framework/views/*`
2. Clear cache: `php artisan optimize:clear`
3. Fix permissions: `chmod -R 775 storage/framework/views`
4. Hard refresh browser (Ctrl+F5)

## Contact

If none of these steps work, please share:
1. Output of `php check-view-compilation.php`
2. Output of `php fix-callmanager-view.php`
3. Any errors from `storage/logs/laravel.log`

