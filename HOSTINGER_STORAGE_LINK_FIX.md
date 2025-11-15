# Fix Storage Symlink on Hostinger

## The Problem
Hostinger disables the `exec()` function, so `php artisan storage:link` won't work.

## Solution: Create Symlink Manually via SSH

### Step 1: Connect to Hostinger via SSH
1. Log into your Hostinger account
2. Go to "Advanced" → "SSH Access"
3. Connect using your SSH credentials

### Step 2: Navigate to Your Project
```bash
cd /home/u394503238/domains/uclm-mentorhub.com/public_html
# OR wherever your Laravel project is located
```

### Step 3: Remove Existing Storage Link (if exists)
```bash
cd public
rm -f storage
```

### Step 4: Create the Symlink Manually
```bash
ln -s ../storage/app/public storage
```

### Step 5: Verify It Worked
```bash
ls -la storage
```
You should see: `storage -> ../storage/app/public`

### Step 6: Set Permissions
```bash
chmod -R 755 storage/app/public
chmod -R 755 public/storage
```

## Alternative: If You Can't Use SSH

The code is already set up with route fallbacks, so images will still work via routes even without the symlink. The book session page tries `asset('storage/...')` first, then falls back to routes automatically.

