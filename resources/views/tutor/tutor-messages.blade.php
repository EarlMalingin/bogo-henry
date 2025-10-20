<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('style/dashboard.css') }}">
    <title>MentorHub - Messages</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            line-height: 1.6;
            color: #333;
            background: linear-gradient(rgba(255, 255, 255, 0.85), rgba(255, 255, 255, 0.85)), url('{{ asset('images/Uc-background.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Main Content */
        main {
            flex: 1;
            margin-top: 80px;
            padding: 2rem 1rem;
            max-width: 1200px;
            width: 100%;
            align-self: center;
        }

        .messaging-container {
            display: flex;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            height: 70vh;
            min-height: 500px;
        }

        /* Sidebar */
        .conversations-sidebar {
            width: 350px;
            border-right: 1px solid #e0e0e0;
            background: #f8f9fa;
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, #4a90e2, #5637d9);
            color: white;
            margin-bottom: 0;
        }

        .sidebar-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.7rem;
        }

        .search-box {
            position: relative;
            margin-bottom: 0.3rem;
        }

        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: none;
            border-radius: 25px;
            background: rgba(255,255,255,0.9);
            font-size: 0.9rem;
            outline: none;
        }

        .search-icon {
            position: absolute;
            left: 0.8rem;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }

        .conversations-list {
            flex: 1;
            overflow-y: auto;
            padding: 0.3rem 0 0 0;
        }

        .conversation-item {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            cursor: pointer;
            transition: background-color 0.3s;
            border-bottom: 1px solid #eee;
        }

        .conversation-item:hover {
            background-color: #e9ecef;
        }

        .conversation-item.active {
            background-color: #4a90e2;
            color: white;
        }

        .conversation-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: #4a90e2;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin-right: 1rem;
            font-size: 1.1rem;
        }

        .conversation-info {
            flex: 1;
            min-width: 0;
        }

        .conversation-name {
            font-weight: 600;
            margin-bottom: 0.3rem;
            font-size: 0.95rem;
        }

        .conversation-preview {
            font-size: 0.85rem;
            color: #666;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .conversation-item.active .conversation-preview {
            color: rgba(255,255,255,0.8);
        }

        .conversation-meta {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.3rem;
        }

        .conversation-time {
            font-size: 0.75rem;
            color: #999;
        }

        .conversation-item.active .conversation-time {
            color: rgba(255,255,255,0.7);
        }

        .unread-badge {
            background: #ff4757;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: bold;
        }

        /* Chat Area */
        .chat-area {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .chat-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e0e0e0;
            background: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .chat-user-info {
            display: flex;
            align-items: center;
        }

        .chat-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: #4a90e2;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin-right: 1rem;
        }

        .chat-user-details h3 {
            font-size: 1.1rem;
            margin-bottom: 0.2rem;
        }

        .chat-user-status {
            font-size: 0.85rem;
            color: #28a745;
            font-weight: 500;
        }

        .chat-actions {
            display: flex;
            gap: 0.5rem;
        }

        .action-btn {
            background: none;
            border: 1px solid #ddd;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 1.1rem;
        }

        .action-btn:hover {
            background: #f8f9fa;
            border-color: #4a90e2;
            color: #4a90e2;
        }

        .messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
            background: #f8f9fa;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .message {
            display: flex;
            align-items: flex-end;
            gap: 0.5rem;
            max-width: 70%;
        }

        .message.sent {
            align-self: flex-end;
            flex-direction: row-reverse;
        }

        .message-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #4a90e2;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .message-content {
            background: white;
            padding: 0.8rem 1rem;
            border-radius: 18px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            position: relative;
        }

        .message.sent .message-content {
            background: #4a90e2;
            color: white;
        }

        .message-text {
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .message-time {
            font-size: 0.75rem;
            color: #999;
            margin-top: 0.3rem;
        }

        .message.sent .message-time {
            color: rgba(255,255,255,0.8);
            text-align: right;
        }

        /* Message Input */
        .message-input-area {
            padding: 1.5rem;
            background: white;
            border-top: 1px solid #e0e0e0;
        }

        .message-input-container {
            display: flex;
            align-items: flex-end;
            gap: 1rem;
            background: #f8f9fa;
            border: 2px solid #e0e0e0;
            border-radius: 25px;
            padding: 0.5rem;
            transition: border-color 0.3s;
        }

        .message-input-container:focus-within {
            border-color: #4a90e2;
        }

        .message-input {
            flex: 1;
            border: none;
            background: none;
            padding: 0.8rem 1rem;
            font-size: 0.9rem;
            outline: none;
            resize: none;
            max-height: 100px;
            min-height: 20px;
        }

        .input-actions {
            display: flex;
            gap: 0.5rem;
        }

        .input-btn {
            background: none;
            border: none;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            color: #666;
        }

        .input-btn:hover {
            background: #e9ecef;
            color: #4a90e2;
        }

        .send-btn {
            background: #4a90e2;
            color: white;
        }

        .send-btn:hover {
            background: #3a7ccc;
        }

        /* Empty State */
        .empty-chat {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #666;
        }

        .empty-chat-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #ddd;
        }

        .empty-chat h3 {
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
            color: #333;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .messaging-container {
                flex-direction: column;
                height: auto;
                min-height: 70vh;
            }

            .conversations-sidebar {
                width: 100%;
                height: 200px;
            }

            .conversations-list {
                flex-direction: row;
                overflow-x: auto;
                overflow-y: hidden;
                padding: 0.5rem;
            }

            .conversation-item {
                min-width: 200px;
                border-right: 1px solid #eee;
                border-bottom: none;
            }

            .chat-area {
                height: 400px;
            }
        }

        /* Footer */
        footer {
            margin-top: 2rem;
            background-color: #333;
            color: white;
            padding: 1.5rem 0;
            width: 100%;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .footer-links {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: center;
        }

        .footer-links a {
            color: #ccc;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: white;
        }

        .copyright {
            font-size: 0.9rem;
            color: #aaa;
            text-align: center;
            width: 100%;
            margin-top: 0.5rem;
        }

        .profile-icon {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #4a90e2;
            color: white;
            font-weight: bold;
            cursor: pointer;
            z-index: 1000;
            transition: transform 0.2s cubic-bezier(0.4,0,0.2,1), box-shadow 0.2s cubic-bezier(0.4,0,0.2,1);
            font-size: 1.1rem;
            overflow: hidden;
        }
        .profile-icon:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 16px rgba(74, 144, 226, 0.15);
        }
        .profile-icon-img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="navbar">
            <a href="#" class="logo">
                <img src="{{asset('images/MentorHub.png')}}" alt="UCTutor Logo" class="logo-img">
                <span>MentorHub</span>
            </a>
            <button class="menu-toggle" id="menu-toggle">☰</button>
            <nav class="nav-links" id="nav-links">
                <a href="{{ route('tutor.dashboard') }}">Dashboard</a>
                <a href="{{ route('tutor.bookings.index') }}">My Bookings</a>
                <a href="#">Students</a>
                <a href="#">Schedule</a>
                
            </nav>
            <div class="profile-dropdown-container" style="position: relative;">
                @auth('tutor')
                    <div class="profile-icon" id="profile-icon">
                        @if(Auth::guard('tutor')->user()->profile_picture)
                            <img src="{{ asset('storage/' . Auth::guard('tutor')->user()->profile_picture) }}?{{ time() }}" alt="Profile Picture" class="profile-icon-img">
                        @else
                            {{ substr(Auth::guard('tutor')->user()->first_name, 0, 1) }}{{ substr(Auth::guard('tutor')->user()->last_name, 0, 1) }}
                        @endif
                    </div>
                    <div class="dropdown-menu" id="dropdown-menu">
                        <a href="{{ route('tutor.profile.edit') }}">My Profile</a>
                        <a href="#">Settings</a>
                        <a href="#">Help Center</a>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" method="POST" action="{{ route('tutor.logout') }}" style="display: none;">
                            @csrf
                        </form>
                    </div>
                @else
                    <div class="profile-icon" id="profile-icon">
                        <a href="{{ route('login.tutor') }}" class="login-link">Login</a>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <div class="messaging-container">
            <!-- Conversations Sidebar -->
            <div class="conversations-sidebar">
                <div class="sidebar-header">
                    <div class="sidebar-title">Messages</div>
                    <div class="search-box">
                        <input type="text" class="search-input" placeholder="Search Students...">
                        <span class="search-icon">🔍</span>
                    </div>
                </div>
                <div class="conversations-list" id="conversations-list">
                    <!-- Sample conversations -->
                    <div class="conversation-item active" data-student-id="1">
                        <div class="conversation-avatar">JD</div>
                        <div class="conversation-info">
                            <div class="conversation-name">John Doe</div>
                            <div class="conversation-preview">Hi! I have a question about calculus...</div>
                        </div>
                        <div class="conversation-meta">
                            <div class="conversation-time">2:30 PM</div>
                            <div class="unread-badge">2</div>
                        </div>
                    </div>
                    
                    <div class="conversation-item" data-student-id="2">
                        <div class="conversation-avatar">MS</div>
                        <div class="conversation-info">
                            <div class="conversation-name">Maria Smith</div>
                            <div class="conversation-preview">Can we reschedule our session for tomorrow?</div>
                        </div>
                        <div class="conversation-meta">
                            <div class="conversation-time">1:15 PM</div>
                        </div>
                    </div>
                    
                    <div class="conversation-item" data-student-id="3">
                        <div class="conversation-avatar">AJ</div>
                        <div class="conversation-info">
                            <div class="conversation-name">Alex Johnson</div>
                            <div class="conversation-preview">Thank you for the help with physics!</div>
                        </div>
                        <div class="conversation-meta">
                            <div class="conversation-time">Yesterday</div>
                        </div>
                    </div>
                    
                    <div class="conversation-item" data-student-id="4">
                        <div class="conversation-avatar">EB</div>
                        <div class="conversation-info">
                            <div class="conversation-name">Emma Brown</div>
                            <div class="conversation-preview">I'm struggling with the chemistry assignment...</div>
                        </div>
                        <div class="conversation-meta">
                            <div class="conversation-time">Monday</div>
                            <div class="unread-badge">1</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat Area -->
            <div class="chat-area">
                <div class="chat-header">
                    <div class="chat-user-info">
                        <div class="chat-avatar" id="chat-avatar">JD</div>
                        <div class="chat-user-details">
                            <h3 id="chat-user-name">John Doe</h3>
                            <div class="chat-user-status">Online • Mathematics Student</div>
                        </div>
                    </div>
                    <div class="chat-actions">
                        <button class="action-btn" title="Video Call">📹</button>
                        <button class="action-btn" title="Voice Call">📞</button>
                        <button class="action-btn" title="More Options">⋯</button>
                    </div>
                </div>

                <div class="messages-container" id="messages-container">
                    <!-- Sample messages -->
                    <div class="message">
                        <div class="message-avatar">JD</div>
                        <div class="message-content">
                            <div class="message-text">Hi! I have a question about calculus. Can you help me understand derivatives?</div>
                            <div class="message-time">2:15 PM</div>
                        </div>
                    </div>

                    <div class="message sent">
                        <div class="message-avatar">SJ</div>
                        <div class="message-content">
                            <div class="message-text">Of course! I'd be happy to help you with derivatives. What specific concept are you having trouble with?</div>
                            <div class="message-time">2:18 PM</div>
                        </div>
                    </div>

                    <div class="message">
                        <div class="message-avatar">JD</div>
                        <div class="message-content">
                            <div class="message-text">I'm confused about the chain rule. When do I use it and how?</div>
                            <div class="message-time">2:20 PM</div>
                        </div>
                    </div>

                    <div class="message sent">
                        <div class="message-avatar">SJ</div>
                        <div class="message-content">
                            <div class="message-text">Great question! The chain rule is used when you have a function inside another function. Let me explain with an example...</div>
                            <div class="message-time">2:25 PM</div>
                        </div>
                    </div>

                    <div class="message">
                        <div class="message-avatar">JD</div>
                        <div class="message-content">
                            <div class="message-text">That makes so much sense! Thank you for explaining it so clearly.</div>
                            <div class="message-time">2:30 PM</div>
                        </div>
                    </div>
                </div>

                <div class="message-input-area">
                    <div class="message-input-container">
                        <textarea class="message-input" placeholder="Type your message..." rows="1" id="message-input"></textarea>
                        <div class="input-actions">
                            <button class="input-btn" title="Attach File">📎</button>
                            <button class="input-btn send-btn" title="Send Message" id="send-btn">➤</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">FAQ</a>
                <a href="#">Contact</a>
            </div>
            <div class="copyright">
                &copy; 2025 MentorHub. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const conversationItems = document.querySelectorAll('.conversation-item');
            const messagesContainer = document.getElementById('messages-container');
            const messageInput = document.getElementById('message-input');
            const sendBtn = document.getElementById('send-btn');
            const chatUserName = document.getElementById('chat-user-name');
            const chatAvatar = document.getElementById('chat-avatar');
            const searchInput = document.querySelector('.search-input');
            const menuToggle = document.getElementById('menu-toggle');
            const navLinks = document.getElementById('nav-links');

            // Sample conversation data
            const conversations = {
                '1': {
                    name: 'John Doe',
                    avatar: 'JD',
                    status: 'Online • Mathematics Student',
                    messages: [
                        { sender: 'student', text: 'Hi! I have a question about calculus. Can you help me understand derivatives?', time: '2:15 PM' },
                        { sender: 'tutor', text: 'Of course! I\'d be happy to help you with derivatives. What specific concept are you having trouble with?', time: '2:18 PM' },
                        { sender: 'student', text: 'I\'m confused about the chain rule. When do I use it and how?', time: '2:20 PM' },
                        { sender: 'tutor', text: 'Great question! The chain rule is used when you have a function inside another function. Let me explain with an example...', time: '2:25 PM' },
                        { sender: 'student', text: 'That makes so much sense! Thank you for explaining it so clearly.', time: '2:30 PM' }
                    ]
                },
                '2': {
                    name: 'Maria Smith',
                    avatar: 'MS',
                    status: 'Online • Physics Student',
                    messages: [
                        { sender: 'student', text: 'Can we reschedule our session for tomorrow? I have a conflict today.', time: '1:10 PM' },
                        { sender: 'tutor', text: 'No problem! What time works better for you tomorrow?', time: '1:12 PM' },
                        { sender: 'student', text: 'How about 3 PM?', time: '1:15 PM' }
                    ]
                },
                '3': {
                    name: 'Alex Johnson',
                    avatar: 'AJ',
                    status: 'Offline • Chemistry Student',
                    messages: [
                        { sender: 'student', text: 'Thank you for the help with physics! The concepts are much clearer now.', time: 'Yesterday' },
                        { sender: 'tutor', text: 'You\'re very welcome! I\'m glad I could help. Keep practicing those problems!', time: 'Yesterday' }
                    ]
                },
                '4': {
                    name: 'Emma Brown',
                    avatar: 'EB',
                    status: 'Online • Chemistry Student',
                    messages: [
                        { sender: 'student', text: 'I\'m struggling with the chemistry assignment. Can you help me with question 5?', time: 'Monday' },
                        { sender: 'tutor', text: 'Absolutely! Let\'s work through it together. What\'s the question about?', time: 'Monday' }
                    ]
                }
            };

            // Handle conversation selection
            conversationItems.forEach(item => {
                item.addEventListener('click', function() {
                    // Remove active class from all items
                    conversationItems.forEach(i => i.classList.remove('active'));
                    // Add active class to clicked item
                    this.classList.add('active');
                    
                    // Remove unread badge
                    const badge = this.querySelector('.unread-badge');
                    if (badge) {
                        badge.remove();
                    }

                    // Load conversation
                    const studentId = this.dataset.studentId;
                    loadConversation(studentId);
                });
            });

            // Load conversation function
            function loadConversation(studentId) {
                const conversation = conversations[studentId];
                if (!conversation) return;

                // Update chat header
                chatUserName.textContent = conversation.name;
                chatAvatar.textContent = conversation.avatar;
                document.querySelector('.chat-user-status').textContent = conversation.status;

                // Clear and load messages
                messagesContainer.innerHTML = '';
                conversation.messages.forEach(message => {
                    addMessage(message.text, message.sender, message.time, conversation.avatar);
                });

                // Scroll to bottom
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            // Add message function
            function addMessage(text, sender, time, studentAvatar) {
                const messageDiv = document.createElement('div');
                messageDiv.className = `message ${sender === 'tutor' ? 'sent' : ''}`;
                
                const avatar = sender === 'tutor' ? 'SJ' : studentAvatar;
                
                messageDiv.innerHTML = `
                    <div class="message-avatar">${avatar}</div>
                    <div class="message-content">
                        <div class="message-text">${text}</div>
                        <div class="message-time">${time}</div>
                    </div>
                `;
                
                messagesContainer.appendChild(messageDiv);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            // Send message function
            function sendMessage() {
                const text = messageInput.value.trim();
                if (!text) return;

                const currentTime = new Date().toLocaleTimeString('en-US', { 
                    hour: 'numeric', 
                    minute: '2-digit',
                    hour12: true 
                });

                addMessage(text, 'tutor', currentTime, 'JD');
                messageInput.value = '';
                
                // Auto-resize textarea
                messageInput.style.height = 'auto';
                
                // Simulate student response after 2 seconds
                setTimeout(() => {
                    const responses = [
                        "Thank you! That helps a lot.",
                        "I understand now. Can you explain the next part?",
                        "That makes sense! I'll practice this.",
                        "Got it! Thanks for the explanation.",
                        "Perfect! I think I've got it now."
                    ];
                    const randomResponse = responses[Math.floor(Math.random() * responses.length)];
                    addMessage(randomResponse, 'student', currentTime, 'JD');
                }, 2000);
            }

            // Send button click
            sendBtn.addEventListener('click', sendMessage);

            // Enter key to send (Shift+Enter for new line)
            messageInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendMessage();
                }
            });

            // Auto-resize textarea
            messageInput.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 100) + 'px';
            });

            // Search functionality
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                conversationItems.forEach(item => {
                    const name = item.querySelector('.conversation-name').textContent.toLowerCase();
                    const preview = item.querySelector('.conversation-preview').textContent.toLowerCase();
                    
                    if (name.includes(searchTerm) || preview.includes(searchTerm)) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });

            // Load default conversation
            loadConversation('1');

            // Menu toggle functionality
            menuToggle.addEventListener('click', function() {
                navLinks.classList.toggle('active');
            });

            const profileIcon = document.getElementById('profile-icon');
            const dropdownMenu = document.getElementById('dropdown-menu');
            if (profileIcon) {
                profileIcon.addEventListener('click', function(e) {
                    e.stopPropagation();
                    if (dropdownMenu) {
                        dropdownMenu.classList.toggle('active');
                    }
                });
            }
            document.addEventListener('click', function() {
                if (dropdownMenu && dropdownMenu.classList.contains('active')) {
                    dropdownMenu.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html> 