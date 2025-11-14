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