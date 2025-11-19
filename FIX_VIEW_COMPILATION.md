# 🚨 CRITICAL: Fix View Compilation Issue

## The Problem
Blade templates are showing raw syntax instead of compiled HTML. This means views aren't being compiled on the server.

## ⚡ COMPLETE FIX - Run ALL These Commands:

```bash
cd public_html  # or your domain folder

# STEP 1: Check and fix permissions
chmod -R 775 storage bootstrap/cache
chmod -R 775 storage/framework/views
chown -R $(whoami):www-data storage bootstrap/cache 2>/dev/null || true

# STEP 2: Remove ALL compiled views
rm -rf storage/framework/views/*
rm -rf bootstrap/cache/*.php

# STEP 3: Clear ALL caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan optimize:clear

# STEP 4: Regenerate autoloader
composer dump-autoload --optimize --no-interaction

# STEP 5: Verify the view file exists and is readable
ls -la resources/views/livewire/call-manager.blade.php
cat resources/views/livewire/call-manager.blade.php | head -20

# STEP 6: Test view compilation manually
php artisan tinker --execute="echo view('livewire.call-manager')->render();" | head -50

# STEP 7: Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# STEP 8: Verify storage is writable
touch storage/framework/views/test.txt
rm storage/framework/views/test.txt
echo "If the above command failed, storage/framework/views is not writable!"
```

## 🔍 Check These Things:

1. **File Permissions:**
   ```bash
   ls -la storage/framework/views
   # Should show drwxrwxr-x (775) permissions
   ```

2. **Disk Space:**
   ```bash
   df -h
   # Make sure you have free space
   ```

3. **PHP Error Logs:**
   ```bash
   tail -50 storage/logs/laravel.log
   # Look for any view compilation errors
   ```

## 🆘 If Still Not Working:

The issue might be that the view file on the server is corrupted or different. Try:

```bash
# Re-upload the call-manager.blade.php file
# Make sure it's the exact same as your local file

# Then run:
rm -rf storage/framework/views/*
php artisan view:clear
php artisan view:cache
```

## 📝 Important Notes:

- The `storage/framework/views` directory MUST be writable
- If you see permission errors, contact Hostinger support
- Make sure the file `resources/views/livewire/call-manager.blade.php` exists on the server
- The file should be exactly 2088 lines (check with `wc -l resources/views/livewire/call-manager.blade.php`)

