# Storage Setup Instructions for Hostinger

Since `exec()` function is disabled on Hostinger, you cannot run `php artisan storage:link`. Here are alternative solutions:

## Solution 1: Create Symlink Manually via cPanel File Manager (Recommended)

1. **Log into your Hostinger cPanel**
2. **Open File Manager**
3. **Navigate to your Laravel project's `public` folder**
   - Usually: `public_html/your-project-name/public/`
4. **Create a new folder called `storage`** (if it doesn't exist)
5. **Delete the `storage` folder if it exists** (it might be empty or broken)
6. **Create a symbolic link:**
   - In cPanel File Manager, look for "Symbolic Link" or "Create Link" option
   - **Source (Target):** `/home/u394503238/domains/yourdomain.com/public_html/your-project-name/storage/app/public`
   - **Link Name:** `storage`
   - **Location:** Inside the `public` folder

**OR via SSH (if you have access):**
```bash
cd public_html/your-project-name/public
rm -rf storage
ln -s ../storage/app/public storage
```

## Solution 2: Copy Files to public/storage (Alternative)

If symlinks don't work, you can copy the files:

1. **Via cPanel File Manager:**
   - Go to `storage/app/public/profile-pictures/`
   - Copy all files
   - Paste them into `public/storage/profile-pictures/`
   - Create the directory structure if needed

2. **Set up automatic sync (optional):**
   - You may need to manually copy new uploads, or
   - Modify the upload code to save directly to `public/storage/`

## Solution 3: Use Route Method (Already Implemented)

The code has been updated to use route methods (`/tutor/profile/picture/{id}`) which serve files through PHP, so the symlink is **not strictly required**. However, having the symlink improves performance.

## Verify It's Working

After setting up, test by accessing:
- `https://yourdomain.com/tutor/profile/picture/1` (replace 1 with a tutor ID that has a profile picture)

If you see the image, it's working!

## Troubleshooting

If images still don't show:
1. Check file permissions (should be 644 for files, 755 for directories)
2. Check Laravel logs: `storage/logs/laravel.log`
3. Verify the file path in database matches actual file location
4. Ensure `profile-pictures` folder exists in `storage/app/public/`

