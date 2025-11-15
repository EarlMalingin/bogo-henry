<div>
    <!-- Call Interface -->
    @if($isInCall)
        <div class="call-overlay" id="call-overlay">
            <div class="call-container">
                <!-- Call Header -->
                <div class="call-header">
                    <h3>{{ $callType === 'video' ? 'Video Call' : 'Voice Call' }}</h3>
                    <p>
                        @if($isCaller)
                            Calling {{ $receiverName }}
                        @else
                            {{ $callerName }} is calling you
                        @endif
                    </p>
                </div>

                <!-- End Call Notification -->
                <div class="end-call-notification" id="endCallNotification" style="display: none;">
                    <div class="notification-content">
                        <div class="notification-icon">📞</div>
                        <div class="notification-text">
                            <h3>Call Ended</h3>
                            <p id="endCallMessage">The call has been ended.</p>
                        </div>
                    </div>
                </div>

                <!-- Video/Audio Elements -->
                <div class="media-container">
                    @if($callType === 'video')
                        <div class="video-grid">
                            <div class="local-video-container">
                                <video id="localVideo" autoplay muted playsinline style="display: none;"></video>
                                <div class="profile-placeholder" id="localProfilePlaceholder" style="display: flex;">
                                    @if($callerProfilePicture)
                                        <img src="{{ asset('storage/' . $callerProfilePicture) }}" alt="You" class="profile-image" 
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                             onload="this.style.display='block'; this.nextElementSibling.style.display='none';"
                                             style="display: block;">
                                        <div class="profile-initials" style="display: none;">{{ substr($callerName ?? 'You', 0, 2) }}</div>
                                    @else
                                        <div class="profile-initials">{{ substr($callerName ?? 'You', 0, 2) }}</div>
                                    @endif
                                </div>
                                <div class="video-label">You</div>
                            </div>
                            <div class="remote-video-container">
                                <video id="remoteVideo" autoplay playsinline style="display: none;"></video>
                                <div class="profile-placeholder" id="remoteProfilePlaceholder" style="display: flex;">
                                    @if($isCaller)
                                        @if($receiverProfilePicture)
                                            <img src="{{ asset('storage/' . $receiverProfilePicture) }}" alt="{{ $receiverName }}" class="profile-image" 
                                                 onerror=" this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                                 onload=" this.style.display='block'; this.nextElementSibling.style.display='none';"
                                                 style="display: block;">
                                            <div class="profile-initials" style="display: none;">{{ substr($receiverName ?? 'RU', 0, 2) }}</div>
                                        @else
                                            <div class="profile-initials">{{ substr($receiverName ?? 'RU', 0, 2) }}</div>
                                        @endif
                                    @else
                                        @if($callerProfilePicture)
                                            <img src="{{ asset('storage/' . $callerProfilePicture) }}" alt="{{ $callerName }}" class="profile-image" 
                                                 onerror=" this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                                 onload=" this.style.display='block'; this.nextElementSibling.style.display='none';"
                                                 style="display: block;">
                                            <div class="profile-initials" style="display: none;">{{ substr($callerName ?? 'CU', 0, 2) }}</div>
                                        @else
                                            <div class="profile-initials">{{ substr($callerName ?? 'CU', 0, 2) }}</div>
                                        @endif
                                    @endif
                                </div>
                                <div class="video-label">
                                    @if($isCaller)
                                        {{ $receiverName }}
                                    @else
                                        {{ $callerName }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="audio-call-interface">
                            <div class="caller-avatar">
                                @if($isCaller)
                                    <div class="avatar-circle">{{ substr($receiverName, 0, 1) }}</div>
                                @else
                                    <div class="avatar-circle">{{ substr($callerName, 0, 1) }}</div>
                                @endif
                            </div>
                            <div class="call-status">
                                <div class="pulse-ring"></div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Call Controls -->
                <div class="call-controls">
                    <button class="control-btn mute-btn" id="muteBtn" title="Mute">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3z"/>
                            <path d="M17 11c0 2.76-2.24 5-5 5s-5-2.24-5-5H5c0 3.53 2.61 6.43 6 6.92V21h2v-3.08c3.39-.49 6-3.39 6-6.92h-2z"/>
                        </svg>
                    </button>
                    
                    @if($callType === 'video')
                        <button class="control-btn camera-btn" id="cameraBtn" title="Toggle Camera">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/>
                            </svg>
                        </button>
                    @endif
                    
                    <button class="control-btn screen-share-btn" id="screenShareBtn" title="Share Screen">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M20 18c1.1 0 1.99-.9 1.99-2L22 5c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v11c0 1.1.9 2 2 2H0c0 1.1.9 2 2 2h20c1.1 0 2-.9 2-2h-4zM4 5h16v11H4V5zm8 14c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1z"/>
                        </svg>
                    </button>
                    
                    <button class="control-btn end-call-btn" wire:click="endCall" title="End Call">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 9c-1.6 0-3.15.25-4.6.72v3.1c0 .39-.23.74-.56.9-.98.49-1.87 1.12-2.66 1.85-.18.18-.43.28-.7.28-.28 0-.53-.11-.71-.29L.29 13.08c-.18-.17-.29-.42-.29-.7 0-.28.11-.53.29-.71C3.34 8.78 7.46 7 12 7s8.66 1.78 11.71 4.67c.18.18.29.43.29.71 0 .28-.11.53-.29.71l-2.48 2.48c-.18.18-.43.29-.71.29-.27 0-.52-.11-.7-.28-.79-.74-1.69-1.36-2.67-1.85-.33-.16-.56-.5-.56-.9v-3.1C15.15 9.25 13.6 9 12 9z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Incoming Call Modal -->
    <div class="incoming-call-modal" id="incomingCallModal" style="display: none;" wire:ignore>
        <div class="modal-content">
            <div class="caller-info">
                <div class="caller-avatar">
                    <div class="avatar-circle" id="incomingCallerAvatar"></div>
                </div>
                <h3 id="incomingCallerName"></h3>
                <p id="incomingCallType"></p>
            </div>
            <div class="incoming-call-controls">
                <button class="answer-btn" id="answerCallBtn">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                    </svg>
                    Answer
                </button>
                <button class="decline-btn" id="declineCallBtn">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 9c-1.6 0-3.15.25-4.6.72v3.1c0 .39-.23.74-.56.9-.98.49-1.87 1.12-2.66 1.85-.18.18-.43.28-.7.28-.28 0-.53-.11-.71-.29L.29 13.08c-.18-.17-.29-.42-.29-.7 0-.28.11-.53.29-.71C3.34 8.78 7.46 7 12 7s8.66 1.78 11.71 4.67c.18.18.29.43.29.71 0 .28-.11.53-.29.71l-2.48 2.48c-.18.18-.43.29-.71.29-.27 0-.52-.11-.7-.28-.79-.74-1.69-1.36-2.67-1.85-.33-.16-.56-.5-.56-.9v-3.1C15.15 9.25 13.6 9 12 9z"/>
                    </svg>
                    Decline
                </button>
            </div>
        </div>
    </div>

    <style>
        .call-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .call-container {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            max-width: 800px;
            width: 90%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .call-header h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .call-header p {
            color: #666;
            margin-bottom: 2rem;
        }

        .video-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .local-video-container,
        .remote-video-container {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            background: #f0f0f0;
        }

        .local-video-container {
            grid-column: 2;
            grid-row: 1;
        }

        .remote-video-container {
            grid-column: 1;
            grid-row: 1;
        }

        video {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 15px;
            background: #000;
        }
        
        video:not([srcObject]) {
            background: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
        }
        
        video:not([srcObject])::before {
            content: "No video stream";
        }

        .profile-placeholder {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 200px;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            z-index: 10;
            border-radius: 50%;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        }

        .profile-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .profile-initials {
            font-size: 4rem;
            font-weight: bold;
            color: white;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .video-label {
            position: absolute;
            bottom: 10px;
            left: 10px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
        }

        .audio-call-interface {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 2rem;
        }

        .caller-avatar {
            margin-bottom: 1rem;
        }

        .avatar-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: bold;
        }

        .call-status {
            position: relative;
        }

        .pulse-ring {
            width: 60px;
            height: 60px;
            border: 3px solid #4ecdc4;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(78, 205, 196, 0.7);
            }
            70% {
                transform: scale(1);
                box-shadow: 0 0 0 10px rgba(78, 205, 196, 0);
            }
            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(78, 205, 196, 0);
            }
        }

        .call-controls {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }

        .control-btn {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .mute-btn {
            background: #6c757d;
        }

        .mute-btn:hover {
            background: #5a6268;
            transform: scale(1.1);
        }

        .camera-btn {
            background: #17a2b8;
        }

        .camera-btn:hover {
            background: #138496;
            transform: scale(1.1);
        }

        .end-call-btn {
            background: #dc3545;
        }

        .end-call-btn:hover {
            background: #c82333;
            transform: scale(1.1);
        }

        .screen-share-btn {
            background: #28a745;
        }

        .screen-share-btn:hover {
            background: #218838;
            transform: scale(1.1);
        }

        .screen-share-btn.active {
            background: #dc3545;
        }

        .screen-share-btn.active:hover {
            background: #c82333;
        }



        .incoming-call-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            max-width: 400px;
            width: 90%;
        }

        .caller-info {
            margin-bottom: 2rem;
        }

        .incoming-call-controls {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .answer-btn,
        .decline-btn {
            padding: 12px 24px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .answer-btn {
            background: #28a745;
            color: white;
        }

        .answer-btn:hover {
            background: #218838;
            transform: scale(1.05);
        }

        .decline-btn {
            background: #dc3545;
            color: white;
        }

        .decline-btn:hover {
            background: #c82333;
            transform: scale(1.05);
        }

        /* End Call Notification Styles */
        .end-call-notification {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.9);
            border-radius: 15px;
            padding: 2rem;
            z-index: 1000;
            animation: fadeInScale 0.3s ease-out;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .notification-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            color: white;
        }

        .notification-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            animation: pulse 1s ease-in-out infinite;
        }

        .notification-text h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: #ff6b6b;
            font-weight: 600;
        }

        .notification-text p {
            font-size: 1rem;
            color: #e0e0e0;
            margin: 0;
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: translate(-50%, -50%) scale(0.8);
            }
            to {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1);
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }
    </style>

    <script>
        // WebRTC implementation
        let localStream = null;
        let remoteStream = null;
        let peerConnection = null;
        let isMuted = false;
        let isCameraOff = false;
        let isScreenSharing = false;
        let screenStream = null;

        // Initialize WebRTC when component loads
        document.addEventListener('livewire:init', () => {
            console.log('=== CALLMANAGER COMPONENT INITIALIZED ===');
            console.log('Current user ID:', window.currentUserId);
            console.log('Current user type:', window.currentUserType);
            console.log('Window location:', window.location.href);
            
            // Check if socket client is available and ensure it's connected
            if (window.chatSocket) {
                console.log('Socket client found:', window.chatSocket);
                console.log('Socket connected:', window.chatSocket.socket?.connected);
                console.log('Socket ID:', window.chatSocket.socket?.id);
                
                // If socket exists but not connected, try to connect
                if (!window.chatSocket.isConnected && !window.chatSocket.socket?.connected) {
                    console.log('Socket not connected, attempting to connect...');
                    try {
                        window.chatSocket.connect();
                    } catch (e) {
                        console.error('Error connecting socket:', e);
                    }
                }
            } else {
                console.log('Socket client NOT found - initializing...');
                // Initialize socket client if it doesn't exist
                if (typeof ChatSocket !== 'undefined') {
                    window.chatSocket = new ChatSocket();
                    window.chatSocket.connect();
                    console.log('Socket client initialized');
                } else {
                    console.error('ChatSocket class not found - socket-client.js may not be loaded');
                }
            }
            
            // Listen for socket ready event
            document.addEventListener('socket:ready', (event) => {
                console.log('=== SOCKET READY EVENT RECEIVED ===');
                console.log('Socket is now ready for calls');
            });
            
            // Listen for socket errors
            document.addEventListener('socket:error', (event) => {
                console.error('=== SOCKET ERROR EVENT ===');
                console.error('Error:', event.detail);
                console.error('Please check if socket server is running on port 3001');
            });
            
            // Listen for incoming calls (only for receivers)
            Livewire.on('callIncoming', (data) => {
                console.log('Incoming call received:', data);
                showIncomingCallModal(data);
            });

            // Listen for call initiation from TutorChat/StudentChat
            Livewire.on('initiateCall', (data) => {
                console.log('CallManager received initiateCall event:', data);
                // Call the handleInitiateCall method on the CallManager component
                @this.call('handleInitiateCall', data);
            });
            
        
        // Direct method to handle call initiation (alternative to events)
        window.handleCallInitiation = function(callData) {
            console.log('=== DIRECT CALL INITIATION ===');
            console.log('Call data received directly:', callData);
            
            if (!callData || typeof callData !== 'object') {
                console.error('Invalid call data received directly');
                return;
            }
            
            // Handle case where data might be an array
            if (Array.isArray(callData) && callData.length > 0) {
                callData = callData[0]; // Extract first element if it's an array
                console.log('Extracted data from array:', callData);
            }
            
            // Log each field individually
            console.log('roomId:', callData.roomId);
            console.log('callType:', callData.callType);
            console.log('callerId:', callData.callerId);
            console.log('callerName:', callData.callerName);
            console.log('receiverId:', callData.receiverId);
            console.log('receiverName:', callData.receiverName);
            console.log('receiverType:', callData.receiverType);
            console.log('callerType:', callData.callerType);
            
            // Validate required fields
            if (!callData.roomId) {
                console.error('Missing roomId in call data');
                return;
            }
            
            if (!callData.callType) {
                console.error('Missing callType in call data');
                return;
            }
            
            if (!callData.receiverId) {
                console.error('Missing receiverId in call data');
                return;
            }
            
            // Send to socket
            sendCallToSocket(callData);
            
            // Show calling interface for caller
            showCallingInterface(callData);
        };
        
        // Listen for direct call initiation event
        Livewire.on('callInitiationDirect', (data) => {
            console.log('=== CALL INITIATION DIRECT EVENT ===');
            console.log('Direct event data:', data);
            
            // Call the direct method
            if (window.handleCallInitiation) {
                window.handleCallInitiation(data);
            } else {
                console.error('handleCallInitiation method not found');
            }
        });

            // Also listen for global socket events
            document.addEventListener('socket:incoming-call-global', (event) => {
                console.log('Global incoming call event received:', event.detail);
                const data = event.detail;
                
                // Show incoming call if this user is the intended receiver (even if IDs match caller but roles differ)
                if (data.receiverId == window.currentUserId && data.receiverType == window.currentUserType) {
                    console.log('Showing incoming call modal from global event');
                    @this.call('handleIncomingCall', data);
                    showIncomingCallModal(data);
                } else {
                    console.log('Incoming call not for this user/role, ignoring');
                }
            });

            // Listen for call answered
            Livewire.on('callAnswered', (data) => {
                console.log('Call answered:', data);
                if (@this.isCaller) {
                    initializeCall(@this.roomId);
                }
            });

            // Listen for call ended
            Livewire.on('callEnded', (data) => {
                console.log('Call ended:', data);
                endCall();
            });

            // Listen for WebRTC initialization
            Livewire.on('initializeWebRTC', (data) => {
                console.log('Initializing WebRTC:', data);
                initializeCall(@this.roomId);
            });

            // Listen for call notification to send to receiver
            Livewire.on('sendCallToReceiver', (data) => {
                console.log('=== sendCallToReceiver EVENT RECEIVED ===');
                console.log('Raw data received:', data);
                console.log('Data type:', typeof data);
                console.log('Data keys:', Object.keys(data));
                
                // Log each field individually
                console.log('roomId:', data.roomId);
                console.log('callType:', data.callType);
                console.log('callerId:', data.callerId);
                console.log('callerName:', data.callerName);
                console.log('receiverId:', data.receiverId);
                console.log('receiverName:', data.receiverName);
                console.log('receiverType:', data.receiverType);
                console.log('callerType:', data.callerType);
                
                // Check if data is properly structured
                if (!data || typeof data !== 'object') {
                    console.error('Invalid data received:', data);
                    return;
                }
                
                // Validate required fields
                if (!data.roomId) {
                    console.error('Missing roomId in call data');
                    return;
                }
                
                // Ensure we have the correct receiver type
                if (!data.receiverType) {
                    data.receiverType = 'student'; // Default to student if not specified
                }
                
                console.log('Call data before sending to socket:', data);
                sendCallToSocket(data);
                
                // For the caller, show the calling interface
                if (@this.isCaller) {
                    showCallingInterface(data);
                }
            });

            Livewire.on('socketCallAnswered', (data) => {
                console.log('Sending call answered to Socket.IO:', data);
                sendCallAnsweredToSocket(data);
            });

            Livewire.on('socketCallDeclined', (data) => {
                console.log('Sending call declined to Socket.IO:', data);
                sendCallDeclinedToSocket(data);
            });

            Livewire.on('socketCallEnded', (data) => {
                console.log('Sending call ended to Socket.IO:', data);
                sendCallEndedToSocket(data);
            });
        });

                                    // Socket.IO WebRTC signaling
        // Instead of creating a new socket connection, use the existing one from socket-client.js
        // The socket-client.js already handles all the socket events and dispatches them as custom events
        
        // Listen for all socket events for debugging
        document.addEventListener('socket:*', (event) => {
            console.log('=== SOCKET EVENT RECEIVED ===');
            console.log('Event type:', event.type);
            console.log('Event detail:', event.detail);
            console.log('Current user ID:', window.currentUserId);
            console.log('Current user type:', window.currentUserType);
        });
        
        // Listen for incoming calls from socket-client.js
        document.addEventListener('socket:incoming-call', (event) => {
            console.log('=== INCOMING CALL RECEIVED FROM SOCKET-CLIENT.JS ===');
            console.log('Event detail:', event.detail);
            console.log('Current user ID:', window.currentUserId);
            console.log('Current user type:', window.currentUserType);
            const data = event.detail;
            
            // Convert IDs to strings for comparison (in case one is number and other is string)
            const receiverId = String(data.receiverId || '');
            const currentUserId = String(window.currentUserId || '');
            const receiverType = String(data.receiverType || '').toLowerCase();
            const currentUserType = String(window.currentUserType || '').toLowerCase();
            
            console.log('Comparison:', {
                receiverId: receiverId,
                currentUserId: currentUserId,
                receiverType: receiverType,
                currentUserType: currentUserType,
                idMatch: receiverId === currentUserId,
                typeMatch: receiverType === currentUserType
            });
            
            // Show incoming call only if this user+role is the receiver
            if (receiverId === currentUserId && receiverType === currentUserType) {
                console.log('Showing incoming call modal for user:', window.currentUserId);
                console.log('Call data:', data);
                // Update the CallManager component with the incoming call data
                @this.call('handleIncomingCall', data);
                showIncomingCallModal(data);
            } else {
                console.log('Incoming call not targeted at this user/role, ignoring');
                console.log('Expected receiverId:', receiverId, 'Current userId:', currentUserId);
                console.log('Expected receiverType:', receiverType, 'Current userType:', currentUserType);
            }
        });

        // Listen for incoming call debug events (fallback broadcast)
        document.addEventListener('socket:incoming-call-debug', (event) => {
            console.log('=== INCOMING CALL DEBUG RECEIVED ===');
            console.log('Debug event detail:', event.detail);
            const data = event.detail;
            
            // Convert IDs to strings for comparison
            const receiverId = String(data.receiverId || '');
            const currentUserId = String(window.currentUserId || '');
            const receiverType = String(data.receiverType || '').toLowerCase();
            const currentUserType = String(window.currentUserType || '').toLowerCase();
            
            // Only handle if this is actually for us
            if (receiverId === currentUserId && receiverType === currentUserType) {
                console.log('This debug call is for us, handling as incoming call');
                @this.call('handleIncomingCall', data);
                showIncomingCallModal(data);
            } else {
                console.log('This debug call is not for us, ignoring');
                console.log('Expected receiverId:', receiverId, 'Current userId:', currentUserId);
                console.log('Expected receiverType:', receiverType, 'Current userType:', currentUserType);
            }
        });

        // Listen for call answered from socket-client.js
        document.addEventListener('socket:call-answered', (event) => {
            console.log('Call answered received from socket-client.js:', event.detail);
            const data = event.detail;
            if (@this.isCaller) {
                initializeCall(data.roomId);
            }
        });

        // Listen for call ended from socket-client.js
        document.addEventListener('socket:call-ended', (event) => {
            console.log('Call ended received from socket-client.js:', event.detail);
            
            // Show notification that the other user ended the call
            showEndCallNotification('The other person ended the call.');
            
            // End the call after a short delay to show the notification
            setTimeout(() => {
                endCall();
            }, 1000);
        });

        // Listen for call declined from socket-client.js
        document.addEventListener('socket:call-declined', (event) => {
            console.log('Call declined received from socket-client.js:', event.detail);
            
            // Show notification that the call was declined
            showEndCallNotification('The call was declined.');
            
            // End the call after a short delay to show the notification
            setTimeout(() => {
                endCall();
            }, 1000);
        });

        // WebRTC signaling events from socket-client.js
        document.addEventListener('socket:webrtc-offer', (event) => {
            console.log('WebRTC offer received from socket-client.js:', event.detail);
            handleOffer(event.detail.offer);
        });

        document.addEventListener('socket:webrtc-answer', (event) => {
            console.log('WebRTC answer received from socket-client.js:', event.detail);
            handleAnswer(event.detail.answer);
        });

        document.addEventListener('socket:webrtc-ice-candidate', (event) => {
            console.log('WebRTC ICE candidate received from socket-client.js:', event.detail);
            handleIceCandidate(event.detail.candidate);
        });


        // Show incoming call modal (for receivers)
        function showIncomingCallModal(data) {
            console.log('Showing incoming call modal with data:', data);
            const modal = document.getElementById('incomingCallModal');
            const avatar = document.getElementById('incomingCallerAvatar');
            const name = document.getElementById('incomingCallerName');
            const type = document.getElementById('incomingCallType');

            // Handle both Livewire and Socket.IO data structures
            const callerName = data.callerName || 'Unknown';
            const callType = data.callType || 'video';

            avatar.textContent = callerName.charAt(0);
            name.textContent = callerName;
            type.textContent = callType === 'video' ? 'Incoming Video Call' : 'Incoming Voice Call';

            modal.style.display = 'flex';

            // Store call data for when user answers/declines
            window.currentIncomingCall = data;

            // Handle answer button
            document.getElementById('answerCallBtn').onclick = async () => {
                modal.style.display = 'none';
                @this.call('answerCall');
                
                // Initialize WebRTC for the receiver
                console.log('Answering call, initializing WebRTC...');
                await initializeCall(@this.roomId);
                
                // Wait a bit for the offer to arrive
                console.log('Waiting for offer from caller...');
            };

            // Handle decline button
            document.getElementById('declineCallBtn').onclick = () => {
                modal.style.display = 'none';
                @this.call('declineCall');
            };
        }

        // Show calling interface (for callers)
        function showCallingInterface(data) {
            console.log('Showing calling interface for caller:', data);
            // The caller should see the main call interface, not the incoming call modal
            // The main call interface is already shown by Livewire when isInCall is true
            // We just need to ensure the call overlay is visible
            const callOverlay = document.getElementById('call-overlay');
            if (callOverlay) {
                callOverlay.style.display = 'flex';
            }
        }

        // Check and request camera permissions
        async function checkCameraPermissions() {
            try {
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    throw new Error('getUserMedia is not supported in this browser');
                }
                
                // Check current permissions
                if (navigator.permissions) {
                    const cameraPermission = await navigator.permissions.query({ name: 'camera' });
                    const microphonePermission = await navigator.permissions.query({ name: 'microphone' });
                    
                    console.log('Camera permission:', cameraPermission.state);
                    console.log('Microphone permission:', microphonePermission.state);
                    
                    if (cameraPermission.state === 'denied' || microphonePermission.state === 'denied') {
                        throw new Error('Camera or microphone access denied');
                    }
                }
                
                return true;
            } catch (error) {
                console.error('Permission check failed:', error);
                return false;
            }
        }

        // Initialize WebRTC call
        async function initializeCall(roomId) {
            try {
                console.log('Initializing call with roomId:', roomId);
                console.log('Call type:', @this.callType);
                
                // Show remote profile placeholder by default until remote video is available
                setTimeout(() => {
                    console.log('Initial check for remote video stream');
                    checkRemoteVideoStream();
                }, 500);
                
                // Also show remote profile placeholder immediately
                setTimeout(() => {
                    console.log('Force showing remote profile placeholder initially');
                    forceShowRemoteProfileAlways();
                }, 100);
                
                // Show remote profile placeholder by default
                setTimeout(() => {
                    console.log('Showing remote profile placeholder by default');
                    forceShowRemoteProfileAlways();
                }, 200);
                
                // Try to reload profile images after a delay
                setTimeout(() => {
                    reloadProfileImages();
                }, 2000);
                
                // Force reload local profile image specifically
                setTimeout(() => {
                    reloadLocalProfileImage();
                }, 1000);
                
                // Force show both profile placeholders
                setTimeout(() => {
                    forceShowBothProfiles();
                }, 300);
                
                // Check permissions first
                const hasPermissions = await checkCameraPermissions();
                if (!hasPermissions) {
                    console.warn('Permission check failed, but continuing with getUserMedia...');
                }
                
                // Get user media with better error handling
                const constraints = {
                    video: @this.callType === 'video' ? {
                        width: { ideal: 1280 },
                        height: { ideal: 720 },
                        facingMode: 'user'
                    } : false,
                    audio: {
                        echoCancellation: true,
                        noiseSuppression: true
                    }
                };

                console.log('Media constraints:', constraints);
                
                // Check if getUserMedia is supported
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    throw new Error('getUserMedia is not supported in this browser');
                }
                
                localStream = await navigator.mediaDevices.getUserMedia(constraints);
                console.log('Local stream obtained:', localStream);
                console.log('Local stream tracks:', localStream.getTracks());
                
                // Display local video immediately
                if (@this.callType === 'video') {
                    const localVideo = document.getElementById('localVideo');
                    if (localVideo) {
                        localVideo.srcObject = localStream;
                        localVideo.muted = true; // Mute local video to prevent echo
                        await localVideo.play();
                        console.log('Local video element updated and playing');
                    } else {
                        console.error('Local video element not found');
                    }
                }

                // Create peer connection with better configuration
                const configuration = {
                    iceServers: [
                        { urls: 'stun:stun.l.google.com:19302' },
                        { urls: 'stun:stun1.l.google.com:19302' },
                        { urls: 'stun:stun2.l.google.com:19302' }
                    ],
                    iceCandidatePoolSize: 10
                };

                peerConnection = new RTCPeerConnection(configuration);

                // Add local stream tracks
                localStream.getTracks().forEach(track => {
                    console.log('Adding track to peer connection:', track.kind, track.label);
                    peerConnection.addTrack(track, localStream);
                });

                // Handle remote stream
                peerConnection.ontrack = (event) => {
                    console.log('=== REMOTE TRACK RECEIVED ===');
                    console.log('Event:', event);
                    console.log('Streams:', event.streams);
                    console.log('Track:', event.track);
                    
                    if (event.streams && event.streams.length > 0) {
                    remoteStream = event.streams[0];
                        console.log('Remote stream assigned:', remoteStream);
                        console.log('Remote stream tracks:', remoteStream.getTracks());
                        
                        // Log each track
                        remoteStream.getTracks().forEach((track, index) => {
                            console.log(`Remote track ${index}:`, {
                                kind: track.kind,
                                label: track.label,
                                enabled: track.enabled,
                                readyState: track.readyState
                            });
                            
                            // Monitor track state changes
                            track.addEventListener('ended', () => {
                                console.log(`Remote ${track.kind} track ended`);
                                if (track.kind === 'video') {
                                    showRemoteProfilePlaceholder();
                                }
                            });
                            
                            track.addEventListener('mute', () => {
                                console.log(`Remote ${track.kind} track muted`);
                                if (track.kind === 'video') {
                                    showRemoteProfilePlaceholder();
                                }
                            });
                            
                            track.addEventListener('unmute', () => {
                                console.log(`Remote ${track.kind} track unmuted`);
                                if (track.kind === 'video') {
                                    hideRemoteProfilePlaceholder();
                                }
                            });
                        });
                        
                        // Check remote video stream after setting up tracks
                        setTimeout(() => {
                            checkRemoteVideoStream();
                        }, 1000);
                        
                        // Set up periodic check for remote video stream
                        setInterval(() => {
                            checkRemoteVideoStream();
                        }, 500);
                        
                        // Also force show remote profile placeholder periodically
                        setInterval(() => {
                            if (!document.getElementById('remoteVideo').srcObject || 
                                document.getElementById('remoteVideo').srcObject.getTracks().length === 0) {
                                forceShowRemoteProfileAlways();
                            }
                        }, 2000);
                        
                        // Also check immediately after setting up tracks
                        setTimeout(() => {
                            checkRemoteVideoStream();
                        }, 2000);
                        
                    if (@this.callType === 'video') {
                        const remoteVideo = document.getElementById('remoteVideo');
                        if (remoteVideo) {
                                console.log('Setting remote video srcObject...');
                            remoteVideo.srcObject = remoteStream;
                                remoteVideo.muted = false; // Don't mute remote video
                                
                                // Force video to play
                                remoteVideo.load(); // Reload the video element
                                
                                remoteVideo.play().then(() => {
                                    console.log('✅ Remote video element updated and playing successfully');
                                    console.log('Remote video dimensions:', remoteVideo.videoWidth, 'x', remoteVideo.videoHeight);
                                }).catch(e => {
                                    console.error('❌ Remote video play error:', e);
                                    // Try alternative approach
                                    setTimeout(() => {
                                        remoteVideo.play().catch(err => {
                                            console.error('❌ Retry remote video play failed:', err);
                                        });
                                    }, 1000);
                                });
                                
                                // Add event listeners for debugging
                                remoteVideo.addEventListener('loadedmetadata', () => {
                                    console.log('✅ Remote video metadata loaded');
                                });
                                
                                remoteVideo.addEventListener('canplay', () => {
                                    console.log('✅ Remote video can play');
                                });
                                
                                remoteVideo.addEventListener('error', (e) => {
                                    console.error('❌ Remote video error:', e);
                                });
                                
                        } else {
                                console.error('❌ Remote video element not found');
                            }
                        }
                    } else {
                        console.error('❌ No remote streams in event');
                    }
                };

                // Handle connection state changes
                peerConnection.onconnectionstatechange = () => {
                    console.log('Connection state:', peerConnection.connectionState);
                    
                    if (peerConnection.connectionState === 'connected') {
                        console.log('✅ Peer connection established!');
                        // Force refresh remote video when connection is established
                        setTimeout(() => {
                            forceRefreshRemoteVideo();
                        }, 1000);
                    } else if (peerConnection.connectionState === 'failed') {
                        console.error('❌ Peer connection failed!');
                        // Try to reconnect
                        setTimeout(() => {
                            retryWebRTCConnection();
                        }, 2000);
                    } else if (peerConnection.connectionState === 'connecting') {
                        console.log('🔄 Peer connection connecting...');
                    } else if (peerConnection.connectionState === 'disconnected') {
                        console.log('⚠️ Peer connection disconnected, attempting to reconnect...');
                        setTimeout(() => {
                            retryWebRTCConnection();
                        }, 3000);
                    }
                };

                peerConnection.oniceconnectionstatechange = () => {
                    console.log('ICE connection state:', peerConnection.iceConnectionState);
                    
                    if (peerConnection.iceConnectionState === 'connected' || peerConnection.iceConnectionState === 'completed') {
                        console.log('✅ ICE connection established!');
                        // Force refresh remote video when ICE connection is established
                        setTimeout(() => {
                            forceRefreshRemoteVideo();
                        }, 500);
                    } else if (peerConnection.iceConnectionState === 'failed') {
                        console.error('❌ ICE connection failed!');
                        // Try to reconnect
                        setTimeout(() => {
                            retryWebRTCConnection();
                        }, 2000);
                    } else if (peerConnection.iceConnectionState === 'connecting') {
                        console.log('🔄 ICE connection connecting...');
                    } else if (peerConnection.iceConnectionState === 'disconnected') {
                        console.log('⚠️ ICE connection disconnected, attempting to reconnect...');
                        setTimeout(() => {
                            retryWebRTCConnection();
                        }, 3000);
                    }
                };

                // Join call room first
                const globalSocket = window.chatSocket ? window.chatSocket.socket : null;
                if (globalSocket) {
                    globalSocket.emit('join_call_room', { roomId: roomId });
                    console.log('Joined call room:', roomId);
                }

                // Handle ICE candidates
                peerConnection.onicecandidate = (event) => {
                    if (event.candidate) {
                        console.log('Sending ICE candidate:', event.candidate);
                        sendIceCandidate(event.candidate);
                    }
                };

                // Only create and send offer if we're the caller
                if (@this.isCaller) {
                    console.log('Creating offer as caller...');
                    
                    // Wait a bit to ensure receiver is ready
                    await new Promise(resolve => setTimeout(resolve, 1000));
                    
                    const offer = await peerConnection.createOffer({
                        offerToReceiveAudio: true,
                        offerToReceiveVideo: @this.callType === 'video'
                    });
                await peerConnection.setLocalDescription(offer);
                    console.log('Local description set:', offer);

                                                        // Send offer through signaling server
                if (globalSocket) {
                    globalSocket.emit('webrtc_offer', {
                        roomId: roomId,
                        offer: offer,
                        from: window.currentUserId
                    });
                        console.log('✅ Offer sent to signaling server');
                        
                        // Wait for answer and retry if needed
                        setTimeout(() => {
                            if (peerConnection.signalingState !== 'stable') {
                                console.log('⚠️ Signaling not stable, retrying offer...');
                                retryWebRTCConnection();
                            }
                        }, 5000);
                        
                        // Auto-retry connection after 10 seconds if not connected
                        setTimeout(() => {
                            autoRetryConnection();
                        }, 10000);
                    } else {
                        console.error('❌ No socket connection available to send offer');
                    }
                } else {
                    console.log('Waiting for offer as receiver...');
                    
                    // Set a timeout to retry if no offer is received
                    setTimeout(() => {
                        if (peerConnection.signalingState === 'stable' && !peerConnection.remoteDescription) {
                            console.log('⚠️ No offer received, retrying connection...');
                            retryWebRTCConnection();
                        }
                    }, 10000);
                }

            } catch (error) {
                console.error('Error initializing call:', error);
                let errorMessage = 'Failed to initialize call. ';
                
                if (error.name === 'NotAllowedError') {
                    errorMessage += 'Please allow camera and microphone access.';
                } else if (error.name === 'NotFoundError') {
                    errorMessage += 'Camera or microphone not found.';
                } else if (error.name === 'NotSupportedError') {
                    errorMessage += 'Your browser does not support video calls.';
                } else {
                    errorMessage += 'Please check your camera and microphone permissions.';
                }
                
                alert(errorMessage);
            }
        }

        // Toggle mute
        document.addEventListener('click', (e) => {
            if (e.target.closest('#muteBtn')) {
                if (localStream) {
                    const audioTrack = localStream.getAudioTracks()[0];
                    if (audioTrack) {
                        audioTrack.enabled = !audioTrack.enabled;
                        isMuted = !audioTrack.enabled;
                        const btn = e.target.closest('#muteBtn');
                        btn.style.background = isMuted ? '#dc3545' : '#6c757d';
                    }
                }
            }
        });

        // Toggle camera
        document.addEventListener('click', (e) => {
            if (e.target.closest('#cameraBtn')) {
                if (localStream) {
                    const videoTrack = localStream.getVideoTracks()[0];
                    if (videoTrack) {
                        videoTrack.enabled = !videoTrack.enabled;
                        isCameraOff = !videoTrack.enabled;
                        const btn = e.target.closest('#cameraBtn');
                        btn.style.background = isCameraOff ? '#dc3545' : '#17a2b8';
                        
                        // Show/hide profile placeholder
                        const localVideo = document.getElementById('localVideo');
                        const localProfilePlaceholder = document.getElementById('localProfilePlaceholder');
                        
                        if (isCameraOff) {
                            localVideo.style.display = 'none';
                            localProfilePlaceholder.style.display = 'flex';
                        } else {
                            localVideo.style.display = 'block';
                            localProfilePlaceholder.style.display = 'none';
                        }
                    }
                }
            }
        });

        // Screen sharing functionality
        document.addEventListener('click', (e) => {
            if (e.target.closest('#screenShareBtn')) {
                if (isScreenSharing) {
                    stopScreenShare();
                } else {
                    startScreenShare();
                }
            }
        });

        // Start screen sharing
        async function startScreenShare() {
            try {
                console.log('Starting screen share...');
                
                // Get screen capture stream
                screenStream = await navigator.mediaDevices.getDisplayMedia({
                    video: {
                        mediaSource: 'screen',
                        width: { ideal: 1920 },
                        height: { ideal: 1080 }
                    },
                    audio: true
                });
                
                console.log('Screen stream obtained:', screenStream);
                
                // Update UI
                const screenShareBtn = document.getElementById('screenShareBtn');
                screenShareBtn.classList.add('active');
                screenShareBtn.title = 'Stop Screen Share';
                isScreenSharing = true;
                
                // Replace video track in peer connection
                if (peerConnection && localStream) {
                    const videoTrack = screenStream.getVideoTracks()[0];
                    const audioTrack = screenStream.getAudioTracks()[0];
                    
                    // Replace video track
                    const sender = peerConnection.getSenders().find(s => 
                        s.track && s.track.kind === 'video'
                    );
                    if (sender) {
                        await sender.replaceTrack(videoTrack);
                        console.log('Video track replaced with screen share');
                    }
                    
                    // Add audio track if available
                    if (audioTrack) {
                        const audioSender = peerConnection.getSenders().find(s => 
                            s.track && s.track.kind === 'audio'
                        );
                        if (audioSender) {
                            await audioSender.replaceTrack(audioTrack);
                            console.log('Audio track replaced with screen share audio');
                        }
                    }
                }
                
                // Update local video display
                const localVideo = document.getElementById('localVideo');
                if (localVideo) {
                    localVideo.srcObject = screenStream;
                    await localVideo.play();
                    console.log('Local video updated with screen share');
                }
                
                // Handle screen share end
                screenStream.getVideoTracks()[0].onended = () => {
                    console.log('Screen share ended by user');
                    stopScreenShare();
                };
                
            } catch (error) {
                console.error('Error starting screen share:', error);
                alert('Failed to start screen sharing. Please check your browser permissions.');
            }
        }

        // Stop screen sharing
        async function stopScreenShare() {
            try {
                console.log('Stopping screen share...');
                
                // Stop screen stream
                if (screenStream) {
                    screenStream.getTracks().forEach(track => track.stop());
                    screenStream = null;
                }
                
                // Update UI
                const screenShareBtn = document.getElementById('screenShareBtn');
                screenShareBtn.classList.remove('active');
                screenShareBtn.title = 'Share Screen';
                isScreenSharing = false;
                
                // Restore original video track
                if (peerConnection && localStream) {
                    const videoTrack = localStream.getVideoTracks()[0];
                    const audioTrack = localStream.getAudioTracks()[0];
                    
                    // Replace video track back to camera
                    const sender = peerConnection.getSenders().find(s => 
                        s.track && s.track.kind === 'video'
                    );
                    if (sender && videoTrack) {
                        await sender.replaceTrack(videoTrack);
                        console.log('Video track restored to camera');
                    }
                    
                    // Restore audio track
                    if (audioTrack) {
                        const audioSender = peerConnection.getSenders().find(s => 
                            s.track && s.track.kind === 'audio'
                        );
                        if (audioSender) {
                            await audioSender.replaceTrack(audioTrack);
                            console.log('Audio track restored to microphone');
                        }
                    }
                }
                
                // Update local video display
                const localVideo = document.getElementById('localVideo');
                if (localVideo && localStream) {
                    localVideo.srcObject = localStream;
                    await localVideo.play();
                    console.log('Local video restored to camera');
                }
                
            } catch (error) {
                console.error('Error stopping screen share:', error);
            }
        }

        // Show remote profile placeholder
        function showRemoteProfilePlaceholder() {
            console.log('Showing remote profile placeholder');
            const remoteVideo = document.getElementById('remoteVideo');
            const remoteProfilePlaceholder = document.getElementById('remoteProfilePlaceholder');
            
            if (remoteVideo && remoteProfilePlaceholder) {
                remoteVideo.style.display = 'none';
                remoteProfilePlaceholder.style.display = 'flex';
                console.log('Remote profile placeholder should now be visible');
            } else {
                console.error('Remote video or profile placeholder element not found');
            }
        }

        // Hide remote profile placeholder
        function hideRemoteProfilePlaceholder() {
            console.log('Hiding remote profile placeholder');
            const remoteVideo = document.getElementById('remoteVideo');
            const remoteProfilePlaceholder = document.getElementById('remoteProfilePlaceholder');
            
            if (remoteVideo && remoteProfilePlaceholder) {
                remoteVideo.style.display = 'block';
                remoteProfilePlaceholder.style.display = 'none';
                console.log('Remote profile placeholder should now be hidden');
            } else {
                console.error('Remote video or profile placeholder element not found');
            }
        }

        // Check remote video stream and show profile placeholder if no stream
        function checkRemoteVideoStream() {
            const remoteVideo = document.getElementById('remoteVideo');
            const remoteProfilePlaceholder = document.getElementById('remoteProfilePlaceholder');
            
            if (remoteVideo && remoteProfilePlaceholder) {
                // Check if remote video has a valid stream
                if (!remoteVideo.srcObject || remoteVideo.srcObject.getTracks().length === 0) {
                    console.log('No remote video stream detected, showing profile placeholder');
                    showRemoteProfilePlaceholder();
                } else {
                    // Check if video track is enabled
                    const videoTracks = remoteVideo.srcObject.getVideoTracks();
                    if (videoTracks.length === 0 || !videoTracks[0].enabled) {
                        console.log('Remote video track disabled, showing profile placeholder');
                        showRemoteProfilePlaceholder();
                    } else {
                        console.log('Remote video stream active, hiding profile placeholder');
                        hideRemoteProfilePlaceholder();
                    }
                }
            } else {
                console.log('Remote video or profile placeholder elements not found');
            }
        }

        // Force show remote profile placeholder (always show by default)
        function forceShowRemoteProfileAlways() {
            console.log('Force showing remote profile placeholder always');
            const remoteVideo = document.getElementById('remoteVideo');
            const remoteProfilePlaceholder = document.getElementById('remoteProfilePlaceholder');
            
            if (remoteVideo && remoteProfilePlaceholder) {
                remoteVideo.style.display = 'none';
                remoteProfilePlaceholder.style.display = 'flex';
                console.log('Remote profile placeholder should now be visible');
            }
        }

        function forceShowRemoteProfile() {
            console.log('Force showing remote profile placeholder');
            showRemoteProfilePlaceholder();
        }

        // Force reload profile images
        function reloadProfileImages() {
            console.log('Reloading profile images...');
            const localImg = document.querySelector('#localProfilePlaceholder .profile-image');
            const remoteImg = document.querySelector('#remoteProfilePlaceholder .profile-image');
            
            if (localImg) {
                const src = localImg.src;
                localImg.src = '';
                setTimeout(() => {
                    localImg.src = src + '?t=' + Date.now();
                }, 100);
            }
            
            if (remoteImg) {
                const src = remoteImg.src;
                remoteImg.src = '';
                setTimeout(() => {
                    remoteImg.src = src + '?t=' + Date.now();
                }, 100);
            }
        }

        // Force reload local profile image specifically
        function reloadLocalProfileImage() {
            console.log('Force reloading local profile image...');
            const localImg = document.querySelector('#localProfilePlaceholder .profile-image');
            if (localImg) {
                const src = localImg.src;
                localImg.src = '';
                setTimeout(() => {
                    localImg.src = src + '?t=' + Date.now();
                }, 100);
            } else {
                console.log('No local profile image found to reload');
            }
        }

        // Force show both profile placeholders
        function forceShowBothProfiles() {
            console.log('Force showing both profile placeholders...');
            
            // Show local profile placeholder
            const localVideo = document.getElementById('localVideo');
            const localProfilePlaceholder = document.getElementById('localProfilePlaceholder');
            if (localVideo && localProfilePlaceholder) {
                localVideo.style.display = 'none';
                localProfilePlaceholder.style.display = 'flex';
                console.log('Local profile placeholder shown');
            }
            
            // Show remote profile placeholder
            const remoteVideo = document.getElementById('remoteVideo');
            const remoteProfilePlaceholder = document.getElementById('remoteProfilePlaceholder');
            if (remoteVideo && remoteProfilePlaceholder) {
                remoteVideo.style.display = 'none';
                remoteProfilePlaceholder.style.display = 'flex';
                console.log('Remote profile placeholder shown');
            }
        }

        // Make functions globally available for debugging
        window.forceShowRemoteProfile = forceShowRemoteProfile;
        window.forceShowRemoteProfileAlways = forceShowRemoteProfileAlways;
        window.checkRemoteVideoStream = checkRemoteVideoStream;
        window.showRemoteProfilePlaceholder = showRemoteProfilePlaceholder;
        window.hideRemoteProfilePlaceholder = hideRemoteProfilePlaceholder;
        window.reloadProfileImages = reloadProfileImages;
        window.reloadLocalProfileImage = reloadLocalProfileImage;
        window.forceShowBothProfiles = forceShowBothProfiles;

        // Force refresh remote video stream
        function forceRefreshRemoteVideo() {
            console.log('=== FORCE REFRESHING REMOTE VIDEO ===');
            
            const remoteVideo = document.getElementById('remoteVideo');
            if (remoteVideo && remoteStream) {
                console.log('Refreshing remote video stream...');
                
                // Clear current stream
                remoteVideo.srcObject = null;
                
                // Wait a bit then set it again
                setTimeout(() => {
                    remoteVideo.srcObject = remoteStream;
                    remoteVideo.load();
                    remoteVideo.play().then(() => {
                        console.log('✅ Remote video refreshed successfully');
                    }).catch(e => {
                        console.error('❌ Remote video refresh failed:', e);
                    });
                }, 100);
            } else {
                console.log('No remote video element or stream available');
            }
        }

        // Check WebRTC connection status
        function checkWebRTCConnection() {
            console.log('=== CHECKING WEBRTC CONNECTION ===');
            
            if (!peerConnection) {
                console.log('❌ No peer connection');
                return false;
            }
            
            console.log('Connection state:', peerConnection.connectionState);
            console.log('ICE connection state:', peerConnection.iceConnectionState);
            console.log('Signaling state:', peerConnection.signalingState);
            
            if (peerConnection.connectionState === 'connected' && peerConnection.iceConnectionState === 'connected') {
                console.log('✅ WebRTC connection is established');
                return true;
            } else {
                console.log('⚠️ WebRTC connection not fully established');
                return false;
            }
        }

        // Retry WebRTC connection
        async function retryWebRTCConnection() {
            console.log('=== RETRYING WEBRTC CONNECTION ===');
            
            if (peerConnection) {
                // Close existing connection
                peerConnection.close();
                peerConnection = null;
            }
            
            // Wait a bit then reinitialize
            setTimeout(async () => {
                console.log('Reinitializing WebRTC connection...');
                await initializeCall(@this.roomId);
            }, 1000);
        }

        // Auto-retry connection if not working
        function autoRetryConnection() {
            console.log('=== AUTO-RETRY CONNECTION CHECK ===');
            
            if (!peerConnection) {
                console.log('No peer connection, retrying...');
                retryWebRTCConnection();
                return;
            }
            
            const isConnected = peerConnection.connectionState === 'connected' && 
                               peerConnection.iceConnectionState === 'connected';
            
            if (!isConnected) {
                console.log('Connection not established, retrying...');
                retryWebRTCConnection();
            } else {
                console.log('✅ Connection is working properly');
            }
        }

        // Debug function to check video elements and streams
        function debugVideoElements() {
            console.log('=== VIDEO DEBUG INFO ===');
            
            const localVideo = document.getElementById('localVideo');
            const remoteVideo = document.getElementById('remoteVideo');
            
            console.log('Local video element:', localVideo);
            console.log('Remote video element:', remoteVideo);
            
            if (localVideo) {
                console.log('Local video srcObject:', localVideo.srcObject);
                console.log('Local video readyState:', localVideo.readyState);
                console.log('Local video paused:', localVideo.paused);
                console.log('Local video muted:', localVideo.muted);
                console.log('Local video dimensions:', localVideo.videoWidth, 'x', localVideo.videoHeight);
            }
            
            if (remoteVideo) {
                console.log('Remote video srcObject:', remoteVideo.srcObject);
                console.log('Remote video readyState:', remoteVideo.readyState);
                console.log('Remote video paused:', remoteVideo.paused);
                console.log('Remote video muted:', remoteVideo.muted);
                console.log('Remote video dimensions:', remoteVideo.videoWidth, 'x', remoteVideo.videoHeight);
                
                // Check if remote video has a stream but isn't showing
                if (remoteVideo.srcObject && remoteVideo.videoWidth === 0) {
                    console.log('⚠️ Remote video has stream but no dimensions - trying to refresh...');
                    forceRefreshRemoteVideo();
                }
            }
            
            console.log('Local stream:', localStream);
            console.log('Remote stream:', remoteStream);
            console.log('Peer connection:', peerConnection);
            
            if (localStream) {
                console.log('Local stream tracks:', localStream.getTracks());
                localStream.getTracks().forEach((track, index) => {
                    console.log(`Local track ${index}:`, {
                        kind: track.kind,
                        label: track.label,
                        enabled: track.enabled,
                        readyState: track.readyState
                    });
                });
            }
            
            if (remoteStream) {
                console.log('Remote stream tracks:', remoteStream.getTracks());
                remoteStream.getTracks().forEach((track, index) => {
                    console.log(`Remote track ${index}:`, {
                        kind: track.kind,
                        label: track.label,
                        enabled: track.enabled,
                        readyState: track.readyState
                    });
                });
            }
            
            if (peerConnection) {
                console.log('Peer connection state:', peerConnection.connectionState);
                console.log('ICE connection state:', peerConnection.iceConnectionState);
                console.log('Signaling state:', peerConnection.signalingState);
                
                // Check if we have remote tracks
                const receivers = peerConnection.getReceivers();
                console.log('Remote receivers:', receivers);
                receivers.forEach((receiver, index) => {
                    console.log(`Receiver ${index}:`, {
                        track: receiver.track,
                        kind: receiver.track?.kind,
                        enabled: receiver.track?.enabled,
                        readyState: receiver.track?.readyState
                    });
                });
            }
            
            console.log('=== END VIDEO DEBUG ===');
        }

        // End call function
        function endCall() {
            console.log('Ending call...');
            
            // Stop screen sharing if active
            if (isScreenSharing) {
                stopScreenShare();
            }
            
            if (localStream) {
                localStream.getTracks().forEach(track => {
                    console.log('Stopping local track:', track.kind);
                    track.stop();
                });
            }
            
            if (screenStream) {
                screenStream.getTracks().forEach(track => {
                    console.log('Stopping screen track:', track.kind);
                    track.stop();
                });
            }
            
            if (peerConnection) {
                console.log('Closing peer connection...');
                peerConnection.close();
            }
            
            if (remoteStream) {
                remoteStream.getTracks().forEach(track => {
                    console.log('Stopping remote track:', track.kind);
                    track.stop();
                });
            }
            
            localStream = null;
            remoteStream = null;
            peerConnection = null;
            screenStream = null;
            
            // Reset states
            isMuted = false;
            isCameraOff = false;
            isScreenSharing = false;
            
            // Show end call notification
            showEndCallNotification('You ended the call.');
            
            console.log('Call ended successfully');
        }

        // Show end call notification
        function showEndCallNotification(message = 'The call has been ended.') {
            const notification = document.getElementById('endCallNotification');
            const messageElement = document.getElementById('endCallMessage');
            
            if (notification && messageElement) {
                messageElement.textContent = message;
                notification.style.display = 'block';
                
                // Auto-hide after 3 seconds
                setTimeout(() => {
                    hideEndCallNotification();
                }, 3000);
            }
        }

        // Hide end call notification
        function hideEndCallNotification() {
            const notification = document.getElementById('endCallNotification');
            if (notification) {
                notification.style.display = 'none';
            }
        }

        // WebRTC signaling functions
        async function handleOffer(offer) {
            console.log('=== HANDLING WEBRTC OFFER ===');
            console.log('Offer received:', offer);
            console.log('Current peer connection:', peerConnection);
            console.log('Is caller:', @this.isCaller);
            
            if (!peerConnection) {
                console.log('No peer connection, initializing for receiver...');
                await initializeCall(@this.roomId);
                
                // Wait a bit for initialization
                await new Promise(resolve => setTimeout(resolve, 1500));
            }
            
            if (peerConnection) {
                try {
                    console.log('Setting remote description...');
                    await peerConnection.setRemoteDescription(new RTCSessionDescription(offer));
                    console.log('✅ Remote description set for offer');
                    
                    console.log('Creating answer...');
                    const answer = await peerConnection.createAnswer({
                        offerToReceiveAudio: true,
                        offerToReceiveVideo: @this.callType === 'video'
                    });
                    console.log('Answer created:', answer);
                    
                    console.log('Setting local description...');
                    await peerConnection.setLocalDescription(answer);
                    console.log('✅ Local description set for answer');
                    
                    const globalSocket = window.chatSocket ? window.chatSocket.socket : null;
                    if (globalSocket) {
                        console.log('Sending answer to signaling server...');
                        globalSocket.emit('webrtc_answer', {
                            roomId: @this.roomId,
                            answer: answer,
                            from: window.currentUserId
                        });
                        console.log('✅ Answer sent to signaling server');
                        
                        // Force refresh remote video after sending answer
                        setTimeout(() => {
                            forceRefreshRemoteVideo();
                        }, 2000);
                    } else {
                        console.error('❌ No socket connection available');
                    }
                } catch (error) {
                    console.error('❌ Error handling offer:', error);
                    console.error('Error details:', error.message);
                    
                    // Retry on error
                    setTimeout(() => {
                        console.log('Retrying offer handling...');
                        handleOffer(offer);
                    }, 2000);
                }
            } else {
                console.error('❌ No peer connection available after initialization');
                
                // Retry initialization
                setTimeout(async () => {
                    console.log('Retrying initialization...');
                    await initializeCall(@this.roomId);
                    setTimeout(() => {
                        handleOffer(offer);
                    }, 1000);
                }, 2000);
            }
        }

        async function handleAnswer(answer) {
            console.log('=== HANDLING WEBRTC ANSWER ===');
            console.log('Answer received:', answer);
            console.log('Current peer connection:', peerConnection);
            console.log('Is caller:', @this.isCaller);
            
            if (peerConnection) {
                try {
                    console.log('Setting remote description for answer...');
                    await peerConnection.setRemoteDescription(new RTCSessionDescription(answer));
                    console.log('✅ Remote description set for answer');
                    
                    // Check connection state
                    console.log('Connection state after answer:', peerConnection.connectionState);
                    console.log('ICE connection state:', peerConnection.iceConnectionState);
                    
                    // Force refresh remote video after receiving answer
                    setTimeout(() => {
                        forceRefreshRemoteVideo();
                    }, 1000);
                    
                } catch (error) {
                    console.error('❌ Error handling answer:', error);
                    console.error('Error details:', error.message);
                    
                    // Retry on error
                    setTimeout(() => {
                        console.log('Retrying answer handling...');
                        handleAnswer(answer);
                    }, 2000);
                }
            } else {
                console.error('❌ No peer connection available to handle answer');
                
                // Retry initialization
                setTimeout(async () => {
                    console.log('Retrying initialization for answer...');
                    await initializeCall(@this.roomId);
                    setTimeout(() => {
                        handleAnswer(answer);
                    }, 1000);
                }, 2000);
            }
        }

        async function handleIceCandidate(candidate) {
            console.log('Handling ICE candidate:', candidate);
            
            if (peerConnection) {
                try {
                    await peerConnection.addIceCandidate(new RTCIceCandidate(candidate));
                    console.log('ICE candidate added successfully');
                } catch (error) {
                    console.error('Error adding ICE candidate:', error);
                }
            } else {
                console.error('No peer connection available to handle ICE candidate');
            }
        }

                                // Send ICE candidates
        function sendIceCandidate(candidate) {
            const globalSocket = window.chatSocket ? window.chatSocket.socket : null;
            if (globalSocket) {
                globalSocket.emit('webrtc_ice_candidate', {
                    roomId: @this.roomId,
                    candidate: candidate,
                    from: window.currentUserId
                });
            } else {
                console.error('Socket not connected');
            }
        }

        // Socket.IO functions for cross-tab communication
        function sendCallToSocket(data) {
            // Get the socket instance from socket-client.js
            const globalSocket = window.chatSocket ? window.chatSocket.socket : null;
            
            // Debug: Log the incoming data
            console.log('=== sendCallToSocket FUNCTION CALLED ===');
            console.log('Raw data received:', data);
            console.log('Data type:', typeof data);
            console.log('Is array:', Array.isArray(data));
            
            // Handle array data (Livewire sometimes passes arrays)
            if (Array.isArray(data)) {
                if (data.length > 0) {
                data = data[0]; // Extract first element if it's an array
                console.log('Extracted data from array:', data);
                } else {
                    console.error('Empty array received in sendCallToSocket');
                    return;
                }
            }
            
            // Handle nested data structures
            if (data && typeof data === 'object' && data.data) {
                data = data.data;
                console.log('Extracted nested data:', data);
            }
            
            console.log('Processed data:', data);
            console.log('Data keys:', Object.keys(data || {}));
            console.log('window.currentUserType:', window.currentUserType);
            
            // Validate required fields
            if (!data || typeof data !== 'object') {
                console.error('Invalid data received:', data);
                return;
            }
            
            if (!data.roomId) {
                console.error('Missing roomId in call data');
                console.error('Data object:', data);
                console.error('Available keys:', Object.keys(data));
                return;
            }
            if (!data.callType) {
                console.error('Missing callType in call data');
                console.error('Data object:', data);
                return;
            }
            if (!data.callerId) {
                console.error('Missing callerId in call data');
                console.error('Data object:', data);
                return;
            }
            if (!data.receiverId) {
                console.error('Missing receiverId in call data');
                console.error('Data object:', data);
                return;
            }
            
            // Ensure socket connection; retry if not yet connected
            const emitCall = () => {
                const sock = window.chatSocket ? window.chatSocket.socket : null;
                if (!sock || !sock.connected) {
                    console.log('Socket not ready for emit:', {
                        socketExists: !!sock,
                        isConnected: sock ? sock.connected : false,
                        socketId: sock?.id
                    });
                    return false;
                }
                
                console.log('=== EMITTING CALL TO SOCKET ===');
                console.log('Call data:', callData);
                console.log('Socket ID:', sock.id);
                
                try {
                sock.emit('call_initiated', callData);
                    console.log('✅ Call notification sent to Socket.IO successfully');
                return true;
                } catch (error) {
                    console.error('❌ Error emitting call:', error);
                    return false;
                }
            };

            // Send call notification to the specific receiver
            // We need to emit to a specific room for the receiver
            const callData = {
                roomId: data.roomId,
                callType: data.callType,
                callerId: data.callerId,
                callerName: data.callerName || 'Unknown Caller',
                receiverId: data.receiverId,
                callerType: data.callerType || window.currentUserType || 'tutor', // Use data.callerType from CallManager
                receiverType: data.receiverType || 'student' // Default to student if not specified
            };
            
            console.log('Final call data being sent to Socket.IO:', callData);
            console.log('Socket connection status:', {
                socketExists: !!globalSocket,
                isConnected: globalSocket ? globalSocket.connected : false,
                chatSocketExists: !!window.chatSocket,
                chatSocketConnected: window.chatSocket ? window.chatSocket.isConnected : false
            });
            
            // Check socket connection status
            const socketReady = globalSocket && globalSocket.connected;
            
            if (!socketReady) {
                console.warn('Socket not connected yet. Will retry to emit call.');
                
                // Try to initialize socket if it doesn't exist
                if (!window.chatSocket) {
                    console.log('Socket client not found, initializing...');
                    if (typeof ChatSocket !== 'undefined') {
                        window.chatSocket = new ChatSocket();
                        window.chatSocket.connect();
                    } else {
                        console.error('ChatSocket class not available');
                        alert('Socket client not loaded. Please refresh the page.');
                        return;
                    }
                }
                
                // Try to connect if not connected
                if (!window.chatSocket.isConnected && !window.chatSocket.socket?.connected) {
                    try { 
                        console.log('Attempting to connect socket...');
                        window.chatSocket.connect();
                    } catch (e) {
                        console.error('Error connecting socket:', e);
                    }
                }
                
                // Wait for connection with retries
                let retries = 40; // ~10 seconds total (more time for connection)
                const interval = setInterval(() => {
                    const sock = window.chatSocket ? window.chatSocket.socket : null;
                    const isConnected = sock && sock.connected;
                    
                    console.log(`Retry ${40 - retries + 1}/${40}: Socket connected = ${isConnected}`);
                    
                    if (isConnected && emitCall()) {
                        console.log('Call sent successfully after retry');
                        clearInterval(interval);
                    } else if (--retries <= 0) {
                        clearInterval(interval);
                        console.error('Failed to send call: socket never connected after all retries.');
                        console.error('Socket status:', {
                            chatSocketExists: !!window.chatSocket,
                            socketExists: !!sock,
                            isConnected: isConnected,
                            socketId: sock?.id
                        });
                        alert('Unable to connect to call server. Please check:\n1. Socket server is running\n2. No firewall blocking port 3001\n3. Refresh the page and try again');
                    }
                }, 250);
            } else {
                console.log('Socket is ready, sending call immediately');
                const result = emitCall();
                if (!result) {
                    console.error('Failed to emit call even though socket appears connected');
                }
            }
        }

        function sendCallAnsweredToSocket(data) {
            const globalSocket = window.chatSocket ? window.chatSocket.socket : null;
            if (globalSocket) {
                globalSocket.emit('call_answered', {
                    roomId: data.roomId,
                    receiverId: data.receiverId
                });
                console.log('Call answered sent to Socket.IO:', data);
            } else {
                console.error('Socket not connected');
            }
        }

        function sendCallDeclinedToSocket(data) {
            const globalSocket = window.chatSocket ? window.chatSocket.socket : null;
            if (globalSocket) {
                globalSocket.emit('call_declined', {
                    roomId: data.roomId,
                    receiverId: data.receiverId
                });
                console.log('Call declined sent to Socket.IO:', data);
            } else {
                console.error('Socket not connected');
            }
        }

        function sendCallEndedToSocket(data) {
            const globalSocket = window.chatSocket ? window.chatSocket.socket : null;
            if (globalSocket) {
                globalSocket.emit('call_ended', {
                    roomId: data.roomId,
                    endedBy: data.endedBy
                });
                console.log('Call ended sent to Socket.IO:', data);
            } else {
                console.error('Socket not connected');
            }
        }

    </script>
</div>
