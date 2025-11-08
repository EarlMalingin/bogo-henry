<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings | MentorHub</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            line-height: 1.6;
            color: #333;
            background:
                linear-gradient(rgba(255, 255, 255, 0.85), rgba(255, 255, 255, 0.85)),
                url('{{ asset('images/Uc-background.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header Styles */
        header {
            background: linear-gradient(135deg, #2d7dd2, #4a3dd9);
            color: white;
            padding: 1rem 0;
            width: 100%;
            position: fixed;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 5%;
            max-width: 1200px;
            margin: 0 auto;
            flex-wrap: wrap;
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

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .nav-links {
            display: flex;
            gap: 1rem;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            padding: 0.5rem 1rem;
            border-radius: 25px;
        }

        .nav-links a:hover, .nav-links a.active {
            background-color: rgba(255,255,255,0.2);
            transform: translateY(-2px);
        }

        .profile-dropdown-container {
            position: relative;
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

        /* Main Content Styles */
        main {
            flex: 1;
            padding: 0 1rem;
            margin-top: 80px;
            max-width: 1200px;
            width: 100%;
            align-self: center;
        }
        .bookings-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .bookings-container h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 2rem;
        }

        .tab-content h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 1.5rem;
        }
        .tabs {
            display: flex;
            border-bottom: 2px solid #e5e7eb;
            margin-bottom: 2rem;
            gap: 0.5rem;
        }
        .tab-link {
            padding: 1rem 1.5rem;
            cursor: pointer;
            border: none;
            background-color: transparent;
            font-size: 1rem;
            font-weight: 500;
            color: #6b7280;
            position: relative;
            transition: all 0.3s;
        }
        .tab-link:hover {
            color: #4a90e2;
            background-color: rgba(74, 144, 226, 0.05);
        }
        .tab-link.active {
            color: #4a90e2;
            border-bottom: 3px solid #4a90e2;
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
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1.5rem;
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
        }

        .booking-card:hover {
            box-shadow: 0 5px 20px rgba(0,0,0,0.12);
            transform: translateY(-2px);
        }

        .booking-details {
            flex: 1;
            min-width: 250px;
        }

        .booking-student {
            font-weight: 600;
            font-size: 1.25rem;
            color: #2d3748;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .booking-student i {
            color: #4a90e2;
            font-size: 1.1rem;
        }

        .booking-info {
            color: #666;
            margin-top: 0.5rem;
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            font-size: 0.95rem;
        }

        .booking-info-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .booking-info-item i {
            color: #4a90e2;
            font-size: 0.9rem;
        }

        .booking-actions {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.7rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.9rem;
            cursor: pointer;
            border: none;
            transition: all 0.3s;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .btn-primary {
            background: linear-gradient(135deg, #4a90e2, #3a7cdd);
            color: white;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            color: white;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.4em 0.8em;
            font-size: 0.85em;
            font-weight: 600;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 20px;
            color: #fff;
            gap: 0.3em;
        }
        .status-rejected { 
            background-color: #dc3545; 
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
        }
        .status-completed { 
            background-color: #28a745; 
            box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
        }
        .status-cancelled { 
            background-color: #6c757d; 
            box-shadow: 0 2px 8px rgba(108, 117, 125, 0.3);
        }
        /* Footer */
        footer {
            background-color: #333;
            color: white;
            padding: 1.5rem 0;
            margin-top: 3rem;
            width: 100%;
        }

        .footer-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .footer-links {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: center;
        }

        .footer-links a {
            color: #ccc;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s;
            padding: 0.3rem 0;
        }

        .footer-links a:hover {
            color: white;
            transform: translateY(-2px);
        }

        .copyright {
            font-size: 0.9rem;
            color: #aaa;
            text-align: center;
            width: 100%;
            margin-top: 0.5rem;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }

            .nav-links {
                display: none;
                width: 100%;
                flex-direction: column;
                gap: 1rem;
                margin-top: 1rem;
                text-align: center;
            }

            .nav-links.active {
                display: flex;
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

            .bookings-container h1 {
                font-size: 1.5rem;
            }

            .tab-content h2 {
                font-size: 1.25rem;
            }

            .booking-card {
                flex-direction: column;
                align-items: flex-start;
            }

            .booking-actions {
                width: 100%;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* Footer Modal Styles */
        .footer-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .footer-modal.show {
            display: flex;
            opacity: 1;
        }

        .footer-modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 0;
            border-radius: 12px;
            width: 90%;
            max-width: 800px;
            max-height: 80vh;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            transform: translateY(-20px);
            transition: transform 0.3s ease;
        }

        .footer-modal.show .footer-modal-content {
            transform: translateY(0);
        }

        .footer-modal-header {
            background: linear-gradient(135deg, #4a90e2, #5637d9);
            color: white;
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-modal-header h2 {
            margin: 0;
            font-size: 1.5rem;
        }

        .footer-modal-body {
            padding: 2rem;
            max-height: 60vh;
            overflow-y: auto;
            line-height: 1.6;
        }

        .footer-modal-body h3 {
            color: #2c3e50;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
            font-size: 1.2rem;
        }

        .footer-modal-body p {
            margin-bottom: 1rem;
            color: #555;
        }

        .footer-modal-body ul {
            margin-bottom: 1rem;
            padding-left: 1.5rem;
        }

        .footer-modal-body li {
            margin-bottom: 0.5rem;
            color: #555;
        }

        .footer-modal-close {
            background: none;
            border: none;
            color: white;
            font-size: 2rem;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background-color 0.3s ease;
        }

        .footer-modal-close:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .footer-modal-body a {
            color: #4a90e2;
            text-decoration: none;
            font-weight: 500;
        }

        .footer-modal-body a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <div class="navbar">
            <a href="#" class="logo">
                <img src="{{ asset('images/MentorHub.png') }}" alt="MentorHub Logo" class="logo-img">
            </a>
            <button class="menu-toggle" id="menu-toggle">☰</button>
            <nav class="nav-links" id="nav-links">
                <a href="{{ route('tutor.dashboard') }}">Dashboard</a>
                <a href="{{ route('tutor.bookings.index') }}" class="active">My Bookings</a>
                <a href="{{ route('tutor.students') }}">Students</a>
                <a href="{{ route('tutor.schedule') }}">Schedule</a>
                
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
                        @if($tutor->profile_picture)
                            <img src="{{ asset('storage/' . $tutor->profile_picture) }}?{{ time() }}" alt="Profile Picture" class="profile-icon-img">
                        @else
                            {{ strtoupper(substr($tutor->first_name, 0, 1) . substr($tutor->last_name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="dropdown-menu" id="dropdown-menu">
                        <a href="{{ route('tutor.profile.edit') }}">My Profile</a>
                        <a href="{{ route('tutor.settings') }}">Achievements</a>
                        <a href="{{ route('tutor.report-problem') }}">Report a Problem</a>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" method="POST" action="{{ route('tutor.logout') }}" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main>
        <div class="bookings-container">
            <h1>My Bookings</h1>

            @if(session('success'))
                <div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 1rem; border-radius: 5px; margin-bottom: 1rem; border: 1px solid #c3e6cb;">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error" style="background-color: #f8d7da; color: #721c24; padding: 1rem; border-radius: 5px; margin-bottom: 1rem; border: 1px solid #f5c6cb;">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error" style="background-color: #f8d7da; color: #721c24; padding: 1rem; border-radius: 5px; margin-bottom: 1rem; border: 1px solid #f5c6cb;">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="tabs">
                <button class="tab-link active" onclick="openTab(event, 'pending')">Pending Requests ({{ $pendingBookings->count() }})</button>
                <button class="tab-link" onclick="openTab(event, 'accepted')">Upcoming Sessions ({{ $acceptedBookings->count() }})</button>
                <button class="tab-link" onclick="openTab(event, 'history')">History</button>
            </div>

            <div id="pending" class="tab-content active">
                <h2>Pending Requests</h2>
                @forelse($pendingBookings as $booking)
                    <div class="booking-card">
                        <div class="booking-details">
                            <div class="booking-student">
                                <i class="fas fa-user-circle"></i>
                                {{ $booking->student->first_name }} {{ $booking->student->last_name }}
                            </div>
                            <div class="booking-info">
                                <div class="booking-info-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>{{ $booking->formatted_date }}</span>
                                </div>
                                <div class="booking-info-item">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ $booking->formatted_start_time }} - {{ $booking->formatted_end_time }}</span>
                                </div>
                                <div class="booking-info-item">
                                    <i class="fas fa-video"></i>
                                    <span>{{ ucwords(str_replace('_', ' ', $booking->session_type)) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="booking-actions">
                            <a href="{{ route('tutor.bookings.show', $booking->id) }}" class="btn btn-primary">
                                <i class="fas fa-eye"></i>
                                View Details
                            </a>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 3rem; background: white; border-radius: 12px; box-shadow: 0 3px 10px rgba(0,0,0,0.08);">
                        <i class="fas fa-inbox" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
                        <p style="color: #666; font-size: 1.1rem;">No pending booking requests</p>
                    </div>
                @endforelse
            </div>

            <div id="accepted" class="tab-content">
                <h2>Upcoming Sessions</h2>
                @forelse($acceptedBookings as $booking)
                    <div class="booking-card">
                        <div class="booking-details">
                            <div class="booking-student">
                                <i class="fas fa-user-circle"></i>
                                {{ $booking->student->first_name }} {{ $booking->student->last_name }}
                            </div>
                            <div class="booking-info">
                                <div class="booking-info-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>{{ $booking->formatted_date }}</span>
                                </div>
                                <div class="booking-info-item">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ $booking->formatted_start_time }} - {{ $booking->formatted_end_time }}</span>
                                </div>
                                <div class="booking-info-item">
                                    <i class="fas fa-video"></i>
                                    <span>{{ ucwords(str_replace('_', ' ', $booking->session_type)) }}</span>
                                </div>
                            </div>
                        </div>
                         <div class="booking-actions">
                            <a href="{{ route('tutor.bookings.show', $booking->id) }}" class="btn btn-secondary">
                                <i class="fas fa-eye"></i>
                                View Details
                            </a>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 3rem; background: white; border-radius: 12px; box-shadow: 0 3px 10px rgba(0,0,0,0.08);">
                        <i class="fas fa-calendar-check" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
                        <p style="color: #666; font-size: 1.1rem;">No upcoming sessions scheduled</p>
                    </div>
                @endforelse
            </div>

            <div id="history" class="tab-content">
                <h2>Booking History</h2>
                @forelse($rejectedBookings->merge($completedBookings) as $booking)
                     <div class="booking-card">
                        <div class="booking-details">
                            <div class="booking-student">
                                <i class="fas fa-user-circle"></i>
                                {{ $booking->student->first_name }} {{ $booking->student->last_name }}
                            </div>
                            <div class="booking-info">
                                <div class="booking-info-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>{{ $booking->formatted_date }}</span>
                                </div>
                                <div class="booking-info-item">
                                    <i class="fas fa-info-circle"></i>
                                    <span class="status-badge status-{{ strtolower($booking->status) }}">{{ ucfirst($booking->status) }}</span>
                                </div>
                            </div>
                        </div>
                         <div class="booking-actions">
                            <a href="{{ route('tutor.bookings.show', $booking->id) }}" class="btn btn-secondary">
                                <i class="fas fa-eye"></i>
                                View Details
                            </a>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 3rem; background: white; border-radius: 12px; box-shadow: 0 3px 10px rgba(0,0,0,0.08);">
                        <i class="fas fa-history" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
                        <p style="color: #666; font-size: 1.1rem;">No booking history</p>
                    </div>
                @endforelse
            </div>
        </div>
    </main>

    @include('layouts.footer-modals')
    
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const menuToggle = document.getElementById('menu-toggle');
            const navLinks = document.getElementById('nav-links');

            if (menuToggle && navLinks) {
                menuToggle.addEventListener('click', function() {
                    navLinks.classList.toggle('active');
                });
            }

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
            fetch('{{ route('tutor.wallet.balance') }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                const currencyAmount = document.getElementById('currency-amount');
                if (currencyAmount && data.balance !== undefined) {
                    currencyAmount.textContent = '₱' + parseFloat(data.balance).toFixed(2);
                }
            })
            .catch(error => {
                console.error('Error loading currency data:', error);
            });
        }

        function viewWallet() {
            window.location.href = "{{ route('tutor.wallet') }}";
        }

        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tab-link");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }
    </script>
    @include('layouts.footer-js')
</body>
</html> 