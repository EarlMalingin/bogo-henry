# Quick Start: Socket Server for Local Development

## The Problem
You're seeing "Connection Error: websocket error" because the **socket server is not running**.

## Solution: Start the Socket Server

### Step 1: Open a New Terminal/Command Prompt

**Keep your Laravel server running** (php artisan serve), and open a **NEW terminal window** for the socket server.

### Step 2: Navigate to Your Project

```bash
cd C:\xampp\htdocs\Henrybuang-main
```

### Step 3: Install Dependencies (First Time Only)

```bash
npm install
```

This installs:
- express
- socket.io
- cors
- Other required packages

### Step 4: Start the Socket Server

**Option A: Using npm script (Recommended)**
```bash
npm run socket
```

**Option B: Direct node command**
```bash
node socket-server.js
```

**Option C: Using the batch file (Windows)**
```bash
start-socket-server.bat
```

### Step 5: Verify It's Running

You should see output like:
```
Socket.IO server running on port 3001
Health check available at http://localhost:3001/health
Allowed origins: [ 'http://localhost:8000', 'http://127.0.0.1:8000', ... ]
Environment: development
```

### Step 6: Test the Connection

1. Open your browser to: `http://localhost:3001/health`
2. You should see: `{"status":"ok","connectedUsers":0,"timestamp":"..."}`

3. Refresh your messages page (`http://127.0.0.1:8000/student/messages`)
4. The "Connection Error" should disappear
5. Check browser console (F12) - you should see "Connected to socket server"

---

## Running Both Servers

You need **TWO terminal windows** running:

### Terminal 1: Laravel Server
```bash
cd C:\xampp\htdocs\Henrybuang-main
php artisan serve
```
**Keep this running!**

### Terminal 2: Socket Server
```bash
cd C:\xampp\htdocs\Henrybuang-main
npm run socket
```
**Keep this running too!**

---

## Troubleshooting

### Error: "Cannot find module 'express'"
**Solution:** Run `npm install` first

### Error: "Port 3001 is already in use"
**Solution:** 
1. Another socket server is already running
2. Find and close it, or change the port in `.env`:
   ```env
   SOCKET_PORT=3002
   ```

### Error: "node: command not found"
**Solution:** 
1. Install Node.js from https://nodejs.org
2. Restart your terminal after installation

### Still seeing "Connection Error"
**Solution:**
1. Make sure socket server is running (check Terminal 2)
2. Check browser console (F12) for specific errors
3. Verify socket server URL in browser console
4. Try refreshing the page

---

## Quick Commands Reference

```bash
# Install dependencies (first time only)
npm install

# Start socket server
npm run socket

# Or directly
node socket-server.js

# Check if port 3001 is in use (Windows)
netstat -ano | findstr :3001

# Check if port 3001 is in use (Linux/Mac)
lsof -i :3001
```

---

## What Should Happen

✅ **Socket server running** → Terminal shows "Socket.IO server running on port 3001"

✅ **Laravel server running** → Terminal shows "Laravel development server started"

✅ **Browser connected** → Console shows "Connected to socket server"

✅ **No errors** → "Connection Error" popup disappears

---

## For Production (Hostinger)

On Hostinger, you'll use PM2 to keep the socket server running:

```bash
pm2 start socket-server.js --name mentorhub-socket
pm2 save
pm2 startup
```

But for **local development**, just run `npm run socket` in a terminal!

