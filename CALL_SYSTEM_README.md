# Call System Setup and Testing Guide

## Overview
This guide explains how to set up and test the video/voice call system in MentorHub.

## Prerequisites
1. Node.js installed on your system
2. Laravel application running
3. Socket.IO server running

## Setup Steps

### 1. Start the Socket.IO Server
```bash
# Navigate to your project directory
cd /path/to/your/project

# Install dependencies (if not already done)
npm install

# Start the socket server
node socket-server.js
```

The socket server will run on port 3001 by default.

### 2. Verify Socket Server is Running
Open your browser and navigate to:
```
http://localhost:3001/health
```

You should see a JSON response with the server status.

### 3. Test the Call System

#### Option A: Use the Test HTML File
1. Open `test-call-system.html` in your browser
2. Click "Connect" to connect to the socket server
3. Set your user ID and type (e.g., User ID: 1, User Type: Tutor)
4. Click "Authenticate"
5. Set the receiver ID and type (e.g., Receiver ID: 2, Receiver Type: Student)
6. Click "Start Video Call" or "Start Voice Call"

#### Option B: Test in the Actual Application
1. Open two browser tabs/windows
2. In the first tab, log in as a tutor and go to `/tutor/messages`
3. In the second tab, log in as a student and go to `/student/messages`
4. In the tutor tab, click the video call button on a student conversation
5. The student should receive a call notification

## Troubleshooting

### Common Issues

#### 1. Socket Connection Failed
- Check if the socket server is running on port 3001
- Verify there are no firewall issues
- Check browser console for connection errors

#### 2. Call Notifications Not Received
- Ensure both users are authenticated with the socket server
- Check that user IDs and types match between the application and socket server
- Verify the socket server logs for authentication and call events

#### 3. WebRTC Issues
- Ensure both users have granted camera/microphone permissions
- Check browser console for WebRTC errors
- Verify STUN server configuration

### Debug Steps

1. **Check Socket Server Logs**
   - Look for connection, authentication, and call events
   - Verify user authentication is successful

2. **Check Browser Console**
   - Look for socket connection status
   - Check for call event logs
   - Verify WebRTC initialization

3. **Check Network Tab**
   - Verify WebSocket connections are established
   - Check for failed requests

## File Structure

```
├── socket-server.js              # Socket.IO server
├── public/js/socket-client.js    # Client-side socket handling
├── app/Livewire/CallManager.php  # Livewire call management component
├── app/Livewire/TutorChat.php    # Tutor chat component
├── app/Livewire/StudentChat.php  # Student chat component
├── resources/views/livewire/call-manager.blade.php  # Call interface
├── test-call-system.html         # Test interface
└── CALL_SYSTEM_README.md         # This file
```

## Testing Scenarios

### 1. Basic Call Flow
- Tutor initiates video call to student
- Student receives call notification
- Student answers call
- WebRTC connection established
- Both users can see/hear each other

### 2. Call Decline Flow
- Tutor initiates call
- Student receives notification
- Student declines call
- Tutor receives decline notification

### 3. Call End Flow
- Either user ends the call
- Both users receive call ended notification
- WebRTC connection closed

### 4. Multiple Users
- Test with multiple students and tutors
- Verify call notifications go to correct recipients
- Test concurrent calls

## Security Considerations

1. **Authentication**: Users must be authenticated before making/receiving calls
2. **Authorization**: Users can only call students they have sessions with
3. **Rate Limiting**: Consider implementing call rate limiting
4. **Logging**: All call activities are logged for audit purposes

## Performance Optimization

1. **STUN Servers**: Multiple STUN servers for better connectivity
2. **ICE Candidate Pool**: Configured for optimal connection establishment
3. **Connection Timeout**: 20-second timeout for socket connections
4. **Transport Fallback**: WebSocket with polling fallback

## Future Enhancements

1. **TURN Servers**: For users behind restrictive firewalls
2. **Call Recording**: Option to record calls
3. **Screen Sharing**: Enhanced collaboration features
4. **Call Quality Metrics**: Monitor and display call quality
5. **Mobile Support**: Optimize for mobile devices

## Support

If you encounter issues:
1. Check the troubleshooting section above
2. Review socket server logs
3. Check browser console for errors
4. Verify network connectivity
5. Test with the provided test interface first
