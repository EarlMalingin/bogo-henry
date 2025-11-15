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
            margin: 0 1px;
            display: inline-block;
            position: relative;
        }

        .star.empty {
            color: #e0e0e0;
        }

        .star.half {
            position: relative;
            color: #e0e0e0;
        }

        .star.half::before {
            content: '\2605';
            position: absolute;
            left: 0;
            top: 0;
            width: 50%;
            overflow: hidden;
            color: #ffc107;
        }

        .rating-text {
            font-size: 0.9rem;
            color: #666;
            margin-left: 0.5rem;
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
        
        .booking-type-toggle {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .booking-type-btn {
            flex: 1;
            padding: 1.2rem;
            border: 2px solid #ddd;
            border-radius: 12px;
            background-color: white;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
        }
        
        .booking-type-btn:hover {
            border-color: #4a90e2;
            background-color: #f0f7ff;
        }
        
        .booking-type-btn.active {
            background-color: #4a90e2;
            border-color: #4a90e2;
            color: white;
        }
        
        .booking-type-btn.active div {
            color: white !important;
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
                                <img src="{{ route('student.profile.picture') }}?v={{ time() }}" alt="Profile Picture" class="profile-icon-img">
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
                                    <img src="{{ asset('storage/' . $tutor->profile_picture) }}?v={{ $tutor->updated_at ? $tutor->updated_at->timestamp : time() }}" alt="{{ $tutor->first_name }} {{ $tutor->last_name }}" onerror="this.onerror=null; this.src='{{ route('tutor.profile.picture.view', $tutor->id) }}?v={{ $tutor->updated_at ? $tutor->updated_at->timestamp : time() }}';" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                    <div style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center; background-color: #f5f5f5; color: #666; font-weight: bold; font-size: 1.5rem; border-radius: 50%;">{{ substr($tutor->first_name, 0, 1) }}{{ substr($tutor->last_name, 0, 1) }}</div>
                                @else
                                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background-color: #f5f5f5; color: #666; font-weight: bold; font-size: 1.5rem; border-radius: 50%;">
                                        {{ substr($tutor->first_name, 0, 1) }}{{ substr($tutor->last_name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="tutor-body">
                            <div class="tutor-name">{{ $tutor->first_name }} {{ $tutor->last_name }}</div>
                            <div class="tutor-title">{{ $tutor->specialization ?? 'Tutor' }}</div>
                            @php
                                $hourlyRate = $tutor->hourly_rate ?? $tutor->session_rate ?? 0;
                                $monthlyRate = $tutor->session_rate;
                                $averageRating = round($tutor->reviews_avg_rating ?? 0, 1);
                                $ratingCount = $tutor->reviews_count ?? 0;
                                $filledStars = floor($averageRating);
                                $hasHalfStar = ($averageRating - $filledStars) >= 0.5;
                            @endphp
                            <div class="tutor-rate">₱{{ number_format($hourlyRate, 2) }}/hour</div>
                            @if(!is_null($monthlyRate))
                                <div class="tutor-rate-month" style="font-size: 0.9rem; color: #666;">₱{{ number_format($monthlyRate, 2) }}/month</div>
                            @endif
                            <div class="tutor-rating" aria-label="Tutor rating">
                                @for($i = 1; $i <= 5; $i++)
                                    @php
                                        $starClass = 'empty';
                                        if ($i <= $filledStars) {
                                            $starClass = 'filled';
                                        } elseif ($i === $filledStars + 1 && $hasHalfStar) {
                                            $starClass = 'half';
                                        }
                                    @endphp
                                    <span class="star {{ $starClass }}">&#9733;</span>
                                @endfor
                                <span class="rating-text">
                                    @if($ratingCount > 0)
                                        {{ number_format($averageRating, 1) }} ({{ $ratingCount }})
                                    @else
                                        No reviews yet
                                    @endif
                                </span>
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
                            <div class="tutor-rating" id="modal-rating" style="margin-bottom: 0.75rem; display: flex; align-items: center;"></div>
                            <div id="modal-tutor-rate" style="font-size: 1.1rem; font-weight: 600; color: #4a90e2;"></div>
                        </div>
                    </div>
                    
                    <div class="session-options">
                        <h3>Booking Type</h3>
                        
                        <div class="booking-type-toggle" style="margin-bottom: 2rem;">
                            <button type="button" class="booking-type-btn active" data-booking-type="hourly" id="booking-type-hourly">
                                <div style="font-weight: 600; font-size: 1rem;">Book a Session</div>
                                <div style="font-size: 0.85rem; color: #666; margin-top: 0.25rem;">Pay per hour</div>
                            </button>
                            <button type="button" class="booking-type-btn" data-booking-type="monthly" id="booking-type-monthly">
                                <div style="font-weight: 600; font-size: 1rem;">Book a Tutor</div>
                                <div style="font-size: 0.85rem; color: #666; margin-top: 0.25rem;">Pay per month</div>
                            </button>
                        </div>
                        <input type="hidden" id="booking-type" name="booking_type" value="hourly">
                        
                        <h3>Session Details</h3>
                        
                        <div class="session-type-toggle">
                            <button type="button" class="active" data-type="online">Online Session</button>
                            <button type="button" data-type="face_to_face">Face-to-Face</button>
                        </div>
                        <input type="hidden" id="session-type" name="session_type" value="online">
                        <input type="hidden" id="start-time" name="start_time" value="00:00:00">
                        <input type="hidden" id="end-time" name="end_time" value="23:59:59">
                        
                        <div class="calendar-container">
                            <h4>Select Date</h4>
                            <input type="date" id="session-date" name="date" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" style="padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px; width: 100%;">
                            
                            <!-- Time selection for hourly bookings -->
                            <div id="time-selection-container" style="margin-top: 1rem; display: none;">
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                    <div>
                                        <label for="session-start-time" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">Start Time *</label>
                                        <input type="time" id="session-start-time" style="padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px; width: 100%;">
                                    </div>
                                    <div>
                                        <label for="session-end-time" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">End Time *</label>
                                        <input type="time" id="session-end-time" style="padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px; width: 100%;">
                                    </div>
                                </div>
                                <div id="time-error" style="margin-top: 0.5rem; color: #e74c3c; font-size: 0.85rem; display: none;"></div>
                            </div>
                            
                            <!-- End date display for monthly bookings -->
                            <div id="end-date-container" style="margin-top: 1rem; padding: 0.75rem; background-color: #f8f9fa; border-radius: 5px; border-left: 3px solid #4a90e2; display: none;">
                                <div style="font-size: 0.85rem; color: #666; margin-bottom: 0.25rem;">Session End Date:</div>
                                <div id="end-session-date-display" style="font-size: 1rem; font-weight: 600; color: #e74c3c;">{{ date('F j, Y', strtotime('+1 month')) }}</div>
                            </div>
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
                                <span class="summary-label">Session Start Date:</span>
                                <span class="summary-value" id="summary-date">{{ date('F j, Y') }}</span>
                            </div>
                            <div class="summary-item" id="summary-time-item" style="display: none;">
                                <span class="summary-label">Time:</span>
                                <span class="summary-value" id="summary-time"></span>
                            </div>
                            <div class="summary-item" id="summary-end-date-item" style="display: none;">
                                <span class="summary-label">Session End Date:</span>
                                <span class="summary-value" id="summary-end-date" style="color: #e74c3c; font-weight: 600;">{{ date('F j, Y', strtotime('+1 month')) }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Rate:</span>
                                <span class="summary-value" id="summary-rate">₱0.00/hour</span>
                            </div>
                        </div>
                        
                        <div class="terms-container" style="margin-top: 1.5rem; padding: 1rem; background-color: #fff3cd; border-radius: 8px; border-left: 4px solid #ffc107;">
                            <h4 style="margin: 0 0 0.75rem 0; color: #856404; font-size: 1rem;">Terms and Conditions</h4>
                            <div style="font-size: 0.9rem; color: #856404; margin-bottom: 1rem; line-height: 1.6;">
                                By confirming this booking, you agree to the following terms:
                                <ul style="margin: 0.5rem 0 0 1.5rem; padding-left: 1rem;">
                                    <li>Payment is required upon booking confirmation</li>
                                    <li>If you are absent or fail to attend the session, you will still be charged the full session rate</li>
                                    <li>Rescheduling must be done at least 24 hours before the session</li>
                                    <li>Refunds are only available for cancellations made at least 48 hours in advance</li>
                                </ul>
                            </div>
                            <label style="display: flex; align-items: flex-start; cursor: pointer; margin-top: 0.5rem;">
                                <input type="checkbox" id="terms-checkbox" style="margin-right: 0.75rem; margin-top: 0.25rem; cursor: pointer; width: 18px; height: 18px;">
                                <span style="font-size: 0.95rem; color: #856404;">I have read and agree to the terms and conditions</span>
                            </label>
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
        
        function extractRatingData(tutor) {
            const rawAverage = tutor.reviews_avg_rating ?? tutor.average_rating ?? 0;
            const rawCount = tutor.reviews_count ?? tutor.rating_count ?? 0;
            const average = Math.round((parseFloat(rawAverage) || 0) * 10) / 10;
            const count = parseInt(rawCount, 10) || 0;
            return { average, count };
        }

        function renderRatingStars(average, count) {
            const filledStars = Math.floor(average);
            const hasHalfStar = average - filledStars >= 0.5;
            let starsHtml = '';
            for (let i = 1; i <= 5; i++) {
                let starClass = 'empty';
                if (i <= filledStars) {
                    starClass = 'filled';
                } else if (i === filledStars + 1 && hasHalfStar) {
                    starClass = 'half';
                }
                starsHtml += `<span class="star ${starClass}">&#9733;</span>`;
            }
            const ratingLabel = count > 0
                ? `${average.toFixed(1)} (${count} review${count === 1 ? '' : 's'})`
                : 'No reviews yet';
            return `${starsHtml}<span class="rating-text">${ratingLabel}</span>`;
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
                        const storageUrl = `/storage/${tutor.profile_picture}?v=${tutor.updated_at ? new Date(tutor.updated_at).getTime() : Date.now()}`;
                        const routeUrl = `/tutor/profile/picture/${tutor.id}?v=${tutor.updated_at ? new Date(tutor.updated_at).getTime() : Date.now()}`;
                        avatarContainer.innerHTML = `<img src="${storageUrl}" alt="${tutor.first_name}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;" onerror="this.onerror=null; this.src='${routeUrl}'; this.onerror=function(){this.style.display='none'; this.nextElementSibling.style.display='flex';};"><div style="display: none; width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background-color: #f5f5f5; color: #666; font-weight: bold; font-size: 2.5rem;">${tutor.first_name.charAt(0)}${tutor.last_name.charAt(0)}</div>`;
                    } else {
                        avatarContainer.innerHTML = `<div style="width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background-color: #f5f5f5; color: #666; font-weight: bold; font-size: 2.5rem;">
                                        ${tutor.first_name.charAt(0)}${tutor.last_name.charAt(0)}
                                     </div>`;
                    }

                    document.getElementById('modal-tutor-name').textContent = `${tutor.first_name} ${tutor.last_name}`;
                    document.getElementById('modal-tutor-title').textContent = tutor.specialization || 'Tutor';
                    const hourlyRate = parseFloat(tutor.hourly_rate ?? tutor.session_rate ?? 0);
                    const monthlyRate = parseFloat(tutor.session_rate ?? 0);
                    const ratingData = extractRatingData(tutor);
                    
                    // Store rates globally for booking type toggle
                    window.currentTutorRates = {
                        hourly: hourlyRate,
                        monthly: monthlyRate
                    };

                    const modalRatingEl = document.getElementById('modal-rating');
                    if (modalRatingEl) {
                        modalRatingEl.innerHTML = renderRatingStars(ratingData.average, ratingData.count);
                    }
                    
                    const modalRateEl = document.getElementById('modal-tutor-rate');
                    modalRateEl.innerHTML = `
                        <div>₱${hourlyRate.toFixed(2)}/hour</div>
                        ${monthlyRate > 0 ? `<div style="font-size: 0.9rem; color: #666;">₱${monthlyRate.toFixed(2)}/month (Book a tutor)</div>` : ''}
                    `;
                    
                    document.getElementById('summary-tutor').textContent = `${tutor.first_name} ${tutor.last_name}`;
                    updateBookingRate('hourly'); // Initialize with hourly rate

                    // Initialize end session date display
                    const currentDate = new Date(document.getElementById('session-date').value);
                    const endDate = new Date(currentDate);
                    endDate.setMonth(endDate.getMonth() + 1);
                    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                    document.getElementById('end-session-date-display').textContent = endDate.toLocaleDateString('en-US', options);

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
        
        // Function to update booking rate based on booking type
        function updateBookingRate(bookingType) {
            if (!window.currentTutorRates) return;
            
            document.getElementById('booking-type').value = bookingType;
            
            if (bookingType === 'monthly') {
                const rate = window.currentTutorRates.monthly;
                document.getElementById('summary-rate').textContent = `₱${rate.toFixed(2)}/month`;
            } else {
                // For hourly, rate will be calculated based on duration in updateSummaryTime
                // Just show the hourly rate for now, it will be updated when time is selected
                const hourlyRate = window.currentTutorRates.hourly;
                document.getElementById('summary-rate').textContent = `₱${hourlyRate.toFixed(2)}/hour`;
            }
            
            const endDateContainer = document.getElementById('end-date-container');
            const timeSelectionContainer = document.getElementById('time-selection-container');
            const sessionDate = document.getElementById('session-date');
            const date = new Date(sessionDate.value);
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            
            // Update end date display for monthly bookings
            const summaryEndDateItem = document.getElementById('summary-end-date-item');
            const summaryTimeItem = document.getElementById('summary-time-item');
            if (bookingType === 'monthly') {
                const endDate = new Date(date);
                endDate.setMonth(endDate.getMonth() + 1);
                document.getElementById('end-session-date-display').textContent = endDate.toLocaleDateString('en-US', options);
                document.getElementById('summary-end-date').textContent = endDate.toLocaleDateString('en-US', options);
                endDateContainer.style.display = 'block';
                timeSelectionContainer.style.display = 'none';
                summaryEndDateItem.style.display = 'flex';
                summaryTimeItem.style.display = 'none';
                // Set default times for monthly bookings
                document.getElementById('start-time').value = '00:00:00';
                document.getElementById('end-time').value = '23:59:59';
                // Remove required attribute for time fields
                const startTimeInput = document.getElementById('session-start-time');
                const endTimeInput = document.getElementById('session-end-time');
                if (startTimeInput) startTimeInput.removeAttribute('required');
                if (endTimeInput) endTimeInput.removeAttribute('required');
            } else {
                // For hourly, show time selection and hide end date display
                document.getElementById('summary-end-date').textContent = date.toLocaleDateString('en-US', options);
                endDateContainer.style.display = 'none';
                timeSelectionContainer.style.display = 'block';
                summaryEndDateItem.style.display = 'none';
                summaryTimeItem.style.display = 'flex';
                // Set default times for hourly bookings (e.g., 9:00 AM to 10:00 AM)
                const startTimeInput = document.getElementById('session-start-time');
                const endTimeInput = document.getElementById('session-end-time');
                if (startTimeInput && endTimeInput) {
                    if (!startTimeInput.value) startTimeInput.value = '09:00';
                    if (!endTimeInput.value) endTimeInput.value = '10:00';
                    // Add required attribute for time fields
                    startTimeInput.setAttribute('required', 'required');
                    endTimeInput.setAttribute('required', 'required');
                    updateTimeFields();
                    updateSummaryTime();
                }
            }
        }
        
        // Function to update hidden time fields and validate
        function updateTimeFields() {
            const startTimeInput = document.getElementById('session-start-time');
            const endTimeInput = document.getElementById('session-end-time');
            const timeError = document.getElementById('time-error');
            
            if (startTimeInput && endTimeInput && startTimeInput.value && endTimeInput.value) {
                const startTime = startTimeInput.value;
                const endTime = endTimeInput.value;
                
                // Calculate hours to check if valid
                const hours = calculateHours(startTime, endTime);
                
                // Validate that duration is reasonable
                if (hours <= 0) {
                    timeError.textContent = 'End time must be after start time';
                    timeError.style.display = 'block';
                    return false;
                } else if (hours > 24) {
                    timeError.textContent = 'Session duration cannot exceed 24 hours';
                    timeError.style.display = 'block';
                    return false;
                } else {
                    timeError.style.display = 'none';
                    // Update hidden fields
                    document.getElementById('start-time').value = startTime + ':00';
                    
                    // If we treated 00:00 as 12:00 PM in calculateHours, store 12:00:00 instead
                    let endTimeToStore = endTime;
                    const [endHours, endMinutes] = endTime.split(':').map(Number);
                    const [startHours, startMinutes] = startTime.split(':').map(Number);
                    
                    if (endHours === 0 && startHours < 12) {
                        // Check if calculateHours treated it as 12:00 PM
                        const calculatedHours = calculateHours(startTime, endTime);
                        const startTotalMinutes = startHours * 60 + startMinutes;
                        const sameDayHours = (12 * 60 - startTotalMinutes) / 60;
                        if (calculatedHours === sameDayHours && calculatedHours <= 12) {
                            // We treated 00:00 as 12:00 PM, so store 12:00:00
                            endTimeToStore = '12:00';
                        }
                    }
                    document.getElementById('end-time').value = endTimeToStore + ':00';
                    return true;
                }
            }
            return false;
        }
        
        // Helper function to format time (HH:MM to 12-hour format)
        function formatTime(time24) {
            if (!time24) return '';
            const [hours, minutes] = time24.split(':');
            const hour = parseInt(hours);
            const ampm = hour >= 12 ? 'PM' : 'AM';
            const hour12 = hour % 12 || 12;
            return `${hour12}:${minutes} ${ampm}`;
        }
        
        // Function to calculate hours between two times
        function calculateHours(startTime, endTime) {
            const [startHours, startMinutes] = startTime.split(':').map(Number);
            const [endHours, endMinutes] = endTime.split(':').map(Number);
            
            const startTotalMinutes = startHours * 60 + startMinutes;
            let endTotalMinutes = endHours * 60 + endMinutes;
            
            // Handle case where end time is before start time
            if (endTotalMinutes < startTotalMinutes) {
                // If end time is 00:00 (midnight) and start is in the morning (before 12:00 PM),
                // and the duration would be > 12 hours, assume user meant 12:00 PM (noon) instead
                if (endHours === 0 && startHours < 12) {
                    const nextDayDuration = (24 * 60 - startTotalMinutes + endTotalMinutes) / 60;
                    const sameDayDuration = (12 * 60 - startTotalMinutes) / 60; // Treating 00:00 as 12:00 PM
                    
                    // If same-day duration is more reasonable (< 12 hours), use that
                    if (sameDayDuration > 0 && sameDayDuration <= 12 && nextDayDuration > 12) {
                        endTotalMinutes = 12 * 60; // Treat as 12:00 PM (noon)
                    } else {
                        endTotalMinutes += 24 * 60; // Add 24 hours for next day
                    }
                } else {
                    endTotalMinutes += 24 * 60; // Add 24 hours for next day
                }
            }
            
            const diffMinutes = endTotalMinutes - startTotalMinutes;
            return diffMinutes / 60; // Convert to hours
        }
        
        // Function to update summary with time information and calculate total rate
        function updateSummaryTime() {
            const bookingType = document.getElementById('booking-type')?.value;
            const summaryTimeItem = document.getElementById('summary-time-item');
            if (bookingType === 'hourly') {
                const startTimeInput = document.getElementById('session-start-time');
                const endTimeInput = document.getElementById('session-end-time');
                if (startTimeInput && endTimeInput && startTimeInput.value && endTimeInput.value) {
                    const startTime = startTimeInput.value;
                    const endTime = endTimeInput.value;
                    // Format time for display (e.g., "9:00 AM - 10:00 AM")
                    const startFormatted = formatTime(startTime);
                    const endFormatted = formatTime(endTime);
                    const timeDisplay = document.getElementById('summary-time');
                    if (timeDisplay) {
                        timeDisplay.textContent = `${startFormatted} - ${endFormatted}`;
                    }
                    if (summaryTimeItem) {
                        summaryTimeItem.style.display = 'flex';
                    }
                    
                    // Calculate total rate based on duration
                    if (window.currentTutorRates) {
                        const hours = calculateHours(startTime, endTime);
                        const hourlyRate = window.currentTutorRates.hourly;
                        const totalRate = hourlyRate * hours;
                        const rateDisplay = document.getElementById('summary-rate');
                        if (rateDisplay) {
                            rateDisplay.textContent = `₱${totalRate.toFixed(2)} (₱${hourlyRate.toFixed(2)}/hour)`;
                        }
                        
                        // Update time display if we corrected 00:00 to 12:00 PM
                        if (endTime === '00:00' && startTime.split(':')[0] < 12) {
                            const startTotalMinutes = parseInt(startTime.split(':')[0]) * 60 + parseInt(startTime.split(':')[1]);
                            const sameDayHours = (12 * 60 - startTotalMinutes) / 60;
                            if (hours === sameDayHours && hours <= 12) {
                                // Update the displayed time to show 12:00 PM instead of 12:00 AM
                                const endFormatted = formatTime('12:00');
                                timeDisplay.textContent = `${startFormatted} - ${endFormatted}`;
                            }
                        }
                    }
                }
            } else {
                if (summaryTimeItem) {
                    summaryTimeItem.style.display = 'none';
                }
            }
        }
        
        function initializeBookingModal() {
            const sessionTypeButtons = document.querySelectorAll('.session-type-toggle button');
            const bookingTypeButtons = document.querySelectorAll('.booking-type-toggle button');
            const sessionDate = document.getElementById('session-date');
            const confirmBooking = document.getElementById('confirm-booking');
            
            // Booking type toggle
            bookingTypeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    bookingTypeButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    const bookingType = this.getAttribute('data-booking-type');
                    updateBookingRate(bookingType);
                    validateForm();
                });
            });
            
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
            
            // Time input handlers for hourly bookings
            const startTimeInput = document.getElementById('session-start-time');
            const endTimeInput = document.getElementById('session-end-time');
            
            if (startTimeInput && endTimeInput) {
                startTimeInput.addEventListener('change', function() {
                    updateTimeFields();
                    updateSummaryTime();
                    validateForm();
                });
                
                endTimeInput.addEventListener('change', function() {
                    updateTimeFields();
                    updateSummaryTime();
                    validateForm();
                });
            }
            
            function validateForm() {
                const isSessionTypeSelected = document.querySelector('.session-type-toggle button.active') !== null;
                const isDateSelected = sessionDate.value !== '';
                const isTermsAccepted = document.getElementById('terms-checkbox').checked;
                const bookingType = document.getElementById('booking-type').value;
                
                let isValid = isSessionTypeSelected && isDateSelected && isTermsAccepted;
                
                // For hourly bookings, also validate time fields
                if (bookingType === 'hourly') {
                    const timeValid = updateTimeFields();
                    isValid = isValid && timeValid;
                }
                
                confirmBooking.disabled = !isValid;
            }
            
            // Add event listener to terms checkbox
            const termsCheckbox = document.getElementById('terms-checkbox');
            if (termsCheckbox) {
                termsCheckbox.addEventListener('change', validateForm);
            }
            
            function updateSummaryDate() {
                const date = new Date(sessionDate.value);
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                document.getElementById('summary-date').textContent = date.toLocaleDateString('en-US', options);
                
                // Get current booking type
                const bookingType = document.getElementById('booking-type').value;
                const endDateContainer = document.getElementById('end-date-container');
                
                const summaryEndDateItem = document.getElementById('summary-end-date-item');
                const summaryTimeItem = document.getElementById('summary-time-item');
                if (bookingType === 'monthly') {
                    // Calculate end date (1 month from start date)
                    const endDate = new Date(date);
                    endDate.setMonth(endDate.getMonth() + 1);
                    document.getElementById('summary-end-date').textContent = endDate.toLocaleDateString('en-US', options);
                    document.getElementById('end-session-date-display').textContent = endDate.toLocaleDateString('en-US', options);
                    endDateContainer.style.display = 'block';
                    summaryEndDateItem.style.display = 'flex';
                    summaryTimeItem.style.display = 'none';
                } else {
                    // For hourly, end date is same as start date and hide end date container
                    document.getElementById('summary-end-date').textContent = date.toLocaleDateString('en-US', options);
                    endDateContainer.style.display = 'none';
                    summaryEndDateItem.style.display = 'none';
                    summaryTimeItem.style.display = 'flex';
                    updateSummaryTime();
                }
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
                    const ratingData = extractRatingData(tutor);
                    const ratingHtml = renderRatingStars(ratingData.average, ratingData.count);
                    
                    let avatarHtml;
                    if (tutor.profile_picture) {
                        const storageUrl = `/storage/${tutor.profile_picture}?v=${tutor.updated_at ? new Date(tutor.updated_at).getTime() : Date.now()}`;
                        const routeUrl = `/tutor/profile/picture/${tutor.id}?v=${tutor.updated_at ? new Date(tutor.updated_at).getTime() : Date.now()}`;
                        avatarHtml = `<img src="${storageUrl}" alt="${tutor.first_name}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;" onerror="this.onerror=null; this.src='${routeUrl}'; this.onerror=function(){this.style.display='none'; this.nextElementSibling.style.display='flex';};"><div style="display: none; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background-color: #f5f5f5; color: #666; font-weight: bold; font-size: 2.5rem; border-radius: 50%;">${tutor.first_name.charAt(0)}${tutor.last_name.charAt(0)}</div>`;
                    } else {
                        avatarHtml = `<div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background-color: #f5f5f5; color: #666; font-weight: bold; font-size: 2.5rem; border-radius: 50%;">
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
                                    ${ratingHtml}
                                </div>
                                <div class="tutor-modal-rate" style="font-size: 1.1rem; font-weight: 600; color: #4a90e2;">
                                    <div>₱${parseFloat(tutor.hourly_rate ?? tutor.session_rate ?? 0).toFixed(2)}/hour</div>
                                    ${tutor.session_rate !== null && tutor.session_rate !== undefined ? `<div style="font-size: 0.9rem; color: #666;">₱${parseFloat(tutor.session_rate || 0).toFixed(2)}/month (Book a tutor)</div>` : ''}
                                </div>
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
    </script>
    
    @include('layouts.footer-js')
</body>
</html>