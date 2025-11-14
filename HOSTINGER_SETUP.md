# Hostinger Setup Instructions

## Important: Choose Your Setup

**Hostinger Shared Hosting** → Cannot run Node.js → **Use Pusher** (see below)
**Hostinger VPS/Cloud** → Can run Node.js → **Use Socket Server** (see full deployment guide)

---

## For Shared Hosting: Using Pusher

Since shared hosting doesn't support running Node.js servers, you'll use Pusher for real-time features.

### Step 1: Create Pusher Account
1. Go to https://pusher.com
2. Sign up (free tier: 200k messages/day, 100 concurrent connections)
3. Create a new app
4. Copy your credentials:
   - App ID
   - Key
   - Secret
   - Cluster (e.g., `mt1`, `us2`)

### Step 2: Configure .env
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database (from Hostinger control panel)
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=u123456789_dbname
DB_USERNAME=u123456789_username
DB_PASSWORD=your_password

# Pusher Configuration
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=1234567
PUSHER_APP_KEY=abcdef123456
PUSHER_APP_SECRET=secret123456
PUSHER_APP_CLUSTER=mt1
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https

# Disable socket server (not needed with Pusher)
# SOCKET_PORT=3001
```

### Step 3: Upload Files to Hostinger

**Via FileZilla or Hostinger File Manager:**

1. Connect to your Hostinger FTP
2. Navigate to `public_html` (or your domain folder)
3. Upload all files **EXCEPT**:
   - `node_modules/`
   - `.git/`
   - `.env` (create new one on server)
   - `storage/logs/*` (keep folder, but not log files)

4. **Important:** Your document root should point to `public_html/public`

### Step 4: Set File Permissions

Via Hostinger File Manager or SSH:
- `storage/` → **775**
- `bootstrap/cache/` → **775**

### Step 5: Install Dependencies via SSH

1. Access SSH in Hostinger control panel
2. Navigate to your domain folder:
   ```bash
   cd public_html  # or cd domains/yourdomain.com/public_html
   ```

3. Install Composer dependencies:
   ```bash
   composer install --optimize-autoloader --no-dev
   ```

4. Generate app key:
   ```bash
   php artisan key:generate
   ```

5. Create storage link:
   ```bash
   php artisan storage:link
   ```

6. Run migrations:
   ```bash
   php artisan migrate --force
   ```

7. Cache configuration:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

### Step 6: Build Assets (On Your Local Machine)

Since shared hosting may not have Node.js, build assets locally:

```bash
npm install
npm run build
```

Then upload the `public/build/` folder to your server.

### Step 7: Configure Hostinger Settings

1. **PHP Version:**
   - Go to: Hostinger Control Panel → PHP Configuration
   - Select: **PHP 8.2** or higher
   - Enable required extensions

2. **Database:**
   - Go to: Databases → MySQL Databases
   - Create database and user
   - Use credentials in `.env`

3. **SSL Certificate:**
   - Go to: SSL
   - Enable **Let's Encrypt** (free)

4. **Document Root:**
   - Go to: Domains
   - Set to: `public_html/public`

---

## For VPS/Cloud Hosting: Using Socket Server

If you have VPS or Cloud hosting, you can run the Node.js socket server.

### Follow the Full Deployment Guide

See `HOSTINGER_DEPLOYMENT.md` for complete VPS setup instructions.

**Quick Steps:**
1. SSH into your server
2. Install Node.js and PM2
3. Upload files
4. Configure `.env`
5. Install dependencies
6. Start socket server: `pm2 start ecosystem.config.js --env production`
7. Configure Nginx to proxy socket connections

---

## Important Notes for Hostinger

### Database Host
- Usually: `localhost`
- Sometimes: `localhost:3306`
- Check in Hostinger control panel → Databases

### File Paths
- Shared hosting: Usually `public_html/`
- VPS: Usually `/home/username/domains/yourdomain.com/public_html/`

### PHP Extensions Required
- `openssl`
- `pdo`
- `mbstring`
- `tokenizer`
- `xml`
- `ctype`
- `json`
- `bcmath`
- `fileinfo`
- `gd` (for image processing)

### File Upload Limits
If you get upload errors, increase limits in PHP Configuration:
- `upload_max_filesize`: 64M
- `post_max_size`: 64M
- `max_execution_time`: 300

Or add to `.htaccess`:
```apache
php_value upload_max_filesize 64M
php_value post_max_size 64M
php_value max_execution_time 300
```

---

## Troubleshooting

### Error: "500 Internal Server Error"
**Solutions:**
1. Check file permissions (storage/, bootstrap/cache/ → 775)
2. Check `.env` file exists and is configured
3. View error logs: Hostinger Control Panel → Error Logs
4. Check Laravel logs: `storage/logs/laravel.log`

### Error: "Database Connection Failed"
**Solutions:**
1. Verify database credentials in `.env`
2. Check database host (usually `localhost`)
3. Ensure database user has all privileges
4. Test connection via Hostinger phpMyAdmin

### Error: "Class not found" or "Composer autoload"
**Solutions:**
1. Run: `composer install --optimize-autoloader --no-dev`
2. Clear cache: `php artisan config:clear`
3. Regenerate autoload: `composer dump-autoload`

### Assets Not Loading
**Solutions:**
1. Build assets locally: `npm run build`
2. Upload `public/build/` folder
3. Clear browser cache
4. Check file permissions on `public/build/`

### Socket Connection Fails (VPS only)
**Solutions:**
1. Check socket server: `pm2 status`
2. Check logs: `pm2 logs mentorhub-socket`
3. Verify firewall allows port 3001
4. Check CORS configuration in `.env`

---

## Quick Checklist

- [ ] Determined hosting type (Shared/VPS)
- [ ] Created Pusher account (if Shared Hosting)
- [ ] Configured `.env` file
- [ ] Uploaded files to server
- [ ] Set file permissions (775 for storage, bootstrap/cache)
- [ ] Installed Composer dependencies
- [ ] Generated app key
- [ ] Created storage link
- [ ] Ran migrations
- [ ] Cached configuration
- [ ] Built and uploaded assets
- [ ] Configured PHP version (8.2+)
- [ ] Set up database
- [ ] Enabled SSL certificate
- [ ] Set document root to `public/`
- [ ] Started socket server (VPS only)
- [ ] Tested the application

---

## Need More Help?

1. **Hostinger Support:** Contact via control panel
2. **Laravel Logs:** Check `storage/logs/laravel.log`
3. **Server Logs:** Check Hostinger error logs
4. **Full Guide:** See `HOSTINGER_DEPLOYMENT.md` for detailed instructions

