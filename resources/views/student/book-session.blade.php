<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('style/dashboard.css')}}">
    <link rel="stylesheet" href="{{asset('style/session-modal.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Book Session | MentorHub</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        /* Additional styles for the book session page */
        .book-session-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .tutor-search {
            margin-bottom: 2rem;
        }
        
        .search-bar {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .search-bar input {
            flex: 1;
            padding: 0.8rem 1rem;
            border: 1px solid #ddd;
            border-radius: 50px;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.3s;
        }
        
        .search-bar input:focus {
            border-color: #4a90e2;
        }
        
        .search-bar button {
            background-color: #4a90e2;
            color: white;
            border: none;
            padding: 0 1.5rem;
            border-radius: 50px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }
        
        .search-bar button:hover {
            background-color: #3a7ccc;
        }
        
        .filter-options {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .filter-options select {
            padding: 0.5rem 1rem;
            border: 1px solid #ddd;
            border-radius: 50px;
            font-size: 0.9rem;
            outline: none;
            background-color: white;
            cursor: pointer;
        }
        
        .filter-options select:focus {
            border-color: #4a90e2;
        }
        
        .tutor-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .tutor-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .tutor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .tutor-header {
            position: relative;
            height: 120px;
            background: linear-gradient(135deg, #4a90e2, #5637d9);
            display: flex;
            justify-content: center;
            align-items: flex-end;
            padding-bottom: 1rem;
        }
        
        .tutor-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid white;
            background-color: #f5f5f5;
            position: absolute;
            bottom: -40px;
            overflow: hidden;
        }
        
        .tutor-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .tutor-body {
            padding: 3rem 1.5rem 1.5rem;
            text-align: center;
        }
        
        .tutor-name {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        
        .tutor-title {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        
        .tutor-rate {
            color: #4a90e2;
            font-weight: bold;
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }
        
        .tutor-specialties {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }
        
        .specialty-badge {
            background-color: #ebf4ff;
            color: #4a90e2;
            padding: 0.3rem 0.6rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .tutor-rating {
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .star {
            color: #ffc107;
            font-size: 1.1rem;
        }
        
        .tutor-footer {
            display: flex;
            justify-content: space-between;
            padding: 1rem 1.5rem;
            border-top: 1px solid #eee;
        }
        
        .tutor-footer button, .tutor-footer a {
            background-color: transparent;
            border: none;
            color: #4a90e2;
            font-weight: 600;
            cursor: pointer;
            padding: 0.5rem;
            transition: color 0.3s;
            text-decoration: none;
            font-size: inherit;
        }
        
        .tutor-footer button:hover, .tutor-footer a:hover {
            color: #3a7ccc;
        }
        
        /* Modal styles */
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
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s, visibility 0.3s;
        }
        
        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .modal {
            background-color: white;
            border-radius: 8px;
            width: 90%;
            max-width: 800px;
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
        
        .tutor-modal-header {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .tutor-modal-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
        }
        
        .tutor-modal-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .tutor-modal-info {
            flex: 1;
        }
        
        .tutor-modal-name {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        
        .tutor-modal-title {
            color: #666;
            margin-bottom: 0.5rem;
        }
        
        .tutor-modal-rate {
            color: #4a90e2;
            font-weight: bold;
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }
        
        .tutor-modal-bio {
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }
        
        .session-options {
            margin-top: 2rem;
        }
        
        .session-type-toggle {
            display: flex;
            border: 1px solid #ddd;
            border-radius: 50px;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        
        .session-type-toggle button {
            flex: 1;
            padding: 0.8rem;
            border: none;
            background-color: transparent;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .session-type-toggle button.active {
            background-color: #4a90e2;
            color: white;
        }
        
        .calendar-container {
            margin-bottom: 1.5rem;
        }
        
        .duration-select {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        
        .notes-container {
            margin-bottom: 1.5rem;
        }
        
        .notes-container textarea {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            resize: vertical;
            min-height: 80px;
        }
        
        .booking-summary {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 1.5rem;
        }
        
        .booking-summary h4 {
            margin-bottom: 1rem;
            color: #4a90e2;
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }
        
        .summary-label {
            color: #666;
        }
        
        .summary-value {
            font-weight: 600;
        }
        
        .booking-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .booking-actions button {
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-secondary {
            background-color: transparent;
            border: 1px solid #ddd;
            color: #333;
        }
        
        .btn-secondary:hover {
            background-color: #f5f5f5;
        }
        
        .btn-primary {
            background-color: #4a90e2;
            border: none;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #3a7ccc;
        }
        
        .btn-primary:disabled {
            background-color: #ccc;
            cursor: not-allowed;
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
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-icon {
            flex-shrink: 0;
            width: 1.5rem;
            height: 1.5rem;
        }
        
        @media (max-width: 768px) {
            .tutor-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
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
            
            .tutor-modal-header {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            
            .tutor-modal-avatar {
                margin-bottom: 1rem;
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
                <a href="{{route('student.dashboard')}}">Dashboard</a>
                <a href="{{route('student.book-session')}}" class="active">Book Session</a>
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
        <div class="book-session-container">
            <div class="dashboard-header" style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem;">
                <div style="display: flex; flex-direction: column; align-items: flex-start;">
                    <h1 class="greeting">Book a Session</h1>
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
                    <div class="alert-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1.707-4.293a1 1 0 001.414 1.414L10 11.414l.293.293a1 1 0 001.414-1.414L11.414 10l.293-.293a1 1 0 00-1.414-1.414L10 8.586l-.293-.293a1 1 0 00-1.414 1.414L8.586 10l-.293.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                     <div class="alert-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1.707-4.293a1 1 0 001.414 1.414L10 11.414l.293.293a1 1 0 001.414-1.414L11.414 10l.293-.293a1 1 0 00-1.414-1.414L10 8.586l-.293-.293a1 1 0 00-1.414 1.414L8.586 10l-.293.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <!-- Tutor Search -->
            <div class="tutor-search">
                <div class="search-bar">
                    <input type="text" id="search-input" placeholder="Search for tutors by name, subject, or keyword...">
                    <button onclick="searchTutors()">Search</button>
                </div>
                <div class="filter-options">
                    <select id="price-filter">
                        <option value="">Sort by Price</option>
                        <option value="lowest">Lowest to Highest</option>
                        <option value="highest">Highest to Lowest</option>
                    </select>
                </div>
            </div>
            
            <!-- Tutor Grid -->
            <h2 class="section-title">Available Tutors</h2>
            <div class="tutor-grid" id="tutor-grid">
                @forelse($tutors as $tutor)
                    <div class="tutor-card" data-tutor-id="{{ $tutor->id }}">
                        <div class="tutor-header">
                            <div class="tutor-avatar">
                                @if($tutor->profile_picture)
                                    <img src="{{ asset('storage/' . $tutor->profile_picture) }}" alt="{{ $tutor->first_name }} {{ $tutor->last_name }}">
                                @else
                                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background-color: #f5f5f5; color: #666; font-weight: bold; font-size: 1.5rem;">
                                        {{ substr($tutor->first_name, 0, 1) }}{{ substr($tutor->last_name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="tutor-body">
                            <div class="tutor-name">{{ $tutor->first_name }} {{ $tutor->last_name }}</div>
                            <div class="tutor-title">{{ $tutor->specialization ?? 'Tutor' }}</div>
                            <div class="tutor-rate">₱{{ number_format($tutor->session_rate ?? 0, 2) }}/month</div>
                            <div class="tutor-rating">
                                <span class="star">&#9733;</span>
                                <span class="star">&#9733;</span>
                                <span class="star">&#9733;</span>   
                                <span class="star">&#9733;</span>
                                <span class="star">&#9733;</span>
                                <span style="font-size: 0.9rem; color: #666; margin-left: 0.5rem;">(12)</span>
                            </div>
                            @if($tutor->specialization)
                                <div class="tutor-specialties">
                                    @foreach(explode(',', $tutor->specialization) as $specialty)
                                        <span class="specialty-badge">{{ trim($specialty) }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="tutor-footer">
                            <a href="{{ route('student.messages') }}?tutor_id={{ $tutor->id }}" class="message-tutor">Message</a>
                            <button class="view-details" onclick="viewTutorDetails({{ $tutor->id }})">View Details</button>
                            <button class="book-session" onclick="bookSession({{ $tutor->id }})">Book Session</button>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: #666;">
                        <h3>No tutors available at the moment</h3>
                        <p>Please check back later or contact support for assistance.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </main>
    
    <!-- Footer (same as dashboard) -->
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
    
    <!-- Tutor Details Modal -->
    <div class="modal-overlay" id="tutor-details-modal">
        <div class="modal">
            <div class="modal-header">
                <div class="modal-title">Tutor Details</div>
                <button class="modal-close" onclick="closeTutorDetailsModal()">&times;</button>
            </div>
            <div class="modal-body" id="tutor-details-content">
                <!-- Tutor details will be loaded here dynamically -->
            </div>
        </div>
    </div>

    <!-- Booking Modal -->
    <div class="modal-overlay" id="booking-modal">
        <div class="modal">
            <div class="modal-header">
                <div class="modal-title">Book Session</div>
                <button class="modal-close" onclick="closeBookingModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="booking-form" method="POST" action="{{ route('student.book-session.store') }}">
                    @csrf
                    <input type="hidden" id="tutor-id" name="tutor_id">
                    
                    <div class="tutor-modal-header">
                        <div class="tutor-modal-avatar">
                            <!-- Avatar will be loaded here dynamically -->
                        </div>
                        <div class="tutor-modal-info">
                            <div id="modal-tutor-name" style="font-size: 1.8rem; font-weight: 600;"></div>
                            <div id="modal-tutor-title" style="font-size: 1rem; color: #555; margin-bottom: 0.5rem;"></div>
                            <div class="tutor-rating" style="margin-bottom: 0.75rem; display: flex; align-items: center;">
                                <span class="star">&#9733;</span>
                                <span class="star">&#9733;</span>
                                <span class="star">&#9733;</span>
                                <span class="star">&#9733;</span>
                                <span class="star">&#9733;</span>
                                <span style="font-size: 0.9rem; color: #666; margin-left: 0.5rem;">(12 reviews)</span>
                            </div>
                            <div id="modal-tutor-rate" style="font-size: 1.1rem; font-weight: 600; color: #4a90e2;"></div>
                        </div>
                    </div>
                    
                    <div class="session-options">
                        <h3>Session Details</h3>
                        
                        <div class="session-type-toggle">
                            <button type="button" class="active" data-type="online">Online Session</button>
                            <button type="button" data-type="face_to_face">Face-to-Face</button>
                        </div>
                        <input type="hidden" id="session-type" name="session_type" value="online">
                        <input type="hidden" name="start_time" value="00:00:00">
                        <input type="hidden" name="end_time" value="23:59:59">
                        
                        <div class="calendar-container">
                            <h4>Select Date</h4>
                            <input type="date" id="session-date" name="date" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" style="padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px; width: 100%;">
                        </div>
                        
                        <div class="notes-container">
                            <h4>Additional Notes (Optional)</h4>
                            <textarea id="notes" name="notes" placeholder="Any specific topics you'd like to cover or questions you have..."></textarea>
                        </div>
                        
                        <div class="booking-summary">
                            <h4>Booking Summary</h4>
                            <div class="summary-item">
                                <span class="summary-label">Tutor:</span>
                                <span class="summary-value" id="summary-tutor"></span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Session Type:</span>
                                <span class="summary-value" id="summary-type">Online</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Date:</span>
                                <span class="summary-value" id="summary-date">{{ date('F j, Y') }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Rate:</span>
                                <span class="summary-value" id="summary-rate">$0.00</span>
                            </div>
                        </div>
                        
                        <div class="booking-actions">
                            <button type="button" class="btn-secondary" onclick="closeBookingModal()">Cancel</button>
                            <button type="submit" class="btn-primary" id="confirm-booking" disabled>Confirm Booking</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
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

            // Update current date and time
            const dateTimeElement = document.getElementById('current-date-time');
            if (dateTimeElement) {
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                const currentDate = new Date();
                dateTimeElement.textContent = currentDate.toLocaleDateString('en-US', options);
            }

            // Initialize currency display
            initializeCurrencyDisplay();
            // Load currency data with a slight delay to ensure DOM is ready
            setTimeout(function() {
                loadCurrencyData();
            }, 100);
            
            // Initialize booking modal functionality
            initializeBookingModal();
            
            // Add event listener for price filter
            const priceFilter = document.getElementById('price-filter');
            if (priceFilter) {
                priceFilter.addEventListener('change', function() {
                    searchTutors();
                });
            }
        });

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
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const currencyAmount = document.getElementById('currency-amount');
                    if (currencyAmount && data.balance !== undefined) {
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
        
        function searchTutors() {
            const searchTerm = document.getElementById('search-input').value.toLowerCase();
            const priceFilter = document.getElementById('price-filter').value;
            const tutorCards = document.querySelectorAll('.tutor-card');
            
            // First, filter by search term
            tutorCards.forEach(card => {
                const tutorName = card.querySelector('.tutor-name').textContent.toLowerCase();
                const tutorTitle = card.querySelector('.tutor-title').textContent.toLowerCase();
                
                let showCard = true;
                
                // Search filter
                if (searchTerm && !tutorName.includes(searchTerm) && !tutorTitle.includes(searchTerm)) {
                    showCard = false;
                }
                
                card.style.display = showCard ? 'block' : 'none';
            });
            
            // Then sort by price if a price filter is selected
            if (priceFilter) {
                sortTutorsByPrice(priceFilter);
            }
        }
        
        function sortTutorsByPrice(sortOrder) {
            const tutorGrid = document.getElementById('tutor-grid');
            const tutorCards = Array.from(tutorGrid.querySelectorAll('.tutor-card'));
            
            // Filter out hidden cards
            const visibleCards = tutorCards.filter(card => card.style.display !== 'none');
            
            // Sort cards by price
            visibleCards.sort((a, b) => {
                const priceA = parseFloat(a.querySelector('.tutor-rate').textContent.replace(/[^0-9.]/g, ''));
                const priceB = parseFloat(b.querySelector('.tutor-rate').textContent.replace(/[^0-9.]/g, ''));
                
                if (sortOrder === 'lowest') {
                    return priceA - priceB;
                } else if (sortOrder === 'highest') {
                    return priceB - priceA;
                }
                return 0;
            });
            
            // Re-append sorted cards to maintain order
            visibleCards.forEach(card => {
                tutorGrid.appendChild(card);
            });
        }
        
        function bookSession(tutorId) {
            const url = `/student/tutor/${tutorId}/details`;
            fetch(url)
                .then(response => {
                    if (!response.ok) { throw new Error('Network response was not ok'); }
                    return response.json();
                })
                .then(tutor => {
                    // Populate modal with tutor data
                    document.getElementById('tutor-id').value = tutorId;

                    const avatarContainer = document.querySelector('#booking-modal .tutor-modal-avatar');
                    if (tutor.profile_picture) {
                        avatarContainer.innerHTML = `<img src="/storage/${tutor.profile_picture}" alt="${tutor.first_name}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;">`;
                    } else {
                        avatarContainer.innerHTML = `<div style="width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background-color: #f5f5f5; color: #666; font-weight: bold; font-size: 2.5rem;">
                                        ${tutor.first_name.charAt(0)}${tutor.last_name.charAt(0)}
                                     </div>`;
                    }

                    document.getElementById('modal-tutor-name').textContent = `${tutor.first_name} ${tutor.last_name}`;
                    document.getElementById('modal-tutor-title').textContent = tutor.specialization || 'Tutor';
                    document.getElementById('modal-tutor-rate').textContent = `₱${parseFloat(tutor.session_rate || 0).toFixed(2)}/month`;
                    
                    document.getElementById('summary-tutor').textContent = `${tutor.first_name} ${tutor.last_name}`;
                    document.getElementById('summary-rate').textContent = `₱${parseFloat(tutor.session_rate || 0).toFixed(2)}/month`;

                    // Show modal
                    document.getElementById('booking-modal').classList.add('active');
                })
                .catch(error => {
                    console.error('Error fetching tutor details for booking:', error);
                    alert('Could not prepare the booking form. Please try again later.');
                });
        }
        
        function closeBookingModal() {
            document.getElementById('booking-modal').classList.remove('active');
            document.getElementById('booking-form').reset();
            document.getElementById('confirm-booking').disabled = true;
        }
        
        function initializeBookingModal() {
            const sessionTypeButtons = document.querySelectorAll('.session-type-toggle button');
            const sessionDate = document.getElementById('session-date');
            const confirmBooking = document.getElementById('confirm-booking');
            
            // Session type toggle
            sessionTypeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    sessionTypeButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    document.getElementById('session-type').value = this.getAttribute('data-type');
                    document.getElementById('summary-type').textContent = 
                        this.getAttribute('data-type') === 'online' ? 'Online' : 'Face-to-Face';
                    validateForm();
                });
            });
            
            // Date change handler
            sessionDate.addEventListener('change', function() {
                updateSummaryDate();
                validateForm();
            });
            
            function validateForm() {
                const isSessionTypeSelected = document.querySelector('.session-type-toggle button.active') !== null;
                const isDateSelected = sessionDate.value !== '';
                
                confirmBooking.disabled = !(isSessionTypeSelected && isDateSelected);
            }
            
            function updateSummaryDate() {
                const date = new Date(sessionDate.value);
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                document.getElementById('summary-date').textContent = date.toLocaleDateString('en-US', options);
            }
        }
        
        // Close modal when clicking outside
        window.addEventListener('click', function(e) {
            if (e.target === document.getElementById('booking-modal')) {
                closeBookingModal();
            }
            if (e.target === document.getElementById('tutor-details-modal')) {
                closeTutorDetailsModal();
            }
        });

        function viewTutorDetails(tutorId) {
            const url = `/student/tutor/${tutorId}/details`;
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(tutor => {
                    const content = document.getElementById('tutor-details-content');
                    
                    let avatarHtml;
                    if (tutor.profile_picture) {
                        avatarHtml = `<img src="/storage/${tutor.profile_picture}" alt="${tutor.first_name}" style="width: 100%; height: 100%; object-fit: cover;">`;
                    } else {
                        avatarHtml = `<div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background-color: #f5f5f5; color: #666; font-weight: bold; font-size: 2.5rem;">
                                        ${tutor.first_name.charAt(0)}${tutor.last_name.charAt(0)}
                                     </div>`;
                    }
                    
                    let specialtiesHtml = '';
                    if (tutor.specialization) {
                         specialtiesHtml = tutor.specialization.split(',').map(s => `<span class="specialty-badge">${s.trim()}</span>`).join('');
                    }

                    content.innerHTML = `
                        <div class="tutor-modal-header" style="padding-bottom: 1rem; border-bottom: 1px solid #eee;">
                            <div class="tutor-modal-avatar" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1); border: 2px solid white;">
                                ${avatarHtml}
                            </div>
                            <div class="tutor-modal-info">
                                <div class="tutor-modal-name" style="font-size: 1.8rem; font-weight: 600;">${tutor.first_name} ${tutor.last_name}</div>
                                <div class="tutor-modal-title" style="font-size: 1rem; color: #555; margin-bottom: 0.5rem;">${tutor.specialization || 'Tutor'}</div>
                                <div class="tutor-rating" style="margin-bottom: 0.75rem; display: flex; align-items: center;">
                                    <span class="star">&#9733;</span>
                                    <span class="star">&#9733;</span>
                                    <span class="star">&#9733;</span>
                                    <span class="star">&#9733;</span>
                                    <span class="star">&#9733;</span>
                                    <span style="font-size: 0.9rem; color: #666; margin-left: 0.5rem;">(12 reviews)</span>
                                </div>
                                <div class="tutor-modal-rate" style="font-size: 1.1rem; font-weight: 600; color: #4a90e2;">₱${parseFloat(tutor.session_rate || 0).toFixed(2)}/month</div>
                            </div>
                        </div>

                        <div style="padding-top: 1.5rem;">
                            <div style="margin-bottom: 1.5rem;">
                                <h4 style="font-size: 1.1rem; font-weight: 600; color: #333; margin-bottom: 0.75rem; border-left: 4px solid #4a90e2; padding-left: 1rem;">About Me</h4>
                                <p style="line-height: 1.6; color: #666; padding-left: 1.25rem;">${tutor.bio || 'No biography provided.'}</p>
                            </div>

                            <div style="margin-bottom: 1.5rem;">
                                <h4 style="font-size: 1.1rem; font-weight: 600; color: #333; margin-bottom: 0.75rem; border-left: 4px solid #4a90e2; padding-left: 1rem;">Specialties</h4>
                                <div class="tutor-specialties" style="margin-top: 0.5rem; justify-content: flex-start; padding-left: 1.25rem;">
                                    ${specialtiesHtml || 'No specialties listed.'}
                                </div>
                            </div>

                            <div>
                                <h4 style="font-size: 1.1rem; font-weight: 600; color: #333; margin-bottom: 0.75rem; border-left: 4px solid #4a90e2; padding-left: 1rem;">Contact Information</h4>
                                <div style="font-size: 0.95rem; color: #555; line-height: 1.8; padding-left: 1.25rem;">
                                    <div><strong>Tutor ID:</strong> ${tutor.tutor_id}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="booking-actions" style="margin-top: 2rem; border-top: 1px solid #eee; padding-top: 1.5rem; text-align: right;">
                             <button type="button" class="btn-secondary" onclick="closeTutorDetailsModal()">Close</button>
                             <button type="button" class="btn-primary" onclick="bookSession(${tutor.id}); closeTutorDetailsModal();">Book Session</button>
                        </div>
                    `;
                    document.getElementById('tutor-details-modal').classList.add('active');
                })
                .catch(error => {
                    console.error('Error fetching tutor details:', error);
                    alert('Could not load tutor details. Please try again later.');
                });
        }

        function closeTutorDetailsModal() {
            document.getElementById('tutor-details-modal').classList.remove('active');
        }
    </script>
</body>
</html>