// Socket.IO Client for Laravel Application
class ChatSocket {
    constructor() {
        this.socket = null;
        this.isConnected = false;
        this.userId = null;
        this.userType = null;
        this.currentChatRoom = null;
        this.callHandlers = new Map();
    }

    // Initialize socket connection
    connect() {
        try {
            // Get the current host and protocol
            const currentHost = window.location.hostname;
            const protocol = window.location.protocol === 'https:' ? 'https:' : 'http:';
            const port = window.location.port ? `:${window.location.port}` : '';
            
            // For production, use the same domain and protocol as the main app
            // Socket server should be proxied through the same domain or use a subdomain
            // If using a proxy, use the same origin; otherwise use port 3001
            let socketServerUrl;
            
            // Check if we have a custom socket URL from Laravel config
            if (window.socketServerUrl) {
                socketServerUrl = window.socketServerUrl;
            } else if (window.location.port === '443' || window.location.port === '' && protocol === 'https:') {
                // HTTPS production - socket should be on same domain (proxied) or use wss
                socketServerUrl = `${protocol}//${currentHost}${port}/socket.io`;
            } else {
                // Development or HTTP - use port 3001
                socketServerUrl = `${protocol}//${currentHost}:3001`;
            }
            
            console.log('Connecting to socket server:', socketServerUrl);
            
            this.socket = io(socketServerUrl, {
                transports: ['websocket', 'polling'],
                timeout: 20000,
                reconnection: true,
                reconnectionDelay: 1000,
                reconnectionAttempts: 5
            });

            this.socket.on('connect', () => {
                console.log('Connected to socket server');
                this.isConnected = true;
                this.authenticateUser();
            });

            this.socket.on('authenticated', (data) => {
                console.log('=== AUTHENTICATION CONFIRMED BY SERVER ===');
                console.log('Server response:', data);
                console.log('Local user ID:', this.userId);
                console.log('Local user type:', this.userType);
                console.log('Authentication successful!');
            });

            this.socket.on('disconnect', () => {
                console.log('Disconnected from socket server');
                this.isConnected = false;
            });

            this.socket.on('connect_error', (error) => {
                console.error('Socket connection error:', error);
                this.isConnected = false;
            });

            // Listen for new messages
            this.socket.on('new_message', (data) => {
                this.handleNewMessage(data);
            });

            // Listen for typing indicators
            this.socket.on('user_typing', (data) => {
                this.handleTypingIndicator(data);
            });

            // Listen for read receipts
            this.socket.on('message_read', (data) => {
                this.handleReadReceipt(data);
            });

            // Listen for incoming calls
            this.socket.on('incoming_call', (data) => {
                this.handleIncomingCall(data);
            });

            // Listen for incoming call debug (fallback broadcast)
            this.socket.on('incoming_call_debug', (data) => {
                console.log('=== INCOMING CALL DEBUG RECEIVED ===');
                console.log('Debug call data:', data);
                console.log('Current user ID:', this.userId);
                console.log('Current user type:', this.userType);
                
                // Dispatch as a DOM event for the CallManager to listen to
                const event = new CustomEvent('socket:incoming-call-debug', {
                    detail: data
                });
                document.dispatchEvent(event);
                
                // Only handle if this is actually for us
                if (data.receiverId == this.userId && data.receiverType == this.userType) {
                    console.log('This debug call is for us, handling as incoming call');
                    this.handleIncomingCall(data);
                } else {
                    console.log('This debug call is not for us, ignoring');
                }
            });

            // Listen for call answered
            this.socket.on('call_answered', (data) => {
                this.handleCallAnswered(data);
            });

            // Listen for call ended
            this.socket.on('call_ended', (data) => {
                this.handleCallEnded(data);
            });

            // Listen for call declined
            this.socket.on('call_declined', (data) => {
                this.handleCallDeclined(data);
            });

            // WebRTC signaling events
            this.socket.on('webrtc_offer', (data) => {
                this.handleWebRTCOffer(data);
            });

            this.socket.on('webrtc_answer', (data) => {
                this.handleWebRTCAnswer(data);
            });

            this.socket.on('webrtc_ice_candidate', (data) => {
                this.handleWebRTCIceCandidate(data);
            });

        } catch (error) {
            console.error('Failed to initialize socket:', error);
        }
    }

