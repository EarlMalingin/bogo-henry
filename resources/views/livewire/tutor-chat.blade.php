<div wire:poll.3s="loadMessages" wire:poll.5s="loadConversations">
    <div class="messaging-container">
        <!-- Conversations Sidebar -->
        <div class="conversations-sidebar">
            <div class="sidebar-header">
                <div class="sidebar-title">Messages</div>
                <div class="search-box">
                    <input type="text" wire:model.live="searchTerm" class="search-input" placeholder="Search Students...">
                    <span class="search-icon">🔍</span>
                </div>
            </div>
            <div class="conversations-list">
                @forelse($filteredConversations as $conversation)
                    <div class="conversation-item {{ $selectedStudentId == $conversation['id'] ? 'active' : '' }}" 
                         wire:click="selectStudent({{ $conversation['id'] }})">
                        <div class="conversation-avatar">
                            @if($conversation['has_profile_picture'])
                                <img src="{{ $conversation['avatar'] }}" alt="{{ $conversation['name'] }}" class="avatar-image">
                            @else
                                {{ $conversation['avatar'] }}
                            @endif
                        </div>

                        <div class="conversation-info">
                            <div class="conversation-name">{{ $conversation['name'] }}</div>
                            <div class="conversation-preview">{{ $conversation['last_message'] }}</div>
                        </div>
                        <div class="conversation-meta">
                            <div class="conversation-time">{{ $conversation['last_message_time'] }}</div>
                            @if($conversation['unread_count'] > 0)
                                <div class="unread-badge">{{ $conversation['unread_count'] }}</div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="empty-conversations">
                        <p>No conversations yet</p>
                        <p>Start a session with a student to begin messaging</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Chat Area -->
        <div class="chat-area">
            @if($selectedStudentId)
                @php
                    $selectedConversation = collect($conversations)->firstWhere('id', $selectedStudentId);
                @endphp
                <div class="chat-header">
                    <div class="chat-user-info">
                        <div class="chat-avatar">
                            @if($selectedConversation['has_profile_picture'] ?? false)
                                <img src="{{ $selectedConversation['avatar'] }}" alt="{{ $selectedConversation['name'] }}" class="avatar-image">
                            @else
                                {{ $selectedConversation['avatar'] ?? 'S' }}
                            @endif
                        </div>
                        <div class="chat-user-details">
                            <h3>{{ $selectedConversation['name'] ?? 'Student' }}</h3>
                            <div class="chat-user-status">
                                @if($selectedConversation['online'] ?? false)
                                    Online • Student
                                @else
                                    Offline • Student
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($selectedStudentId)
                        <div class="chat-actions">
                            <button class="action-btn" title="Video Call" wire:click="startCall('video', {{ $selectedStudentId }}, '{{ $selectedConversation['name'] ?? 'Student' }}')">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/>
                                </svg>
                            </button>
                            <button class="action-btn" title="Voice Call" wire:click="startCall('voice', {{ $selectedStudentId }}, '{{ $selectedConversation['name'] ?? 'Student' }}')">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                                </svg>
                            </button>
                        </div>
                    @endif
                </div>

                <div class="messages-container" id="messages-container">
                    @forelse($messages as $message)
                        <div class="message {{ $message['sender_type'] === 'tutor' ? 'sent' : '' }}">
                                                    <div class="message-avatar">
                            @if($message['display_has_profile_picture'])
                                <img src="{{ $message['display_avatar'] }}" alt="{{ $message['sender_name'] }}" class="avatar-image" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center; background-color: #4a90e2; color: white; font-weight: bold; border-radius: 50%;">{{ $message['sender_avatar'] }}</div>
                            @else
                                {{ $message['display_avatar'] }}
                            @endif
                        </div>
                            <div class="message-content">
                                @if($message['is_file'])
                                    @if($message['is_image'])
                                        <div class="message-file">
                                            <img src="{{ $message['file_url'] }}" alt="{{ $message['file_name'] }}" 
                                                 class="message-image" onclick="openImageModal('{{ $message['file_url'] }}')"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                            <div class="file-name" style="display: none;">{{ $message['file_name'] }}</div>
                                        </div>
                                    @else
                                        <div class="message-file">
                                            <div class="file-icon">📎</div>
                                            <div class="file-info">
                                                <div class="file-name">{{ $message['file_name'] }}</div>
                                                <a href="{{ $message['file_url'] }}" target="_blank" class="download-link">Download</a>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                                @if($message['message'])
                                    <div class="message-text">{{ $message['message'] }}</div>
                                @endif
                                <div class="message-time">{{ $message['time'] }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-messages">
                            <p>No messages yet</p>
                            <p>Start the conversation!</p>
                        </div>
                    @endforelse
                </div>

                <div class="message-input-area">
                    <div class="message-input-container">
                        <textarea wire:model="message" class="message-input" placeholder="Type your message..." 
                                  rows="1" wire:keydown.enter.prevent="sendMessage"></textarea>
                        <div class="input-actions">
                            <label for="file-upload-tutor" class="input-btn" title="Attach File">
                                📎
                                <input type="file" id="file-upload-tutor" wire:model="file" style="display: none;" 
                                       accept="image/*,.pdf,.doc,.docx,.txt">
                            </label>
                            @if($file)
                                <div class="file-preview">
                                    <span>{{ $file->getClientOriginalName() }}</span>
                                    <button wire:click="$set('file', null)" class="remove-file">×</button>
                                </div>
                            @endif
                            <button class="input-btn send-btn" title="Send Message" wire:click="sendMessage">
                                ➤
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <div class="empty-chat">
                    <div class="empty-chat-icon">💬</div>
                    <h3>Select a conversation</h3>
                    <p>Choose a student from the sidebar to start messaging</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Image Modal -->
    <div id="image-modal" class="image-modal" onclick="closeImageModal()">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <img id="modal-image" src="" alt="Full size image">
        </div>
    </div>



    <style>
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
            overflow: hidden;
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
            overflow: hidden;
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 50%;
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 1.2rem;
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }

        .action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .action-btn:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }

        .action-btn:hover::before {
            left: 100%;
        }

        .action-btn:active {
            transform: translateY(0) scale(0.98);
        }

        /* Specific button styles */
        .action-btn:first-child {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
        }

        .action-btn:first-child:hover {
            background: linear-gradient(135deg, #ee5a24 0%, #ff6b6b 100%);
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.4);
        }

        .action-btn:last-child {
            background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);
            box-shadow: 0 4px 15px rgba(78, 205, 196, 0.3);
        }

        .action-btn:last-child:hover {
            background: linear-gradient(135deg, #44a08d 0%, #4ecdc4 100%);
            box-shadow: 0 8px 25px rgba(78, 205, 196, 0.4);
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
            overflow: hidden;
        }

        .avatar-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
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

        /* File Messages */
        .message-file {
            margin-bottom: 0.5rem;
        }

        .message-image {
            max-width: 200px;
            max-height: 200px;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .message-image:hover {
            transform: scale(1.05);
        }

        .file-name {
            font-size: 0.8rem;
            color: #666;
            margin-top: 0.3rem;
        }

        .file-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .file-info {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }

        .download-link {
            color: #4a90e2;
            text-decoration: none;
            font-size: 0.8rem;
        }

        .download-link:hover {
            text-decoration: underline;
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
            align-items: center;
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

        .file-preview {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: #e9ecef;
            padding: 0.3rem 0.5rem;
            border-radius: 15px;
            font-size: 0.8rem;
        }

        .remove-file {
            background: none;
            border: none;
            color: #ff4757;
            cursor: pointer;
            font-size: 1.2rem;
            line-height: 1;
        }

        /* Empty States */
        .empty-conversations {
            padding: 2rem;
            text-align: center;
            color: #666;
        }

        .empty-messages {
            text-align: center;
            color: #666;
            padding: 2rem;
        }

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

        /* Image Modal */
        .image-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.8);
        }

        .modal-content {
            position: relative;
            margin: auto;
            padding: 20px;
            width: 80%;
            max-width: 800px;
            text-align: center;
            top: 50%;
            transform: translateY(-50%);
        }

        .close-modal {
            color: white;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            position: absolute;
            right: 20px;
            top: 10px;
        }

        .close-modal:hover {
            color: #ddd;
        }

        #modal-image {
            max-width: 100%;
            max-height: 80vh;
            border-radius: 8px;
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


    </style>

    <script>
        // Auto-scroll to bottom when new messages arrive
        document.addEventListener('livewire:init', () => {
            Livewire.on('message-sent', () => {
                setTimeout(() => {
                    const container = document.getElementById('messages-container');
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                    }
                }, 100);
            });

            // Auto-scroll on every Livewire update
            Livewire.hook('message.processed', () => {
                setTimeout(() => {
                    const container = document.getElementById('messages-container');
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                    }
                }, 100);
            });
        });

        // Image modal functions
        function openImageModal(imageUrl) {
            const modal = document.getElementById('image-modal');
            const modalImage = document.getElementById('modal-image');
            modalImage.src = imageUrl;
            modal.style.display = 'block';
        }

        function closeImageModal() {
            const modal = document.getElementById('image-modal');
            modal.style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('image-modal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }

        // Auto-resize textarea
        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.querySelector('.message-input');
            if (textarea) {
                textarea.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = Math.min(this.scrollHeight, 100) + 'px';
                });
            }
        });
    </script>

    <script>
        // Call button functions
        function startVideoCall(studentId, studentName) {
            console.log('Starting video call to:', studentId, studentName);
            // Find the Livewire component and call the method
            const component = document.querySelector('[wire\\:id]');
            if (component) {
                const wireId = component.getAttribute('wire:id');
                window.Livewire.find(wireId).call('startCall', 'video', studentId, studentName);
            } else {
                console.error('Livewire component not found');
            }
        }

        function startVoiceCall(studentId, studentName) {
            console.log('Starting voice call to:', studentId, studentName);
            // Find the Livewire component and call the method
            const component = document.querySelector('[wire\\:id]');
            if (component) {
                const wireId = component.getAttribute('wire:id');
                window.Livewire.find(wireId).call('startCall', 'voice', studentId, studentName);
            } else {
                console.error('Livewire component not found');
            }
        }
    </script>
</div> 