#!/bin/bash
# Fix CallManager Component on Hostinger
# Run this script on your Hostinger server via SSH

echo "🔧 Fixing CallManager Component..."

# Navigate to project directory
cd public_html || cd domains/*/public_html || exit 1

echo "📂 Current directory: $(pwd)"

# Remove all compiled views
echo "🗑️  Removing compiled views..."
rm -rf storage/framework/views/*

# Clear all Laravel caches
echo "🧹 Clearing Laravel caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan optimize:clear

# Regenerate autoloader (CRITICAL for Livewire components)
echo "🔄 Regenerating autoloader..."
composer dump-autoload --optimize --no-interaction

# Set proper permissions
echo "🔐 Setting permissions..."
chmod -R 775 storage bootstrap/cache
chown -R $(whoami):www-data storage bootstrap/cache 2>/dev/null || true

# Rebuild caches
echo "🔨 Rebuilding caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Done! Please refresh your browser (Ctrl+F5 or Cmd+Shift+R)"

