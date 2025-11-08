<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('style/loginPage.css')}}">
    <title>MentorHub - Reset Password</title>
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
                    <h1>Reset Password</h1>
                    <p>Enter your new password</p>
                </div>
                
                <!-- Display validation errors -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <div class="alert-content">
                            <strong>Error:</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <button class="alert-close" onclick="this.parentElement.style.display='none'">×</button>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('password.update') }}" id="resetPasswordForm">
                    @csrf
                    
                    <div class="form-group">
                        <label for="password">New Password</label>
                        <input type="password" id="password" name="password" class="form-control" required minlength="8" placeholder="Enter new password">
                        <small class="form-text">Password must be at least 8 characters long</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirmation">Confirm New Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required minlength="8" placeholder="Confirm new password">
                    </div>
                    
                    <button type="submit" class="cta-btn">Reset Password</button>
                    
                    <div class="login-footer">
                        <p><a href="{{route('select-role-login')}}">Back to login</a></p>
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
            const resetPasswordForm = document.getElementById('resetPasswordForm');
            const submitBtn = resetPasswordForm.querySelector('.cta-btn');
            
            resetPasswordForm.addEventListener('submit', function() {
                submitBtn.innerHTML = 'Resetting...';
                submitBtn.disabled = true;
            });

            // Password confirmation validation
            const password = document.getElementById('password');
            const passwordConfirmation = document.getElementById('password_confirmation');
            
            function validatePassword() {
                if (password.value !== passwordConfirmation.value) {
                    passwordConfirmation.setCustomValidity('Passwords do not match');
                } else {
                    passwordConfirmation.setCustomValidity('');
                }
            }
            
            password.addEventListener('change', validatePassword);
            passwordConfirmation.addEventListener('keyup', validatePassword);
        });
    </script>
</body>
</html> 