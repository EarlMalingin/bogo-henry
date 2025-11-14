# Hostinger Deployment Guide

This guide is specifically tailored for deploying MentorHub to Hostinger hosting.

## Hostinger Hosting Types

Hostinger offers different hosting plans:
- **Shared Hosting**: Limited Node.js support, no PM2
- **VPS Hosting**: Full control, can run Node.js and PM2
- **Cloud Hosting**: Similar to VPS

## Option 1: Shared Hosting (Recommended: Use Pusher Instead)

If you're on **Shared Hosting**, you **cannot** run a Node.js socket server directly. Instead, use **Pusher** (which is already configured in your Laravel app).

### Step 1: Set Up Pusher Account

1. Go to [pusher.com](https://pusher.com) and create a free account
2. Create a new app/channel
3. Get your credentials (App ID, Key, Secret, Cluster)

### Step 2: Configure Laravel for Pusher

Update your `.env` file:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your-pusher-app-id
PUSHER_APP_KEY=your-pusher-key
PUSHER_APP_SECRET=your-pusher-secret
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

# Disable socket server (not needed with Pusher)
SOCKET_PORT=3001
```

### Step 3: Update Frontend to Use Pusher

You'll need to modify the socket client to use Pusher instead of Socket.IO. See the Pusher migration section below.

### Step 4: Deploy Laravel Files

1. **Upload files via FTP/SFTP** to your Hostinger account:
   - Upload all files except `node_modules`, `.git`, `storage/logs/*`
   - Make sure `.env` is uploaded with correct settings

2. **Set file permissions** (via File Manager or SSH):
   ```
   storage/ -> 775
   bootstrap/cache/ -> 775
   ```

3. **Run Laravel commands** via SSH or Hostinger's terminal:
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

4. **Point domain to `public` folder**:
   - In Hostinger control panel, set document root to `public_html/public` (or your domain folder + `/public`)

## Option 2: VPS/Cloud Hosting (Can Run Socket Server)

If you have **VPS or Cloud Hosting**, you can run the Node.js socket server.

### Step 1: SSH into Your Server

```bash
ssh username@your-server-ip
```

### Step 2: Install Node.js and PM2

```bash
# Install Node.js (if not already installed)
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs

# Install PM2 globally
sudo npm install -g pm2
```

### Step 3: Upload Your Application

Use SFTP or Git to upload your files to the server.

### Step 4: Configure Environment

1. Create/update `.env` file:
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

### Step 5: Install Dependencies and Build

```bash
cd /path/to/your/app
composer install --optimize-autoloader --no-dev
npm install --production
npm run build
```

### Step 6: Set Permissions

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### Step 7: Laravel Setup

```bash
php artisan key:generate
php artisan storage:link
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 8: Start Socket Server with PM2

```bash
pm2 start ecosystem.config.js --env production
pm2 save
pm2 startup  # Follow the instructions to enable auto-start
```

### Step 9: Configure Nginx (if using)

If you have Nginx installed, configure it to proxy socket connections:

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name yourdomain.com;

    ssl_certificate /path/to/ssl/cert.pem;
    ssl_certificate_key /path/to/ssl/key.pem;

    root /path/to/your/app/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Socket.IO proxy
    location /socket.io/ {
        proxy_pass http://localhost:3001;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_cache_bypass $http_upgrade;
    }
}
```

## Hostinger-Specific Considerations

### Database Configuration

Hostinger provides MySQL databases through their control panel:
1. Go to Hostinger control panel → Databases → MySQL Databases
2. Create a new database and user
3. Use the provided credentials in your `.env` file

### SSL Certificate

Hostinger provides free SSL certificates:
1. Go to Hostinger control panel → SSL
2. Enable SSL for your domain
3. Use "Let's Encrypt" for free SSL

### PHP Version

Make sure you're using PHP 8.2 or higher:
1. Go to Hostinger control panel → PHP Configuration
2. Select PHP 8.2 or higher
3. Enable required extensions: `openssl`, `pdo`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`

### File Upload Limits

If you encounter file upload issues:
1. Go to PHP Configuration
2. Increase `upload_max_filesize` and `post_max_size`
3. Or add to `.htaccess`:
```apache
php_value upload_max_filesize 64M
php_value post_max_size 64M
```

## Troubleshooting

### Issue: 500 Internal Server Error
- Check file permissions on `storage/` and `bootstrap/cache/`
- Check Laravel logs: `storage/logs/laravel.log`
- Verify `.env` file exists and is configured correctly

### Issue: Database Connection Failed
- Verify database credentials in `.env`
- Check if database host is `localhost` or provided by Hostinger
- Ensure database user has proper permissions

### Issue: Socket Connection Fails (VPS only)
- Check if socket server is running: `pm2 status`
- Verify firewall allows port 3001
- Check socket server logs: `pm2 logs mentorhub-socket`
- Ensure CORS is configured correctly in `socket-server-prod.js`

### Issue: Assets Not Loading
- Run `npm run build` to compile assets
- Check `public/build/` directory exists
- Verify `vite.config.js` is correct
- Clear Laravel cache: `php artisan cache:clear`

## Quick Deployment Checklist

- [ ] Choose hosting type (Shared/VPS)
- [ ] Set up Pusher account (if Shared Hosting)
- [ ] Configure `.env` file with correct values
- [ ] Upload files to server
- [ ] Set file permissions (775 for storage, bootstrap/cache)
- [ ] Install dependencies (`composer install`, `npm install`)
- [ ] Build assets (`npm run build`)
- [ ] Run migrations (`php artisan migrate`)
- [ ] Create storage link (`php artisan storage:link`)
- [ ] Cache config (`php artisan config:cache`)
- [ ] Start socket server with PM2 (VPS only)
- [ ] Configure web server (Nginx/Apache)
- [ ] Enable SSL certificate
- [ ] Test the application

## Support

If you encounter issues:
1. Check Hostinger's documentation
2. Review Laravel logs: `storage/logs/laravel.log`
3. Check PM2 logs (if using): `pm2 logs`
4. Verify all environment variables are set correctly

