// Pusher Client for Laravel Application (Replaces Socket.IO)
class ChatSocket {
    constructor() {
        this.pusher = null;
        this.isConnected = false;
        this.userId = null;
        this.userType = null;
        this.currentChatRoom = null;
        this.callHandlers = new Map();
        this.channels = new Map();
        this.apiBaseUrl = window.location.origin;
    }

    // Initialize Pusher connection
    connect() {
        try {
            const pusherKey = window.pusherKey;
            const pusherCluster = window.pusherCluster || 'mt1';

            if (!pusherKey) {
                console.error('Pusher key not found. Please configure PUSHER_APP_KEY in .env');
                this.showConnectionError(new Error('Pusher not configured'));
                return;
            }

            console.log('Initializing Pusher connection...', { key: pusherKey, cluster: pusherCluster });

            this.pusher = new Pusher(pusherKey, {
                cluster: pusherCluster,
                encrypted: true,
                forceTLS: true,
                enabledTransports: ['ws', 'wss']
            });

            // Connection state handlers
            this.pusher.connection.bind('connected', () => {
                console.log('✅ Connected to Pusher');
                this.isConnected = true;
                this.authenticateUser();
                this.hideConnectionError();
            });

            this.pusher.connection.bind('disconnected', () => {
                console.log('⚠️ Disconnected from Pusher');
                this.isConnected = false;
            });

            this.pusher.connection.bind('error', (error) => {
                console.error('❌ Pusher connection error:', error);
                this.isConnected = false;
                this.showConnectionError(error);
            });

            this.pusher.connection.bind('state_change', (states) => {
                console.log('Pusher state changed:', states);
            });

        } catch (error) {
            console.error('Failed to initialize Pusher:', error);
            this.showConnectionError(error);
        }
    }

    // Authenticate user and subscribe to personal channel
    authenticateUser() {
        const userId = window.currentUserId;
        const userType = window.currentUserType;

        console.log('=== AUTHENTICATING USER ===');
        console.log('User ID:', userId);
        console.log('User type:', userType);

        if (userId && userType) {
            this.userId = userId;
            this.userType = userType;

            // Subscribe to user's personal channel for notifications
            const personalChannel = `private-user-${userType}-${userId}`;
            this.subscribeToChannel(personalChannel, (channel) => {
                // Listen for incoming calls
                channel.bind('incoming-call', (data) => {
                    console.log('Incoming call received:', data);
                    this.handleIncomingCall(data);
                });

                channel.bind('incoming-call-debug', (data) => {
                    console.log('=== INCOMING CALL DEBUG RECEIVED ===');
                    if (data.receiverId == this.userId && data.receiverType == this.userType) {
                        this.handleIncomingCall(data);
                    }
                });

                channel.bind('call-answered', (data) => {
                    this.handleCallAnswered(data);
                });

                channel.bind('call-ended', (data) => {
                    this.handleCallEnded(data);
                });

                channel.bind('call-declined', (data) => {
                    this.handleCallDeclined(data);
                });
            });

            console.log('✅ User authenticated and subscribed to personal channel');
        } else {
            console.error('Missing user data for authentication');
        }
    }

    // Subscribe to a Pusher channel
    subscribeToChannel(channelName, callback) {
        if (!this.pusher) {
            console.error('Pusher not initialized');
            return null;
        }

        if (this.channels.has(channelName)) {
            return this.channels.get(channelName);
        }

        const channel = this.pusher.subscribe(channelName);
        this.channels.set(channelName, channel);

        channel.bind('pusher:subscription_succeeded', () => {
            console.log('✅ Subscribed to channel:', channelName);
            if (callback) callback(channel);
        });

        channel.bind('pusher:subscription_error', (error) => {
            console.error('❌ Subscription error for channel:', channelName, error);
        });

        return channel;
    }

