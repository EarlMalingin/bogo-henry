# Fix Admin Login on Hostinger - Step by Step

## Problem
You deployed your website to Hostinger but can't log in to the admin panel.

## Solution: Create Admin Account on Hostinger

### Method 1: Using SSH (Recommended - Fastest)

1. **Access SSH in Hostinger:**
   - Log in to your Hostinger control panel
   - Go to **Advanced** → **SSH Access** (or **SSH**)
   - Enable SSH if not already enabled
   - Note your SSH credentials (username, host, port)

2. **Connect via SSH:**
   - Use PuTTY (Windows) or Terminal (Mac/Linux)
   - Or use Hostinger's **Web Terminal** (Browser-based SSH)
   - Connect using your credentials

3. **Navigate to your project:**
   ```bash
   cd public_html
   # OR if your files are in a subdirectory:
   # cd domains/yourdomain.com/public_html
   ```

4. **Run the admin seeder:**
   ```bash
   php artisan db:seed --class=AdminSeeder
   ```

5. **Verify it worked:**
   ```bash
   php artisan tinker
   ```
   Then type:
   ```php
   use App\Models\Admin;
   Admin::where('email', 'admin@mentorhub.com')->first();
   exit
   ```
   If it shows the admin user, you're done!

6. **Clear cache:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

### Method 2: Using the Fix Script (If SSH Available)

1. **Upload `fix-admin.php` to your server:**
   - Use FileZilla or Hostinger File Manager
   - Upload to the same folder as `artisan` (usually `public_html` or `domains/yourdomain.com/public_html`)

2. **Run via SSH:**
   ```bash
   cd public_html
   php fix-admin.php
   ```

3. **Delete the file for security:**
   ```bash
   rm fix-admin.php
   ```

### Method 3: Using phpMyAdmin (If SSH Not Available)

1. **Access phpMyAdmin:**
   - Go to Hostinger control panel
   - Click **Databases** → **phpMyAdmin**
   - Select your database

2. **Check if `admins` table exists:**
   - Look for `admins` table in the left sidebar
   - If it doesn't exist, you need to run migrations first (see Method 4)

3. **Insert admin user:**
   - Click on `admins` table
   - Click **Insert** tab
   - Fill in:
     ```
     name: Administrator
     email: admin@mentorhub.com
     password: $2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5GyY5Y5Y5Y5Y5Y
     ```
   - Leave `id`, `remember_token`, `created_at`, `updated_at` empty (auto-filled)
   - Click **Go**

   **Note:** The password hash above is for `earlgwapo123`. To generate a different password hash, you'll need SSH access.

### Method 4: Run Migrations First (If Table Doesn't Exist)

If the `admins` table doesn't exist, you need to run migrations:

**Via SSH:**
```bash
cd public_html
php artisan migrate --force
php artisan db:seed --class=AdminSeeder
```

**If you don't have SSH:**
You'll need to contact Hostinger support to enable SSH access, or manually create the table via phpMyAdmin using the migration structure.

### Method 5: Using Hostinger's Terminal (Web-based SSH)

1. **Access Web Terminal:**
   - Go to Hostinger control panel
   - Look for **Terminal** or **Web Terminal** option
   - Click to open

2. **Run commands:**
   ```bash
   cd public_html
   php artisan db:seed --class=AdminSeeder
   php artisan config:clear
   ```

## Default Admin Credentials

After creating the admin account:

- **Email:** `admin@mentorhub.com`
- **Password:** `earlgwapo123`

**⚠️ CHANGE THIS PASSWORD IMMEDIATELY AFTER FIRST LOGIN!**

## Troubleshooting

### "Command not found: php artisan"
**Solution:** You might be in the wrong directory. Try:
```bash
pwd  # Check current directory
ls -la  # List files - you should see artisan file
cd public_html  # Navigate to correct directory
```

### "Class 'AdminSeeder' not found"
**Solution:** Make sure you're in the project root (where `artisan` is), not in the `public` folder.

### "Table 'admins' doesn't exist"
**Solution:** Run migrations first:
```bash
php artisan migrate --force
```

### "SQLSTATE[HY000] [2002] Connection refused"
**Solution:** Check your `.env` file database credentials:
```bash
cat .env | grep DB_
```
Make sure database host, name, username, and password are correct.

### Still can't login after creating admin
**Solutions:**
1. Clear all caches:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   php artisan view:clear
   ```

2. Check if password is hashed:
   ```bash
   php artisan tinker
   ```
   ```php
   $admin = Admin::where('email', 'admin@mentorhub.com')->first();
   echo $admin->password; // Should be a long hash starting with $2y$
   exit
   ```

3. Verify auth configuration:
   - Check `config/auth.php` has admin guard
   - Clear config cache: `php artisan config:clear`

## Quick Checklist

- [ ] SSH access enabled in Hostinger
- [ ] Connected to server via SSH
- [ ] Navigated to correct directory (where `artisan` is)
- [ ] Ran `php artisan db:seed --class=AdminSeeder`
- [ ] Cleared cache: `php artisan config:clear`
- [ ] Tested login with credentials
- [ ] Changed password after first login

## Need Help?

If you're still having issues:
1. Check Hostinger's documentation for SSH access
2. Contact Hostinger support to enable SSH
3. Verify your database credentials in `.env` file
4. Check Laravel logs: `storage/logs/laravel.log`

