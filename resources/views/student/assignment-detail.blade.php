<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('style/Dashboard.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Assignment Details | MentorHub</title>
    <style>
        .detail-container {
            max-width: 900px;
            margin: 100px auto 2rem;
            padding: 0 1rem;
        }

        .assignment-detail-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            padding: 2rem;
            margin-bottom: 1.5rem;
        }

        .assignment-header {
            border-bottom: 2px solid #eee;
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
        }

        .assignment-subject {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2d7dd2;
            margin-bottom: 0.5rem;
        }

        .assignment-question {
            color: #333;
            line-height: 1.8;
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }

        .assignment-description {
            color: #666;
            margin-top: 1rem;
        }

        .answer-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 2rem;
        }

        .answer-locked {
            text-align: center;
            padding: 3rem;
        }

        .answer-locked i {
            font-size: 4rem;
            color: #ccc;
            margin-bottom: 1rem;
        }

        .btn-pay {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 1rem 2rem;
            border-radius: 8px;
            border: none;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .btn-pay:hover {
            transform: translateY(-2px);
        }

        .btn-pay:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .balance-warning {
            background: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        /* Header Styles - Matching Student Dashboard */
        header {
            background: linear-gradient(135deg, #4a90e2, #5637d9);
            color: white;
            padding: 1rem 0;
            width: 100%;
            position: fixed;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            min-height: 60px;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 5%;
            max-width: 1200px;
            margin: 0 auto;
            flex-wrap: wrap;
            min-height: 60px;
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
            padding: 0.5rem;
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

        /* Responsive Header Styles */
        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }

            .nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background: linear-gradient(135deg, #4a90e2, #5637d9);
                flex-direction: column;
                padding: 1rem 0;
                box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            }

            .nav-links.active {
                display: flex;
            }

            .nav-links a {
                padding: 0.75rem 5%;
                width: 100%;
            }

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

            .logo-img {
                height: 50px;
            }
        }
    </style>
</head>
<body>
    <!-- Header (same as post-assignment) -->
    <header>
        <div class="navbar">
            <a href="#" class="logo">
                <img src="{{asset('images/MentorHub.png')}}" alt="UCTutor Logo" class="logo-img">
                
            </a>
            <button class="menu-toggle" id="menu-toggle">☰</button>
            <nav class="nav-links" id="nav-links">
                <a href="{{ route('student.dashboard') }}">Dashboard</a>
                <a href="{{ route('student.book-session') }}">Book Session</a>
                <a href="{{ route('student.my-sessions') }}">Activities</a>
                <a href="{{ route('student.schedule') }}">Schedule</a>
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
                        <a href="#">Help Center</a>
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

    <div class="detail-container">
        <a href="{{ route('student.assignments.my-assignments') }}" style="color: #2d7dd2; text-decoration: none; margin-bottom: 1rem; display: inline-block;">
            <i class="fas fa-arrow-left"></i> Back to Assignments
        </a>

        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <div class="assignment-detail-card">
            <div class="assignment-header">
                <div class="assignment-subject">{{ $assignment->subject }}</div>
                <div style="color: #666; font-size: 0.9rem;">
                    <i class="far fa-calendar"></i> Posted on {{ $assignment->created_at->format('M d, Y') }}
                </div>
            </div>

            <div class="assignment-question">
                {{ $assignment->question }}
            </div>

            @if($assignment->description)
                <div class="assignment-description">
                    <strong>Additional Details:</strong><br>
                    {{ $assignment->description }}
                </div>
            @endif

            @if($assignment->file_name)
                <div style="margin-top: 1rem;">
                    <a href="{{ route('student.assignments.download', $assignment->id) }}" style="color: #2d7dd2;">
                        <i class="fas fa-paperclip"></i> {{ $assignment->file_name }}
                    </a>
                </div>
            @endif
        </div>

        @if($answer)
            <div class="assignment-detail-card answer-section">
                @if($assignment->status === 'paid')
                    <h3 style="color: #28a745; margin-bottom: 1rem;">
                        <i class="fas fa-unlock"></i> Answer (Purchased)
                    </h3>
                    <div style="color: #333; line-height: 1.8; white-space: pre-wrap;">{{ $answer->answer }}</div>
                    
                    @if($answer->file_name)
                        <div style="margin-top: 1rem;">
                            <a href="{{ asset('storage/' . $answer->file_path) }}" download style="color: #2d7dd2;">
                                <i class="fas fa-download"></i> Download Answer File: {{ $answer->file_name }}
                            </a>
                        </div>
                    @endif

                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #ddd; color: #666; font-size: 0.9rem;">
                        <strong>Answered by:</strong> {{ $answer->tutor->first_name }} {{ $answer->tutor->last_name }}<br>
                        <strong>Date:</strong> {{ $answer->created_at->format('M d, Y h:i A') }}
                    </div>
                @else
                    <div class="answer-locked">
                        <i class="fas fa-lock"></i>
                        <h3>Answer Available</h3>
                        <p>An answer has been provided for this assignment. Pay ₱{{ number_format($assignment->price, 2) }} to view it.</p>
                        
                        @if(!$canAfford)
                            <div class="balance-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                Insufficient balance. Current balance: ₱{{ number_format($wallet->balance, 2) }}. 
                                <a href="{{ route('student.wallet.cash-in') }}" style="color: #856404; text-decoration: underline;">Add funds</a>
                            </div>
                        @endif

                        <form action="{{ route('student.assignments.pay', $assignment->id) }}" method="POST" style="margin-top: 1.5rem;">
                            @csrf
                            <button type="submit" class="btn-pay" {{ !$canAfford ? 'disabled' : '' }}>
                                <i class="fas fa-credit-card"></i> Pay ₱{{ number_format($assignment->price, 2) }} to View Answer
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @else
            <div class="assignment-detail-card">
                <div style="text-align: center; padding: 2rem; color: #666;">
                    <i class="fas fa-clock" style="font-size: 3rem; margin-bottom: 1rem; color: #ccc;"></i>
                    <h3>Waiting for Answer</h3>
                    <p>No tutor has answered this assignment yet. Please check back later.</p>
                </div>
            </div>
        @endif
    </div>

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

