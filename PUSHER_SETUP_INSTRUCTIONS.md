# 🚀 Pusher Setup Instructions for Hostinger

## ✅ What Has Been Changed

Your code has been updated to use **Pusher** instead of Socket.IO for real-time messaging and calls. This works perfectly on Hostinger shared hosting!

### Files Updated:
1. ✅ `resources/views/tutor/messages.blade.php` - Replaced Socket.IO with Pusher
2. ✅ `resources/views/student/chat/student-messages.blade.php` - Replaced Socket.IO with Pusher
3. ✅ `public/js/socket-client.js` - Completely rewritten to use Pusher
4. ✅ Created API controllers for messaging and calls
5. ✅ Created Laravel Events for broadcasting
6. ✅ Added API routes

---

## 📋 Step-by-Step Setup

### **Step 1: Create Pusher Account (5 minutes)**

1. Go to **https://pusher.com**
2. Click **"Sign Up"** (Free tier: 200k messages/day, 100 concurrent connections)
3. After signing up, click **"Create app"** or go to **"Channels"** → **"Create app"**
4. Fill in:
   - **App name**: MentorHub (or any name)
   - **Cluster**: Choose closest to your server
     - `ap1` - Asia Pacific (Singapore)
     - `ap2` - Asia Pacific (Mumbai)
     - `ap3` - Asia Pacific (Tokyo)
     - `ap4` - Asia Pacific (Sydney)
     - `eu` - Europe (Ireland)
     - `us2` - US East (Ohio)
     - `us3` - US West (Oregon)
   - **Front-end tech**: Vanilla JS
   - **Back-end tech**: Laravel
5. Click **"Create app"**
6. Go to **"App Keys"** tab
7. **Copy these values** (you'll need them in Step 2):
   - **App ID** (e.g., `1234567`)
   - **Key** (e.g., `abcdef123456`)
   - **Secret** (e.g., `secret123456`)
   - **Cluster** (e.g., `ap1`)

---

### **Step 2: Update .env File on Hostinger**

1. **Access Hostinger File Manager** or **FTP**
2. Navigate to your domain folder (usually `public_html`)
3. Open `.env` file
4. **Add/Update these lines**:

```env
# Broadcasting Configuration
BROADCAST_DRIVER=pusher

# Pusher Configuration (replace with YOUR values from Step 1)
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

**Example with real values:**
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

---

### **Step 3: Install Pusher PHP SDK (if not already installed)**

Via **SSH** or **Hostinger Terminal**:

```bash
cd public_html  # or your domain folder
composer require pusher/pusher-php-server
```

---

### **Step 4: Clear Laravel Cache**

Via **SSH** or **Hostinger Terminal**:

```bash
cd public_html  # or your domain folder
php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan route:clear
php artisan route:cache
```

---

### **Step 5: Set Up Broadcasting Channels (Important!)**

You need to authorize private channels. Create or update `routes/channels.php`:

```php
<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('private-chat-{roomId}', function ($user, $roomId) {
    // Allow authenticated users to access chat rooms
    return ['id' => $user->id];
});

Broadcast::channel('private-user-{userType}-{userId}', function ($user, $userType, $userId) {
    // Users can only access their own personal channel
    if ($userType === 'student') {
        return $user->id == $userId;
    } elseif ($userType === 'tutor') {
        return $user->id == $userId;
    }
    return false;
});

Broadcast::channel('private-call-{roomId}', function ($user, $roomId) {
    // Allow authenticated users in call rooms
    return ['id' => $user->id];
});
```

---

### **Step 6: Test the Connection**

1. **Clear browser cache** (Ctrl+Shift+R or Cmd+Shift+R)
2. **Open your website** and go to the messages page
3. **Open browser console** (F12)
4. **Look for**: `✅ Connected to Pusher`
5. **Try sending a message** - should work without errors!

---

## 🔧 Troubleshooting

### Still seeing "websocket error"?

1. **Check .env file** - Make sure Pusher credentials are correct
2. **Check Pusher dashboard** - Verify your app is active
3. **Check browser console** - Look for specific error messages
4. **Verify cluster** - Make sure cluster matches in .env and Pusher dashboard
5. **Check Laravel logs**: `storage/logs/laravel.log`

### Messages not sending?

1. **Check Laravel logs**: `storage/logs/laravel.log`
2. **Verify Pusher credentials** in .env
3. **Test Pusher connection** in Pusher dashboard → Debug Console
4. **Check API routes** - Make sure routes are accessible

### Calls not working?

1. **Check WebRTC events** are being broadcast
2. **Verify call room channels** are subscribed
3. **Check browser console** for WebRTC errors

### "Access denied" errors?

1. **Check `routes/channels.php`** - Make sure channel authorization is set up
2. **Verify user authentication** - Make sure users are logged in
3. **Check middleware** - API routes require authentication

---

## 📝 Important Notes

1. **Private Channels**: Pusher requires authentication for private channels. Make sure `routes/channels.php` is configured correctly.

2. **Queue Workers**: For production, you may want to use queue workers for broadcasting:
   ```bash
   php artisan queue:work
   ```

3. **Free Tier Limits**: Pusher free tier includes:
   - 200,000 messages/day
   - 100 concurrent connections
   - 100 channels

4. **HTTPS Required**: Pusher requires HTTPS in production. Make sure your site uses SSL.

---

## ✅ Summary

1. ✅ Sign up for Pusher (free)
2. ✅ Get Pusher credentials
3. ✅ Update `.env` with Pusher config
4. ✅ Install Pusher PHP SDK
5. ✅ Set up channel authorization
6. ✅ Clear cache
7. ✅ Test!

**Your real-time messaging and calls will work perfectly on Hostinger! 🎉**

---

## 🆘 Need Help?

- Pusher Documentation: https://pusher.com/docs
- Laravel Broadcasting: https://laravel.com/docs/broadcasting
- Check `storage/logs/laravel.log` for errors