    // Authenticate user with socket server
    authenticateUser() {
        // Get user data from page (you'll need to set these in your Laravel views)
        const userId = window.currentUserId;
        const userType = window.currentUserType;

        console.log('=== AUTHENTICATING USER WITH SOCKET SERVER ===');
        console.log('User ID from window:', userId);
        console.log('User type from window:', userType);
        console.log('Socket connected:', this.isConnected);
        console.log('Socket instance:', this.socket);

        if (userId && userType) {
            this.userId = userId;
            this.userType = userType;
            
            console.log('Sending authentication to socket server...');
            this.socket.emit('authenticate', {
                userId: userId,
                userType: userType
            });
            console.log('Authentication sent to socket server');
        } else {
            console.error('Missing user data for authentication:', { userId, userType });
        }
    }

    // Join a chat room
    joinChat(studentId, tutorId) {
        if (!this.isConnected) return;

        this.currentChatRoom = { studentId, tutorId };
        
        this.socket.emit('join_chat', {
            studentId: studentId,
            tutorId: tutorId
        });
    }

    // Send a message
    sendMessage(receiverId, receiverType, message, fileData = null) {
        if (!this.isConnected || !this.userId) return;

        const messageData = {
            senderId: this.userId,
            senderType: this.userType,
            receiverId: receiverId,
            receiverType: receiverType,
            message: message,
            fileData: fileData
        };

        this.socket.emit('send_message', messageData);
    }

    // Start typing indicator
    startTyping(receiverId, receiverType) {
        if (!this.isConnected) return;

        this.socket.emit('typing_start', {
            senderId: this.userId,
            senderType: this.userType,
            receiverId: receiverId,
            receiverType: receiverType
        });
    }

    // Stop typing indicator
    stopTyping(receiverId, receiverType) {
        if (!this.isConnected) return;

        this.socket.emit('typing_stop', {
            senderId: this.userId,
            senderType: this.userType,
            receiverId: receiverId,
            receiverType: receiverType
        });
    }

    // Mark message as read
    markMessageAsRead(messageId) {
        if (!this.isConnected) return;

        this.socket.emit('mark_read', {
            messageId: messageId,
            readerId: this.userId,
            readerType: this.userType
        });
    }

    // Handle new message received
    handleNewMessage(data) {
        // Dispatch custom event that Livewire can listen to
        const event = new CustomEvent('socket:new-message', {
            detail: data
        });
        document.dispatchEvent(event);
    }

    // Handle typing indicator
    handleTypingIndicator(data) {
        const event = new CustomEvent('socket:typing', {
            detail: data
        });
        document.dispatchEvent(event);
    }

    // Handle read receipt
    handleReadReceipt(data) {
        const event = new CustomEvent('socket:message-read', {
            detail: data
        });
        document.dispatchEvent(event);
    }

    // Handle incoming call
    handleIncomingCall(data) {
        console.log('Incoming call received:', data);
        
        // Dispatch custom event that Livewire can listen to
        const event = new CustomEvent('socket:incoming-call', {
            detail: data
        });
        document.dispatchEvent(event);
        
        // Also dispatch a global event for the CallManager component
        const globalEvent = new CustomEvent('socket:incoming-call-global', {
            detail: data
        });
        document.dispatchEvent(globalEvent);
        
        console.log('Incoming call events dispatched');
    }

    // Handle call answered
    handleCallAnswered(data) {
        console.log('Call answered:', data);
        const event = new CustomEvent('socket:call-answered', {
            detail: data
        });
        document.dispatchEvent(event);
    }

