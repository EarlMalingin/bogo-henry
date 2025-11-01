<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('style/Dashboard.css')}}">
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
            transition: opacity 0.2s;
        }

        #modal-footer #modal-close-btn {
            background-color: #6c757d;
            color: white;
            margin-left: 0.5rem;
        }

        #modal-footer #modal-message-btn {
            background-color: #4a90e2;
            color: white;
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
                                <img src="{{ asset('storage/' . Auth::guard('student')->user()->profile_picture) }}?v={{ file_exists(public_path('storage/' . Auth::guard('student')->user()->profile_picture)) ? filemtime(public_path('storage/' . Auth::guard('student')->user()->profile_picture)) : time() }}" alt="Profile Picture" class="profile-icon-img">
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
                        <a href="#">Settings</a>
                        <a href="#">Report a Problem</a>
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
            <a href="#" class="quick-link" onclick="alert('Notifications functionality coming soon!'); return false;">
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
        
        <!-- Recent Activity -->
        <h2 class="section-title">Recent Activity</h2>
        <div class="activity-container">
            <div class="activity-item">
                <div class="activity-icon"><i class="fas fa-graduation-cap"></i></div>
                <div class="activity-content">
                    <div class="activity-text">You completed a Calculus session with Ms. Sarah Johnson</div>
                    <div class="activity-time">Today, 11:30 AM</div>
                </div>
            </div>
            
            <div class="activity-item">
                <div class="activity-icon"><i class="fas fa-file-upload"></i></div>
                <div class="activity-content">
                    <div class="activity-text">Submitted Physics assignment: "Motion and Forces"</div>
                    <div class="activity-time">Yesterday, 3:15 PM</div>
                </div>
            </div>
            
            <div class="activity-item">
                <div class="activity-icon"><i class="fas fa-bell"></i></div>
                <div class="activity-content">
                    <div class="activity-text">Reminder: Chemistry quiz scheduled for Friday</div>
                    <div class="activity-time">May 11, 2025</div>
                </div>
            </div>
            
            <a href="#" class="activity-item">
                <div class="activity-icon"><i class="fas fa-star"></i></div>
                <div class="activity-content">
                    <div class="activity-text">You rated your Computer Science session 5 stars</div>
                    <div class="activity-time">May 10, 2025</div>
                </div>
            </a>
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
                const profilePicUrl = `{{ asset('storage') }}/${session.tutor.profile_picture}`;
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

            if(closeBtn) closeBtn.onclick = () => modal.style.display = 'none';
            if(closeFooterBtn) closeFooterBtn.onclick = () => modal.style.display = 'none';
            
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
    </script>
</body>
</html>