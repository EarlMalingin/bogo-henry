# Call and Video Call Server-Side Fixes

## Summary
Fixed server-side issues preventing calls and video calls from working in production deployment.

## Issues Fixed

### 1. ✅ CORS Configuration (socket-server.js)
**Problem:** CORS was hardcoded to only allow `localhost:8000`, which blocked connections in production.

**Solution:**
- Made CORS configurable via environment variables
- Added support for `ALLOWED_ORIGINS` environment variable (comma-separated list)
- In production, allows all origins if `ALLOW_ALL_ORIGINS=true` or `NODE_ENV=production`
- Added proper error handling for server startup

### 2. ✅ Socket Client URL Configuration (socket-client.js)
**Problem:** Socket client couldn't properly detect production URLs and connect to the socket server.

**Solution:**
- Enhanced URL detection logic to handle production environments
- Added support for custom socket URL via `window.socketServerUrl` from Laravel
- Improved HTTPS/HTTP detection
- Added better reconnection logic with more attempts
- Added user-friendly error notifications when connection fails

### 3. ✅ Environment Configuration
**Problem:** No way to configure socket server URL from Laravel environment.

**Solution:**
- Added socket URL configuration in both tutor and student messages views
- Automatically constructs socket URL based on `APP_URL` and `APP_ENV`
- Supports custom `SOCKET_URL` environment variable
- Falls back to intelligent defaults based on environment

### 4. ✅ Error Handling (CallManager.php)
**Problem:** Server-side errors in call operations weren't properly caught and handled.

**Solution:**
- Added try-catch blocks around all call operations
- Added proper error logging with context
- Added error events dispatched to frontend
- Ensured call state is always reset even on errors

## Environment Variables

Add these to your `.env` file:

```env
# Socket Server Configuration
SOCKET_PORT=3001
SOCKET_URL=https://yourdomain.com/socket.io  # Optional: Custom socket URL
ALLOW_ALL_ORIGINS=true  # Optional: Allow all origins in production

# Application Configuration
APP_ENV=production
APP_URL=https://yourdomain.com
```

## Deployment Steps

1. **Update Environment Variables**
   - Add the socket configuration variables to your `.env` file

2. **Start Socket Server**
   ```bash
   # Development
   node socket-server.js
   
   # Production (with PM2)
   pm2 start socket-server.js --name mentorhub-socket
   pm2 save
   ```

3. **Configure Web Server (Nginx/Apache)**
   - If using HTTPS, set up a proxy for `/socket.io/` path
   - Or ensure port 3001 is accessible

4. **Test Connection**
   - Open browser console and check for socket connection logs
   - Try initiating a call to verify it works

## Files Modified

1. `socket-server.js` - CORS configuration and error handling
2. `public/js/socket-client.js` - URL detection and error handling
3. `resources/views/tutor/messages.blade.php` - Socket URL configuration
4. `resources/views/student/chat/student-messages.blade.php` - Socket URL configuration
5. `app/Livewire/CallManager.php` - Error handling improvements

## Testing

1. **Check Socket Connection**
   - Open browser console
   - Look for "Connected to socket server" message
   - Check for any connection errors

2. **Test Call Initiation**
   - Click video/voice call button
   - Check browser console for call events
   - Verify receiver gets incoming call notification

3. **Check Server Logs**
   - Monitor socket server logs for connection events
   - Check Laravel logs for call-related errors

## Common Issues

### Issue: "Server side has a problem" error
**Solution:**
- Check if socket server is running: `pm2 status` or check process
- Verify CORS configuration allows your domain
- Check firewall allows port 3001 (or proxy is configured)
- Verify `SOCKET_URL` or `APP_URL` is correctly set

### Issue: Connection refused
**Solution:**
- Ensure socket server is running on the correct port
- Check if port is accessible (not blocked by firewall)
- Verify socket URL matches server configuration

### Issue: CORS errors
**Solution:**
- Set `ALLOW_ALL_ORIGINS=true` in production
- Or add your domain to `ALLOWED_ORIGINS` environment variable
- Check socket server logs for CORS rejection messages

## Notes

- The socket server now automatically detects production environment
- All error messages are logged for debugging
- User-friendly error notifications appear in the UI
- The system gracefully handles connection failures

