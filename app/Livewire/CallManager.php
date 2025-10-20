<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\WebRTCService;
use Illuminate\Support\Facades\Auth;

class CallManager extends Component
{
    public $isInCall = false;
    public $callType = null;
    public $roomId = null;
    public $callerId = null;
    public $receiverId = null;
    public $callerName = null;
    public $receiverName = null;
    public $callerType = null;
    public $isCaller = false;
    public $isReceiver = false;
    public $callerProfilePicture = null;
    public $receiverProfilePicture = null;

    protected $listeners = [
        'initiateCall' => 'handleInitiateCall',
        'callIncoming' => 'handleIncomingCall',
        'callAnswered' => 'handleCallAnswered',
        'callEnded' => 'handleCallEnded',
    ];

    public function mount()
    {
        // Initialize all properties to null/false to prevent any injection issues
        $this->isInCall = false;
        $this->callType = null;
        $this->roomId = null;
        $this->callerId = null;
        $this->receiverId = null;
        $this->callerName = null;
        $this->receiverName = null;
        $this->callerType = null;
        $this->isCaller = false;
        $this->isReceiver = false;
        $this->callerProfilePicture = null;
        $this->receiverProfilePicture = null;
    }

    public function handleInitiateCall($data)
    {
        // Debug: Log the received data
        \Log::info('CallManager::handleInitiateCall received data:', $data);
        
        $callType = $data['callType'] ?? null;
        $receiverId = $data['receiverId'] ?? null;
        $receiverName = $data['receiverName'] ?? null;

        if (!$receiverId || !$callType) {
            \Log::error('CallManager::handleInitiateCall - Missing required data:', [
                'callType' => $callType,
                'receiverId' => $receiverId,
                'receiverName' => $receiverName
            ]);
            return;
        }

        \Log::info('CallManager::handleInitiateCall - Processing call:', [
            'callType' => $callType,
            'receiverId' => $receiverId,
            'receiverName' => $receiverName
        ]);

        $this->callType = $callType;
        $this->receiverId = $receiverId;
        $this->receiverName = $receiverName;
        
        // Get current user info - use provided caller info if available, otherwise get from current user
        if (isset($data['callerId']) && isset($data['callerName']) && isset($data['callerType'])) {
            // Use provided caller information
            $this->callerId = $data['callerId'];
            $this->callerName = $data['callerName'];
            $this->callerType = $data['callerType'];
            $this->callerProfilePicture = $data['callerProfilePicture'] ?? null;
            $this->receiverProfilePicture = $data['receiverProfilePicture'] ?? null;
            
            // Additional debug logging
            \Log::info('CallManager setting caller profile picture:', [
                'callerId' => $this->callerId,
                'callerName' => $this->callerName,
                'callerType' => $this->callerType,
                'callerProfilePicture' => $this->callerProfilePicture,
                'callerProfilePicturePath' => $this->callerProfilePicture ? asset('storage/' . $this->callerProfilePicture) : 'No picture'
            ]);
            
            // Debug logging
            \Log::info('CallManager received profile data:', [
                'callerProfilePicture' => $this->callerProfilePicture,
                'receiverProfilePicture' => $this->receiverProfilePicture,
                'callerName' => $this->callerName,
                'receiverName' => $this->receiverName,
                'isCaller' => $this->isCaller,
                'callerProfilePicturePath' => $this->callerProfilePicture ? asset('storage/' . $this->callerProfilePicture) : 'No picture',
                'receiverProfilePicturePath' => $this->receiverProfilePicture ? asset('storage/' . $this->receiverProfilePicture) : 'No picture',
                'raw_data_received' => $data
            ]);
        } else {
            // Fallback to getting from current user (for backward compatibility)
            if (Auth::guard('student')->check()) {
                $user = Auth::guard('student')->user();
                $this->callerId = $user->id;
                $this->callerName = $user->getFullName();
                $this->callerType = 'student';
                $this->callerProfilePicture = $user->profile_picture;
            } elseif (Auth::guard('tutor')->check()) {
                $user = Auth::guard('tutor')->user();
                $this->callerId = $user->id;
                $this->callerName = $user->getFullName();
                $this->callerType = 'tutor';
                $this->callerProfilePicture = $user->profile_picture;
            } else {
                return;
            }
            
            // Get receiver profile picture (fallback)
            $this->receiverProfilePicture = $this->getUserProfilePicture($this->receiverId, $this->callerType === 'student' ? 'tutor' : 'student');
        }
        
        
        $this->isCaller = true;
        $this->isReceiver = false;
        
        // Generate room ID
        $webRTCService = new WebRTCService();
        $this->roomId = $webRTCService->generateRoomId();
        
        $this->isInCall = true;
        
        // Log call initiation
        $webRTCService->logCallActivity(
            $this->callerId, 
            $callType, 
            'call_initiated'
        );

        // Send call notification to the receiver through Socket.IO
        // This will be handled by the JavaScript to send to the actual receiver
        $callData = [
            'roomId' => $this->roomId,
            'callType' => $this->callType,
            'callerId' => $this->callerId,
            'callerName' => $this->callerName,
            'callerType' => $this->callerType,
            'receiverId' => $this->receiverId,
            'receiverName' => $this->receiverName,
            'receiverType' => $data['receiverType'] ?? $this->getReceiverType()
        ];
        
        \Log::info('CallManager::handleInitiateCall - Dispatching sendCallToReceiver:', $callData);
        
        // Debug: Log each field individually
        \Log::info('CallManager::handleInitiateCall - Field details:', [
            'roomId' => $this->roomId,
            'callType' => $this->callType,
            'callerId' => $this->callerId,
            'callerName' => $this->callerName,
            'receiverId' => $this->receiverId,
            'receiverName' => $this->receiverName,
            'receiverType' => $data['receiverType'] ?? $this->getReceiverType(),
            'callerType' => $this->callerType
        ]);
        
        // Debug: Log the exact data being dispatched
        $dispatchData = [
            'roomId' => $this->roomId,
            'callType' => $this->callType,
            'callerId' => $this->callerId,
            'callerName' => $this->callerName,
            'receiverId' => $this->receiverId,
            'receiverName' => $this->receiverName,
            'receiverType' => $data['receiverType'] ?? $this->getReceiverType(),
            'callerType' => $this->callerType
        ];
        
        \Log::info('CallManager::handleInitiateCall - Data being dispatched:', $dispatchData);
        
        // Test: Dispatch a simple event first to verify event system works
        $this->dispatch('testEvent', ['message' => 'Test event working']);
        
        // Dispatch to JavaScript to send through socket
        // Ensure all data is properly serialized for JavaScript
        $this->dispatch('sendCallToReceiver', $dispatchData);
        
        // Also try direct JavaScript method call as backup
        $this->dispatch('callInitiationDirect', $dispatchData);
        
        // Also dispatch to initialize WebRTC for the caller
        $this->dispatch('initializeWebRTC', [
            'roomId' => $this->roomId,
            'isCaller' => true,
            'callType' => $this->callType
        ]);
    }

