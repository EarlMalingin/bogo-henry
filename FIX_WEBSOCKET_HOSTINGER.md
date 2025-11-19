# 🔧 Fix WebSocket/Real-Time Messaging on Hostinger

## Problem
Your application is trying to connect to a Socket.IO server, but Hostinger shared hosting **doesn't support running Node.js servers**. This causes the "websocket error" you're seeing.

## Solution Options

### ✅ **Option 1: Use Pusher (Recommended for Shared Hosting)**

Pusher is a cloud service that handles WebSocket connections for you - no Node.js server needed!

---

## Step-by-Step Fix Using Pusher

### **Step 1: Create Pusher Account**

1. Go to **https://pusher.com**
2. Click **"Sign Up"** (Free tier available: 200k messages/day, 100 concurrent connections)
3. After signing up, click **"Create app"** or **"Channels"** → **"Create app"**
4. Fill in:
   - **App name**: MentorHub (or any name)
   - **Cluster**: Choose closest to your server (e.g., `ap1` for Asia, `mt1` for US)
   - **Front-end tech**: Vanilla JS
   - **Back-end tech**: Laravel
5. Click **"Create app"**
6. Go to **"App Keys"** tab
7. Copy these values:
   - **App ID** (e.g., `1234567`)
   - **Key** (e.g., `abcdef123456`)
   - **Secret** (e.g., `secret123456`)
   - **Cluster** (e.g., `ap1` or `mt1`)

### **Step 2: Update .env File on Hostinger**

1. Access your Hostinger **File Manager** or **FTP**
2. Navigate to your domain folder (usually `public_html`)
3. Open `.env` file
4. Add/Update these lines:

```env
# Broadcasting Configuration
BROADCAST_DRIVER=pusher

# Pusher Configuration (use your actual values from Step 1)
PUSHER_APP_ID=your_app_id_here
PUSHER_APP_KEY=your_key_here
PUSHER_APP_SECRET=your_secret_here
PUSHER_APP_CLUSTER=ap1
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https

# Disable Socket.IO (not needed with Pusher)
SOCKET_URL=
SOCKET_PORT=
```

**Example:**
```env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=1234567
PUSHER_APP_KEY=abcdef123456
PUSHER_APP_SECRET=secret123456
PUSHER_APP_CLUSTER=ap1
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
```

5. **Save** the file

### **Step 3: Update Frontend to Use Pusher**

You need to modify the messaging pages to use Pusher instead of Socket.IO.

#### **For Tutor Messages Page:**

1. Open: `resources/views/tutor/messages.blade.php`
2. Find the Socket.IO script (around line 11):
   ```html
   <script src="https://cdn.socket.io/4.8.1/socket.io.min.js"></script>
   ```
3. **Replace** it with Pusher:
   ```html
   <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
   ```

#### **For Student Messages Page:**

1. Open: `resources/views/student/chat/student-messages.blade.php`
2. Find the Socket.IO script (around line 11)
3. **Replace** it with Pusher:
   ```html
   <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
   ```

### **Step 4: Update JavaScript Socket Client**

1. Open: `public/js/socket-client.js`
2. **Replace the entire file** with Pusher-compatible code (see below)

### **Step 5: Clear Cache**

Via **SSH** or **Hostinger Terminal**:

```bash
cd public_html  # or your domain folder
php artisan config:clear
php artisan cache:clear
php artisan config:cache
```

---

## Alternative: Option 2 - Disable Real-Time Features (Quick Fix)

If you don't need real-time messaging right now, you can temporarily disable it:

### **Step 1: Update .env**

```env
BROADCAST_DRIVER=log
SOCKET_URL=
SOCKET_PORT=
```

### **Step 2: Update Frontend**

In your messaging pages, comment out or remove the socket connection code.

### **Step 3: Clear Cache**

```bash
php artisan config:clear
php artisan cache:clear
```

**Note:** This will disable real-time features, but messaging will still work (users need to refresh to see new messages).

---

