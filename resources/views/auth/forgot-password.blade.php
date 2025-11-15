<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('style/loginPage.css')}}">
    <title>MentorHub - Forgot Password</title>
    <style>
        .icon-wrapper {
            display: flex;
            justify-content: center;
            margin-bottom: 1.5rem;
        }
        .icon-wrapper svg {
            background: linear-gradient(135deg, #4a90e2, #5637d9);
            border-radius: 50%;
            padding: 16px;
            color: white !important;
            box-shadow: 0 8px 20px rgba(74, 144, 226, 0.3);
        }
        .login-header h1 {
            font-size: 2rem;
            margin-bottom: 0.75rem;
            background: linear-gradient(135deg, #4a90e2, #5637d9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .login-header p {
            color: #6b7280;
            font-size: 0.95rem;
            line-height: 1.6;
        }
        .form-group label {
            display: flex;
            align-items: center;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }
        .form-group .form-text {
            display: block;
            margin-top: 0.5rem;
            font-size: 0.875rem;
            color: #6b7280;
        }
        .form-control {
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
        }
        .cta-btn {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .cta-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(74, 144, 226, 0.4);
        }
        .cta-btn:active {
            transform: translateY(0);
        }
        .cta-btn .btn-text {
            display: inline-flex;
            align-items: center;
        }
        .cta-btn.loading .btn-text {
            opacity: 0.7;
        }
        .cta-btn.loading .btn-icon {
            display: inline-block !important;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .alert-content {
            display: flex;
            align-items: flex-start;
        }
        .alert-content svg {
            flex-shrink: 0;
            margin-top: 2px;
        }
        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }
        .login-footer a {
            color: #4a90e2;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }
        .login-footer a:hover {
            color: #5637d9;
            text-decoration: underline;
        }
        @media (max-width: 768px) {
            .login-header h1 {
                font-size: 1.75rem;
            }
            .icon-wrapper svg {
                width: 56px;
                height: 56px;
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="navbar">
            <a href="{{ route('home') }}" class="logo">
                <img src="{{ asset('images/MentorHub.png') }}" alt="MentorHub Logo" class="logo-img">
                <div class="brand-text">
                    <span class="brand-title">MentorHub</span>
                    <span class="brand-subtitle">Expert Tutoring Platform</span>
                </div>
            </a>
            <button class="menu-toggle" id="menu-toggle" aria-label="Toggle navigation" aria-expanded="false">☰</button>
            <nav class="nav-links" id="nav-links">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('home') }}#features">Features</a>
                <a href="{{ route('home') }}#subjects">Subjects</a>
                <a href="{{ route('home') }}#contact">Contact</a>
                <a href="{{ route('select-role') }}" class="cta-link">Get Started</a>
            </nav>
        </div>
    </header>
    
    <!-- Main Content -->
    <main>
        <div class="container">
            <div class="login-container">
                <div class="login-header">
                    <div class="icon-wrapper">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: white;">
                            <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" fill="currentColor"/>
                        </svg>
                    </div>
                    <h1>Forgot Password?</h1>
                    <p>No worries! Enter your email address and we'll send you a verification code to reset your password.</p>
                </div>
                
                <!-- Display success message -->
                @if (session('status'))
                    <div class="alert alert-success">
                        <div class="alert-content">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 8px; flex-shrink: 0;">
                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" fill="currentColor"/>
                            </svg>
                            <span>{{ session('status') }}</span>
                        </div>
                        <button class="alert-close" onclick="this.parentElement.style.display='none'">×</button>
                    </div>
                @endif
                
                <!-- Display validation errors -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <div class="alert-content">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 8px; flex-shrink: 0;">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" fill="currentColor"/>
                            </svg>
                            <div>
                                <strong>Error:</strong>
                                <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <button class="alert-close" onclick="this.parentElement.style.display='none'">×</button>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('password.email') }}" id="forgotPasswordForm">
                    @csrf
                    
                    <div class="form-group">
                        <label for="email">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 6px; vertical-align: middle; color: #6b7280;">
                                <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" fill="currentColor"/>
                            </svg>
                            Email Address
                        </label>
                        <input type="email" id="email" name="email" class="form-control" required autocomplete="email" value="{{ old('email') }}" placeholder="Enter your registered email address">
                        <small class="form-text">We'll send a verification code to this email</small>
                    </div>
                    
                    <button type="submit" class="cta-btn">
                        <span class="btn-text">Send Reset Code</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-left: 8px; display: none;" class="btn-icon">
                            <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" fill="currentColor"/>
                        </svg>
                    </button>
                    
                    <div class="login-footer">
                        <p>Remember your password? <a href="{{route('select-role-login')}}">Back to login</a></p>
                    </div>
                </form>
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
            const menuToggle = document.getElementById('menu-toggle');
            const navLinks = document.getElementById('nav-links');

            if (menuToggle && navLinks) {
                menuToggle.addEventListener('click', function() {
                    const isOpen = navLinks.classList.toggle('active');
                    menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                });
            }

            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        if (alert.parentNode) {
                            alert.parentNode.removeChild(alert);
                        }
                    }, 300);
                }, 5000);
            });

            // Form submission loading indicator
            const forgotPasswordForm = document.getElementById('forgotPasswordForm');
            const submitBtn = forgotPasswordForm.querySelector('.cta-btn');
            const btnText = submitBtn.querySelector('.btn-text');
            
            forgotPasswordForm.addEventListener('submit', function(e) {
                submitBtn.classList.add('loading');
                btnText.textContent = 'Sending...';
                submitBtn.disabled = true;
                
                // Re-enable after 10 seconds in case of error
                setTimeout(() => {
                    if (submitBtn.disabled) {
                        submitBtn.classList.remove('loading');
                        btnText.textContent = 'Send Reset Code';
                        submitBtn.disabled = false;
                    }
                }, 10000);
            });
        });
    </script>
</body>
</html> 