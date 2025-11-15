# Connect to Hostinger via PuTTY - Step by Step

## Step 1: Get Your SSH Credentials from Hostinger

1. **Log in to Hostinger Control Panel**
   - Go to https://hpanel.hostinger.com
   - Log in with your Hostinger account

2. **Enable SSH Access (if not already enabled)**
   - Go to **Advanced** → **SSH Access**
   - Click **Enable SSH Access** or **Manage SSH Access**
   - Note: SSH might already be enabled on your plan

3. **Get Your SSH Details**
   - Look for **SSH Username** (usually your hosting username)
   - Look for **SSH Host** (usually something like `ssh.hostinger.com` or `srv1234.hostinger.io`)
   - Look for **SSH Port** (usually `65002` or `22`)
   - **SSH Password** is usually your hosting account password

   **OR** you might see:
   - **Host:** `ssh.hostinger.com`
   - **Port:** `65002` (or `22`)
   - **Username:** Your hosting username
   - **Password:** Your hosting account password

## Step 2: Connect with PuTTY

1. **Open PuTTY**
   - Click Start menu → Search "PuTTY" → Open PuTTY

2. **Enter Connection Details**
   - **Host Name (or IP address):** Enter your SSH Host (e.g., `ssh.hostinger.com` or `srv1234.hostinger.io`)
   - **Port:** Enter the SSH Port (usually `65002` or `22`)
   - **Connection type:** Select **SSH** (should be selected by default)

3. **Save the Session (Optional but Recommended)**
   - In the **Saved Sessions** field, type: `Hostinger`
   - Click **Save** button
   - This saves your settings for next time

4. **Click "Open"**
   - Click the **Open** button at the bottom

5. **Security Alert (First Time Only)**
   - A warning will appear: "The server's host key is not cached..."
   - Click **Yes** to continue

6. **Login**
   - You'll see a black terminal window
   - It will ask: `login as:`
   - Type your **SSH Username** and press Enter
   - It will ask: `password:`
   - Type your **SSH Password** (you won't see it as you type - this is normal!)
   - Press Enter

   **Note:** If you see `$` or `#` prompt, you're successfully connected!

## Step 3: Navigate to Your Project

Once connected, you'll see a command prompt. Type these commands one by one:

```bash
cd public_html
```

**OR** if your files are in a different location:

```bash
cd domains/yourdomain.com/public_html
```

**OR** to find where your files are:

```bash
pwd
ls -la
```

Look for the `artisan` file - that's your Laravel project root.

## Step 4: Create Admin Account

Once you're in the correct directory (where `artisan` file is), run:

```bash
php artisan db:seed --class=AdminSeeder
```

You should see output like:
```
Seeding: AdminSeeder
```

## Step 5: Clear Cache

Run these commands to clear Laravel cache:

```bash
php artisan config:clear
php artisan cache:clear
```

## Step 6: Verify Admin Was Created (Optional)

To check if admin was created:

```bash
php artisan tinker
```

Then type:
```php
use App\Models\Admin;
Admin::where('email', 'admin@mentorhub.com')->first();
exit
```

If it shows the admin user details, you're good!

## Step 7: Test Login

Go to your website:
- URL: `https://yourdomain.com/admin/login`
- Email: `admin@mentorhub.com`
- Password: `earlgwapo123`

## Troubleshooting

### "Access Denied" or "Permission Denied"
- Check your SSH username and password
- Make sure SSH is enabled in Hostinger
- Try resetting your SSH password in Hostinger control panel

### "php: command not found"
- Try: `which php` to find PHP location
- You might need: `/usr/bin/php artisan` instead of `php artisan`
- Or: `php8.2 artisan` (check your PHP version in Hostinger)

### "No such file or directory"
- You're in the wrong directory
- Run: `pwd` to see where you are
- Run: `ls -la` to see files
- Navigate to where `artisan` file is located

### "Class 'AdminSeeder' not found"
- Make sure you're in the project root (where `artisan` is)
- Not in the `public` folder
- Run: `ls -la database/seeders/` to verify AdminSeeder.php exists

### "Table 'admins' doesn't exist"
- Run migrations first:
  ```bash
  php artisan migrate --force
  php artisan db:seed --class=AdminSeeder
  ```

### Can't find SSH credentials in Hostinger
- Look in: **Advanced** → **SSH Access**
- Or: **Files** → **SSH Access**
- Or contact Hostinger support to enable SSH

## Quick Command Reference

```bash
# Navigate to project
cd public_html

# Create admin account
php artisan db:seed --class=AdminSeeder

# Clear cache
php artisan config:clear
php artisan cache:clear

# Check current directory
pwd

# List files
ls -la

# Check if admin exists
php artisan tinker
# Then: use App\Models\Admin; Admin::where('email', 'admin@mentorhub.com')->first(); exit
```

## Alternative: Use Hostinger Web Terminal

If PuTTY is giving you trouble, Hostinger also has a web-based terminal:

1. Go to Hostinger Control Panel
2. Look for **Terminal** or **Web Terminal** option
3. Click to open (no PuTTY needed!)
4. Follow Steps 3-7 above

## Security Note

After fixing the admin login:
- ✅ Change the default password immediately
- ✅ Delete `fix-admin.php` if you uploaded it
- ✅ Keep your SSH credentials secure