## Pusher-Compatible Socket Client Code

Replace `public/js/socket-client.js` with this Pusher version:

```javascript
class ChatSocket {
    constructor() {
        this.pusher = null;
        this.channel = null;
        this.isConnected = false;
        this.connect();
    }

    connect() {
        try {
            // Initialize Pusher
            this.pusher = new Pusher(window.pusherKey || '{{ env("PUSHER_APP_KEY") }}', {
                cluster: window.pusherCluster || '{{ env("PUSHER_APP_CLUSTER") }}',
                encrypted: true,
                forceTLS: true
            });

            this.pusher.connection.bind('connected', () => {
                console.log('Connected to Pusher');
                this.isConnected = true;
                this.onConnect();
            });

            this.pusher.connection.bind('disconnected', () => {
                console.log('Disconnected from Pusher');
                this.isConnected = false;
                this.onDisconnect();
            });

            this.pusher.connection.bind('error', (err) => {
                console.error('Pusher connection error:', err);
                this.onError(err);
            });

        } catch (error) {
            console.error('Failed to initialize Pusher:', error);
            this.onError(error);
        }
    }

    authenticate(userId, userType) {
        if (!this.pusher) return;

        // Subscribe to user's personal channel
        const channelName = `private-user-${userType}-${userId}`;
        this.channel = this.pusher.subscribe(channelName);

        this.channel.bind('pusher:subscription_succeeded', () => {
            console.log('Subscribed to channel:', channelName);
        });

        return { userId, userType };
    }

    joinChat(studentId, tutorId) {
        if (!this.pusher) return;

        const roomId = `chat-${Math.min(studentId, tutorId)}-${Math.max(studentId, tutorId)}`;
        const chatChannel = this.pusher.subscribe(`private-${roomId}`);

        chatChannel.bind('new_message', (data) => {
            if (this.onMessage) {
                this.onMessage(data);
            }
        });

        chatChannel.bind('user_typing', (data) => {
            if (this.onTyping) {
                this.onTyping(data);
            }
        });

        return chatChannel;
    }

    sendMessage(messageData) {
        // Send via AJAX to Laravel, which will broadcast via Pusher
        fetch('/api/messages/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(messageData)
        }).then(response => response.json())
          .then(data => {
              console.log('Message sent:', data);
          })
          .catch(error => {
              console.error('Error sending message:', error);
          });
    }

    onConnect() {
        // Override in your implementation
    }

    onDisconnect() {
        // Override in your implementation
    }

    onError(error) {
        // Override in your implementation
    }

    disconnect() {
        if (this.pusher) {
            this.pusher.disconnect();
        }
    }
}

// Make it globally available
window.ChatSocket = ChatSocket;
```

---

## Testing After Fix

1. **Clear browser cache** (Ctrl+Shift+R)
2. **Refresh the messages page**
3. **Check browser console** (F12) - should see "Connected to Pusher"
4. **Try sending a message** - should work without errors

---

## Troubleshooting

### Still seeing "websocket error"?

1. **Check .env file** - Make sure Pusher credentials are correct
2. **Check Pusher dashboard** - Verify your app is active
3. **Check browser console** - Look for specific error messages
4. **Verify cluster** - Make sure cluster matches in .env and Pusher dashboard

### Messages not sending?

1. **Check Laravel logs**: `storage/logs/laravel.log`
2. **Verify Pusher credentials** in .env
3. **Test Pusher connection** in Pusher dashboard → Debug Console

### Need Help?

- Check Pusher documentation: https://pusher.com/docs
- Laravel Broadcasting: https://laravel.com/docs/broadcasting

---

## Quick Summary

1. ✅ Sign up for Pusher (free)
2. ✅ Get Pusher credentials
3. ✅ Update `.env` with Pusher config
4. ✅ Replace Socket.IO with Pusher in views
5. ✅ Update socket-client.js
6. ✅ Clear cache
7. ✅ Test!

**Your real-time messaging will work perfectly on Hostinger! 🎉**

