<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MentorHub - User Registration</title>
    <link rel="stylesheet" href="{{ asset('style/studentregister2.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            <div class="page-title">
                <h1>Student Registration</h1>
                <p>Join thousands of students who have improved their grades with MentorHub</p>
            </div>
            
            <!-- Display validation errors -->
            @if ($errors->any())
                <div class="alert alert-danger enhanced-alert" id="error-alert">
                    <div class="alert-content">
                        <span class="alert-icon">&#9888;</span>
                        <strong>Please fix the following errors:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button class="alert-close" onclick="this.parentElement.style.display='none'">×</button>
                </div>
            @endif
            
            <!-- Display success message if registration was successful -->
            @if (session('success'))
                <div class="modal-overlay" id="success-modal-overlay"></div>
                <div class="success-popup modal-popup" id="success-popup">
                    <div class="popup-content">
                        <div class="popup-header">
                            <h3>Registration Successful!</h3>
                            <span class="close-popup" onclick="closePopup()">&times;</span>
                        </div>
                        <div class="popup-body">
                            <div class="success-icon">✓</div>
                            <p>{{ session('success') }}</p>
                            <p class="redirect-message">Redirecting to homepage...</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="registration-container">
                <div class="registration-content">
                    <h2>Why choose MentorHub?</h2>
                    <p>Join our community of successful students and get the academic support you need to excel in your studies.</p>
                    
                    <div class="benefits">
                        <h3>User Benefits</h3>
                        
                        <div class="benefit-item">
                            <div class="benefit-icon">📹</div>
                            <div>
                                <h4>Live & Recorded Sessions</h4>
                                <p>Access both online and face-to-face tutoring with recorded sessions for future review.</p>
                            </div>
                        </div>
                        
                        <div class="benefit-item">
                            <div class="benefit-icon">📝</div>
                            <div>
                                <h4>Assignment Help</h4>
                                <p>Get expert solutions and explanations through our assignment marketplace.</p>
                            </div>
                        </div>
                        
                        <div class="benefit-item">
                            <div class="benefit-icon">🎓</div>
                            <div>
                                <h4>Expert Alumni Tutors</h4>
                                <p>Learn from accomplished alumni who understand your curriculum and bring real-world experience.</p>
                            </div>
                        </div>
                        
                        <div class="benefit-item">
                            <div class="benefit-icon">📱</div>
                            <div>
                                <h4>Flexible Learning</h4>
                                <p>Access tutoring anywhere with our mobile platform and track your progress in real-time.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="registration-form">
                    <div class="form-header">
                        <h2>Create Your Account</h2>
                        <p>Start your journey to academic success</p>
                    </div>
                    
