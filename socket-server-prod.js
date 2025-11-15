import express from 'express';
import http from 'http';
import { Server } from 'socket.io';
import cors from 'cors';

const app = express();
const server = http.createServer(app);

// Environment variables
const PORT = process.env.SOCKET_PORT || 3001;
const LARAVEL_URL = process.env.LARAVEL_URL || process.env.APP_URL || 'http://localhost:8000';
const NODE_ENV = process.env.NODE_ENV || 'development';

// Configure CORS for production
// Allow multiple origins from environment variable (comma-separated)
const getAllowedOrigins = () => {
    if (NODE_ENV === 'production') {
        const origins = [];
        
        // Add LARAVEL_URL
        if (LARAVEL_URL) {
            origins.push(LARAVEL_URL);
        }
        
        // Add APP_URL if different
        if (process.env.APP_URL && process.env.APP_URL !== LARAVEL_URL) {
            origins.push(process.env.APP_URL);
        }
        
        // Add SOCKET_ALLOWED_ORIGINS if set (comma-separated)
        if (process.env.SOCKET_ALLOWED_ORIGINS) {
            const additionalOrigins = process.env.SOCKET_ALLOWED_ORIGINS.split(',').map(o => o.trim());
            origins.push(...additionalOrigins);
        }
        
        // If no origins configured, allow all (not recommended for production)
        return origins.length > 0 ? origins : true;
    }
    
    // Development: allow localhost variants
    return [
        'http://localhost:8000',
        'http://127.0.0.1:8000',
        'http://localhost:3000',
        'http://127.0.0.1:3000'
    ];
};

const corsOptions = {
    origin: getAllowedOrigins(),
    methods: ["GET", "POST"],
    credentials: true
};

app.use(cors(corsOptions));

// Create Socket.IO server with production settings
const io = new Server(server, {
    cors: corsOptions,
    transports: ['websocket', 'polling'],
    allowEIO3: true,
    pingTimeout: 60000,
    pingInterval: 25000
});

// Store connected users
const connectedUsers = new Map();

// Logging middleware
app.use((req, res, next) => {
    console.log(`${new Date().toISOString()} - ${req.method} ${req.path}`);
    next();
});

