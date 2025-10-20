<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('style/loginPage.css')}}">
    <title>MentorHub - Verify Code</title>
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
                    <h1>Verify Code</h1>
                    <p>Enter the 6-digit verification code sent to your email</p>
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
                
                <form method="POST" action="{{ route('password.verify.submit') }}" id="verifyCodeForm">
                    @csrf
                    
                    <div class="form-group">
                        <label for="code">Verification Code</label>
                        <input type="text" id="code" name="code" class="form-control" required maxlength="6" pattern="[0-9]{6}" value="{{ old('code') }}" placeholder="Enter 6-digit code" autocomplete="off">
                        <small class="form-text">Enter the 6-digit verification code sent to your email address</small>
                    </div>
                    
                    <button type="submit" class="cta-btn">Verify Code</button>
                    
                    <div class="login-footer">
                        <p>Didn't receive the code? <a href="{{route('password.request')}}">Request new code</a></p>
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
            const verifyCodeForm = document.getElementById('verifyCodeForm');
            const submitBtn = verifyCodeForm.querySelector('.cta-btn');
            
            verifyCodeForm.addEventListener('submit', function() {
                submitBtn.innerHTML = 'Verifying...';
                submitBtn.disabled = true;
            });

            // Auto-focus on code input
            const codeInput = document.getElementById('code');
            codeInput.focus();

            // Auto-advance to next input (if you want to add multiple input fields)
            codeInput.addEventListener('input', function() {
                if (this.value.length === 6) {
                    this.form.submit();
                }
            });
        });
    </script>
</body>
</html> 