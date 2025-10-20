<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - MentorHub</title>
    <link rel="stylesheet" href="{{ asset('style/studentregister2.css') }}">
    <style>
        .verification-container {
            max-width: 500px;
            margin: 2rem auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .verification-header {
            background: linear-gradient(135deg, #2d7dd2, #4a3dd9);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .verification-header h1 {
            margin: 0 0 0.5rem 0;
            font-size: 2rem;
        }
        
        .verification-header p {
            margin: 0;
            opacity: 0.9;
        }
        
        .verification-content {
            padding: 2rem;
        }
        
        .verification-icon {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .verification-icon i {
            font-size: 4rem;
            color: #4a90e2;
        }
        
        .verification-form {
            margin-top: 1.5rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }
        
        .verification-input {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 1.2rem;
            text-align: center;
            letter-spacing: 0.2em;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .verification-input:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
        }
        
        .verify-btn {
            width: 100%;
            background: linear-gradient(135deg, #4a90e2, #357abd);
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }
        
        .verify-btn:hover {
            background: linear-gradient(135deg, #357abd, #2d7dd2);
            transform: translateY(-2px);
        }
        
        .verify-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }
        
        .resend-section {
            text-align: center;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 10px;
            margin-top: 1rem;
        }
        
        .resend-btn {
            background: none;
            border: none;
            color: #4a90e2;
            text-decoration: underline;
            cursor: pointer;
            font-size: 0.9rem;
        }
        
        .resend-btn:hover {
            color: #357abd;
        }
        
        .resend-btn:disabled {
            color: #ccc;
            cursor: not-allowed;
        }
        
        .countdown {
            color: #666;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }
        
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .back-link {
            text-align: center;
            margin-top: 1rem;
        }
        
        .back-link a {
            color: #4a90e2;
            text-decoration: none;
        }
        
        .back-link a:hover {
            text-decoration: underline;
        }
        
        /* Success Popup Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0,0,0,0.5);
            z-index: 9998;
            display: block;
        }
        .modal-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 32px rgba(0,0,0,0.2);
            min-width: 320px;
            max-width: 90vw;
            padding: 0;
            animation: modalFadeIn 0.3s;
        }
        @keyframes modalFadeIn {
            from { opacity: 0; transform: translate(-50%, -60%); }
            to { opacity: 1; transform: translate(-50%, -50%); }
        }
        .popup-content {
            padding: 32px 24px 24px 24px;
            text-align: center;
        }
        .popup-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }
        .popup-header h3 {
            margin: 0;
            color: #333;
            font-size: 1.5rem;
        }
        .close-popup {
            cursor: pointer;
            font-size: 1.5rem;
            color: #888;
            transition: color 0.2s;
        }
        .close-popup:hover {
            color: #333;
        }
        .success-icon {
            font-size: 2.5rem;
            color: #4BB543;
            margin-bottom: 12px;
        }
        .redirect-message {
            color: #888;
            font-size: 0.95rem;
            margin-top: 10px;
        }
        @media (max-width: 500px) {
            .modal-popup {
                min-width: 90vw;
                padding: 0;
            }
            .popup-content {
                padding: 20px 8px 16px 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="verification-container">
            <div class="verification-header">
                <h1>Verify Your Email</h1>
                <p>We've sent a verification code to {{ $email }}</p>
            </div>
            
            <div class="verification-content">
                <div class="verification-icon">
                    <i class="fas fa-envelope-open-text"></i>
                </div>
                
                @if (session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif
                
                @if (session('verification_success'))
                    <div class="modal-overlay" id="success-modal-overlay"></div>
                    <div class="success-popup modal-popup" id="success-popup">
                        <div class="popup-content">
                            <div class="popup-header">
                                <h3>Registration Successful!</h3>
                                <span class="close-popup" onclick="closeSuccessPopup()">&times;</span>
                            </div>
                            <div class="popup-body">
                                <div class="success-icon">✓</div>
                                <p>{{ session('verification_success') }}</p>
                                <p class="redirect-message">Redirecting to login page...</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if (session('error'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ session('error') }}
                    </div>
                @endif
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <ul style="margin: 0; padding-left: 1rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('verify.email') }}" method="POST" class="verification-form" id="verificationForm">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <input type="hidden" name="type" value="{{ $type }}">
                    
                    <div class="form-group">
                        <label for="verification_code">Enter Verification Code</label>
                        <input 
                            type="text" 
                            id="verification_code" 
                            name="verification_code" 
                            class="verification-input" 
                            placeholder="000000"
                            maxlength="6"
                            pattern="[0-9]{6}"
                            inputmode="numeric"
                            required
                            autocomplete="off"
                        >
                    </div>
                    
                    <button type="submit" class="verify-btn" id="verifyBtn">
                        Verify Email
                    </button>
                </form>
                
                <div class="resend-section">
                    <p>Didn't receive the code?</p>
                    <form action="{{ route('resend.verification') }}" method="POST" id="resendForm">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        <input type="hidden" name="type" value="{{ $type }}">
                        <button type="submit" class="resend-btn" id="resendBtn">
                            Resend Code
                        </button>
                    </form>
                    <div class="countdown" id="countdown"></div>
                </div>
                
                <div class="back-link">
                    <a href="{{ route('home') }}">← Back to Home</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const verificationInput = document.getElementById('verification_code');
            const verifyBtn = document.getElementById('verifyBtn');
            const resendBtn = document.getElementById('resendBtn');
            const countdown = document.getElementById('countdown');
            const resendForm = document.getElementById('resendForm');
            
            let resendCooldown = 60; // 60 seconds cooldown
            
            // Auto-format verification code input (numbers only)
            verificationInput.addEventListener('input', function(e) {
                // Remove any non-numeric characters
                let value = e.target.value.replace(/[^0-9]/g, '');
                e.target.value = value;
                
                // Enable/disable verify button based on input length
                verifyBtn.disabled = value.length !== 6;
            });
            
            // Handle form submission
            document.getElementById('verificationForm').addEventListener('submit', function(e) {
                verifyBtn.disabled = true;
                verifyBtn.textContent = 'Verifying...';
            });
            
            // Handle resend form submission
            resendForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (resendCooldown > 0) {
                    return;
                }
                
                resendBtn.disabled = true;
                resendBtn.textContent = 'Sending...';
                
                fetch('{{ route("resend.verification") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        email: '{{ $email }}',
                        type: '{{ $type }}'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showMessage('New verification code sent!', 'success');
                        startResendCooldown();
                    } else {
                        showMessage(data.message || 'Failed to send verification code', 'error');
                    }
                })
                .catch(error => {
                    showMessage('An error occurred. Please try again.', 'error');
                })
                .finally(() => {
                    resendBtn.disabled = false;
                    resendBtn.textContent = 'Resend Code';
                });
            });
            
            function startResendCooldown() {
                resendCooldown = 60;
                resendBtn.disabled = true;
                
                const timer = setInterval(() => {
                    resendCooldown--;
                    countdown.textContent = `Resend available in ${resendCooldown} seconds`;
                    
                    if (resendCooldown <= 0) {
                        clearInterval(timer);
                        resendBtn.disabled = false;
                        countdown.textContent = '';
                    }
                }, 1000);
            }
            
            function showMessage(message, type) {
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${type}`;
                alertDiv.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${message}`;
                
                const content = document.querySelector('.verification-content');
                content.insertBefore(alertDiv, content.firstChild);
                
                setTimeout(() => {
                    alertDiv.remove();
                }, 5000);
            }
            
            // Start initial cooldown
            startResendCooldown();
            
            // Handle success popup
            const successPopup = document.getElementById('success-popup');
            const overlay = document.getElementById('success-modal-overlay');
            
            if (successPopup && overlay) {
                document.body.style.overflow = 'hidden';
                overlay.addEventListener('click', closeSuccessPopup);
                
                // Auto-close after 3 seconds and redirect
                setTimeout(() => {
                    closeSuccessPopup();
                }, 3000);
            }
        });
        
        function closeSuccessPopup() {
            const popup = document.getElementById('success-popup');
            const overlay = document.getElementById('success-modal-overlay');
            if (popup) popup.style.display = 'none';
            if (overlay) overlay.style.display = 'none';
            document.body.style.overflow = 'auto';
            
            // Redirect to appropriate login page
            const type = '{{ $type }}';
            if (type === 'student') {
                window.location.href = '{{ route("login.student") }}';
            } else {
                window.location.href = '{{ route("login.tutor") }}';
            }
        }
    </script>
</body>
</html>
