# How to Create Storage Symlink on Hostinger

Since `exec()` is disabled on Hostinger, you need to create the storage symlink manually via SSH.

## Method 1: Via SSH (Recommended)

1. Connect to your Hostinger server via SSH
2. Navigate to your Laravel project's public directory:
   ```bash
   cd /home/u394503238/domains/uclm-mentorhub.com/public_html/public
   ```
   (Adjust the path based on your actual Hostinger setup)

3. Remove the existing symlink if it exists:
   ```bash
   rm -f storage
   ```

4. Create the symlink manually:
   ```bash
   ln -s ../storage/app/public storage
   ```

5. Verify it worked:
   ```bash
   ls -la storage
   ```
   You should see: `storage -> ../storage/app/public`

## Method 2: Via Hostinger File Manager

1. Log into Hostinger File Manager
2. Navigate to: `public_html/public/` (or wherever your Laravel public folder is)
3. Delete the existing `storage` folder/symlink if it exists
4. Create a new symlink:
   - Look for "Create Symlink" or "Create Link" option
   - Source: `../storage/app/public`
   - Link name: `storage`
   - Destination: `public/storage`

## Method 3: Alternative - Use Routes Only

If you can't create the symlink, the code is already set up to use routes as a fallback. The book session page will try `asset('storage/...')` first, then fall back to the route if that fails.

## Verify It's Working

After creating the symlink, test by accessing:
- `https://uclm-mentorhub.com/storage/profile-pictures/[any-filename].jpg`

If you see the image, the symlink is working!

