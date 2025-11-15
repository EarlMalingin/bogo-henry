<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('style/dashboard.css')}}">
    <link rel="stylesheet" href="{{asset('style/session-modal.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>MentorHub Dashboard</title>
    <style>
        
        .profile-icon {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #4a90e2;
            color: white;
            font-weight: bold;
            cursor: pointer;
            z-index: 1000;
            transition: transform 0.2s cubic-bezier(0.4,0,0.2,1), box-shadow 0.2s cubic-bezier(0.4,0,0.2,1);
        }

        .profile-icon:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 16px rgba(74, 144, 226, 0.15);
        }

        .profile-icon-img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            width: 180px;
            margin-top: 10px;
            z-index: 1001;
            overflow: hidden;
        }

        .dropdown-menu.active {
            display: block;
        }

        .dropdown-menu a {
            display: block;
            padding: 12px 15px;
            color: #333;
            text-decoration: none;
            transition: background-color 0.3s;
            /* border-bottom: 1px solid #eee; */
        }

        /* .dropdown-menu a:last-child {
            border-bottom: none;
        } */

        .dropdown-menu a:hover {
            background-color: #f5f5f5;
        }

        .modal {
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
            display: none; /* Hidden by default */
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
            animation-name: animatetop;
            animation-duration: 0.4s
        }
        @keyframes animatetop {
            from {top: -300px; opacity: 0}
            to {top: 0; opacity: 1}
        }
        .close-btn {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close-btn:hover,
        .close-btn:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .tutor-profile-pic-container {
            text-align: center;
            margin-bottom: 15px;
        }

        .tutor-profile-pic {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ddd;
        }
        #modal-footer {
            display: flex;
            justify-content: flex-end;
            margin-top: 1rem;
        }
        #modal-footer button {
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        #modal-footer #modal-close-btn {
            background-color: #6c757d;
            color: white;
            margin-left: 0.5rem;
        }

        #modal-footer #modal-close-btn:hover {
            background-color: #5a6268;
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 6px 20px rgba(108, 117, 125, 0.35);
        }

        #modal-footer #modal-message-btn {
            background-color: #4a90e2;
            color: white;
        }

        #modal-footer #modal-message-btn:hover {
            background-color: #357abd;
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 6px 20px rgba(74, 144, 226, 0.35);
        }

        #modal-footer button:active {
            transform: translateY(0) scale(0.98);
            transition: all 0.1s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .logo {
            display: flex;
            align-items: center;
            font-size: 2rem;
            font-weight: bold;
            color: white;
            text-decoration: none;
            text-shadow: 0 2px 8px rgba(44, 62, 80, 0.12);
        }

        .logo-img {
            margin-right: 0.5rem;
            height: 70px;
        }

        .header-right-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .currency-display {
            display: flex;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.15);
            padding: 0.5rem 1rem;
            border-radius: 25px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .currency-display:hover {
            background-color: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .currency-icon {
            font-size: 1.2rem;
            margin-right: 0.5rem;
            color: #ffd700;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        .currency-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .currency-amount {
            font-size: 1.1rem;
            font-weight: bold;
            color: white;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
            line-height: 1;
        }

        .currency-label {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.8);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 2px;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .header-right-section {
                gap: 0.5rem;
            }

            .currency-display {
                padding: 0.4rem 0.8rem;
            }

            .currency-amount {
                font-size: 1rem;
            }

            .currency-label {
                font-size: 0.7rem;
            }
        }
        
        /* Modal Overlay Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s, visibility 0.3s;
        }
        
        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .modal-overlay .modal {
            background-color: white;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            transform: translateY(-20px);
            transition: transform 0.3s;
        }
        
        .modal-overlay.active .modal {
            transform: translateY(0);
        }
        
        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: #4a90e2;
        }
        
        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
        }
        
        .modal-body {
            padding: 1.5rem;
        }
        
        .booking-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }
        
        .btn-secondary {
            background-color: transparent;
            border: 1px solid #ddd;
            color: #333;
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .btn-secondary:hover {
            background-color: #f5f5f5;
            border-color: #bbb;
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .btn-secondary:active {
            transform: translateY(0) scale(0.98);
            transition: all 0.1s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .btn-primary {
            background-color: #4a90e2;
            border: none;
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(74, 144, 226, 0.2);
        }
        
        .btn-primary:hover {
            background-color: #357abd;
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 6px 20px rgba(74, 144, 226, 0.35);
        }

        .btn-primary:active {
            transform: translateY(0) scale(0.98);
            transition: all 0.1s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Unread notification styling */
        .activity-item.unread {
            background-color: #f0f7ff;
            border-left: 3px solid #4a90e2;
        }
        
        .activity-item.unread .activity-text {
            font-weight: 600;
        }
        
        .activity-item {
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .activity-item:hover {
            background-color: #f8f9fa;
            transform: translateX(4px);
        }
        
        .activity-message {
            color: #666;
            font-size: 0.9rem;
            margin-top: 0.25rem;
            line-height: 1.4;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="navbar">
            <a href="#" class="logo">
                <img src="{{asset('images/MentorHub.png')}}" alt="UCTutor Logo" class="logo-img">
                
            </a>
            <button class="menu-toggle" id="menu-toggle">☰</button>
            <nav class="nav-links" id="nav-links">
                <a href="#" class="active">Dashboard</a>
                <a href="{{route('student.book-session')}}">Book Session</a>
                <a href="{{route('student.my-sessions')}}">Activities</a>
                <a href="{{route('student.schedule')}}">Schedule</a>
            </nav>
            <div class="header-right-section">
                <!-- Currency Display -->
                <div class="currency-display">
                    <div class="currency-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="currency-info">
                        <div class="currency-amount" id="currency-amount">₱0.00</div>
                        <div class="currency-label">Balance</div>
                    </div>
                </div>
                
                <!-- Profile Dropdown -->
                <div class="profile-dropdown-container" style="position: relative;">
                    <div class="profile-icon" id="profile-icon">
                        @auth('student')
                            @if(Auth::guard('student')->user()->profile_picture)
                                <img src="{{ route('student.profile.picture') }}?v={{ time() }}" alt="Profile Picture" class="profile-icon-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center; background-color: #f5f5f5; color: #666; font-weight: bold; font-size: 1.2rem; border-radius: 50%;">{{ substr(Auth::guard('student')->user()->first_name, 0, 1) }}{{ substr(Auth::guard('student')->user()->last_name, 0, 1) }}</div>
                            @else
                                {{ substr(Auth::guard('student')->user()->first_name, 0, 1) }}{{ substr(Auth::guard('student')->user()->last_name, 0, 1) }}
                            @endif
                        @else
                            <a href="{{ route('login.student') }}" class="login-link">Login</a>
                        @endauth
                    </div>
                    @auth('student')
                    <div class="dropdown-menu" id="dropdown-menu">
                        <a href="{{ route('student.profile.edit') }}">My Profile</a>
                        <a href="{{ route('student.settings') }}">Achievements</a>
                        <a href="{{ route('student.report-problem') }}">Report a Problem</a>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" method="POST" action="{{ route('student.logout') }}" style="display: none;">
                            @csrf
                        </form>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>
    
    <!-- Main Content -->
    <main>
        <div class="dashboard-header" style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem;">
            <div style="display: flex; flex-direction: column; align-items: flex-start;">
                <div class="greeting">
                    @auth('student')
                        Welcome, {{ ucwords(Auth::guard('student')->user()->first_name . ' ' . Auth::guard('student')->user()->last_name) }}!
                    @else
                        Welcome to MentorHub!
                    @endauth
                </div>
                @auth('student')
                    <div class="badge badge-student">Student</div>
                @endauth
            </div>
            <div class="date-time" id="current-date-time">Tuesday, May 13, 2025</div>
        </div>
        
        <!-- Upcoming Sessions -->
        <h2 class="section-title">Upcoming Sessions</h2>
        <div class="sessions-container" id="sessions-container">
            <!-- Sessions will be loaded here dynamically -->
        </div>
        
        <!-- Quick Links -->
        <h2 class="section-title">Quick Links</h2>
        <div class="quick-links">
            <a href="{{ route('student.notifications') }}" class="quick-link">
                <div class="quick-link-icon"><i class="fas fa-bell"></i></div>
                <div>Notifications</div>
            </a>
            <a href="{{ route('student.messages') }}" class="quick-link">
                <div class="quick-link-icon"><i class="fas fa-comments"></i></div>
                <div>Messages</div> 
            </a>
            <a href="{{ route('student.schedule') }}" class="quick-link">
                <div class="quick-link-icon"><i class="fas fa-calendar-alt"></i></div>
                <div>My Schedule</div>
            </a>
            <a href="{{ route('student.assignments.post') }}" class="quick-link">
                <div class="quick-link-icon"><i class="fas fa-file-alt"></i></div>
                <div>Post Assignments</div>
            </a>
            <a href="#" class="quick-link" onclick="viewWallet()">
                <div class="quick-link-icon"><i class="fas fa-wallet"></i></div>
                <div>Wallet</div>
            </a>
        </div>
        
        <!-- Notifications -->
        <h2 class="section-title">Notifications</h2>
        <div class="activity-container">
            @forelse($notifications ?? [] as $notification)
                <div class="activity-item {{ !$notification->is_read ? 'unread' : '' }}" data-id="{{ $notification->id }}">
                    <div class="activity-icon">
                        @if($notification->type === 'problem_report_response')
                            <i class="fas fa-exclamation-circle"></i>
                        @elseif($notification->type === 'booking_confirmed')
                            <i class="fas fa-calendar-check"></i>
                        @elseif($notification->type === 'activity_posted')
                            <i class="fas fa-tasks"></i>
                        @elseif($notification->type === 'payment_received')
                            <i class="fas fa-money-bill-wave"></i>
                        @elseif($notification->type === 'new_message')
                            <i class="fas fa-envelope"></i>
                        @elseif($notification->type === 'achievement_unlocked')
                            <i class="fas fa-trophy"></i>
                        @elseif($notification->type === 'achievement_progress')
                            <i class="fas fa-chart-line"></i>
                        @else
                            <i class="fas fa-bell"></i>
                        @endif
                    </div>
                    <div class="activity-content">
                        <div class="activity-text">{{ $notification->title }}</div>
                        <div class="activity-message" style="color: #666; font-size: 0.9rem; margin-top: 0.25rem;">{{ $notification->message }}</div>
                        <div class="activity-time">{{ $notification->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            @empty
                <div class="activity-item" style="text-align: center; padding: 2rem;">
                    <div class="activity-icon"><i class="fas fa-bell-slash"></i></div>
                    <div class="activity-content">
                        <div class="activity-text" style="color: #666;">No notifications yet</div>
                    </div>
                </div>
            @endforelse
            @if(isset($notifications) && $notifications->count() > 0)
                <div style="text-align: center; margin-top: 1rem;">
                    <a href="{{ route('student.notifications') }}" style="color: #4a90e2; text-decoration: none; font-weight: 600;">
                        View All Notifications <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            @endif
        </div>
    </main>

    <!-- Session Details Modal -->
    <div id="session-modal" class="session-modal">
        <div class="session-modal-content">
            <span class="session-modal-close-btn">&times;</span>
            <h2>Session Details</h2>
            <div class="session-modal-body">
                <!-- Session details will be populated here -->
            </div>
            <div class="session-modal-footer" id="modal-footer">
                <button id="modal-message-btn">Message</button>
                <button id="modal-close-btn">Close</button>
            </div>
        </div>
    </div>
    
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
    </footer>

    <!-- Report a Problem Modal -->
    <div class="modal-overlay" id="report-problem-modal">
        <div class="modal">
            <div class="modal-header">
                <div class="modal-title">Report a Problem</div>
                <button class="modal-close" onclick="closeReportProblemModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="report-problem-form" method="POST" action="{{ route('student.report-problem.store') }}">
                    @csrf
                    
                    <div style="margin-bottom: 1.5rem;">
                        <h4 style="margin-bottom: 0.5rem;">Problem Type</h4>
                        <select name="problem_type" id="problem-type" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem;">
                            <option value="">Select a problem type...</option>
                            <option value="technical">Technical Issue</option>
                            <option value="payment">Payment Issue</option>
                            <option value="tutor">Tutor Related</option>
                            <option value="booking">Booking Issue</option>
                            <option value="account">Account Issue</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div style="margin-bottom: 1.5rem;">
                        <h4 style="margin-bottom: 0.5rem;">Subject</h4>
                        <input type="text" name="subject" id="problem-subject" required placeholder="Brief description of the problem" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem;">
                    </div>
                    
                    <div style="margin-bottom: 1.5rem;">
                        <h4 style="margin-bottom: 0.5rem;">Description</h4>
                        <textarea name="description" id="problem-description" required placeholder="Please provide detailed information about the problem you're experiencing..." style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem; resize: vertical; min-height: 150px;"></textarea>
                    </div>
                    
                    <div class="booking-actions" style="margin-top: 1.5rem;">
                        <button type="button" class="btn-secondary" onclick="closeReportProblemModal()">Cancel</button>
                        <button type="submit" class="btn-primary">Submit Report</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menu-toggle');
            const navLinks = document.getElementById('nav-links');
            
            menuToggle.addEventListener('click', function() {
                navLinks.classList.toggle('active');
            });
            
            // Profile dropdown functionality
            const profileIcon = document.getElementById('profile-icon');
            const dropdownMenu = document.getElementById('dropdown-menu');
            
            if (profileIcon && dropdownMenu) {
                profileIcon.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdownMenu.classList.toggle('active');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!profileIcon.contains(e.target)) {
                        dropdownMenu.classList.remove('active');
                    }
                });
            }
            
            const dateTimeElement = document.getElementById('current-date-time');
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const currentDate = new Date();
            if(dateTimeElement) {
                dateTimeElement.textContent = currentDate.toLocaleDateString('en-US', options);
            }
            
            loadUpcomingSessions();
            
            // Initialize currency display
            initializeCurrencyDisplay();
            loadCurrencyData();
        });

        function loadUpcomingSessions() {
            fetch('{{ route("student.sessions.upcoming") }}')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.statusText);
                    }
                    return response.json();
                })
                .then(sessions => {
                    const container = document.getElementById('sessions-container');
                    if (!container) return;
                    container.innerHTML = '';

                    if (sessions.length === 0) {
                        container.innerHTML = '<div class="no-sessions-card" style="background-color: white; padding: 2rem; text-align: center; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">No upcoming sessions.</div>';
                        return;
                    }

                    sessions.forEach(session => {
                        const sessionDate = new Date(session.date);
                        const today = new Date();
                        today.setHours(0, 0, 0, 0);

                        let dayDisplay = 'INVALID DATE';
                        if (!isNaN(sessionDate)) {
                             if (sessionDate.getTime() === today.getTime()) {
                                dayDisplay = 'TODAY';
                            } else {
                                dayDisplay = sessionDate.toLocaleDateString('en-US', { weekday: 'short' }).toUpperCase();
                            }
                        }
                        
                        const startTime = new Date(`1970-01-01T${session.start_time}`);
                        const hourDisplay = !isNaN(startTime) ? startTime.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true }) : 'INVALID TIME';

                        const card = document.createElement('div');
                        card.className = 'session-card';
                        card.innerHTML = `
                            <div class="session-time">
                                <div class="session-day">${dayDisplay}</div>
                                <div class="session-hour">${hourDisplay}</div>
                            </div>
                            <div class="session-details">
                                <div class="session-subject">${session.tutor && session.tutor.specialization ? session.tutor.specialization.split(',')[0] : 'General Tutoring'}</div>
                                <div class="session-tutor">With ${session.tutor ? `${session.tutor.first_name} ${session.tutor.last_name}` : 'N/A'}</div>
                            </div>
                            <div class="session-actions">
                                <button class="btn view-details-btn" style="background-color: #4a90e2; color: white; text-decoration: none; padding: 8px 16px; border-radius: 20px; border: none; cursor: pointer;">View</button>
                            </div>
                        `;
                        
                        card.querySelector('.view-details-btn').addEventListener('click', () => {
                            showSessionModal(session);
                        });
                        
                        container.appendChild(card);
                    });
                })
                .catch(error => {
                    console.error('Error loading upcoming sessions:', error);
                    const container = document.getElementById('sessions-container');
                    if(container) {
                        container.innerHTML = '<div class="no-sessions-card" style="background-color: white; padding: 2rem; text-align: center; border-radius: 8px; color: #dc3545;">Could not load sessions. Please try again later.</div>';
                    }
                });
        }

        function showSessionModal(session) {
            const modal = document.getElementById('session-modal');
            const modalBody = modal.querySelector('.session-modal-body');

            if (!modal || !modalBody) return;

            const sessionDate = new Date(session.date);
            const formattedDate = !isNaN(sessionDate) ? sessionDate.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) : 'Invalid Date';
            
            const startTime = new Date(`1970-01-01T${session.start_time}`);
            const formattedStartTime = !isNaN(startTime) ? startTime.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true }) : 'Invalid Time';

            const endTime = new Date(`1970-01-01T${session.end_time}`);
            const formattedEndTime = !isNaN(endTime) ? endTime.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true }) : 'Invalid Time';

            const statusClass = session.status ? session.status.toLowerCase() : '';

            let tutorProfilePicHtml = '';
            if (session.tutor && session.tutor.profile_picture) {
                const profilePicUrl = `/tutor/profile/picture/${session.tutor.id}`;
                tutorProfilePicHtml = `
                    <div class="tutor-profile-pic-container">
                        <img src="${profilePicUrl}" alt="Tutor Profile Picture" class="tutor-profile-pic">
                    </div>
                `;
            } else if (session.tutor) {
                const initials = (session.tutor.first_name ? session.tutor.first_name.charAt(0) : '') + (session.tutor.last_name ? session.tutor.last_name.charAt(0) : '');
                tutorProfilePicHtml = `
                    <div class="tutor-profile-pic-container">
                        <div class="profile-icon" style="width: 80px; height: 80px; font-size: 2rem; margin: 0 auto; display: flex; align-items: center; justify-content: center;">
                            ${initials}
                        </div>
                    </div>
                `;
            }

            modalBody.innerHTML = `
                ${tutorProfilePicHtml}
                <p><strong>Tutor:</strong> ${session.tutor ? `${session.tutor.first_name} ${session.tutor.last_name}` : 'N/A'}</p>
                <p><strong>Subject:</strong> ${session.tutor && session.tutor.specialization ? session.tutor.specialization.split(',')[0] : 'General Tutoring'}</p>
                <p><strong>Date:</strong> ${formattedDate}</p>
                <p><strong>Time:</strong> ${formattedStartTime} - ${formattedEndTime}</p>
                <p><strong>Type:</strong> ${session.session_type === 'face_to_face' ? 'Face-to-Face' : 'Online'}</p>
                <p><strong>Status:</strong> <span class="status-badge ${statusClass}">${session.status}</span></p>
                ${session.notes ? `<p><strong>Notes:</strong> ${session.notes}</p>` : ''}
            `;

            modal.style.display = 'flex';

            const closeBtn = modal.querySelector('.session-modal-close-btn');
            const closeFooterBtn = modal.querySelector('#modal-close-btn');
            const messageBtn = modal.querySelector('#modal-message-btn');

            if(closeBtn) closeBtn.onclick = () => modal.style.display = 'none';
            if(closeFooterBtn) closeFooterBtn.onclick = () => modal.style.display = 'none';
            
            if(messageBtn && session.tutor) {
                messageBtn.onclick = () => {
                    window.location.href = `{{ route('student.messages') }}?tutor_id=${session.tutor.id}`;
                };
            } else if(messageBtn) {
                messageBtn.style.display = 'none';
            }
            
            window.onclick = (event) => {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            };
        }

        function viewWallet() {
            window.location.href = "{{ route('student.wallet') }}";
        }

        // Currency display functionality
        function initializeCurrencyDisplay() {
            const currencyDisplay = document.querySelector('.currency-display');
            if (currencyDisplay) {
                currencyDisplay.addEventListener('click', function() {
                    viewWallet();
                });
            }
        }

        // Load currency data from API
        function loadCurrencyData() {
            fetch('{{ route("student.wallet.balance") }}')
                .then(response => response.json())
                .then(data => {
                    const currencyAmount = document.getElementById('currency-amount');
                    if (currencyAmount) {
                        currencyAmount.textContent = '₱' + parseFloat(data.balance).toFixed(2);
                    }
                })
                .catch(error => {
                    console.error('Error loading wallet balance:', error);
                });
        }
        
        // Report Problem Modal Functions
        function openReportProblemModal() {
            document.getElementById('report-problem-modal').classList.add('active');
            // Close the dropdown menu
            const dropdownMenu = document.getElementById('dropdown-menu');
            if (dropdownMenu) {
                dropdownMenu.classList.remove('active');
            }
        }
        
        function closeReportProblemModal() {
            document.getElementById('report-problem-modal').classList.remove('active');
            document.getElementById('report-problem-form').reset();
        }
        
        // Close report problem modal when clicking outside
        window.addEventListener('click', function(e) {
            if (e.target === document.getElementById('report-problem-modal')) {
                closeReportProblemModal();
            }
        });

        // Footer Modal Functions
        function getScrollbarWidth() {
            // Create a temporary div to measure scrollbar width
            const outer = document.createElement('div');
            outer.style.visibility = 'hidden';
            outer.style.overflow = 'scroll';
            outer.style.msOverflowStyle = 'scrollbar';
            document.body.appendChild(outer);
            
            const inner = document.createElement('div');
            outer.appendChild(inner);
            
            const scrollbarWidth = outer.offsetWidth - inner.offsetWidth;
            outer.parentNode.removeChild(outer);
            
            return scrollbarWidth;
        }

        function openFooterModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                // Calculate scrollbar width before hiding it
                const scrollbarWidth = getScrollbarWidth();
                
                // Store original padding-right if it exists
                const originalPaddingRight = document.body.style.paddingRight || '';
                
                // Add padding to prevent layout shift
                document.body.style.paddingRight = scrollbarWidth + 'px';
                document.body.style.overflow = 'hidden';
                
                modal.style.display = 'flex';
                // Force reflow to ensure display is set before adding class
                void modal.offsetWidth;
                modal.classList.add('show');
            }
        }

        function closeFooterModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('show');
                
                // Wait for animation to complete before restoring scrollbar
                setTimeout(() => {
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';
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
        
        // Mark notification as read when clicked
        document.querySelectorAll('.activity-item[data-id]').forEach(item => {
            item.addEventListener('click', function() {
                const notificationId = this.getAttribute('data-id');
                if (notificationId && this.classList.contains('unread')) {
                    // Mark as read via AJAX
                    fetch(`/student/notifications/${notificationId}/mark-read`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.classList.remove('unread');
                            const activityText = this.querySelector('.activity-text');
                            if (activityText) {
                                activityText.style.fontWeight = 'normal';
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error marking notification as read:', error);
                    });
                }
            });
        });
    </script>
</body>
</html>