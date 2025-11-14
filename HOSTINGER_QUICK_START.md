# Hostinger Quick Start Guide

## Determine Your Hosting Type

**Check your Hostinger plan:**
- **Shared Hosting** → Use Pusher (see Option 1 below)
- **VPS/Cloud Hosting** → Can run Socket Server (see Option 2 below)

## Option 1: Shared Hosting (Easiest - Use Pusher)

### Step 1: Get Pusher Credentials
1. Sign up at [pusher.com](https://pusher.com) (free tier available)
2. Create a new app
3. Copy your credentials

### Step 2: Update .env File
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=mt1
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
```

### Step 3: Upload Files
1. Use FileZilla or Hostinger File Manager
2. Upload all files to `public_html` (or your domain folder)
3. **Important:** Point document root to `public_html/public` folder

### Step 4: Set Permissions
Via Hostinger File Manager or SSH:
- `storage/` folder → 775
- `bootstrap/cache/` folder → 775

### Step 5: Run Commands via SSH
```bash
cd public_html  # or your domain folder
composer install --optimize-autoloader --no-dev
php artisan key:generate
php artisan storage:link
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 6: Build Assets (on your local machine)
```bash
npm install
npm run build
```
Then upload the `public/build/` folder to your server.

**Note:** For shared hosting, you'll need to modify the socket client to use Pusher instead of Socket.IO. See migration guide below.

---

## Option 2: VPS/Cloud Hosting (Full Control)

### Step 1: SSH into Server
```bash
ssh username@your-server-ip
```

### Step 2: Install Node.js
```bash
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs
sudo npm install -g pm2
```

### Step 3: Upload Files
Use SFTP or Git to upload your application files.

### Step 4: Configure Environment
Create `.env` file with:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

SOCKET_PORT=3001
LARAVEL_URL=https://yourdomain.com
```

### Step 5: Install & Build
```bash
composer install --optimize-autoloader --no-dev
npm install --production
npm run build
```

### Step 6: Laravel Setup
```bash
php artisan key:generate
php artisan storage:link
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 7: Start Socket Server
```bash
pm2 start ecosystem.config.js --env production
pm2 save
pm2 startup  # Follow instructions
```

### Step 8: Configure Web Server
If using Nginx, add socket proxy (see full guide).

---

## Hostinger Control Panel Settings

### 1. PHP Version
- Go to: **PHP Configuration**
- Select: **PHP 8.2** or higher
- Enable extensions: `openssl`, `pdo`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`

### 2. Database
- Go to: **Databases → MySQL Databases**
- Create database and user
- Use credentials in `.env`

### 3. SSL Certificate
- Go to: **SSL**
- Enable **Let's Encrypt** (free)

### 4. Document Root
- Go to: **Domains**
- Set document root to: `public_html/public` (or `yourdomain/public`)

---

## Common Issues

### "500 Internal Server Error"
- Check file permissions (storage/, bootstrap/cache/ → 775)
- Check `.env` file exists
- View error logs in Hostinger control panel

### "Database Connection Failed"
- Verify database credentials in `.env`
- Check database host (usually `localhost` on Hostinger)
- Ensure database user has permissions

### "Assets Not Loading"
- Run `npm run build` locally
- Upload `public/build/` folder
- Clear browser cache

---

## Need Help?

1. Check `HOSTINGER_DEPLOYMENT.md` for detailed instructions
2. Review Laravel logs: `storage/logs/laravel.log`
3. Contact Hostinger support for server-specific issues

