# Deployment Fixes Applied

This document outlines all the fixes applied to resolve deployment issues.

## Issues Fixed

### 1. ✅ PM2 Configuration
**Problem:** PM2 ecosystem config wasn't using the production socket server file.

**Fix:** Updated `ecosystem.config.js` to:
- Use `socket-server-prod.js` for production environment
- Properly read environment variables for `SOCKET_PORT` and `LARAVEL_URL`
- Fallback to `APP_URL` if `LARAVEL_URL` is not set

### 2. ✅ Socket Client HTTPS Support
**Problem:** Socket client was hardcoded to use HTTP, which doesn't work in production with HTTPS.

**Fix:** Updated `public/js/socket-client.js` to:
- Detect protocol (HTTP/HTTPS) from current page
- Support proxied socket connections (when socket is on same domain)
- Allow custom socket URL via `window.socketServerUrl` from Laravel
- Use port 3001 for development, proxied path for production HTTPS
- Added reconnection logic for better reliability

### 3. ✅ Socket Server CORS Configuration
**Problem:** Production socket server had hardcoded placeholder domain and didn't properly read environment variables.

**Fix:** Updated `socket-server-prod.js` to:
- Read `LARAVEL_URL` and `APP_URL` from environment
- Support multiple allowed origins via `SOCKET_ALLOWED_ORIGINS` (comma-separated)
- Properly handle development vs production CORS settings
- Fallback to localhost variants for development

### 4. ✅ Deployment Scripts
**Problem:** No automated deployment process.

**Fix:** Created deployment scripts:
- `deploy.sh` - For Linux/macOS servers
- `deploy.ps1` - For Windows servers

Both scripts handle:
- Environment file checks
- Dependency installation
- Asset building
- Laravel optimization
- PM2 socket server setup

## Environment Variables Required

Add these to your `.env` file for production:

```env
# Application
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Socket Server
SOCKET_PORT=3001
LARAVEL_URL=https://yourdomain.com

# Optional: Multiple allowed origins for socket (comma-separated)
SOCKET_ALLOWED_ORIGINS=https://yourdomain.com,https://www.yourdomain.com
```

## Deployment Steps

### Option 1: Using Deployment Scripts

**Linux/macOS:**
```bash
chmod +x deploy.sh
./deploy.sh
```

**Windows:**
```powershell
.\deploy.ps1
```

### Option 2: Manual Deployment

1. **Install Dependencies**
   ```bash
   composer install --optimize-autoloader --no-dev
   npm install --production
   npm run build
   ```

2. **Laravel Setup**
   ```bash
   php artisan key:generate  # If APP_KEY not set
   php artisan storage:link
   php artisan migrate --force
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Start Socket Server**
   ```bash
   pm2 start ecosystem.config.js --env production
   pm2 save
   pm2 startup  # For auto-start on boot
   ```

## Web Server Configuration

### Nginx Configuration for Socket.IO Proxy

Add this to your Nginx server block:

```nginx
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
```

### Apache Configuration (if using)

Add to your `.htaccess` or virtual host:

```apache
# Socket.IO proxy (requires mod_proxy)
ProxyPreserveHost On
ProxyPass /socket.io/ http://localhost:3001/socket.io/
ProxyPassReverse /socket.io/ http://localhost:3001/socket.io/
```

## Laravel Configuration

To set a custom socket URL in Laravel, add this to your layout file (e.g., `resources/views/layouts/app.blade.php`):

```php
<script>
    // Set socket server URL from Laravel config
    window.socketServerUrl = @json(config('app.socket_url', null));
</script>
```

Then add to `config/app.php`:

```php
'socket_url' => env('SOCKET_URL', null), // e.g., 'https://yourdomain.com/socket.io'
```

## Testing Deployment

1. **Check Socket Server**
   ```bash
   curl http://localhost:3001/health
   # or
   curl https://yourdomain.com/socket.io/health
   ```

2. **Check Laravel**
   ```bash
   php artisan route:list
   php artisan config:show app
   ```

3. **Check PM2**
   ```bash
   pm2 status
   pm2 logs mentorhub-socket
   ```

## Common Issues and Solutions

### Issue: Socket connection fails in production
**Solution:** 
- Ensure socket server is running: `pm2 status`
- Check CORS configuration in `socket-server-prod.js`
- Verify `LARAVEL_URL` and `APP_URL` in `.env`
- Check firewall allows port 3001 (or use proxy)

### Issue: Mixed content errors (HTTP/HTTPS)
**Solution:**
- Ensure socket client uses HTTPS when page is HTTPS
- Use proxy to serve socket on same domain
- Or configure socket server with SSL certificate

### Issue: Database sessions table read-only
**Solution:**
- Check database user permissions
- Run: `GRANT ALL PRIVILEGES ON database_name.* TO 'user'@'localhost';`
- Or switch to file-based sessions in `config/session.php`

### Issue: Missing .env file
**Solution:**
- Copy `.env.example` to `.env`
- Run `php artisan key:generate`
- Configure all required environment variables

## Additional Notes

- The socket server will automatically use `socket-server-prod.js` when `NODE_ENV=production`
- For development, it uses `socket-server.js`
- Socket client automatically detects protocol and adjusts connection URL
- All fixes are backward compatible with existing development setup

