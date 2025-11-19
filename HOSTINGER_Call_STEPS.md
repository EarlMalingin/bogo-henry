# Hostinger Deployment - Step by Step

## ✅ What You DON'T Need to Change

**You do NOT need to change localhost in the code files!** The localhost references are:
- Default fallback values for development
- Automatically overridden by environment variables in production
- Used only for detection (to know if you're in dev or production)

## ✅ What You DO Need to Change

### 1. Your `.env` File on Hostinger

Create/update your `.env` file on Hostinger with these values:

```env
# Application Settings
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database (from Hostinger control panel)
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=u123456789_yourdb
DB_USERNAME=u123456789_youruser
DB_PASSWORD=your_password

# Socket Server Configuration
SOCKET_PORT=3001
SOCKET_URL=https://yourdomain.com/socket.io
# OR if socket is on different port:
# SOCKET_URL=https://yourdomain.com:3001

# Optional: Allow all origins (for production)
ALLOW_ALL_ORIGINS=true

# Optional: Specific allowed origins (comma-separated)
# ALLOWED_ORIGINS=https://yourdomain.com,https://www.yourdomain.com
```

**Important:** Replace `yourdomain.com` with your actual Hostinger domain!

---

## Hostinger Hosting Type Check

### Option A: Shared Hosting (Most Common)
**Cannot run Node.js socket server directly**

**Solution:** Use Pusher (recommended for shared hosting)

1. **Get Pusher Account** (free tier available)
   - Go to https://pusher.com
   - Sign up and create an app
   - Copy credentials

2. **Update `.env`:**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Pusher Configuration
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=mt1
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https

# Socket server not needed with Pusher
# SOCKET_PORT=3001
```

3. **Note:** You'll need to modify the socket client to use Pusher instead of Socket.IO (separate task)

---

### Option B: VPS/Cloud Hosting
**Can run Node.js socket server**

1. **Update `.env` with your domain:**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Socket Server
SOCKET_PORT=3001
LARAVEL_URL=https://yourdomain.com
ALLOW_ALL_ORIGINS=true
```

2. **Start Socket Server:**
```bash
# Via SSH
cd /path/to/your/app
pm2 start socket-server.js --name mentorhub-socket
pm2 save
pm2 startup
```

3. **Configure Nginx/Apache** to proxy `/socket.io/` to `http://localhost:3001`

---

## Step-by-Step Deployment

### Step 1: Upload Files
1. Use FileZilla or Hostinger File Manager
2. Upload all files to `public_html` (or your domain folder)
3. **Important:** Point document root to `public_html/public`

### Step 2: Create `.env` File
1. In Hostinger File Manager, create `.env` file
2. Copy from `.env.example` if available
3. Update with your Hostinger values (see above)

### Step 3: Set File Permissions
Via Hostinger File Manager or SSH:
- `storage/` folder → **775**
- `bootstrap/cache/` folder → **775**

### Step 4: Run Commands via SSH
```bash
cd public_html  # or your domain folder

# Install dependencies
composer install --optimize-autoloader --no-dev

# Generate app key
php artisan key:generate

# Create storage link
php artisan storage:link

# Run migrations
php artisan migrate --force

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 5: Build Assets (on your local machine)
```bash
npm install
npm run build
```
Then upload the `public/build/` folder to your server.

---

## Environment Variables Summary

### Required for Production:
- ✅ `APP_URL` - Your actual domain (e.g., `https://yourdomain.com`)
- ✅ `APP_ENV=production`
- ✅ `APP_DEBUG=false`

### For Socket Server (VPS only):
- ✅ `SOCKET_PORT=3001`
- ✅ `LARAVEL_URL` - Your actual domain
- ✅ `ALLOW_ALL_ORIGINS=true` (or specific origins)

### For Pusher (Shared Hosting):
- ✅ `BROADCAST_DRIVER=pusher`
- ✅ `PUSHER_APP_ID`, `PUSHER_APP_KEY`, `PUSHER_APP_SECRET`, `PUSHER_APP_CLUSTER`

---

## How It Works

1. **Code checks environment variables first:**
   - If `APP_URL` is set → uses that
   - If `SOCKET_URL` is set → uses that
   - Otherwise → falls back to localhost (for development)

2. **Production detection:**
   - Code checks if hostname includes "localhost" or "127.0.0.1"
   - If NOT → assumes production and uses `APP_URL`
   - If YES → assumes development and uses localhost

3. **CORS handling:**
   - In production, `ALLOW_ALL_ORIGINS=true` allows your domain
   - Or specify exact domains in `ALLOWED_ORIGINS`

---

## Quick Checklist

- [ ] Updated `.env` with your actual domain (`APP_URL`)
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] If VPS: Set `SOCKET_PORT`, `LARAVEL_URL`, `ALLOW_ALL_ORIGINS`
- [ ] If Shared: Set Pusher credentials
- [ ] Uploaded files to Hostinger
- [ ] Set file permissions (storage/, bootstrap/cache/)
- [ ] Ran `php artisan config:cache`
- [ ] Built assets and uploaded `public/build/`
- [ ] If VPS: Started socket server with PM2

---

## Testing After Deployment

1. **Check Laravel:**
   - Visit `https://yourdomain.com`
   - Should see your app (not errors)

2. **Check Socket Connection:**
   - Open browser console (F12)
   - Look for "Connected to socket server" message
   - If error, check socket server is running (VPS) or Pusher is configured (Shared)

3. **Test Calls:**
   - Try initiating a video/voice call
   - Check browser console for any errors
   - Verify receiver gets notification

---

## Common Issues

### "Server side has a problem"
- Check socket server is running (VPS): `pm2 status`
- Check Pusher credentials are correct (Shared)
- Verify `APP_URL` matches your actual domain
- Check CORS settings

### Connection refused
- Verify socket server is running
- Check firewall allows port 3001 (VPS)
- Verify `SOCKET_URL` is correct

### CORS errors
- Set `ALLOW_ALL_ORIGINS=true` in production
- Or add your domain to `ALLOWED_ORIGINS`

---

## Summary

**You don't need to change localhost in code!** Just:
1. Set `APP_URL` to your actual domain in `.env`
2. Set other environment variables as needed
3. The code automatically uses your domain instead of localhost

The localhost references are just defaults that get overridden by your environment variables.

