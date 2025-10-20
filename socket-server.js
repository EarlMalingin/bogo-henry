import express from 'express';
import http from 'http';
import { Server } from 'socket.io';
import cors from 'cors';

const app = express();
const server = http.createServer(app);

// Configure CORS
app.use(cors({
    origin: ["http://localhost:8000", "http://127.0.0.1:8000"], // Allow both localhost and 127.0.0.1
    methods: ["GET", "POST"]
}));

// Create Socket.IO server
const io = new Server(server, {
    cors: {
        origin: ["http://localhost:8000", "http://127.0.0.1:8000"], // Allow both localhost and 127.0.0.1
        methods: ["GET", "POST"]
    }
});

// Store connected users
const connectedUsers = new Map();

io.on('connection', (socket) => {
    console.log('=== USER CONNECTED ===');
    console.log('Socket ID:', socket.id);
    console.log('Remote address:', socket.handshake.address);
    console.log('User agent:', socket.handshake.headers['user-agent']);

    // Handle user authentication
    socket.on('authenticate', (userData) => {
        const { userId, userType } = userData;
        console.log(`=== AUTHENTICATION REQUEST RECEIVED ===`);
        console.log(`Socket ID: ${socket.id}`);
        console.log(`User data:`, userData);
        
        // Check if this user is already connected with a different socket
        let existingSocketId = null;
        for (const [socketId, user] of connectedUsers.entries()) {
            if (user.userId == userId && user.userType === userType) {
                existingSocketId = socketId;
                break;
            }
        }
        
        // If user is already connected, disconnect the old connection
        if (existingSocketId) {
            console.log(`User ${userId} (${userType}) already connected on socket ${existingSocketId}, disconnecting old connection`);
            const oldSocket = io.sockets.sockets.get(existingSocketId);
            if (oldSocket) {
                oldSocket.disconnect();
            }
            connectedUsers.delete(existingSocketId);
        }
        
        // Add new connection
        connectedUsers.set(socket.id, { userId, userType });
        socket.userId = userId;
        socket.userType = userType;
        
        console.log(`User ${userId} (${userType}) authenticated and added to connected users`);
        console.log('Current connected users:', Array.from(connectedUsers.entries()));
        
        // Join user to their personal room
        socket.join(`user_${userType}_${userId}`);
        
        // Send confirmation back to client
        socket.emit('authenticated', { userId, userType });
        
        console.log(`User ${userId} (${userType}) joined room: user_${userType}_${userId}`);
    });

    // Handle joining chat room
    socket.on('join_chat', (data) => {
        const { studentId, tutorId } = data;
        const roomId = `chat_${studentId}_${tutorId}`;
        socket.join(roomId);
        console.log(`User joined chat room: ${roomId}`);
    });

    // Handle sending messages
    socket.on('send_message', (messageData) => {
        const { senderId, senderType, receiverId, receiverType, message, fileData } = messageData;
        
        // Create room ID for the conversation
        const roomId = `chat_${Math.min(senderId, receiverId)}_${Math.max(senderId, receiverId)}`;
        
        // Broadcast message to the chat room
        io.to(roomId).emit('new_message', {
            senderId,
            senderType,
            receiverId,
            receiverType,
            message,
            fileData,
            timestamp: new Date()
        });
        
        console.log(`Message sent in room ${roomId}:`, message);
    });

    // Handle typing indicators
    socket.on('typing_start', (data) => {
        const { senderId, senderType, receiverId, receiverType } = data;
        const roomId = `chat_${Math.min(senderId, receiverId)}_${Math.max(senderId, receiverId)}`;
        
        socket.to(roomId).emit('user_typing', {
            senderId,
            senderType,
            isTyping: true
        });
    });

    socket.on('typing_stop', (data) => {
        const { senderId, senderType, receiverId, receiverType } = data;
        const roomId = `chat_${Math.min(senderId, receiverId)}_${Math.max(senderId, receiverId)}`;
        
        socket.to(roomId).emit('user_typing', {
            senderId,
            senderType,
            isTyping: false
        });
    });

    // Handle read receipts
    socket.on('mark_read', (data) => {
        const { messageId, readerId, readerType } = data;
        
        // Broadcast read receipt
        io.emit('message_read', {
            messageId,
            readerId,
            readerType,
            timestamp: new Date()
        });
    });

    // WebRTC Signaling
    socket.on('call_initiated', (data) => {
        console.log('=== CALL INITIATED EVENT RECEIVED ===');
        console.log('Raw call data:', data);
        
        const { callType, callerId, callerName, callerType, receiverId, receiverType, roomId } = data;
        
        // Validate required fields
        if (!callType) {
            console.error('Missing callType in call data');
            return;
        }
        if (!callerId) {
            console.error('Missing callerId in call data');
            return;
        }
        if (!receiverId) {
            console.error('Missing receiverId in call data');
            return;
        }
        if (!roomId) {
            console.error('Missing roomId in call data');
            return;
        }
        
        // Prevent users from calling themselves in the same role
        if (callerId == receiverId && callerType === receiverType) {
            console.error(`User ${callerId} (${callerType}) cannot call themselves in the same role`);
            return;
        }
        
        // Allow calls between users with same ID but different roles
        if (callerId == receiverId && callerType !== receiverType) {
            console.log(`Allowing call between user ${callerId} as ${callerType} to ${receiverType}`);
        }
        
        console.log(`Call initiated: ${callType} call from ${callerId} (${callerName || 'Unknown'}) to ${receiverId}`);
        console.log('Parsed call data:', { callType, callerId, callerName, receiverId, receiverType, roomId });
        console.log('Connected users:', Array.from(connectedUsers.values()));
        
        // Find the receiver's socket and send them the call notification
        let receiverFound = false;
        
        // First try to find by exact match
        for (const [socketId, userData] of connectedUsers.entries()) {
            console.log(`Checking user: ${userData.userId} (${userData.userType}) against receiver: ${receiverId} (${receiverType})`);
            console.log(`User ID comparison: ${userData.userId} == ${receiverId} = ${userData.userId == receiverId}`);
            console.log(`User type comparison: ${userData.userType} === ${receiverType} = ${userData.userType === receiverType}`);
            
            // Allow calls between users with same ID but different roles
            // Prevent calls to the exact same user in the same role
            if (userData.userId == receiverId && userData.userType === receiverType && 
                !(userData.userId == callerId && userData.userType === callerType)) {
                const callNotification = {
                    roomId,
                    callType,
                    callerId,
                    callerName: callerName || 'Unknown Caller',
                    receiverId,
                    receiverType,
                    timestamp: new Date()
                };
                
                console.log(`Sending incoming call to user ${receiverId} (${receiverType}):`, callNotification);
                io.to(socketId).emit('incoming_call', callNotification);
                receiverFound = true;
                break;
            }
        }
        
        if (!receiverFound) {
            console.log(`Receiver ${receiverId} (${receiverType}) not found in connected users`);
            
            // Try to find by just userId if userType doesn't match
            for (const [socketId, userData] of connectedUsers.entries()) {
                if (userData.userId == receiverId) {
                    console.log(`Found user by ID only: ${userData.userId} (${userData.userType})`);
                    const callNotification = {
                        roomId,
                        callType,
                        callerId,
                        callerName: callerName || 'Unknown Caller',
                        receiverId,
                        receiverType: userData.userType,
                        timestamp: new Date()
                    };
                    
                    console.log(`Sending incoming call to user ${receiverId} (${userData.userType}):`, callNotification);
                    io.to(socketId).emit('incoming_call', callNotification);
                    receiverFound = true;
                    break;
                }
            }
        }
        
        if (!receiverFound) {
            console.log(`Could not find receiver ${receiverId} in any connected users`);
            console.log('Available users:', Array.from(connectedUsers.values()));
            
            // Try to emit to all connected users as a fallback (for debugging)
            console.log('Attempting to emit to all users as fallback...');
            io.emit('incoming_call_debug', {
                roomId,
                callType,
                callerId,
                callerName: callerName || 'Unknown Caller',
                receiverId,
                receiverType,
                timestamp: new Date(),
                debug: 'Receiver not found, broadcasting to all users'
            });
        }
    });



    socket.on('call_answered', (data) => {
        const { roomId, receiverId, receiverType } = data;
        
        // Notify caller that call was answered
        const callerRoom = `user_${receiverType === 'student' ? 'tutor' : 'student'}_${receiverId}`;
        io.to(callerRoom).emit('call_answered', {
            roomId,
            receiverId,
            timestamp: new Date()
        });
        
        console.log(`Call answered in room: ${roomId}`);
    });

    socket.on('call_ended', (data) => {
        const { roomId, endedBy } = data;
        
        // Broadcast call ended to all users in the call
        io.emit('call_ended', {
            roomId,
            endedBy,
            timestamp: new Date()
        });
        
        console.log(`Call ended in room: ${roomId}`);
    });

    socket.on('call_declined', (data) => {
        const { roomId, receiverId, receiverType } = data;
        
        // Find the caller and notify them that the call was declined
        for (const [socketId, userData] of connectedUsers.entries()) {
            if (userData.userId == receiverId && userData.userType === receiverType) {
                io.to(socketId).emit('call_declined', {
                    roomId,
                    receiverId,
                    timestamp: new Date()
                });
                console.log(`Call declined notification sent to user ${receiverId} (${receiverType})`);
                break;
            }
        }
        
        console.log(`Call declined in room: ${roomId}`);
    });

    // WebRTC peer connection signaling
    socket.on('join_call_room', (data) => {
        const { roomId } = data;
        socket.join(`call_${roomId}`);
        console.log(`User joined call room: ${roomId}`);
    });

    socket.on('webrtc_offer', (data) => {
        const { roomId, offer, from } = data;
        socket.to(`call_${roomId}`).emit('webrtc_offer', {
            offer,
            from,
            timestamp: new Date()
        });
    });

    socket.on('webrtc_answer', (data) => {
        const { roomId, answer, from } = data;
        socket.to(`call_${roomId}`).emit('webrtc_answer', {
            answer,
            from,
            timestamp: new Date()
        });
    });

    socket.on('webrtc_ice_candidate', (data) => {
        const { roomId, candidate, from } = data;
        socket.to(`call_${roomId}`).emit('webrtc_ice_candidate', {
            candidate,
            from,
            timestamp: new Date()
        });
    });

    // Handle disconnection
    socket.on('disconnect', () => {
        const userData = connectedUsers.get(socket.id);
        if (userData) {
            console.log(`User ${userData.userId} (${userData.userType}) disconnected`);
            connectedUsers.delete(socket.id);
        }
        console.log('User disconnected:', socket.id);
    });
});

// Health check endpoint
app.get('/health', (req, res) => {
    res.json({ 
        status: 'ok', 
        connectedUsers: connectedUsers.size,
        timestamp: new Date()
    });
});

const PORT = process.env.SOCKET_PORT || 3001;

server.listen(PORT, '0.0.0.0', () => {
    console.log(`Socket.IO server running on port ${PORT}`);
    console.log(`Health check available at http://localhost:${PORT}/health`);
});