    public function handleIncomingCall($data)
    {
        // This method handles when this user receives a call
        $this->roomId = $data['roomId'];
        $this->callType = $data['callType'];
        $this->callerId = $data['callerId'];
        $this->callerName = $data['callerName'];
        $this->receiverId = $data['receiverId'];
        
        // Get current user info and set profile pictures correctly
        if (Auth::guard('student')->check()) {
            $currentUser = Auth::guard('student')->user();
            $this->receiverId = $currentUser->id;
            $this->receiverName = $currentUser->getFullName();
            
            // For incoming calls, the current user (student) is the "caller" in terms of display
            // and the actual caller (tutor) is the "receiver" in terms of display
            $this->callerName = $currentUser->getFullName(); // Student's own name for local display
            $this->callerProfilePicture = $currentUser->profile_picture; // Student's own profile picture
            $this->receiverProfilePicture = $this->getUserProfilePicture($data['callerId'], 'tutor'); // Tutor's profile picture
            
            \Log::info('Student receiving call - profile pictures set:', [
                'studentProfilePicture' => $this->callerProfilePicture,
                'tutorProfilePicture' => $this->receiverProfilePicture,
                'studentName' => $this->callerName,
                'tutorName' => $data['callerName']
            ]);
        } elseif (Auth::guard('tutor')->check()) {
            $currentUser = Auth::guard('tutor')->user();
            $this->receiverId = $currentUser->id;
            $this->receiverName = $currentUser->getFullName();
            
            // For incoming calls, the current user (tutor) is the "caller" in terms of display
            // and the actual caller (student) is the "receiver" in terms of display
            $this->callerName = $currentUser->getFullName(); // Tutor's own name for local display
            $this->callerProfilePicture = $currentUser->profile_picture; // Tutor's own profile picture
            $this->receiverProfilePicture = $this->getUserProfilePicture($data['callerId'], 'student'); // Student's profile picture
            
            \Log::info('Tutor receiving call - profile pictures set:', [
                'tutorProfilePicture' => $this->callerProfilePicture,
                'studentProfilePicture' => $this->receiverProfilePicture,
                'tutorName' => $this->callerName,
                'studentName' => $data['callerName']
            ]);
        }
        
        $this->isCaller = false;
        $this->isReceiver = true;
        $this->isInCall = true;
    }

