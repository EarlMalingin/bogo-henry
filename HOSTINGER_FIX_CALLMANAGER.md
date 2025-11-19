# Fix CallManager Component on Hostinger

## Problem
The CallManager component is showing raw Blade syntax instead of rendered content.

## Solution - Run These Commands on Hostinger via SSH

```bash
# Navigate to your project directory
cd public_html  # or your domain folder

# Clear all Laravel caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Regenerate autoloader (IMPORTANT for Livewire components)
composer dump-autoload --optimize

# Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
chmod -R 775 storage bootstrap/cache
```

## Alternative: If Still Not Working

If the issue persists, try:

```bash
# Remove all compiled views
rm -rf storage/framework/views/*

# Clear all caches again
php artisan optimize:clear

# Rebuild
php artisan optimize
```

## Verify the Fix

After running the commands:
1. Refresh your browser (hard refresh: Ctrl+F5 or Cmd+Shift+R)
2. The call modal should now show proper content instead of raw Blade syntax
3. Check that the component loads without errors

## Note

The CallManager component will only show when there's an active call (`$isInCall = true`). If you're seeing the modal with raw syntax, it means the component is loading but the Blade template isn't being compiled properly on the server.

