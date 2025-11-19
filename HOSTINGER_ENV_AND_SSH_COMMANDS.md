# Hostinger: .env File + SSH Commands

## Step 1: Add to Your `.env` File on Hostinger

Add these lines to your `.env` file on Hostinger:

```env
# Application Settings
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Socket Server Configuration
SOCKET_PORT=3001
LARAVEL_URL=https://yourdomain.com
ALLOW_ALL_ORIGINS=true
```

**Important:** Replace `yourdomain.com` with your actual Hostinger domain!

**Example:**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://mentorhub.com

SOCKET_PORT=3001
LARAVEL_URL=https://mentorhub.com
ALLOW_ALL_ORIGINS=true
```

---

## Step 2: SSH Commands to Run in Hostinger

Connect to Hostinger via SSH, then run these commands **one by one**:

### 1. Navigate to Your Project
```bash
cd public_html
```
(Or wherever your Laravel project is located)

### 2. Install PM2 (One Time Only)
```bash
npm install -g pm2
```

### 3. Install Node.js Dependencies (If Not Done)
```bash
npm install --production
```

### 4. Start Socket Server with PM2
```bash
pm2 start socket-server.js --name mentorhub-socket
```

### 5. Save PM2 Configuration
```bash
pm2 save
```

### 6. Set PM2 to Auto-Start on Server Reboot
```bash
pm2 startup
```
**Important:** Copy and run the command it shows you (usually something like `sudo env PATH=...`)

### 7. Verify It's Running
```bash
pm2 status
```
You should see `mentorhub-socket` with status "online"

### 8. Check Logs (Optional)
```bash
pm2 logs mentorhub-socket
```

---

## Complete Command List (Copy & Paste)

```bash
# Navigate to project
cd public_html

# Install PM2 (one time)
npm install -g pm2

# Install dependencies (if needed)
npm install --production

# Start socket server
pm2 start socket-server.js --name mentorhub-socket

# Save configuration
pm2 save

# Set auto-start (then run the command it shows)
pm2 startup

# Check status
pm2 status
```

---

## Verify Everything Works

### 1. Check PM2 Status
```bash
pm2 status
```
Should show: `mentorhub-socket | online`

### 2. Test Socket Server
```bash
curl http://localhost:3001/health
```
Should return: `{"status":"ok","connectedUsers":0,"timestamp":"..."}`

### 3. Check Your Website
- Visit your website
- Open browser console (F12)
- Look for "Connected to socket server" message
- Try making a call

---

## Troubleshooting Commands

### If Socket Server Stops
```bash
# Restart it
pm2 restart mentorhub-socket

# Or start it again
pm2 start socket-server.js --name mentorhub-socket
```

### If You Need to Stop It
```bash
pm2 stop mentorhub-socket
```

### If You Need to Delete It
```bash
pm2 delete mentorhub-socket
```

### View Logs (to see errors)
```bash
pm2 logs mentorhub-socket
```

### Check if Port 3001 is in Use
```bash
netstat -tulpn | grep 3001
```

---

## Important Notes

1. **Replace `yourdomain.com`** in `.env` with your actual domain
2. **Run `pm2 save`** after starting - this saves the configuration
3. **Run `pm2 startup`** - this makes it start automatically when server reboots
4. **Keep the SSH session open** while running commands (or use `screen`/`tmux`)

---

## Quick Checklist

- [ ] Updated `.env` file with your domain
- [ ] Connected to Hostinger via SSH
- [ ] Ran `npm install -g pm2`
- [ ] Ran `pm2 start socket-server.js --name mentorhub-socket`
- [ ] Ran `pm2 save`
- [ ] Ran `pm2 startup` and executed the command it showed
- [ ] Verified with `pm2 status` - shows "online"
- [ ] Tested on your website - calls work!

---

## Example Session

Here's what a successful session looks like:

```bash
$ cd public_html
$ npm install -g pm2
... (installs PM2)
$ pm2 start socket-server.js --name mentorhub-socket
[PM2] Starting socket-server.js
[PM2] Process started
$ pm2 save
[PM2] Configuration saved
$ pm2 startup
[PM2] To setup the Startup Script, copy/paste the following command:
sudo env PATH=$PATH:/usr/bin pm2 startup systemd -u yourusername --hp /home/yourusername
$ sudo env PATH=$PATH:/usr/bin pm2 startup systemd -u yourusername --hp /home/yourusername
... (sets up auto-start)
$ pm2 status
┌─────┬─────────────────────┬─────────┬─────────┬──────────┐
│ id  │ name                │ status  │ restart │ uptime   │
├─────┼─────────────────────┼─────────┼─────────┼──────────┤
│ 0   │ mentorhub-socket    │ online  │ 0       │ 5s       │
└─────┴─────────────────────┴─────────┴─────────┴──────────┘
```

That's it! Your socket server is now running permanently.

