<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MentorHub - Expert Tutoring Platform</title>
    <link rel="stylesheet" href="{{asset('style/homePage.css')}}">
    <link rel="stylesheet" href="{{asset('style/subjects.css')}}">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            min-height: 100vh;
        }
        footer {
            margin-bottom: 0;
            padding-bottom: 0;
            background: #323232; /* match your footer background */
        }
    </style>
</head>
<body>
    @if(session('success'))
        <div class="modal-overlay" id="success-modal-overlay"></div>
        <div class="success-popup modal-popup" id="success-popup" style="display:block;">
            <div class="popup-content">
                <div class="popup-header">
                    <h3>Registration Successful!</h3>
                    <span class="close-popup" id="close-success-popup">&times;</span>
                </div>
                <div class="popup-body">
                    <div class="success-icon">✓</div>
                    <p>{{ session('success') }}</p>
                    <p class="redirect-message">This will close automatically...</p>
                </div>
            </div>
        </div>
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
        </style>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const popup = document.getElementById('success-popup');
                const overlay = document.getElementById('success-modal-overlay');
                const closeBtn = document.getElementById('close-success-popup');
                function closeModal() {
                    if (popup) popup.style.display = 'none';
                    if (overlay) overlay.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
                if (popup && overlay) {
                    document.body.style.overflow = 'hidden';
                    overlay.addEventListener('click', closeModal);
                    if (closeBtn) closeBtn.addEventListener('click', closeModal);
                    setTimeout(closeModal, 3000);
                }
            });
        </script>
    @endif

    <!-- Header Section -->
    <header>
        <div class="navbar">
            <div class="logo">
                <img src="{{asset('images/MentorHub.png')}}" alt="MentorHub Logo" class="logo-img" style="image-rendering: crisp-edges; width: auto; height: 80px;">
            </div>
            
            <nav class="nav-links">
                <a href="#features">Features</a>
                <a href="#subjects">Subjects</a>
                <a href="#about">About</a>
                <a href="#contact">Contact</a>
            </nav>
            <div class="nav-buttons">