    // Join a chat room
    joinChat(studentId, tutorId) {
        if (!this.isConnected) {
            console.warn('Not connected to Pusher');
            return;
        }

        this.currentChatRoom = { studentId, tutorId };
        const roomId = `chat-${Math.min(studentId, tutorId)}-${Math.max(studentId, tutorId)}`;
        const channelName = `private-${roomId}`;

        this.subscribeToChannel(channelName, (channel) => {
            // Listen for new messages
            channel.bind('new-message', (data) => {
                this.handleNewMessage(data);
            });

            // Listen for typing indicators
            channel.bind('user-typing', (data) => {
                this.handleTypingIndicator(data);
            });

            // Listen for read receipts
            channel.bind('message-read', (data) => {
                this.handleReadReceipt(data);
            });
        });
    }

    // Send a message via Laravel API (which will broadcast via Pusher)
    async sendMessage(receiverId, receiverType, message, fileData = null) {
        if (!this.userId) return;

        try {
            const response = await fetch(`${this.apiBaseUrl}/api/messages/send`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    senderId: this.userId,
                    senderType: this.userType,
                    receiverId: receiverId,
                    receiverType: receiverType,
                    message: message,
                    fileData: fileData
                })
            });

            const data = await response.json();
            if (data.success) {
                console.log('Message sent successfully');
            } else {
                console.error('Failed to send message:', data.error);
            }
        } catch (error) {
            console.error('Error sending message:', error);
        }
    }

    // Start typing indicator
    async startTyping(receiverId, receiverType) {
        await this.sendTypingEvent(receiverId, receiverType, true);
    }

    // Stop typing indicator
    async stopTyping(receiverId, receiverType) {
        await this.sendTypingEvent(receiverId, receiverType, false);
    }

    // Send typing event
    async sendTypingEvent(receiverId, receiverType, isTyping) {
        try {
            await fetch(`${this.apiBaseUrl}/api/messages/typing`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    senderId: this.userId,
                    senderType: this.userType,
                    receiverId: receiverId,
                    receiverType: receiverType,
                    isTyping: isTyping
                })
            });
        } catch (error) {
            console.error('Error sending typing event:', error);
        }
    }

    // Mark message as read
    async markMessageAsRead(messageId) {
        try {
            await fetch(`${this.apiBaseUrl}/api/messages/mark-read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    messageId: messageId,
                    readerId: this.userId,
                    readerType: this.userType
                })
            });
        } catch (error) {
            console.error('Error marking message as read:', error);
        }
    }

    // Handle new message received
    handleNewMessage(data) {
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
        
        const event = new CustomEvent('socket:incoming-call', {
            detail: data
        });
        document.dispatchEvent(event);
        
        const globalEvent = new CustomEvent('socket:incoming-call-global', {
            detail: data
        });
        document.dispatchEvent(globalEvent);
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
    async initiateCall(callType, receiverId, receiverType, roomId) {
        if (!this.userId) return;

        try {
            await fetch(`${this.apiBaseUrl}/api/calls/initiate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    callType,
                    callerId: this.userId,
                    callerName: this.getCurrentUserName(),
                    receiverId,
                    receiverType,
                    roomId
                })
            });
        } catch (error) {
            console.error('Error initiating call:', error);
        }
    }

    // Answer a call
    async answerCall(roomId, receiverId, receiverType) {
        try {
            await fetch(`${this.apiBaseUrl}/api/calls/answer`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    roomId,
                    receiverId,
                    receiverType
                })
            });
        } catch (error) {
            console.error('Error answering call:', error);
        }
    }

    // End a call
    async endCall(roomId, endedBy) {
        try {
            await fetch(`${this.apiBaseUrl}/api/calls/end`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    roomId,
                    endedBy
                })
            });
        } catch (error) {
            console.error('Error ending call:', error);
        }
    }

    // Join call room for WebRTC signaling
    joinCallRoom(roomId) {
        if (!this.isConnected) return;

        const channelName = `private-call-${roomId}`;
        this.subscribeToChannel(channelName, (channel) => {
            // Listen for WebRTC signaling events
            channel.bind('webrtc-offer', (data) => {
                this.handleWebRTCOffer(data);
            });

            channel.bind('webrtc-answer', (data) => {
                this.handleWebRTCAnswer(data);
            });

            channel.bind('webrtc-ice-candidate', (data) => {
                this.handleWebRTCIceCandidate(data);
            });
        });
    }

    // Send WebRTC offer
    async sendWebRTCOffer(roomId, offer, from) {
        try {
            await fetch(`${this.apiBaseUrl}/api/webrtc/offer`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    roomId,
                    offer,
                    from
                })
            });
        } catch (error) {
            console.error('Error sending WebRTC offer:', error);
        }
    }

    // Send WebRTC answer
    async sendWebRTCAnswer(roomId, answer, from) {
        try {
            await fetch(`${this.apiBaseUrl}/api/webrtc/answer`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    roomId,
                    answer,
                    from
                })
            });
        } catch (error) {
            console.error('Error sending WebRTC answer:', error);
        }
    }

    // Send WebRTC ICE candidate
    async sendWebRTCIceCandidate(roomId, candidate, from) {
        try {
            await fetch(`${this.apiBaseUrl}/api/webrtc/ice-candidate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    roomId,
                    candidate,
                    from
                })
            });
        } catch (error) {
            console.error('Error sending WebRTC ICE candidate:', error);
        }
    }

    // Get current user name
    getCurrentUserName() {
        const nameElement = document.querySelector('.profile-icon');
        if (nameElement && nameElement.textContent) {
            return nameElement.textContent.trim();
        }
        return 'User';
    }

    // Show connection error to user
    showConnectionError(error) {
        if (document.querySelector('.messaging-container') || document.querySelector('[wire\\:id*="call-manager"]')) {
            let errorDiv = document.getElementById('socket-connection-error');
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.id = 'socket-connection-error';
                errorDiv.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: #ff4757;
                    color: white;
                    padding: 15px 20px;
                    border-radius: 8px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
                    z-index: 10000;
                    max-width: 400px;
                    font-size: 14px;
                `;
                document.body.appendChild(errorDiv);
            }
            
            errorDiv.innerHTML = `
                <strong>Connection Error</strong><br>
                Unable to connect to server. Calls and real-time messaging may not work.<br>
                <small>Error: ${error.message || 'websocket error'}</small>
            `;
        }
    }

    // Hide connection error
    hideConnectionError() {
        const errorDiv = document.getElementById('socket-connection-error');
        if (errorDiv) {
            errorDiv.style.opacity = '0';
            errorDiv.style.transition = 'opacity 0.5s';
            setTimeout(() => {
                if (errorDiv && errorDiv.parentNode) {
                    errorDiv.parentNode.removeChild(errorDiv);
                }
            }, 500);
        }
    }

    // Disconnect Pusher
    disconnect() {
        if (this.pusher) {
            this.pusher.disconnect();
            this.isConnected = false;
            this.channels.clear();
        }
    }

    // Compatibility: Expose socket property for existing code
    get socket() {
        return {
            emit: (event, data) => {
                console.warn('socket.emit() called. Use API methods instead:', event, data);
                // Route to appropriate API method
                if (event === 'send_message') {
                    this.sendMessage(data.receiverId, data.receiverType, data.message, data.fileData);
                } else if (event === 'join_chat') {
                    this.joinChat(data.studentId, data.tutorId);
                } else if (event === 'webrtc_offer') {
                    this.sendWebRTCOffer(data.roomId, data.offer, data.from);
                } else if (event === 'webrtc_answer') {
                    this.sendWebRTCAnswer(data.roomId, data.answer, data.from);
                } else if (event === 'webrtc_ice_candidate') {
                    this.sendWebRTCIceCandidate(data.roomId, data.candidate, data.from);
                } else if (event === 'join_call_room') {
                    this.joinCallRoom(data.roomId);
                }
            },
            on: () => {}, // Events are handled via Pusher channels
            disconnect: () => this.disconnect()
        };
    }
}

// Initialize socket when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, checking for messaging container or CallManager...');
    
    if (document.querySelector('.messaging-container') || document.querySelector('[wire\\:id*="call-manager"]')) {
        console.log('Chat page or CallManager found, initializing Pusher...');
        window.chatSocket = new ChatSocket();
        window.chatSocket.connect();
    } else {
        console.log('Neither messaging container nor CallManager found, Pusher not initialized');
    }
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ChatSocket;
}
