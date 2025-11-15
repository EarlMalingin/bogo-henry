<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use App\Models\Message;
use App\Models\Tutor;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentChat extends Component
{
    use WithFileUploads;

    public $selectedTutorId = null;
    public $message = '';
    public $file;
    public $conversations = [];
    public $messages = [];
    public $searchTerm = '';
    public $isTyping = false;

    protected $listeners = [
        'echo:chat,MessageSent' => 'refreshMessages',
        'echo:chat,Typing' => 'handleTyping',
        'socket:new-message' => 'handleSocketMessage',
        'socket:typing' => 'handleSocketTyping',
        'socket:message-read' => 'handleSocketReadReceipt'
    ];

    public function mount()
    {
        // Check if tutor_id is provided in the URL query parameter before loading conversations
        $tutorId = request()->query('tutor_id');
        
        $this->loadConversations($tutorId);
        
        // Select the tutor if provided
        if ($tutorId) {
            $tutor = Tutor::find($tutorId);
            if ($tutor) {
                $this->selectTutor($tutorId);
            }
        }
    }

    public function loadConversations($additionalTutorId = null)
    {
        $studentId = Auth::guard('student')->id();
        
        // Get all tutors that the student has sessions with or has messaged
        $tutors = Tutor::whereHas('sessions', function($query) use ($studentId) {
            $query->where('student_id', $studentId);
        })->orWhereHas('messages', function($query) use ($studentId) {
            $query->where('receiver_id', $studentId)
                  ->where('receiver_type', 'student');
        })->orWhereHas('messages', function($query) use ($studentId) {
            $query->where('sender_id', $studentId)
                  ->where('sender_type', 'student');
        })->get();

        // If a tutor ID is provided via query parameter, ensure it's included even if no conversation exists
        if ($additionalTutorId) {
            $additionalTutor = Tutor::find($additionalTutorId);
            if ($additionalTutor && !$tutors->contains('id', $additionalTutorId)) {
                $tutors->push($additionalTutor);
            }
        }

        $this->conversations = $tutors->map(function($tutor) use ($studentId) {
            $lastMessage = Message::betweenUsers($studentId, 'student', $tutor->id, 'tutor')
                ->latest()
                ->first();

            $unreadCount = Message::betweenUsers($studentId, 'student', $tutor->id, 'tutor')
                ->where('receiver_id', $studentId)
                ->where('receiver_type', 'student')
                ->where('is_read', false)
                ->count();

            return [
                'id' => $tutor->id,
                'name' => $tutor->getFullName(),
                'avatar' => $tutor->getAvatar(),
                'has_profile_picture' => $tutor->profile_picture ? true : false,
                'last_message' => $lastMessage ? $lastMessage->message : 'No messages yet',
                'last_message_time' => $lastMessage ? $lastMessage->formatted_time : '',
                'unread_count' => $unreadCount,
                'online' => true // You can implement real online status later
            ];
        })->toArray();
    }

    public function selectTutor($tutorId)
    {
        $this->selectedTutorId = $tutorId;
        $this->loadMessages();
        $this->markMessagesAsRead();
    }

    public function loadMessages()
    {
        if (!$this->selectedTutorId) return;

        $studentId = Auth::guard('student')->id();
        $currentStudent = Auth::guard('student')->user();
        
        $this->messages = Message::betweenUsers($studentId, 'student', $this->selectedTutorId, 'tutor')
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function($message) use ($currentStudent) {
                $fileUrl = null;
                if ($message->file_path) {
                    // Use the current request URL to generate the correct file URL
                    $baseUrl = request()->getSchemeAndHttpHost();
                    $fileUrl = $baseUrl . '/storage/' . $message->file_path;
                }
                
                // Determine if this message is from the current user (student)
                $isFromCurrentUser = $message->sender_type === 'student';
                
                // For display purposes, always show the current user's profile picture for their own messages
                // and the chat mate's profile picture for messages from the chat mate
                if ($isFromCurrentUser) {
                    // Message from current student - show student's profile picture
                    $displayAvatar = $currentStudent->profile_picture ? 
                        asset('storage/' . $currentStudent->profile_picture) : 
                        $currentStudent->getInitials();
                    $displayHasProfilePicture = $currentStudent->profile_picture ? true : false;
                } else {
                    // Message from tutor - show tutor's profile picture
                    // Make sure we're getting the actual tutor data
                    $tutor = $message->sender;
                    
                    // Debug: Check what's in the tutor sender object
                    \Log::info('Tutor sender data:', [
                        'tutor_id' => $tutor ? $tutor->id : 'null',
                        'tutor_name' => $tutor ? $tutor->getFullName() : 'null',
                        'profile_picture' => $tutor ? $tutor->profile_picture : 'null',
                        'getAvatar_result' => $tutor ? $tutor->getAvatar() : 'null'
                    ]);
                    
                    if ($tutor && $tutor->profile_picture) {
                        $displayAvatar = route('tutor.profile.picture.view', $tutor->id);
                        $displayHasProfilePicture = true;
                    } else {
                        $displayAvatar = $tutor ? $tutor->getInitials() : 'T';
                        $displayHasProfilePicture = false;
                    }
                }
                
                // Log if sender is null for debugging
                if (!$message->sender) {
                    \Log::warning('Message sender is null', [
                        'message_id' => $message->id,
                        'sender_id' => $message->sender_id,
                        'sender_type' => $message->sender_type
                    ]);
                }
                
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'sender_type' => $message->sender_type,
                    'sender_name' => $message->sender ? $message->sender->getFullName() : 'Unknown User',
                    'sender_avatar' => $message->sender ? $message->sender->getAvatar() : 'Unknown',
                    'sender_has_profile_picture' => $message->sender && $message->sender->profile_picture ? true : false,
                    'display_avatar' => $displayAvatar,
                    'display_has_profile_picture' => $displayHasProfilePicture,
                    'time' => $message->formatted_time,
                    'date' => $message->formatted_date,
                    'is_file' => $message->isFile(),
                    'file_name' => $message->file_name,
                    'file_url' => $fileUrl,
                    'is_image' => $message->isImage(),
                    'file_type' => $message->file_type
                ];
            })->toArray();
    }

    public function markMessagesAsRead()
    {
        if (!$this->selectedTutorId) return;

        $studentId = Auth::guard('student')->id();
        
        Message::where('sender_id', $this->selectedTutorId)
            ->where('sender_type', 'tutor')
            ->where('receiver_id', $studentId)
            ->where('receiver_type', 'student')
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);
    }

    public function startCall($callType, $receiverId, $receiverName)
    {
        // Debug: Log the call attempt
        \Log::info('StudentChat::startCall called with:', [
            'callType' => $callType,
            'receiverId' => $receiverId,
            'receiverName' => $receiverName,
            'selectedTutorId' => $this->selectedTutorId
        ]);
        
        if (!$receiverId || $receiverId === 'null') {
            \Log::error('StudentChat::startCall - Invalid receiverId:', ['receiverId' => $receiverId]);
            return;
        }
        
        // Validate callType
        if (!in_array($callType, ['video', 'voice'])) {
            \Log::error('StudentChat::startCall - Invalid callType:', ['callType' => $callType]);
            return;
        }
        
        // Get current student information
        $student = Auth::guard('student')->user();
        $studentId = $student->id;
        $studentName = $student->getFullName();
        $studentProfilePicture = $student->profile_picture;
        
        // Debug logging for student profile picture
        \Log::info('Student profile picture data:', [
            'studentId' => $studentId,
            'studentName' => $studentName,
            'profilePicture' => $studentProfilePicture,
            'profilePicturePath' => $studentProfilePicture ? asset('storage/' . $studentProfilePicture) : 'No picture',
            'receiverId' => $receiverId,
            'receiverName' => $receiverName
        ]);
        
        // Get receiver profile picture
        $receiverProfilePicture = null;
        try {
            $receiver = \App\Models\Tutor::find($receiverId);
            $receiverProfilePicture = $receiver ? $receiver->profile_picture : null;
        } catch (\Exception $e) {
            \Log::error('Error getting receiver profile picture:', ['receiverId' => $receiverId, 'error' => $e->getMessage()]);
        }
        
        // Create complete call data with all necessary information
        $callData = [
            'callType' => $callType,
            'callerId' => $studentId,
            'callerName' => $studentName,
            'callerType' => 'student',
            'callerProfilePicture' => $studentProfilePicture,
            'receiverId' => $receiverId,
            'receiverName' => $receiverName,
            'receiverType' => 'tutor',
            'receiverProfilePicture' => $receiverProfilePicture
        ];
        
        \Log::info('StudentChat::startCall - Complete call data:', $callData);
        
        // Dispatch to the CallManager component with complete data
        $this->dispatch('initiateCall', $callData);
        
        \Log::info('StudentChat::startCall - Event dispatched successfully');
    }

    public function sendMessage()
    {
        if (empty($this->message) && !$this->file) return;

        $studentId = Auth::guard('student')->id();
        
        $messageData = [
            'chat_room_id' => 1, // Default chat room ID
            'conversation_id' => 1, // Default conversation ID
            'sender_id' => $studentId,
            'sender_type' => 'student',
            'receiver_id' => $this->selectedTutorId,
            'receiver_type' => 'tutor',
            'message' => $this->message ?: '',
        ];

        // Handle file upload
        if ($this->file) {
            $path = $this->file->store('chat-files', 'public');
            $messageData['file_path'] = $path;
            $messageData['file_name'] = $this->file->getClientOriginalName();
            $messageData['file_type'] = $this->file->getMimeType();
        }

        Message::create($messageData);

        // Reset form
        $this->message = '';
        $this->file = null;
        
        // Reload messages and conversations
        $this->loadMessages();
        $this->loadConversations();

        // Broadcast to other users
        $this->dispatch('message-sent', tutorId: $this->selectedTutorId);
        
        // Dispatch a global event for real-time updates
        $this->dispatch('chat-message-sent', [
            'sender_id' => $studentId,
            'sender_type' => 'student',
            'receiver_id' => $this->selectedTutorId,
            'receiver_type' => 'tutor'
        ]);
    }

    public function updatedSearchTerm()
    {
        $this->loadConversations();
    }

    public function getFilteredConversationsProperty()
    {
        if (empty($this->searchTerm)) {
            return $this->conversations;
        }

        return collect($this->conversations)->filter(function($conversation) {
            return str_contains(strtolower($conversation['name']), strtolower($this->searchTerm)) ||
                   str_contains(strtolower($conversation['last_message']), strtolower($this->searchTerm));
        })->toArray();
    }

    #[On('message-sent')]
    public function refreshMessages($tutorId = null)
    {
        if ($tutorId && $tutorId == $this->selectedTutorId) {
            $this->loadMessages();
        }
        $this->loadConversations();
    }

    #[On('chat-message-sent')]
    public function handleGlobalMessage($data)
    {
        // Check if this message is relevant to the current user
        $studentId = Auth::guard('student')->id();
        
        if (($data['sender_id'] == $this->selectedTutorId && $data['sender_type'] == 'tutor' && $data['receiver_id'] == $studentId && $data['receiver_type'] == 'student') ||
            ($data['receiver_id'] == $this->selectedTutorId && $data['receiver_type'] == 'tutor' && $data['sender_id'] == $studentId && $data['sender_type'] == 'student')) {
            $this->loadMessages();
            $this->loadConversations();
        }
    }

    // Auto-refresh messages every 3 seconds when a tutor is selected
    public function getPollingProperty()
    {
        return $this->selectedTutorId ? 3000 : null;
    }

    // Socket event handlers
    public function handleSocketMessage($data)
    {
        $studentId = Auth::guard('student')->id();
        
        // Check if this message is relevant to the current user
        if (($data['sender_id'] == $this->selectedTutorId && $data['sender_type'] == 'tutor' && $data['receiver_id'] == $studentId && $data['receiver_type'] == 'student') ||
            ($data['receiver_id'] == $this->selectedTutorId && $data['receiver_type'] == 'tutor' && $data['sender_id'] == $studentId && $data['sender_type'] == 'student')) {
            $this->loadMessages();
            $this->loadConversations();
        }
    }

    public function handleSocketTyping($data)
    {
        $studentId = Auth::guard('student')->id();
        
        if ($data['sender_id'] == $this->selectedTutorId && $data['sender_type'] == 'tutor' && $data['receiver_id'] == $studentId && $data['receiver_type'] == 'student') {
            $this->isTyping = $data['isTyping'];
        }
    }

    public function handleSocketReadReceipt($data)
    {
        // Handle read receipts if needed
        // This can be used to update message read status
    }

    public function render()
    {
        return view('livewire.student-chat', [
            'filteredConversations' => $this->filteredConversations
        ]);
    }
}
