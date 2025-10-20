# MentorHub Call System Fix

## Issue Identified
The call system between Earl (student) and Michaela (tutor) is not working because:

1. **Socket Server Not Running**: The Socket.IO server needs to be running for real-time communication
2. **Missing Dependencies**: Node.js dependencies need to be installed
3. **Call Flow Issues**: Fixed some issues in the CallManager component

## What I Fixed

### 1. Enhanced StudentChat Component
- Added proper `startCall` method with complete call data
- Added comprehensive logging for debugging
- Ensured proper data flow to CallManager

### 2. Fixed CallManager Component
- Fixed receiver type detection to use provided data instead of guessing
- Improved logging for better debugging
- Enhanced call data structure

### 3. Created Startup Scripts
- `start-socket-server.bat` - Windows batch file to start socket server
- `start-socket-server.ps1` - PowerShell script for better compatibility

## How to Fix the Call System

### Step 1: Install Dependencies
```bash
npm install
```

### Step 2: Start the Socket Server
You have two options:

**Option A: Using the batch file**
```bash
start-socket-server.bat
```

**Option B: Using PowerShell**
```powershell
.\start-socket-server.ps1
```

**Option C: Using npm script**
```bash
npm run socket
```

### Step 3: Start Laravel Development Server
In a separate terminal:
```bash
php artisan serve
```

### Step 4: Test the Call System
1. Open two browser windows/tabs
2. Login as Earl (student) in one window
3. Login as Michaela (tutor) in another window
4. Navigate to the messages page in both windows
5. Try to initiate a call from either side

## Debugging

### Check Socket Server Logs
The socket server will show detailed logs in the terminal where it's running. Look for:
- User authentication messages
- Call initiation events
- Connection status

### Check Laravel Logs
Check `storage/logs/laravel.log` for:
- Call initiation attempts
- CallManager events
- Any errors

### Browser Console
Open browser developer tools and check the console for:
- Socket connection status
- Call events
- Any JavaScript errors

## Expected Behavior

1. **Socket Connection**: Both users should see "Connected to socket server" in browser console
2. **Authentication**: Both users should see "Authentication successful!" in browser console
3. **Call Initiation**: When clicking call buttons, you should see call events in both browser consoles
4. **Call Reception**: The receiver should see an incoming call modal

## Troubleshooting

### If Socket Server Won't Start
- Make sure Node.js is installed: `node --version`
- Make sure dependencies are installed: `npm install`
- Check if port 3001 is available

### If Calls Don't Work
- Check browser console for errors
- Verify both users are authenticated
- Check socket server logs for connection issues
- Ensure both users are on the messages page

### If Call Modal Doesn't Appear
- Check if CallManager component is loaded
- Verify socket events are being received
- Check for JavaScript errors in console

## File Structure
```
├── socket-server.js          # Socket.IO server
├── public/js/socket-client.js # Client-side socket handling
├── app/Livewire/
│   ├── StudentChat.php       # Student chat component
│   ├── TutorChat.php         # Tutor chat component
│   └── CallManager.php       # Call management component
├── resources/views/livewire/
│   ├── student-chat.blade.php
│   ├── tutor-chat.blade.php
│   └── call-manager.blade.php
└── start-socket-server.*     # Startup scripts
```

## Next Steps
1. Start the socket server using one of the provided scripts
2. Test the call system between Earl and Michaela
3. Check logs if issues persist
4. Report any remaining issues with specific error messages
