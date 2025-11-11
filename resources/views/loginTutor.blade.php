<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('style/loginPage.css')}}">
    <title>MentorHub - Tutor Login</title>
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
            </nav>
        </div>
    </header>
    
    <!-- Main Content -->
    <main>
        <div class="container">
            <div class="login-container">
                <div class="login-header">
                    <h1>Welcome Tutor</h1>
                    <p>Log in to access your tutor dashboard</p>
                </div>
                
                <!-- Display validation errors -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <div class="alert-content">
                            <strong>Login failed:</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <button class="alert-close" onclick="this.parentElement.style.display='none'">×</button>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('login.tutor.submit') }}" id="loginForm">
                    @csrf
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" required autocomplete="email" value="{{ old('email') }}" placeholder="Enter your email">
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required autocomplete="current-password" placeholder="Enter your password">
                    </div>
                    
                    <div class="form-footer">
                        <div class="checkbox-group">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">Remember me</label>
                        </div>
                        <div class="forgot-password">
                            <a href="{{ route('password.request') }}">Forgot Password?</a>
                        </div>
                    </div>
                    
                    <button type="submit" class="cta-btn">Log In</button>
                    
                    <div class="login-footer">
                        <p>Don't have an account? <a href="{{route('select-role')}}">Register here</a></p>
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
            const loginForm = document.getElementById('loginForm');
            const submitBtn = loginForm.querySelector('.cta-btn');
            
            loginForm.addEventListener('submit', function() {
                submitBtn.innerHTML = 'Logging in...';
                submitBtn.disabled = true;
            });
        });
    </script>
</body>
</html> 