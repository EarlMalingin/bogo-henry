<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('style/loginPage.css')}}">
    <title>MentorHub - Forgot Password</title>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="navbar">
            <a href="{{route('home')}}" class="logo">
                <img src="{{asset('images/MentorHub.png')}}" alt="MentorHub Logo" class="logo-img">
            </a>
            <nav class="nav-links" id="nav-links">
                <a href="{{route('home')}}">Home</a>
                <a href="{{route('home')}}#features">About</a>
                <a href="{{route('home')}}#subjects">Subjects</a>
                <a href="{{route('home')}}#contact">Contact</a>
            </nav>
        </div>
    </header>
    
    <!-- Main Content -->
    <main>
        <div class="container">
            <div class="login-container">
                <div class="login-header">
                    <h1>Forgot Password</h1>
                    <p>Enter your email address to receive a password reset verification code</p>
                </div>
                
                <!-- Display success message -->
                @if (session('status'))
                    <div class="alert alert-success">
                        <div class="alert-content">
                            {{ session('status') }}
                        </div>
                        <button class="alert-close" onclick="this.parentElement.style.display='none'">×</button>
                    </div>
                @endif
                
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
                
                <form method="POST" action="{{ route('password.email') }}" id="forgotPasswordForm">
                    @csrf
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" required autocomplete="email" value="{{ old('email') }}" placeholder="Enter your email">
                    </div>
                    
                    <button type="submit" class="cta-btn">Send Reset Code</button>
                    
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
            // Mobile menu functionality removed
            
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
            
            forgotPasswordForm.addEventListener('submit', function() {
                submitBtn.innerHTML = 'Sending...';
                submitBtn.disabled = true;
            });
        });
    </script>
</body>
</html> 