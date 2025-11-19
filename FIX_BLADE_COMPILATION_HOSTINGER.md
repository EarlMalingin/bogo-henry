# 🚨 URGENT: Fix Blade View Compilation on Hostinger

## The Problem
Your CallManager component is showing **raw Blade syntax** instead of rendered HTML. This means Laravel is not compiling the Blade templates.

## ⚡ STEP-BY-STEP FIX

### Step 1: Upload Diagnostic Script
1. Upload `check-view-compilation.php` to your Hostinger server (same directory as `artisan`)
2. Run: `php check-view-compilation.php`
3. This will tell you exactly what's wrong

### Step 2: Fix Permissions (CRITICAL!)

```bash
cd public_html  # or your domain folder

# Make storage writable
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chmod -R 775 storage/framework
chmod -R 775 storage/framework/views

# If the above doesn't work, try:
chmod -R 777 storage/framework/views
```

### Step 3: Remove ALL Compiled Views

```bash
# Remove all compiled views
rm -rf storage/framework/views/*

# Remove bootstrap cache
rm -rf bootstrap/cache/*.php
```

### Step 4: Clear ALL Caches

```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan optimize:clear
```

### Step 5: Verify View File

```bash
# Check if the file exists and is correct
ls -la resources/views/livewire/call-manager.blade.php

# Check file size (should be around 60-70KB)
wc -l resources/views/livewire/call-manager.blade.php
# Should show: 2088 lines
```

### Step 6: Regenerate Autoloader

```bash
composer dump-autoload --optimize --no-interaction
```

### Step 7: Test View Compilation

```bash
# Try to compile the view manually
php artisan tinker
# Then type: view('livewire.call-manager')->render()
# Press Enter
# If it shows HTML, it's working. If it shows an error, note the error.
```

### Step 8: Rebuild Caches

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🔍 Common Issues & Solutions

### Issue 1: "Permission denied" errors
**Solution:**
```bash
chmod -R 777 storage/framework/views
# Then try again
```

### Issue 2: View file is corrupted or missing
**Solution:**
- Re-upload `resources/views/livewire/call-manager.blade.php` from your local computer
- Make sure it's exactly 2088 lines
- Run the commands again

### Issue 3: Storage directory doesn't exist
**Solution:**
```bash
mkdir -p storage/framework/views
chmod -R 775 storage/framework/views
```

### Issue 4: PHP version or extension issues
**Solution:**
- Check PHP version: `php -v` (should be 8.2+)
- Check if required extensions are installed
- Contact Hostinger support if needed

## 🆘 If Nothing Works

1. **Contact Hostinger Support** - They may need to fix server-side permissions
2. **Check PHP Error Logs:**
   ```bash
   tail -100 storage/logs/laravel.log
   ```
3. **Try a different approach** - We can modify the component to use a different rendering method

## ✅ Success Indicators

After running the fix, you should see:
- ✅ No raw Blade syntax in the call modal
- ✅ Proper HTML rendered (buttons, text, etc.)
- ✅ Calls can be initiated and received
- ✅ No errors in browser console

## 📝 After Fixing

1. **Hard refresh browser**: `Ctrl+F5` or `Cmd+Shift+R`
2. **Clear browser cache** if needed
3. **Test making a call**
4. **Check browser console** for any JavaScript errors

