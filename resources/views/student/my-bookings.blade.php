<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('style/dashboard.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>My Sessions | MentorHub</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Additional styles for the my-bookings page */
        .bookings-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .tabs {
            display: flex;
            border-bottom: 2px solid #ddd;
            margin-bottom: 2rem;
        }
        
        .tab-link {
            padding: 1rem 1.5rem;
            cursor: pointer;
            border: none;
            background-color: transparent;
            font-size: 1rem;
            font-weight: 500;
            color: #666;
            position: relative;
            top: 2px;
        }
        
        .tab-link.active {
            color: #4a90e2;
            border-bottom: 2px solid #4a90e2;
            font-weight: 600;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .booking-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .booking-details {
            flex: 1;
            min-width: 250px;
        }
        
        .booking-tutor {
            font-weight: 600;
            font-size: 1.1rem;
            color: #4a90e2;
        }
        
        .booking-info {
            color: #666;
            margin-top: 0.5rem;
            line-height: 1.4;
        }
        
        .booking-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            text-decoration: none;
            font-size: 0.9rem;
            cursor: pointer;
            border: none;
            transition: all 0.3s;
            display: inline-block;
            text-align: center;
        }
        
        .btn-primary {
            background-color: #4a90e2;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #3a7ccc;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        
        .btn-success {
            background-color: #28a745;
            color: white;
        }
        
        .btn-success:hover {
            background-color: #218838;
        }
        
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #c82333;
        }
        
        .status-badge {
            display: inline-block;
            padding: 0.3em 0.7em;
            font-size: 0.75em;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
            color: #fff;
        }
        
        .status-pending { background-color: #ffc107; color: #000; }
        .status-accepted { background-color: #28a745; }
        .status-rejected { background-color: #dc3545; }
        .status-completed { background-color: #17a2b8; }
        .status-cancelled { background-color: #6c757d; }
        
        .no-bookings {
            text-align: center;
            padding: 3rem;
            color: #666;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .no-bookings h3 {
            margin-bottom: 1rem;
            color: #4a90e2;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
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
        }

        .dropdown-menu a:hover {
            background-color: #f5f5f5;
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
        
        @media (max-width: 768px) {
            .booking-card {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .booking-actions {
                width: 100%;
                justify-content: flex-start;
            }
            
            .tabs {
                flex-wrap: wrap;
            }

            .header-right-section {
                flex-direction: column;
                gap: 0.5rem;
                width: 100%;
                margin-top: 1rem;
            }

            .currency-display {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Header (same as dashboard) -->
    <header>
        <div class="navbar">
            <a href="#" class="logo">
                <img src="{{asset('images/MentorHub.png')}}" alt="MentorHub Logo" class="logo-img">
            </a>
            <button class="menu-toggle" id="menu-toggle">☰</button>
            <nav class="nav-links" id="nav-links">
                <a href="{{route('student.dashboard')}}">Dashboard</a>
                <a href="{{route('student.book-session')}}">Book Session</a>
                <a href="{{route('student.my-bookings')}}" class="active">Activities</a>
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
                                <img src="{{ asset('storage/' . Auth::guard('student')->user()->profile_picture) }}?{{ time() }}" alt="Profile Picture" class="profile-icon-img">
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
        <div class="bookings-container">
            <div class="dashboard-header" style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem;">
                <div style="display: flex; flex-direction: column; align-items: flex-start;">
                    <h1 class="greeting">My Sessions</h1>
                    <div class="badge badge-student">Student</div>
                </div>
                <div class="date-time" id="current-date-time">Tuesday, May 13, 2025</div>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Tabs -->
            <div class="tabs">
                <button class="tab-link active" onclick="openTab(event, 'upcoming')">Upcoming Sessions</button>
                <button class="tab-link" onclick="openTab(event, 'pending')">Pending Approval</button>
                <button class="tab-link" onclick="openTab(event, 'history')">Session History</button>
            </div>

            <!-- Upcoming Sessions Tab -->
            <div id="upcoming" class="tab-content active">
                <h2>Upcoming Sessions</h2>
                @php
                    $upcomingBookings = $bookings->where('status', 'accepted')->where('date', '>=', now()->toDateString());
                @endphp
                
                @forelse($upcomingBookings as $booking)
                    <div class="booking-card">
                        <div class="booking-details">
                            <div class="booking-tutor">{{ $booking->tutor->first_name }} {{ $booking->tutor->last_name }}</div>
                            <div class="booking-info">
                                <strong>Subject:</strong> {{ $booking->tutor->specialization ?? 'General Tutoring' }}<br>
                                <strong>Date:</strong> {{ $booking->formatted_date }}<br>
                                <strong>Time:</strong> {{ $booking->formatted_start_time }} - {{ $booking->formatted_end_time }}<br>
                                <strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $booking->session_type)) }}<br>
                                <strong>Status:</strong> <span class="status-badge status-{{ strtolower($booking->status) }}">{{ ucfirst($booking->status) }}</span>
                            </div>
                        </div>
                        <div class="booking-actions">
                            <a href="{{ route('student.messages') }}" class="btn btn-primary">Message Tutor</a>
                            <button class="btn btn-danger" onclick="cancelSession({{ $booking->id }})">Cancel</button>
                        </div>
                    </div>
                @empty
                    <div class="no-bookings">
                        <h3>No upcoming sessions</h3>
                        <p>You don't have any upcoming sessions scheduled.</p>
                        <a href="{{ route('student.book-session') }}" class="btn btn-primary">Book a Session</a>
                    </div>
                @endforelse
            </div>

            <!-- Pending Approval Tab -->
            <div id="pending" class="tab-content">
                <h2>Pending Approval</h2>
                @php
                    $pendingBookings = $bookings->where('status', 'pending');
                @endphp
                
                @forelse($pendingBookings as $booking)
                    <div class="booking-card">
                        <div class="booking-details">
                            <div class="booking-tutor">{{ $booking->tutor->first_name }} {{ $booking->tutor->last_name }}</div>
                            <div class="booking-info">
                                <strong>Subject:</strong> {{ $booking->tutor->specialization ?? 'General Tutoring' }}<br>
                                <strong>Date:</strong> {{ $booking->formatted_date }}<br>
                                <strong>Time:</strong> {{ $booking->formatted_start_time }} - {{ $booking->formatted_end_time }}<br>
                                <strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $booking->session_type)) }}<br>
                                <strong>Status:</strong> <span class="status-badge status-{{ strtolower($booking->status) }}">{{ ucfirst($booking->status) }}</span>
                            </div>
                        </div>
                        <div class="booking-actions">
                            <a href="{{ route('student.messages') }}" class="btn btn-primary">Message Tutor</a>
                            <button class="btn btn-danger" onclick="cancelSession({{ $booking->id }})">Cancel</button>
                        </div>
                    </div>
                @empty
                    <div class="no-bookings">
                        <h3>No pending sessions</h3>
                        <p>You don't have any sessions waiting for approval.</p>
                    </div>
                @endforelse
            </div>

            <!-- Session History Tab -->
            <div id="history" class="tab-content">
                <h2>Session History</h2>
                @php
                    $historyBookings = $bookings->whereIn('status', ['completed', 'rejected', 'cancelled'])->where('date', '<', now()->toDateString());
                @endphp
                
                @forelse($historyBookings as $booking)
                    <div class="booking-card">
                        <div class="booking-details">
                            <div class="booking-tutor">{{ $booking->tutor->first_name }} {{ $booking->tutor->last_name }}</div>
                            <div class="booking-info">
                                <strong>Subject:</strong> {{ $booking->tutor->specialization ?? 'General Tutoring' }}<br>
                                <strong>Date:</strong> {{ $booking->formatted_date }}<br>
                                <strong>Time:</strong> {{ $booking->formatted_start_time }} - {{ $booking->formatted_end_time }}<br>
                                <strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $booking->session_type)) }}<br>
                                <strong>Status:</strong> <span class="status-badge status-{{ strtolower($booking->status) }}">{{ ucfirst($booking->status) }}</span>
                            </div>
                        </div>
                        <div class="booking-actions">
                            @if($booking->status === 'completed')
                                <button class="btn btn-success">Rate Session</button>
                            @endif
                            <a href="{{ route('student.messages') }}" class="btn btn-secondary">Message Tutor</a>
                        </div>
                    </div>
                @empty
                    <div class="no-bookings">
                        <h3>No session history</h3>
                        <p>You haven't completed any sessions yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </main>
    
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
    
    @include('layouts.footer-modals')
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const menuToggle = document.getElementById('menu-toggle');
            const navLinks = document.getElementById('nav-links');
            
            menuToggle.addEventListener('click', function() {
                navLinks.classList.toggle('active');
            });
            
            // Profile dropdown
            const profileIcon = document.getElementById('profile-icon');
            const dropdownMenu = document.getElementById('dropdown-menu');
            
            if (profileIcon && dropdownMenu) {
                profileIcon.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdownMenu.classList.toggle('active');
                });
                
                document.addEventListener('click', function(e) {
                    if (!profileIcon.contains(e.target)) {
                        dropdownMenu.classList.remove('active');
                    }
                });
            }
            
            // Update current date and time
            const dateTimeElement = document.getElementById('current-date-time');
            if (dateTimeElement) {
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                const currentDate = new Date();
                dateTimeElement.textContent = currentDate.toLocaleDateString('en-US', options);
            }

            // Initialize currency display
            initializeCurrencyDisplay();
            loadCurrencyData();
        });

        function initializeCurrencyDisplay() {
            const currencyDisplay = document.querySelector('.currency-display');
            if (currencyDisplay) {
                currencyDisplay.addEventListener('click', function() {
                    viewWallet();
                });
            }
        }

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

        function viewWallet() {
            window.location.href = "{{ route('student.wallet') }}";
        }
        
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].classList.remove('active');
            }
            tablinks = document.getElementsByClassName("tab-link");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].classList.remove('active');
            }
            document.getElementById(tabName).classList.add('active');
            evt.currentTarget.classList.add('active');
        }
        
        function cancelSession(sessionId) {
            if (confirm('Are you sure you want to cancel this session?')) {
                // Here you would typically make an AJAX request to cancel the session
                // For now, we'll just show an alert
                alert('Session cancellation feature will be implemented soon.');
            }
        }
    </script>
    
    @include('layouts.footer-js')
</body>
</html>