io.on('connection', (socket) => {
    console.log(`User connected: ${socket.id}`);

    // Handle user authentication
    socket.on('authenticate', (userData) => {
        try {
            const { userId, userType } = userData;
            
            if (!userId || !userType) {
                console.error('Invalid authentication data:', userData);
                return;
            }

            connectedUsers.set(socket.id, { userId, userType });
            socket.userId = userId;
            socket.userType = userType;
            
            console.log(`User ${userId} (${userType}) authenticated`);
            
            // Join user to their personal room
            socket.join(`user_${userType}_${userId}`);
            
            // Send confirmation
            socket.emit('authenticated', { success: true });
        } catch (error) {
            console.error('Authentication error:', error);
            socket.emit('error', { message: 'Authentication failed' });
        }
    });

    // Handle joining chat room
    socket.on('join_chat', (data) => {
        try {
            const { studentId, tutorId } = data;
            
            if (!studentId || !tutorId) {
                console.error('Invalid chat room data:', data);
                return;
            }

            const roomId = `chat_${studentId}_${tutorId}`;
            socket.join(roomId);
            console.log(`User joined chat room: ${roomId}`);
            
            socket.emit('joined_chat', { roomId });
        } catch (error) {
            console.error('Join chat error:', error);
        }
    });

    // Handle sending messages
    socket.on('send_message', (messageData) => {
        try {
            const { senderId, senderType, receiverId, receiverType, message, fileData } = messageData;
            
            if (!senderId || !senderType || !receiverId || !receiverType) {
                console.error('Invalid message data:', messageData);
                return;
            }

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
        } catch (error) {
            console.error('Send message error:', error);
        }
    });

    // Handle typing indicators
    socket.on('typing_start', (data) => {
        try {
            const { senderId, senderType, receiverId, receiverType } = data;
            const roomId = `chat_${Math.min(senderId, receiverId)}_${Math.max(senderId, receiverId)}`;
            
            socket.to(roomId).emit('user_typing', {
                senderId,
                senderType,
                isTyping: true
            });
        } catch (error) {
            console.error('Typing start error:', error);
        }
    });

    socket.on('typing_stop', (data) => {
        try {
            const { senderId, senderType, receiverId, receiverType } = data;
            const roomId = `chat_${Math.min(senderId, receiverId)}_${Math.max(senderId, receiverId)}`;
            
            socket.to(roomId).emit('user_typing', {
                senderId,
                senderType,
                isTyping: false
            });
        } catch (error) {
            console.error('Typing stop error:', error);
        }
    });

    // Handle read receipts
    socket.on('mark_read', (data) => {
        try {
            const { messageId, readerId, readerType } = data;
            
            // Broadcast read receipt
            io.emit('message_read', {
                messageId,
                readerId,
                readerType,
                timestamp: new Date()
            });
        } catch (error) {
            console.error('Mark read error:', error);
        }
    });

    // Handle call initiation
    socket.on('call_initiated', (data) => {
        try {
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
        } catch (error) {
            console.error('Call initiated error:', error);
        }
    });

    // Handle call answered
    socket.on('call_answered', (data) => {
        try {
            const { roomId, receiverId, receiverType } = data;
            
            // Notify caller that call was answered
            const callerRoom = `user_${receiverType === 'student' ? 'tutor' : 'student'}_${receiverId}`;
            io.to(callerRoom).emit('call_answered', {
                roomId,
                receiverId,
                receiverType
            });
            
            console.log(`Call answered in room ${roomId} by ${receiverId} (${receiverType})`);
        } catch (error) {
            console.error('Call answered error:', error);
        }
    });

    // Handle call declined
    socket.on('call_declined', (data) => {
        try {
            const { roomId, receiverId, receiverType } = data;
            
            // Notify caller that call was declined
            const callerRoom = `user_${receiverType === 'student' ? 'tutor' : 'student'}_${receiverId}`;
            io.to(callerRoom).emit('call_declined', {
                roomId,
                receiverId,
                receiverType
            });
            
            console.log(`Call declined in room ${roomId} by ${receiverId} (${receiverType})`);
        } catch (error) {
            console.error('Call declined error:', error);
        }
    });

    // Handle call ended
    socket.on('call_ended', (data) => {
        try {
            const { roomId, endedBy } = data;
            
            // Broadcast call ended to all users in the room
            io.to(roomId).emit('call_ended', {
                roomId,
                endedBy
            });
            
            console.log(`Call ended in room ${roomId} by ${endedBy}`);
        } catch (error) {
            console.error('Call ended error:', error);
        }
    });

    // WebRTC Signaling - Join call room
    socket.on('join_call_room', (data) => {
        try {
            const { roomId } = data;
            socket.join(roomId);
            console.log(`User joined call room: ${roomId}`);
        } catch (error) {
            console.error('Join call room error:', error);
        }
    });

    // WebRTC Signaling - Offer
    socket.on('webrtc_offer', (data) => {
        try {
            const { roomId, offer, from } = data;
            socket.to(roomId).emit('webrtc_offer', {
                roomId,
                offer,
                from
            });
            console.log(`WebRTC offer sent in room ${roomId}`);
        } catch (error) {
            console.error('WebRTC offer error:', error);
        }
    });

    // WebRTC Signaling - Answer
    socket.on('webrtc_answer', (data) => {
        try {
            const { roomId, answer, from } = data;
            socket.to(roomId).emit('webrtc_answer', {
                roomId,
                answer,
                from
            });
            console.log(`WebRTC answer sent in room ${roomId}`);
        } catch (error) {
            console.error('WebRTC answer error:', error);
        }
    });

    // WebRTC Signaling - ICE Candidate
    socket.on('webrtc_ice_candidate', (data) => {
        try {
            const { roomId, candidate, from } = data;
            socket.to(roomId).emit('webrtc_ice_candidate', {
                roomId,
                candidate,
                from
            });
            console.log(`WebRTC ICE candidate sent in room ${roomId}`);
        } catch (error) {
            console.error('WebRTC ICE candidate error:', error);
        }
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

    // Handle errors
    socket.on('error', (error) => {
        console.error('Socket error:', error);
    });
});

// Health check endpoint
app.get('/health', (req, res) => {
    res.json({ 
        status: 'ok', 
        connectedUsers: connectedUsers.size,
        timestamp: new Date(),
        environment: NODE_ENV,
        version: '1.0.0'
    });
});

// Status endpoint
app.get('/status', (req, res) => {
    res.json({
        server: 'running',
        port: PORT,
        environment: NODE_ENV,
        uptime: process.uptime(),
        memory: process.memoryUsage(),
        connectedUsers: connectedUsers.size
    });
});

// Error handling
app.use((err, req, res, next) => {
    console.error('Express error:', err);
    res.status(500).json({ error: 'Internal server error' });
});

server.listen(PORT, () => {
    console.log(`🚀 Socket.IO server running on port ${PORT}`);
    console.log(`🌍 Environment: ${NODE_ENV}`);
    console.log(`🔗 Health check: http://localhost:${PORT}/health`);
    console.log(`📊 Status: http://localhost:${PORT}/status`);
});

// Graceful shutdown
process.on('SIGTERM', () => {
    console.log('SIGTERM received, shutting down gracefully');
    server.close(() => {
        console.log('Process terminated');
        process.exit(0);
    });
});

process.on('SIGINT', () => {
    console.log('SIGINT received, shutting down gracefully');
    server.close(() => {
        console.log('Process terminated');
        process.exit(0);
    });
}); 