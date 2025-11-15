<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use App\Models\Message;
use App\Models\Student;
use App\Models\Tutor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TutorChat extends Component
{
    use WithFileUploads;

    public $selectedStudentId = null;
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
        // Check if student_id is provided in the URL query parameter
        $studentId = request()->query('student_id');
        
        $this->loadConversations();
        
        // Select the student if provided, otherwise use first conversation
        if ($studentId) {
            $student = Student::find($studentId);
            if ($student) {
                $this->selectStudent($studentId);
            }
        } elseif (!empty($this->conversations)) {
            $this->selectStudent($this->conversations[0]['id']);
        }
    }

    public function loadConversations()
    {
        $tutorId = Auth::guard('tutor')->id();
        
        // Get all students that the tutor has sessions with or has messaged
        $students = Student::whereHas('sessions', function($query) use ($tutorId) {
            $query->where('tutor_id', $tutorId);
        })->orWhereHas('messages', function($query) use ($tutorId) {
            $query->where('receiver_id', $tutorId)
                  ->where('receiver_type', 'tutor');
        })->orWhereHas('messages', function($query) use ($tutorId) {
            $query->where('sender_id', $tutorId)
                  ->where('sender_type', 'tutor');
        })->get();

        $this->conversations = $students->map(function($student) use ($tutorId) {
            $lastMessage = Message::betweenUsers($tutorId, 'tutor', $student->id, 'student')
                ->latest()
                ->first();

            $unreadCount = Message::betweenUsers($tutorId, 'tutor', $student->id, 'student')
                ->where('receiver_id', $tutorId)
                ->where('receiver_type', 'tutor')
                ->where('is_read', false)
                ->count();

            // Always use initials instead of profile pictures
            $avatar = $student->getInitials();
            $hasProfilePicture = false;

            return [
                'id' => $student->id,
                'name' => $student->getFullName(),
                'avatar' => $avatar,
                'has_profile_picture' => $hasProfilePicture,
                'last_message' => $lastMessage ? $lastMessage->message : 'No messages yet',
                'last_message_time' => $lastMessage ? $lastMessage->formatted_time : '',
                'unread_count' => $unreadCount,
                'online' => true // You can implement real online status later
            ];
        })->toArray();
    }

    public function selectStudent($studentId)
    {
        $this->selectedStudentId = $studentId;
        $this->loadMessages();
        $this->markMessagesAsRead();
    }

    public function loadMessages()
    {
        if (!$this->selectedStudentId) return;

        $tutorId = Auth::guard('tutor')->id();
        $currentTutor = Auth::guard('tutor')->user();
        
        $this->messages = Message::betweenUsers($tutorId, 'tutor', $this->selectedStudentId, 'student')
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function($message) use ($currentTutor) {
                $fileUrl = null;
                if ($message->file_path) {
                    // Use the current request URL to generate the correct file URL
                    $baseUrl = request()->getSchemeAndHttpHost();
                    $fileUrl = $baseUrl . '/storage/' . $message->file_path;
                }
                
                // Determine if this message is from the current user (tutor)
                $isFromCurrentUser = $message->sender_type === 'tutor';
                
                // Always use initials instead of profile pictures
                if ($isFromCurrentUser) {
                    // Message from current tutor - show tutor's initials
                    $displayAvatar = $currentTutor->getInitials();
                    $displayHasProfilePicture = false;
                } else {
                    // Message from student - show student's initials
                    $studentId = $message->sender_id;
                    $student = Student::find($studentId);
                    $displayAvatar = $student ? $student->getInitials() : 'S';
                    $displayHasProfilePicture = false;
                }
                
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'sender_type' => $message->sender_type,
                    'sender_name' => $message->sender ? $message->sender->getFullName() : 'Unknown User',
                    'sender_avatar' => $message->sender ? $message->sender->getInitials() : 'Unknown',
                    'sender_has_profile_picture' => false,
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
        if (!$this->selectedStudentId) return;

        $tutorId = Auth::guard('tutor')->id();
        
        Message::where('sender_id', $this->selectedStudentId)
            ->where('sender_type', 'student')
            ->where('receiver_id', $tutorId)
            ->where('receiver_type', 'tutor')
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);
    }

    public function startCall($callType, $receiverId, $receiverName)
    {
        if (!$receiverId || $receiverId === 'null') {
            return;
        }
        
        // Validate callType
        if (!in_array($callType, ['video', 'voice'])) {
            return;
        }
        
        // Get current tutor information
        $tutor = Auth::guard('tutor')->user();
        $tutorId = $tutor->id;
        $tutorName = $tutor->getFullName();
        $tutorProfilePicture = $tutor->profile_picture;
        
        // Get receiver profile picture
        $receiverProfilePicture = null;
        try {
            $receiver = Student::find($receiverId);
            $receiverProfilePicture = $receiver ? $receiver->profile_picture : null;
        } catch (\Exception $e) {
            \Log::error('Error getting receiver profile picture:', ['receiverId' => $receiverId, 'error' => $e->getMessage()]);
        }
        
        // Create complete call data with all necessary information
        $callData = [
            'callType' => $callType,
            'callerId' => $tutorId,
            'callerName' => $tutorName,
            'callerType' => 'tutor',
            'callerProfilePicture' => $tutorProfilePicture,
            'receiverId' => $receiverId,
            'receiverName' => $receiverName,
            'receiverType' => 'student',
            'receiverProfilePicture' => $receiverProfilePicture
        ];
        
        // Dispatch to the CallManager component with complete data
        $this->dispatch('initiateCall', $callData);
    }

    public function sendMessage()
    {
        if (empty($this->message) && !$this->file) return;

        $tutorId = Auth::guard('tutor')->id();
        
        $messageData = [
            'chat_room_id' => 1, // Default chat room ID
            'conversation_id' => 1, // Default conversation ID
            'sender_id' => $tutorId,
            'sender_type' => 'tutor',
            'receiver_id' => $this->selectedStudentId,
            'receiver_type' => 'student',
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
        $this->dispatch('message-sent', studentId: $this->selectedStudentId);
        
        // Dispatch a global event for real-time updates
        $this->dispatch('chat-message-sent', [
            'sender_id' => $tutorId,
            'sender_type' => 'tutor',
            'receiver_id' => $this->selectedStudentId,
            'receiver_type' => 'student'
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
    public function refreshMessages($studentId = null)
    {
        if ($studentId && $studentId == $this->selectedStudentId) {
            $this->loadMessages();
        }
        $this->loadConversations();
    }

    #[On('chat-message-sent')]
    public function handleGlobalMessage($data)
    {
        // Check if this message is relevant to the current user
        $tutorId = Auth::guard('tutor')->id();
        
        if (($data['sender_id'] == $this->selectedStudentId && $data['sender_type'] == 'student' && $data['receiver_id'] == $tutorId && $data['receiver_type'] == 'tutor') ||
            ($data['receiver_id'] == $this->selectedStudentId && $data['receiver_type'] == 'student' && $data['sender_id'] == $tutorId && $data['sender_type'] == 'tutor')) {
            $this->loadMessages();
            $this->loadConversations();
        }
    }

    // Auto-refresh messages every 3 seconds when a student is selected
    public function getPollingProperty()
    {
        return $this->selectedStudentId ? 3000 : null;
    }

    // Socket event handlers
    public function handleSocketMessage($data)
    {
        $tutorId = Auth::guard('tutor')->id();
        
        // Check if this message is relevant to the current user
        if (($data['sender_id'] == $this->selectedStudentId && $data['sender_type'] == 'student' && $data['receiver_id'] == $tutorId && $data['receiver_type'] == 'tutor') ||
            ($data['receiver_id'] == $this->selectedStudentId && $data['receiver_type'] == 'student' && $data['sender_id'] == $tutorId && $data['sender_type'] == 'tutor')) {
            $this->loadMessages();
            $this->loadConversations();
        }
    }

    public function handleSocketTyping($data)
    {
        $tutorId = Auth::guard('tutor')->id();
        
        if ($data['sender_id'] == $this->selectedStudentId && $data['sender_type'] == 'student' && $data['receiver_id'] == $tutorId && $data['receiver_type'] == 'tutor') {
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
        return view('livewire.tutor-chat', [
            'filteredConversations' => $this->filteredConversations
        ]);
    }
}
