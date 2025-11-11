<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('style/loginPage.css')}}">
    <title>MentorHub - Student Login</title>
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
                    <h1>Welcome Learner</h1>
                    <p>Log in to continue your learning journey</p>
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
                
                <form method="POST" action="{{ route('login.student.submit') }}" id="loginForm">
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

    <!-- Footer Modals -->
    <!-- Privacy Policy Modal -->
    <div id="privacy-modal" class="footer-modal">
        <div class="footer-modal-content">
            <div class="footer-modal-header">
                <h2>Privacy Policy</h2>
                <button class="footer-modal-close">&times;</button>
            </div>
            <div class="footer-modal-body">
                <p><strong>Last updated:</strong> May 30, 2025</p>
                
                <h3>1. Information We Collect</h3>
                <p>We collect the following types of information:</p>
                <ul>
                    <li><strong>Personal Information:</strong> Name, email address, phone number, academic information</li>
                    <li><strong>Usage Data:</strong> Session attendance, progress metrics, platform interactions</li>
                    <li><strong>Technical Data:</strong> IP address, browser type, device information, cookies</li>
                    <li><strong>Communication Data:</strong> Messages, feedback, and support requests</li>
                </ul>
                
                <h3>2. How We Use Your Information</h3>
                <p>We use your information to:</p>
                <ul>
                    <li>Provide tutoring services and match you with appropriate tutors</li>
                    <li>Process payments and manage your account</li>
                    <li>Track your academic progress and generate reports</li>
                    <li>Communicate with you about sessions and platform updates</li>
                    <li>Improve our services and user experience</li>
                    <li>Comply with legal obligations</li>
                </ul>
                
                <h3>3. Data Security</h3>
                <p>We implement industry-standard security measures to protect your personal information, including encryption, regular security audits, and access controls.</p>
                
                <h3>4. Your Rights</h3>
                <p>You have the right to access, correct, delete your personal information, and opt-out of marketing communications.</p>
                
                <h3>5. Contact Us</h3>
                <p>For questions about this Privacy Policy, contact us at:</p>
                <ul>
                    <li>Email: <a href="mailto:MentorHub.Website@gmail.com">MentorHub.Website@gmail.com</a></li>
                    <li>Phone: +63958667092</li>
                    <li>Address: University of Cebu, Cebu City, Philippines</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Terms of Service Modal -->
    <div id="terms-modal" class="footer-modal">
        <div class="footer-modal-content">
            <div class="footer-modal-header">
                <h2>Terms of Service</h2>
                <button class="footer-modal-close">&times;</button>
            </div>
            <div class="footer-modal-body">
                <p><strong>Last updated:</strong> May 30, 2025</p>
                
                <h3>1. Acceptance of Terms</h3>
                <p>By accessing and using MentorHub's services, you agree to be bound by these Terms and Conditions. If you do not agree to these terms, please do not use our platform.</p>
                
                <h3>2. Description of Service</h3>
                <p>MentorHub is an online tutoring platform that connects students with qualified tutors. We provide:</p>
                <ul>
                    <li>One-on-one tutoring sessions</li>
                    <li>Group study sessions</li>
                    <li>Educational resources and materials</li>
                    <li>Progress tracking and reporting</li>
                </ul>
                
                <h3>3. User Responsibilities</h3>
                <p>As a user, you agree to:</p>
                <ul>
                    <li>Provide accurate and complete registration information</li>
                    <li>Maintain the confidentiality of your account credentials</li>
                    <li>Treat tutors and other users with respect</li>
                    <li>Use the platform only for educational purposes</li>
                </ul>
                
                <h3>4. Payment and Refunds</h3>
                <p>Payment for tutoring sessions is required in advance. Refunds may be provided in accordance with our refund policy, typically for sessions cancelled with at least 24 hours notice.</p>
                
                <h3>5. Contact Information</h3>
                <p>For questions about these Terms and Conditions, please contact us at <a href="mailto:MentorHub.Website@gmail.com">MentorHub.Website@gmail.com</a> or through our support channels.</p>
            </div>
        </div>
    </div>

    <!-- FAQ Modal -->
    <div id="faq-modal" class="footer-modal">
        <div class="footer-modal-content">
            <div class="footer-modal-header">
                <h2>Frequently Asked Questions</h2>
                <button class="footer-modal-close">&times;</button>
            </div>
            <div class="footer-modal-body">
                <h3>How do I book a tutoring session?</h3>
                <p>You can book a session by going to the "Book Session" page, selecting a tutor, choosing your preferred date and time, and confirming your booking.</p>
                
                <h3>What subjects are available for tutoring?</h3>
                <p>We offer tutoring in a wide range of subjects including Mathematics, Science, English, History, and more. Available subjects vary by tutor specialization.</p>
                
                <h3>Can I reschedule or cancel a session?</h3>
                <p>Yes, sessions can be rescheduled or cancelled up to 24 hours in advance. Cancellations made within 24 hours may be subject to charges.</p>
                
                <h3>How do payments work?</h3>
                <p>Payments are processed securely through our integrated payment system. You can add funds to your wallet and use them to pay for sessions and assignments.</p>
                
                <h3>What should I do if I have a technical issue?</h3>
                <p>Please use the "Report a Problem" feature in your dashboard to submit a detailed report of any technical issues you encounter.</p>
            </div>
        </div>
    </div>

    <!-- Contact Modal -->
    <div id="contact-modal" class="footer-modal">
        <div class="footer-modal-content">
            <div class="footer-modal-header">
                <h2>Contact Us</h2>
                <button class="footer-modal-close">&times;</button>
            </div>
            <div class="footer-modal-body">
                <h3>Get in Touch</h3>
                <p>We're here to help! Reach out to us through any of the following channels:</p>
                
                <h3>Email</h3>
                <p>
                    <strong>Email Us:</strong> <a href="mailto:MentorHub.Website@gmail.com">MentorHub.Website@gmail.com</a><br>
                    <small style="color: #666;">We typically respond within 24 hours</small>
                </p>
                
                <h3>Phone</h3>
                <p>+63958667092</p>
                
                <h3>Address</h3>
                <p>University of Cebu<br>Cebu City, Philippines</p>
                
                <h3>Business Hours</h3>
                <p>Monday - Friday: 8:00 AM - 6:00 PM<br>Saturday: 9:00 AM - 3:00 PM<br>Sunday: Closed</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-links">
                    <a href="#" id="footer-privacy-link">Privacy Policy</a>
                    <a href="#" id="footer-terms-link">Terms of Service</a>
                    <a href="#" id="footer-faq-link">FAQ</a>
                    <a href="#" id="footer-contact-link">Contact</a>
                </div>
                <div class="copyright">
                    &copy; 2025 MentorHub. All rights reserved.
                </div>
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

            // Footer Modal Functions
            function openFooterModal(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.style.display = 'flex';
                    // Force reflow to ensure display is set before adding class
                    void modal.offsetWidth;
                    modal.classList.add('show');
                    document.body.classList.add('modal-open');
                }
            }

            function closeFooterModal(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.remove('show');
                    document.body.classList.remove('modal-open');
                    setTimeout(() => {
                        modal.style.display = 'none';
                    }, 300);
                }
            }

            // Add event listeners for footer links
            document.getElementById('footer-privacy-link')?.addEventListener('click', function(e) {
                e.preventDefault();
                openFooterModal('privacy-modal');
            });

            document.getElementById('footer-terms-link')?.addEventListener('click', function(e) {
                e.preventDefault();
                openFooterModal('terms-modal');
            });

            document.getElementById('footer-faq-link')?.addEventListener('click', function(e) {
                e.preventDefault();
                openFooterModal('faq-modal');
            });

            document.getElementById('footer-contact-link')?.addEventListener('click', function(e) {
                e.preventDefault();
                openFooterModal('contact-modal');
            });

            // Close buttons for footer modals
            document.querySelectorAll('.footer-modal-close').forEach(btn => {
                btn.addEventListener('click', function() {
                    const modal = this.closest('.footer-modal');
                    if (modal) {
                        closeFooterModal(modal.id);
                    }
                });
            });

            // Close footer modals when clicking outside
            document.querySelectorAll('.footer-modal').forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeFooterModal(this.id);
                    }
                });
            });
        });
    </script>
</body>
</html>