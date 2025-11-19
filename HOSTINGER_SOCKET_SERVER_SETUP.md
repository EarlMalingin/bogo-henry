# Hostinger Socket Server Setup Guide

## ⚠️ Important: Choose Your Hosting Type First!

### Option 1: Shared Hosting (Most Common)
**❌ CANNOT run Node.js socket server**

**Solution:** Use **Pusher** instead (no Node.js needed)
- Free tier available
- No server setup required
- Works on shared hosting

**Skip to:** [Pusher Setup](#pusher-setup-for-shared-hosting)

---

### Option 2: VPS/Cloud Hosting
**✅ CAN run Node.js socket server**

**Solution:** Use **PM2** to keep socket server running permanently

**Continue reading:** [PM2 Setup](#pm2-setup-for-vps-cloud-hosting)

---

## PM2 Setup for VPS/Cloud Hosting

### ❌ DON'T Do This (Wrong Way):
```bash
npm run socket  # ❌ This stops when you close terminal!
node socket-server.js  # ❌ This stops when you close terminal!
```

### ✅ DO This (Correct Way):

#### Step 1: Install PM2 (One Time)
```bash
npm install -g pm2
```

#### Step 2: Start Socket Server with PM2
```bash
# Navigate to your project
cd /path/to/your/project

# Start socket server with PM2
pm2 start socket-server.js --name mentorhub-socket

# Save PM2 configuration (so it restarts on server reboot)
pm2 save

# Set PM2 to start on server boot
pm2 startup
# Follow the instructions it gives you
```

#### Step 3: Verify It's Running
```bash
# Check status
pm2 status

# View logs
pm2 logs mentorhub-socket

# Check if it's responding
curl http://localhost:3001/health
```

#### Step 4: Configure Environment Variables
Create or update your `.env` file:
```env
APP_ENV=production
APP_URL=https://yourdomain.com
SOCKET_PORT=3001
LARAVEL_URL=https://yourdomain.com
ALLOW_ALL_ORIGINS=true
```

---

## PM2 Commands Reference

```bash
# Start socket server
pm2 start socket-server.js --name mentorhub-socket

# Stop socket server
pm2 stop mentorhub-socket

# Restart socket server
pm2 restart mentorhub-socket

# View logs
pm2 logs mentorhub-socket

# View status
pm2 status

# Save current process list
pm2 save

# Delete from PM2
pm2 delete mentorhub-socket

# Monitor (real-time)
pm2 monit
```

---

## Pusher Setup for Shared Hosting

If you're on **Shared Hosting**, you **cannot** run Node.js, so use Pusher instead.

### Step 1: Create Pusher Account
1. Go to https://pusher.com
2. Sign up (free tier: 200k messages/day)
3. Create a new app
4. Copy your credentials:
   - App ID
   - Key
   - Secret
   - Cluster (e.g., `mt1`, `us2`)

### Step 2: Update `.env` File
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
```

### Step 3: Update Frontend
You'll need to modify the socket client to use Pusher instead of Socket.IO. This requires code changes.

---

## Complete Hostinger Deployment Steps

### For VPS/Cloud Hosting (with Socket Server):

1. **Upload Files to Hostinger**
   - Use FileZilla or Hostinger File Manager
   - Upload to `public_html` folder

2. **SSH into Your Server**
   ```bash
   ssh username@your-server-ip
   ```

3. **Install Node.js** (if not installed)
   ```bash
   curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
   sudo apt-get install -y nodejs
   ```

4. **Install Dependencies**
   ```bash
   cd /path/to/your/project
   npm install --production
   ```

5. **Configure Environment**
   - Create `.env` file with your settings
   - Set `APP_URL` to your domain
   - Set `SOCKET_PORT=3001`

6. **Start Socket Server with PM2**
   ```bash
   npm install -g pm2
   pm2 start socket-server.js --name mentorhub-socket
   pm2 save
   pm2 startup
   ```

7. **Configure Web Server** (Nginx/Apache)
   - Set up proxy for `/socket.io/` to `http://localhost:3001`
   - Or allow port 3001 through firewall

8. **Test**
   - Visit your website
   - Check browser console for "Connected to socket server"
   - Try making a call

---

## Why PM2 Instead of `npm run socket`?

| Method | Keeps Running? | Survives Reboot? | Production Ready? |
|--------|---------------|------------------|-------------------|
| `npm run socket` | ❌ No (stops when terminal closes) | ❌ No | ❌ No |
| `node socket-server.js` | ❌ No (stops when terminal closes) | ❌ No | ❌ No |
| **PM2** | ✅ Yes (runs in background) | ✅ Yes (with `pm2 startup`) | ✅ Yes |

---

## Quick Checklist

### For VPS/Cloud Hosting:
- [ ] Node.js installed
- [ ] PM2 installed globally
- [ ] Socket server started with PM2
- [ ] PM2 save executed
- [ ] PM2 startup configured
- [ ] `.env` file configured with your domain
- [ ] Web server proxy configured (if using HTTPS)
- [ ] Firewall allows port 3001 (or proxy configured)

### For Shared Hosting:
- [ ] Pusher account created
- [ ] Pusher credentials in `.env`
- [ ] Frontend modified to use Pusher (code changes needed)
- [ ] Tested Pusher connection

---

## Troubleshooting

### PM2 Process Not Starting
```bash
# Check PM2 logs
pm2 logs mentorhub-socket

# Check if port is in use
netstat -tulpn | grep 3001

# Restart PM2
pm2 restart mentorhub-socket
```

### Socket Server Not Accessible
- Check firewall: `sudo ufw allow 3001`
- Check if server is running: `pm2 status`
- Check logs: `pm2 logs mentorhub-socket`

### Connection Errors After Deployment
- Verify `APP_URL` matches your actual domain
- Check CORS settings in socket-server.js
- Verify web server proxy is configured correctly

---

## Summary

**For Hostinger VPS/Cloud:**
1. ✅ Use **PM2** to run socket server permanently
2. ❌ Don't use `npm run socket` (stops when terminal closes)
3. ✅ Run: `pm2 start socket-server.js --name mentorhub-socket`
4. ✅ Run: `pm2 save` and `pm2 startup`

**For Hostinger Shared Hosting:**
1. ✅ Use **Pusher** (no Node.js needed)
2. ❌ Cannot run socket server
3. ✅ Configure Pusher in `.env` file

The socket server must run **24/7** for calls to work, which is why PM2 is essential for production!

