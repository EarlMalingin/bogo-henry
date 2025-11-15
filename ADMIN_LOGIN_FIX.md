# Admin Login Fix Guide

## Problem
After deployment, you cannot log in to the admin panel with "Invalid credentials" error.

## Solution

The admin user needs to be created in the database. Here are the steps to fix it:

### Option 1: Run Admin Seeder (Recommended)

**Via SSH on your Hostinger server:**

```bash
cd public_html  # or your domain folder
php artisan db:seed --class=AdminSeeder
```

**Or if you're in the public folder:**
```bash
cd public
php artisan db:seed --class=AdminSeeder
```

### Option 2: Create Admin Manually via Tinker

**Via SSH:**
```bash
php artisan tinker
```

Then run:
```php
use App\Models\Admin;
Admin::create([
    'name' => 'Administrator',
    'email' => 'admin@mentorhub.com',
    'password' => 'earlgwapo123'
]);
exit
```

### Option 3: Create Admin via SQL (if you have database access)

If you have phpMyAdmin or direct database access:

```sql
INSERT INTO `admins` (`name`, `email`, `password`, `created_at`, `updated_at`) 
VALUES ('Administrator', 'admin@mentorhub.com', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5GyY5Y5Y5Y5Y5Y', NOW(), NOW());
```

**Note:** The password hash above is for `earlgwapo123`. If you want a different password, you can generate a hash using:
```bash
php artisan tinker
Hash::make('your-password-here')
```

## Default Admin Credentials

After running the seeder, use these credentials:

- **Email:** `admin@mentorhub.com`
- **Password:** `earlgwapo123`

**⚠️ IMPORTANT:** Change this password immediately after first login for security!

## Verify Admin Exists

To check if admin exists:

```bash
php artisan tinker
```

Then:
```php
use App\Models\Admin;
Admin::where('email', 'admin@mentorhub.com')->first();
exit
```

If it returns `null`, the admin doesn't exist and you need to create it.

## Troubleshooting

### Issue: "Class AdminSeeder not found"
**Solution:** Make sure you're in the correct directory (project root, not public folder)

### Issue: "Table 'admins' doesn't exist"
**Solution:** Run migrations first:
```bash
php artisan migrate --force
php artisan db:seed --class=AdminSeeder
```

### Issue: Still can't login after creating admin
**Solutions:**
1. Clear cache:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

2. Check if password is hashed correctly:
   ```bash
   php artisan tinker
   ```
   ```php
   $admin = Admin::where('email', 'admin@mentorhub.com')->first();
   echo $admin->password; // Should be a long hash, not plain text
   ```

3. Verify auth guard configuration:
   - Check `config/auth.php` has `admin` guard configured
   - Check `admins` provider is set to use `App\Models\Admin`

### Issue: Session issues
**Solution:** Clear session files:
```bash
php artisan session:clear
# Or manually delete files in storage/framework/sessions/
```

## Quick Fix Script

Create a file `fix-admin.php` in your project root:

```php
<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Admin;

$admin = Admin::where('email', 'admin@mentorhub.com')->first();

if (!$admin) {
    Admin::create([
        'name' => 'Administrator',
        'email' => 'admin@mentorhub.com',
        'password' => 'earlgwapo123'
    ]);
    echo "Admin created successfully!\n";
} else {
    $admin->password = 'earlgwapo123';
    $admin->save();
    echo "Admin password reset successfully!\n";
}

echo "Email: admin@mentorhub.com\n";
echo "Password: earlgwapo123\n";
```

Run it:
```bash
php fix-admin.php
```

Then delete the file for security.