    // Handle call ended
    handleCallEnded(data) {
        console.log('Call ended:', data);
        const event = new CustomEvent('socket:call-ended', {
            detail: data
        });
        document.dispatchEvent(event);
    }

    // Handle call declined
    handleCallDeclined(data) {
        console.log('Call declined:', data);
        const event = new CustomEvent('socket:call-declined', {
            detail: data
        });
        document.dispatchEvent(event);
    }

    // Handle WebRTC offer
    handleWebRTCOffer(data) {
        console.log('WebRTC offer received:', data);
        const event = new CustomEvent('socket:webrtc-offer', {
            detail: data
        });
        document.dispatchEvent(event);
    }

    // Handle WebRTC answer
    handleWebRTCAnswer(data) {
        console.log('WebRTC answer received:', data);
        const event = new CustomEvent('socket:webrtc-answer', {
            detail: data
        });
        document.dispatchEvent(event);
    }

    // Handle WebRTC ICE candidate
    handleWebRTCIceCandidate(data) {
        console.log('WebRTC ICE candidate received:', data);
        const event = new CustomEvent('socket:webrtc-ice-candidate', {
            detail: data
        });
        document.dispatchEvent(event);
    }

    // Initiate a call
    initiateCall(callType, receiverId, receiverType, roomId) {
        if (!this.isConnected || !this.userId) return;

        const callData = {
            callType,
            callerId: this.userId,
            callerName: this.getCurrentUserName(),
            receiverId,
            receiverType,
            roomId
        };

        console.log('Initiating call:', callData);
        this.socket.emit('call_initiated', callData);
    }

    // Answer a call
    answerCall(roomId, receiverId, receiverType) {
        if (!this.isConnected) return;

        const callData = {
            roomId,
            receiverId,
            receiverType
        };

        console.log('Answering call:', callData);
        this.socket.emit('call_answered', callData);
    }

    // End a call
    endCall(roomId, endedBy) {
        if (!this.isConnected) return;

        const callData = {
            roomId,
            endedBy
        };

        console.log('Ending call:', callData);
        this.socket.emit('call_ended', callData);
    }

    // Join call room for WebRTC signaling
    joinCallRoom(roomId) {
        if (!this.isConnected) return;

        this.socket.emit('join_call_room', { roomId });
    }

    // Send WebRTC offer
    sendWebRTCOffer(roomId, offer, from) {
        if (!this.isConnected) return;

        this.socket.emit('webrtc_offer', { roomId, offer, from });
    }

    // Send WebRTC answer
    sendWebRTCAnswer(roomId, answer, from) {
        if (!this.isConnected) return;

        this.socket.emit('webrtc_answer', { roomId, answer, from });
    }

    // Send WebRTC ICE candidate
    sendWebRTCIceCandidate(roomId, candidate, from) {
        if (!this.isConnected) return;

        this.socket.emit('webrtc_ice_candidate', { roomId, candidate, from });
    }

    // Get current user name (you can customize this)
    getCurrentUserName() {
        // Try to get from page elements or set a default
        const nameElement = document.querySelector('.profile-icon');
        if (nameElement && nameElement.textContent) {
            return nameElement.textContent.trim();
        }
        return 'User';
    }

    // Disconnect socket
    disconnect() {
        if (this.socket) {
            this.socket.disconnect();
            this.isConnected = false;
        }
    }
}

    // Initialize socket when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, checking for messaging container or CallManager...');
        
        // Initialize if we're on a chat page (has messaging container) or has CallManager component
        if (document.querySelector('.messaging-container') || document.querySelector('[wire\\:id*="call-manager"]')) {
            console.log('Chat page or CallManager found, initializing socket...');
            window.chatSocket = new ChatSocket();
            window.chatSocket.connect();
        } else {
            console.log('Neither messaging container nor CallManager found, socket not initialized');
        }
    });

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ChatSocket;
} 