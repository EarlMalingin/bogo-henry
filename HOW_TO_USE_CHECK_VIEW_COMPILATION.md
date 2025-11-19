# How to Use check-view-compilation.php

## Step 1: Upload the File to Hostinger

### Option A: Using File Manager (Easiest)
1. Log into your Hostinger control panel (hpanel.hostinger.com)
2. Go to **File Manager**
3. Navigate to your domain folder (usually `public_html` or `domains/yourdomain.com/public_html`)
4. Upload the file `check-view-compilation.php` to the **root** of your Laravel project (same folder where `artisan` is located)

### Option B: Using FTP/SFTP
1. Connect to your Hostinger server using FileZilla or similar
2. Navigate to your Laravel project root (where `artisan` file is)
3. Upload `check-view-compilation.php` to that folder

## Step 2: Access SSH Terminal

### Option A: Hostinger Control Panel
1. Log into Hostinger control panel
2. Go to **Advanced** → **SSH Access**
3. Click **Open Terminal** or **Launch SSH Terminal**

### Option B: Using SSH Client (PuTTY, Terminal, etc.)
```bash
ssh u394503238@my-kul-web2046.hostinger.com
# Use your Hostinger SSH credentials
```

## Step 3: Navigate to Your Project

```bash
cd public_html
# OR
cd domains/yourdomain.com/public_html
# OR
cd laravel_project  # if that's your folder name
```

## Step 4: Run the Diagnostic Script

```bash
php check-view-compilation.php
```

## Step 5: Read the Output

The script will show you:
- ✅ If the view file exists and its permissions
- ✅ If the storage directory is writable
- ✅ If the view can be compiled successfully
- ✅ If the CallManager component class exists
- ❌ Any errors that are preventing compilation

## Example Output

```
=== View Compilation Diagnostic ===

1. View file exists: YES ✓
   File size: 65432 bytes
   File permissions: 0644

2. Storage directory exists: YES ✓
   Directory permissions: 0775
   Is writable: YES ✓
   Files in directory: 15

3. Testing view compilation:
   View compiled successfully! ✓
   Compiled size: 45234 bytes
   First 100 chars: <div>    <!-- Call Interface -->    @if($isInCall)        <div class="call-overlay"...

4. Checking CallManager component:
   Class exists: YES ✓
   Component instantiated successfully! ✓

=== End Diagnostic ===
```

## What to Do Based on Results

### If "View file exists: NO ✗"
- The file is missing on the server
- **Solution:** Re-upload `resources/views/livewire/call-manager.blade.php`

### If "Is writable: NO ✗"
- Storage directory doesn't have write permissions
- **Solution:** Run `chmod -R 777 storage/framework/views`

### If "View compiled successfully" shows an ERROR
- There's a syntax error in the Blade template
- **Solution:** Check the error message and fix the syntax error

### If "Class exists: NO ✗"
- CallManager component isn't being autoloaded
- **Solution:** Run `composer dump-autoload --optimize`

## After Running the Diagnostic

1. **Copy the output** and share it if you need help
2. **Fix any issues** shown in the diagnostic
3. **Run the script again** to verify the fixes worked
4. **Refresh your browser** (Ctrl+F5) to see if the call modal works

## Troubleshooting

### "Command not found: php"
- PHP might not be in your PATH
- Try: `/usr/bin/php check-view-compilation.php`
- Or: `php8.2 check-view-compilation.php`

### "Permission denied"
- The file might not be executable
- Run: `chmod +x check-view-compilation.php`

### "File not found"
- Make sure you're in the correct directory
- Run: `ls -la check-view-compilation.php` to verify it's there