<form action="/register/student" method="POST" class="register-form">
                        @csrf
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="first_name">First Name *</label>
                                <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required pattern="[a-zA-Z\s\-\']+" title="First name can only contain letters, spaces, hyphens, and apostrophes" onkeypress="return /[a-zA-Z\s\-\']/.test(event.key)">
                                @error('first_name')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="last_name">Last Name *</label>
                                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required pattern="[a-zA-Z\s\-\']+" title="Last name can only contain letters, spaces, hyphens, and apostrophes" onkeypress="return /[a-zA-Z\s\-\']/.test(event.key)">
                                @error('last_name')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email" required>
                            @error('email')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="password">Password *</label>
                                <input type="password" id="password" name="password" autocomplete="new-password" required>
                                @error('password')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password *</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="student-id-display">Student ID</label>
                                <input type="text" id="student-id-display" value="Will be generated automatically" disabled style="background-color: #f3f4f6; color: #6b7280;">
                                <small style="display:block; margin-top:0.5rem; color:#6b7280;">Your Student ID will be assigned by the system after registration.</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="year_level">Year Level *</label>
                                <select id="year_level" name="year_level" required>
                                    <option value="">Select Year Level</option>
                                    <option value="Pre-school" {{ old('year_level') == 'Pre-school' ? 'selected' : '' }}>Pre-school</option>
                                    <option value="Kindergarten" {{ old('year_level') == 'Kindergarten' ? 'selected' : '' }}>Kindergarten</option>
                                    <option value="Elementary" {{ old('year_level') == 'Elementary' ? 'selected' : '' }}>Elementary</option>
                                    <option value="Highschool" {{ old('year_level') == 'Highschool' ? 'selected' : '' }}>Highschool</option>
                                    <option value="Senior Highschool" {{ old('year_level') == 'Senior Highschool' ? 'selected' : '' }}>Senior Highschool</option>
                                    <option value="College" {{ old('year_level') == 'College' ? 'selected' : '' }}>College</option>
                                </select>
                                @error('year_level')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="course">Course/Program *</label>
                            <input type="text" id="course" name="course" value="{{ old('course') }}" required>
                            @error('course')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="subjects_interest">Subjects of Interest</label>
                            <textarea id="subjects_interest" name="subjects_interest" rows="3" placeholder="List subjects you need help with (e.g., Mathematics, Science, English)...">{{ old('subjects_interest') }}</textarea>
                            @error('subjects_interest')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Optional">
                            @error('phone')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group checkbox-group">
                            <input type="checkbox" id="terms" name="terms" {{ old('terms') ? 'checked' : '' }} required>
                            <label for="terms">I agree to the <a href="#" class="modal-link" id="terms-link">Terms and Conditions</a> and <a href="#" class="modal-link" id="privacy-link">Privacy Policy</a></label>
                            @error('terms')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <button type="submit" class="register-btn">Create Account</button>
                    </form>
                    
                    <div class="form-footer">
                        <p>Already have an account? <a href="/login">Login here</a></p>
                        <p><a href="{{ route('home') }}">← Back to Registration Selection</a></p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Terms and Conditions Modal -->
    <div id="terms-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Terms and Conditions</h2>
                <button class="close" id="terms-close">&times;</button>
            </div>
            <div class="modal-body">
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
                
                <h3>5. Intellectual Property</h3>
                <p>All content, materials, and resources provided through MentorHub remain the property of MentorHub or its content providers. Users may not reproduce, distribute, or create derivative works without express permission.</p>
                
                <h3>6. Privacy and Data Protection</h3>
                <p>Your privacy is important to us. Please review our Privacy Policy to understand how we collect, use, and protect your personal information.</p>
                
                <h3>7. Platform Availability</h3>
                <p>While we strive to maintain continuous service, MentorHub does not guarantee uninterrupted access to the platform. We reserve the right to perform maintenance and updates as needed.</p>
                
                <h3>8. User Conduct</h3>
                <p>Users must not:</p>
                <ul>
                    <li>Violate any applicable laws or regulations</li>
                    <li>Harass, abuse, or harm other users</li>
                    <li>Share inappropriate or offensive content</li>
                    <li>Attempt to gain unauthorized access to our systems</li>
                </ul>
                
                <h3>9. Termination</h3>
                <p>MentorHub reserves the right to suspend or terminate user accounts that violate these terms or engage in inappropriate behavior.</p>
                
                <h3>10. Limitation of Liability</h3>
                <p>MentorHub's liability is limited to the maximum extent permitted by law. We are not responsible for indirect, incidental, or consequential damages arising from use of our services.</p>
                
                <h3>11. Changes to Terms</h3>
                <p>We reserve the right to modify these terms at any time. Users will be notified of significant changes, and continued use of the platform constitutes acceptance of updated terms.</p>
                
                <h3>12. Contact Information</h3>
                <p>For questions about these Terms and Conditions, please contact us at MentorHub.Website@gmail.com or through our support channels.</p>
            </div>
        </div>
    </div>

    <!-- Privacy Policy Modal -->
    <div id="privacy-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Privacy Policy</h2>
                <button class="close" id="privacy-close">&times;</button>
            </div>
            <div class="modal-body">
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
                
                <h3>3. Information Sharing</h3>
                <p>We may share your information with:</p>
                <ul>
                    <li><strong>Tutors:</strong> Necessary academic and contact information for session delivery</li>
                    <li><strong>Service Providers:</strong> Third-party vendors who assist in platform operations</li>
                    <li><strong>Educational Institutions:</strong> With your consent, for academic reporting purposes</li>
                    <li><strong>Legal Authorities:</strong> When required by law or to protect our rights</li>
                </ul>
                
                <h3>4. Data Security</h3>
                <p>We implement industry-standard security measures to protect your personal information, including:</p>
                <ul>
                    <li>Encryption of sensitive data in transit and at rest</li>
                    <li>Regular security audits and updates</li>
                    <li>Access controls and authentication requirements</li>
                    <li>Staff training on data protection practices</li>
                </ul>
                
                <h3>5. Your Rights</h3>
                <p>You have the right to:</p>
                <ul>
                    <li>Access and review your personal information</li>
                    <li>Correct inaccurate or incomplete data</li>
                    <li>Request deletion of your personal information</li>
                    <li>Opt-out of marketing communications</li>
                    <li>Port your data to another service</li>
                </ul>
                
                <h3>6. Cookies and Tracking</h3>
                <p>We use cookies and similar technologies to enhance your user experience, analyze platform usage, and provide personalized content. You can manage cookie preferences through your browser settings.</p>
                
                <h3>7. Data Retention</h3>
                <p>We retain your personal information for as long as necessary to provide services and comply with legal obligations. Academic progress data may be retained longer for educational continuity purposes.</p>
                
                <h3>8. International Data Transfers</h3>
                <p>Your information may be processed and stored in countries other than your own. We ensure appropriate safeguards are in place for international data transfers.</p>
                
                <h3>9. Children's Privacy</h3>
                <p>We take special care to protect the privacy of users under 18. Parental consent may be required for certain data collection and processing activities.</p>
                
                <h3>10. Third-Party Links</h3>
                <p>Our platform may contain links to third-party websites. We are not responsible for the privacy practices of external sites and encourage you to review their privacy policies.</p>
                
                <h3>11. Updates to This Policy</h3>
                <p>We may update this Privacy Policy periodically. We will notify users of significant changes and post the updated policy on our platform.</p>
                
                <h3>12. Contact Us</h3>
                <p>For questions about this Privacy Policy or to exercise your rights, contact us at:</p>
                <ul>
                    <li>Email: MentorHub.Website@gmail.com</li>
                    <li>Phone: +63958667092</li>
                    <li>Address: University of Cebu, Cebu City, Philippines</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-links">
                <a href="#" id="footer-privacy">Privacy Policy</a>
                <a href="#" id="footer-terms">Terms of Service</a>
                <a href="#">FAQ</a>
                <a href="#">Contact</a>
            </div>
            <div class="copyright">
                &copy; {{ date('Y') }} MentorHub. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const menuToggle = document.getElementById('menu-toggle');
            const navLinks = document.getElementById('nav-links');

            if (menuToggle && navLinks) {
                menuToggle.addEventListener('click', function() {
                    const isOpen = navLinks.classList.toggle('active');
                    menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                });
            }

            // Modal functionality
            const termsModal = document.getElementById('terms-modal');
            const privacyModal = document.getElementById('privacy-modal');
            
            const termsLinks = [
                document.getElementById('terms-link'),
                document.getElementById('footer-terms')
            ];
            
            const privacyLinks = [
                document.getElementById('privacy-link'),
                document.getElementById('footer-privacy')
            ];
            
            const termsClose = document.getElementById('terms-close');
            const privacyClose = document.getElementById('privacy-close');

            // Track animation state to prevent conflicts
            let isAnimating = false;

            // Functions to open/close modals with smooth animations
            function openModal(modal) {
                if (isAnimating || !modal) return;
                isAnimating = true;
                
                modal.style.display = 'flex';
                // Force reflow to ensure display is set before adding class
                void modal.offsetWidth;
                modal.classList.add('show');
                document.body.style.overflow = 'hidden';
                
                setTimeout(() => {
                    isAnimating = false;
                }, 300);
            }

            function closeModal(modal) {
                if (isAnimating || !modal) return;
                isAnimating = true;
                
                modal.classList.remove('show');
                document.body.style.overflow = 'auto';
                
                // Wait for transition to complete before hiding
                modal.addEventListener('transitionend', function handler() {
                    modal.removeEventListener('transitionend', handler);
                    if (!modal.classList.contains('show')) {
                        modal.style.display = 'none';
                    }
                    isAnimating = false;
                }, { once: true });
            }

            // Add event listeners for terms modal
            termsLinks.forEach(link => {
                if (link) {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        openModal(termsModal);
                    });
                }
            });

            // Add event listeners for privacy modal
            privacyLinks.forEach(link => {
                if (link) {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        openModal(privacyModal);
                    });
                }
            });

            // Close button event listeners
            if (termsClose) {
                termsClose.addEventListener('click', function() {
                    closeModal(termsModal);
                });
            }

            if (privacyClose) {
                privacyClose.addEventListener('click', function() {
                    closeModal(privacyModal);
                });
            }

            // Close modal when clicking outside
            if (termsModal) {
                termsModal.addEventListener('click', function(e) {
                    if (e.target === termsModal) {
                        closeModal(termsModal);
                    }
                });
            }

            if (privacyModal) {
                privacyModal.addEventListener('click', function(e) {
                    if (e.target === privacyModal) {
                        closeModal(privacyModal);
                    }
                });
            }

            // Close modals with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeModal(termsModal);
                    closeModal(privacyModal);
                }
            });

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

            // Password strength indicator (optional)
            const passwordInput = document.getElementById('password');
            if (passwordInput) {
                passwordInput.addEventListener('input', function() {
                    // Add password strength checking logic here
                });
            }
        });

        function closePopup() {
            const popup = document.getElementById('success-popup');
            const overlay = document.getElementById('success-modal-overlay');
            if (popup) popup.style.display = 'none';
            if (overlay) overlay.style.display = 'none';
            document.body.style.overflow = 'auto';
            window.location.href = "{{ route('home') }}"; // Redirect to homepage
        }

        // Auto-close success popup after 3 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const successPopup = document.getElementById('success-popup');
            const overlay = document.getElementById('success-modal-overlay');
            if (successPopup && overlay) {
                document.body.style.overflow = 'hidden';
                overlay.addEventListener('click', closePopup);
                setTimeout(() => {
                    closePopup();
                }, 3000);
            }
        });

    </script>

    <style>
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
            border-radius: 12px;
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
        .enhanced-alert {
            border: 1.5px solid #f44336;
            background: #fff6f6;
            color: #b71c1c;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(244,67,54,0.08);
            margin: 18px 0 24px 0;
            padding: 18px 24px 18px 18px;
            position: relative;
            display: flex;
            align-items: flex-start;
            animation: fadeInAlert 0.4s;
        }
        .enhanced-alert .alert-content {
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex: 1;
        }
        .enhanced-alert .alert-icon {
            font-size: 1.6rem;
            color: #f44336;
            margin-right: 10px;
            vertical-align: middle;
            display: inline-block;
        }
        .enhanced-alert strong {
            font-size: 1.08rem;
            margin-bottom: 2px;
        }
        .enhanced-alert ul {
            margin: 0 0 0 18px;
            padding: 0;
            list-style: disc inside;
            font-size: 1rem;
        }
        .enhanced-alert .alert-close {
            background: none;
            border: none;
            color: #f44336;
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
            position: absolute;
            top: 10px;
            right: 16px;
            transition: color 0.2s;
            line-height: 1;
        }
        .enhanced-alert .alert-close:hover {
            color: #b71c1c;
        }
        @keyframes fadeInAlert {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @media (max-width: 600px) {
            .enhanced-alert {
                padding: 12px 8px 12px 8px;
            }
            .enhanced-alert .alert-close {
                right: 8px;
            }
        }
    </style>
</body>
</html>