    public function answerCall()
    {
        if (!$this->isInCall || !$this->isReceiver) {
            return;
        }

        // Log call answered
        $webRTCService = new WebRTCService();
        $webRTCService->logCallActivity(
            $this->receiverId, 
            $this->callType, 
            'call_answered'
        );
        
        // Notify caller that call was answered
        $this->dispatch('callAnswered', [
            'roomId' => $this->roomId,
            'receiverId' => $this->receiverId
        ]);

        // Also dispatch to Socket.IO for cross-tab communication
        $this->dispatch('socketCallAnswered', [
            'roomId' => $this->roomId,
            'receiverId' => $this->receiverId
        ]);
    }

    public function declineCall()
    {
        if (!$this->isInCall || !$this->isReceiver) {
            return;
        }

        // Log call declined
        $webRTCService = new WebRTCService();
        $webRTCService->logCallActivity(
            $this->receiverId, 
            $this->callType, 
            'call_declined'
        );
        
        // Notify caller that call was declined
        $this->dispatch('callDeclined', [
            'roomId' => $this->roomId,
            'receiverId' => $this->receiverId
        ]);

        // Also dispatch to Socket.IO for cross-tab communication
        $this->dispatch('socketCallDeclined', [
            'roomId' => $this->roomId,
            'receiverId' => $this->receiverId
        ]);
        
        $this->resetCall();
    }

    public function handleCallAnswered($data)
    {
        // This method handles when the call is answered by the receiver
        if ($this->isInCall && $this->isCaller) {
            // Call was answered, initialize WebRTC
            $this->dispatch('initializeWebRTC', [
                'roomId' => $this->roomId,
                'isCaller' => true
            ]);
        }
    }

    public function handleCallEnded($data)
    {
        // This method handles when the call ends
        $this->resetCall();
    }

    public function endCall()
    {
        if ($this->isInCall) {
            // Get current user ID from appropriate guard
            $currentUserId = null;
            if (Auth::guard('student')->check()) {
                $currentUserId = Auth::guard('student')->id();
            } elseif (Auth::guard('tutor')->check()) {
                $currentUserId = Auth::guard('tutor')->id();
            }
            
            // Log call ended
            $webRTCService = new WebRTCService();
            $webRTCService->logCallActivity(
                $this->isCaller ? $this->callerId : $this->receiverId, 
                $this->callType, 
                'call_ended'
            );
            
            // Notify other party
            $this->dispatch('callEnded', [
                'roomId' => $this->roomId,
                'endedBy' => $currentUserId
            ]);

            // Also dispatch to Socket.IO for cross-tab communication
            $this->dispatch('socketCallEnded', [
                'roomId' => $this->roomId,
                'endedBy' => $currentUserId
            ]);
            
            $this->resetCall();
        }
    }

    private function resetCall()
    {
        $this->isInCall = false;
        $this->callType = null;
        $this->roomId = null;
        $this->callerId = null;
        $this->receiverId = null;
        $this->callerName = null;
        $this->receiverName = null;
        $this->isCaller = false;
        $this->isReceiver = false;
    }

    private function getReceiverType()
    {
        // If the caller is a tutor, the receiver is a student
        // If the caller is a student, the receiver is a tutor
        if (Auth::guard('tutor')->check()) {
            return 'student';
        } elseif (Auth::guard('student')->check()) {
            return 'tutor';
        }
        return 'student'; // Default fallback
    }

    private function getUserProfilePicture($userId, $userType)
    {
        try {
            if ($userType === 'student') {
                $user = \App\Models\Student::find($userId);
            } else {
                $user = \App\Models\Tutor::find($userId);
            }
            
            $profilePicture = $user ? $user->profile_picture : null;
            
            
            return $profilePicture;
        } catch (\Exception $e) {
            \Log::error('Error getting user profile picture:', ['userId' => $userId, 'userType' => $userType, 'error' => $e->getMessage()]);
            return null;
        }
    }

    public function render()
    {
        return view('livewire.call-manager');
    }
}
