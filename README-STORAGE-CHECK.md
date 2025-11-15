# How to Run Storage Diagnostic on Hostinger

## Method 1: Via Web Browser (Easiest)

1. **Upload the file:**
   - The file `public/check-storage.php` has been created
   - Upload it to your Hostinger server in the `public` folder (same folder as `index.php`)

2. **Access it:**
   - Open your browser and go to: `https://yourdomain.com/check-storage.php`
   - Or: `https://yourdomain.com/check-storage.php?key=YOUR_SECRET_KEY` (if you set a secret key)

3. **View the results:**
   - The page will show a detailed diagnostic report with:
     - Storage paths
     - Profile pictures directory status
     - List of students/tutors with profile pictures
     - File existence status
     - Recommendations

4. **Delete after use:**
   - **IMPORTANT:** Delete `public/check-storage.php` after running it for security reasons!

## Method 2: Via SSH (If Available)

If you have SSH access to your Hostinger account:

1. **Connect via SSH:**
   ```bash
   ssh username@yourdomain.com
   # or
   ssh username@your-server-ip
   ```

2. **Navigate to your project:**
   ```bash
   cd public_html
   # or wherever your Laravel project is located
   ```

3. **Run the diagnostic:**
   ```bash
   php check-storage.php
   ```

4. **Or run Laravel commands directly:**
   ```bash
   php artisan storage:link
   php artisan tinker
   # Then in tinker:
   Storage::disk('public')->exists('profile-pictures');
   ```

## Method 3: Via Hostinger File Manager

1. Log into your Hostinger control panel
2. Go to File Manager
3. Navigate to your Laravel project's `public` folder
4. Upload `check-storage.php` there
5. Access it via browser as in Method 1

## Common Issues and Solutions

### Issue: Storage symlink doesn't exist
**Solution:**
```bash
php artisan storage:link
```

### Issue: Profile pictures directory doesn't exist
**Solution:**
The directory should be created automatically when users upload profile pictures. If it doesn't exist:
```bash
mkdir -p storage/app/public/profile-pictures
chmod 755 storage/app/public/profile-pictures
```

### Issue: Files exist but can't be accessed
**Solution:**
Check file permissions:
```bash
chmod -R 755 storage/app/public
chown -R www-data:www-data storage/app/public  # Adjust user/group as needed
```

### Issue: Files are missing
**Solution:**
- Users may need to re-upload their profile pictures
- Check if files were uploaded correctly
- Verify the database `profile_picture` column has correct paths

## Security Note

⚠️ **Always delete `check-storage.php` after running it!** It exposes sensitive information about your file structure and database.