<button class="cta-btn" onclick="window.location.href='/select-role-login'">Login</button>
                <button class="cta-btn" onclick="window.location.href='select-role'">Get Started</button>
            </div>
        </div>
        
        <div class="hero">
            <h1>Transform Your Learning Journey</h1>
            <p>Connect with expert tutors worldwide for personalized online and face-to-face sessions. Track progress, complete assignments, and achieve academic excellence with our comprehensive learning platform.</p>
            <div class="hero-buttons">
                <button class="cta-btn" onclick="window.location.href='select-role'">Start Learning Today</button>
            </div>
        </div>
        <div class="wave"></div>
    </header>
    
    <main>
        <!-- Features Section -->
        <section id="features">
            <div class="container">
                <div class="section-head">
                    <h2>Powerful Learning Features</h2>
                    <p>Everything you need for successful online and offline tutoring in one comprehensive platform</p>
                </div>
                
                <div class="features">
                    <div class="feature-card">
                        <div class="feature-icon">📹</div>
                        <h3>Live & Recorded Sessions</h3>
                        <p>Book online video sessions or meet face-to-face. All sessions are recorded for future review and better learning retention.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">📊</div>
                        <h3>Progress Tracking</h3>
                        <p>Monitor your academic growth with detailed analytics, performance metrics, and personalized learning insights.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">📝</div>
                        <h3>Assignment Marketplace</h3>
                        <p>Post assignments and get expert solutions. Students pay tutors directly for quality homework help and explanations.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">💰</div>
                        <h3>Integrated Wallet System</h3>
                        <p>Secure payment system with digital wallets for students and tutors. Easy deposits, withdrawals, and transaction tracking.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">🎓</div>
                        <h3>Alumni Tutors Network</h3>
                        <p>Connect with accomplished alumni who've mastered your subjects. Our verified alumni tutors bring real-world experience and proven academic success.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">📱</div>
                        <h3>Mobile Learning</h3>
                        <p>Learn anywhere with our responsive platform. Access sessions, assignments, and progress reports on any device.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="about-section">
            <div class="container">
                <div class="section-head">
                    <h2>About MentorHub</h2>
                    <p>Our mission and vision for transforming education</p>
                </div>
                <div class="about-content">
                    <div class="about-image">
                        <img src="{{asset('images/sample.jpg')}}" alt="MentorHub Team" class="about-img">
                        <div class="about-stats">
                            <div class="stat-item">
                                <div class="stat-number">10,000+</div>
                                <div class="stat-label">Active Students</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">1,500+</div>
                                <div class="stat-label">Expert Tutors</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">95%</div>
                                <div class="stat-label">Satisfaction Rate</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">50+</div>
                                <div class="stat-label">Subjects Covered</div>
                            </div>
                        </div>
                    </div>
                    <div class="about-text">
                        <h3>Who We Are</h3>
                        <p>MentorHub is a revolutionary tutoring platform founded in 2020 by a team of educators and technologists from top universities. We've created an ecosystem where knowledge transfer happens seamlessly between verified experts and eager learners.</p>
                        
                        <h3>Our Educational Philosophy</h3>
                        <p>We believe learning should be personalized, accessible, and measurable. Our platform combines the best of human expertise with cutting-edge technology to create transformative educational experiences.</p>
                        
                        <h3>Our Mission</h3>
                        <p>To democratize access to quality education by connecting learners with the perfect tutors, creating personalized learning experiences that help students achieve their academic goals and unlock their full potential.</p>
                        
                        <h3>Our Vision</h3>
                        <p>A world where every student has access to personalized, high-quality education regardless of their location, background, or financial situation. We envision MentorHub becoming the global standard for supplemental education.</p>
                        
                        <h3>Our Values</h3>
                        <p>Excellence in education, integrity in our relationships, innovation in our solutions, and inclusivity in our community. We measure our success by our students' achievements.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Subjects Section -->
        <section id="subjects" class="subjects-section">
            <div class="container">
                <div class="section-head">
                    <h2>Subjects We Cover</h2>
                    <p>Comprehensive tutoring across all academic levels and subjects</p>
                </div>
                <div class="subjects-container">
                    <div class="subject-card">
                        <div class="subject-icon">➗</div>
                        <h3>Mathematics</h3>
                        <p>Algebra, Calculus, Statistics, Geometry</p>
                    </div>
                    <div class="subject-card">
                        <div class="subject-icon">🔬</div>
                        <h3>Sciences</h3>
                        <p>Physics, Chemistry, Biology, Computer Science</p>
                    </div>
                    <div class="subject-card">
                        <div class="subject-icon">🗣️</div>
                        <h3>Languages</h3>
                        <p>English, Spanish, French, Mandarin</p>
                    </div>
                    <div class="subject-card">
                        <div class="subject-icon">🌍</div>
                        <h3>Geography</h3>
                        <p>Physical, Human, Environmental Geography</p>
                    </div>
                    <div class="subject-card">
                        <div class="subject-icon">🏛️</div>
                        <h3>Humanities</h3>
                        <p>History, Literature, Philosophy, Arts</p>
                    </div>
                    <div class="subject-card">
                        <div class="subject-icon">💼</div>
                        <h3>Business</h3>
                        <p>Economics, Accounting, Finance, Marketing</p>
                    </div>
                    <div class="subject-card">
                        <div class="subject-icon">💻</div>
                        <h3>Programming</h3>
                        <p>Python, Java, C++, Web Development</p>
                    </div>
                    <div class="subject-card">
                        <div class="subject-icon">🎨</div>
                        <h3>Arts & Crafts</h3>
                        <p>Drawing, Painting, Sculpture, Creative Skills</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contact" class="contact-section">
            <div class="container">
                <div class="section-head">
                    <h2>Contact Us</h2>
                    <p>We're here to help with any questions</p>
                </div>
                <div class="contact-content">
                    <div class="contact-info">
                        <h3>Get in Touch</h3>
                        <p>Have questions about our platform, pricing, or how to get started? Reach out to our support team who are available 24/7 to assist you.</p>
                        
                        <div class="info-item">
                            <div class="icon">&#128205;</div>
                            <div>
                                <h4>Our Headquarters</h4>
                                <p>Looc, Mandaue City</p>
                                <p>University of Cebu Lapu-Lapu and Mandaue(UCLM)</p>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="icon">&#128222;</div>
                            <div>
                                <h4>Phone Support</h4>
                                <p>09958667092</p>
                                <p style="font-size: 0.9rem; color: #666; margin-top: 0.3rem;">Call us during office hours for immediate assistance</p>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="icon">&#9993;</div>
                            <div>
                                <h4>Email Us</h4>
                                <p>MentorHub.Website@gmail.com</p>
                                <p style="font-size: 0.9rem; color: #666; margin-top: 0.3rem;">We typically respond within 24 hours</p>
                            </div>
                        </div>
                        
                        <h3 class="hours-heading">Office Hours</h3>
                        <div class="hours-info">
                            <p>Monday-Friday: 8:00 AM - 8:00 PM EST</p>
                            <p>Saturday: 9:00 AM - 5:00 PM EST</p>
                            <p>Sunday: Emergency Support Only</p>
                        </div>
                    </div>
                    
                    <div class="contact-image">
                        <div class="contact-card">
                            <h3>Why Choose MentorHub?</h3>
                            <div class="feature-list">
                                <div class="feature-item">
                                    <span class="checkmark">✓</span>
                                    <span>Verified Expert Tutors</span>
                                </div>
                                <div class="feature-item">
                                    <span class="checkmark">✓</span>
                                    <span>Flexible Scheduling</span>
                                </div>
                                <div class="feature-item">
                                    <span class="checkmark">✓</span>
                                    <span>Affordable Pricing</span>
                                </div>
                                <div class="feature-item">
                                    <span class="checkmark">✓</span>
                                    <span>24/7 Support</span>
                                </div>
                                <div class="feature-item">
                                    <span class="checkmark">✓</span>
                                    <span>Progress Tracking</span>
                                </div>
                                <div class="feature-item">
                                    <span class="checkmark">✓</span>
                                    <span>Secure Payments</span>
                                </div>
                            </div>
                            <div class="cta-section" style="margin-top: 2rem; text-align: center;">
                                <p style="margin-bottom: 1rem; color: #555;">Ready to start your learning journey?</p>
                                <button class="cta-btn" onclick="window.location.href='select-role'" style="padding: 12px 24px; font-size: 1rem;">Get Started Now</button>
                            </div>
                        </div>
                        
                        <div class="stats-card" style="margin-top: 1.5rem;">
                            <h4 style="color: #4a90e2; margin-bottom: 1rem; text-align: center;">Our Impact</h4>
                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                                <div style="text-align: center; padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                                    <div style="font-size: 2rem; font-weight: bold; color: #4a90e2;">10,000+</div>
                                    <div style="font-size: 0.9rem; color: #666;">Students Helped</div>
                                </div>
                                <div style="text-align: center; padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                                    <div style="font-size: 2rem; font-weight: bold; color: #4a90e2;">1,500+</div>
                                    <div style="font-size: 0.9rem; color: #666;">Expert Tutors</div>
                                </div>
                                <div style="text-align: center; padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                                    <div style="font-size: 2rem; font-weight: bold; color: #4a90e2;">95%</div>
                                    <div style="font-size: 0.9rem; color: #666;">Success Rate</div>
                                </div>
                                <div style="text-align: center; padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                                    <div style="font-size: 2rem; font-weight: bold; color: #4a90e2;">50+</div>
                                    <div style="font-size: 0.9rem; color: #666;">Subjects</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>For Students</h3>
                    <ul>
                        <li><a href="{{ route('signup', ['role' => 'student']) }}">Student Signup</a></li>
                        <li><a href="{{ route('login.student') }}">Student Login</a></li>
                        <li><a href="#subjects">Browse Subjects</a></li>
                    </ul>
                </div>

                <div class="footer-column">
                    <h3>For Tutors</h3>
                    <ul>
                        <li><a href="{{ route('signup', ['role' => 'tutor']) }}">Tutor Signup</a></li>
                        <li><a href="{{ route('login.tutor') }}">Tutor Login</a></li>
                        <li><a href="{{ route('select-role') }}">Choose Your Role</a></li>
                    </ul>
                </div>

                <div class="footer-column">
                    <h3>Platform</h3>
                    <ul>
                        <li><a href="#features">Features</a></li>
                        <li><a href="#about">About MentorHub</a></li>
                        <li><a href="#subjects">Subjects</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>

                <div class="footer-column">
                    <h3>Support</h3>
                    <ul>
                        <li><a href="#contact">Contact Support</a></li>
                        <li><a href="mailto:MentorHub.Website@gmail.com">Email Us</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="copyright">
                <p>&copy; 2025 MentorHub. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Close success popup
        function closePopup() {
            const popup = document.getElementById('success-popup');
            if (popup) {
                popup.classList.remove('show');
                // Remove the popup from DOM after animation completes
                setTimeout(() => {
                    popup.remove();
                }, 300);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Auto-close popup after 5 seconds
            const popup = document.getElementById('success-popup');
            if (popup) {
                setTimeout(() => {
                    closePopup();
                }, 5000);
            }

            // Close when clicking outside popup content
            document.addEventListener('click', function(e) {
                const popup = document.getElementById('success-popup');
                if (popup && e.target === popup) {
                    closePopup();
                }
            });

            // Smooth scrolling for navigation links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Subject tab functionality
            const tabBtns = document.querySelectorAll('.tab-btn');
            tabBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    // Remove active class from all buttons and content
                    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                    
                    // Add active class to clicked button
                    btn.classList.add('active');
                    
                    // Show corresponding content
                    const tabId = btn.getAttribute('data-tab');
                    document.getElementById(tabId).classList.add('active');
                });
            });
        });
    </script>
</body>
</html>