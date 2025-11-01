<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Answer Assignment | MentorHub</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                url('{{ asset("images/Uc-background.jpg") }}');
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

        .answer-container {
            max-width: 900px;
            margin: 100px auto 2rem;
            padding: 0 1rem;
        }

        .assignment-card, .answer-form-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            padding: 2rem;
            margin-bottom: 1.5rem;
        }

        .assignment-subject {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2d7dd2;
            margin-bottom: 1rem;
        }

        .assignment-question {
            color: #333;
            line-height: 1.8;
            margin-bottom: 1rem;
        }

        .earnings-info {
            background: #e8f4fd;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .earnings-info strong {
            color: #28a745;
            font-size: 1.2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }

        .form-group textarea {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            font-family: inherit;
            resize: vertical;
            min-height: 200px;
        }

        .form-group textarea:focus {
            outline: none;
            border-color: #2d7dd2;
        }

        .btn-submit {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s;
            width: 100%;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffc107;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="navbar">
            <a href="#" class="logo">
                <img src="{{ asset('images/MentorHub.png') }}" alt="MentorHub Logo" class="logo-img">
                
            </a>
            <button class="menu-toggle" id="menu-toggle">☰</button>
            <nav class="nav-links" id="nav-links">
                <a href="{{ route('tutor.dashboard') }}">Dashboard</a>
                <a href="{{ route('tutor.bookings.index') }}">My Bookings</a>
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
                        <a href="#">Settings</a>
                        <a href="#">Report a Problem</a>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" method="POST" action="{{ route('tutor.logout') }}" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="answer-container">
        <a href="{{ route('tutor.assignments.index') }}" style="color: #2d7dd2; text-decoration: none; margin-bottom: 1rem; display: inline-block;">
            <i class="fas fa-arrow-left" style="margin-right: 0.5rem;"></i> Back to Assignments
        </a>

        @if($hasAnswered)
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> You have already submitted an answer for this assignment.
            </div>
        @endif

        @if($errors->any())
            <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                <ul style="margin: 0; padding-left: 1.2rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($assignment->answers->count() > 0 && !$hasAnswered)
            <div style="background: #fff3cd; border: 1px solid #ffc107; color: #856404; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                <i class="fas fa-users"></i> <strong>Competition:</strong> {{ $assignment->answers->count() }} other tutor(s) have already answered this assignment. Submit your answer to compete!
            </div>
        @endif

        <div class="assignment-card">
            <div class="assignment-subject">{{ $assignment->subject }}</div>
            <div class="assignment-question">{{ $assignment->question }}</div>
            
            @if($assignment->description)
                <div style="color: #666; margin-top: 1rem;">
                    <strong>Additional Details:</strong><br>
                    {{ $assignment->description }}
                </div>
            @endif

            @if($assignment->file_name)
                <div style="margin-top: 1rem;">
                    <a href="{{ route('tutor.assignments.download', $assignment->id) }}" style="color: #2d7dd2;">
                        <i class="fas fa-paperclip"></i> {{ $assignment->file_name }}
                    </a>
                </div>
            @endif

            <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #eee; color: #666; font-size: 0.9rem;">
                <strong>Student:</strong> {{ $assignment->student->first_name }} {{ $assignment->student->last_name }}<br>
                <strong>Posted:</strong> {{ $assignment->created_at->format('M d, Y') }}
            </div>
        </div>

        @if(!$hasAnswered)
            <div class="earnings-info">
                <i class="fas fa-money-bill-wave" style="font-size: 1.5rem; color: #28a745;"></i><br>
                <strong>You'll earn ₱{{ number_format($assignment->price, 2) }}</strong> when the student purchases your answer!
            </div>

            <div class="answer-form-card">
                <h2 style="margin-bottom: 1.5rem; color: #333;">Your Answer</h2>

                <form action="{{ route('tutor.assignments.answer', $assignment->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="answer">Detailed Answer *</label>
                        <textarea id="answer" name="answer" required placeholder="Provide a detailed and helpful answer to the student's question...">{{ old('answer') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="file">Attachment (Optional)</label>
                        <input type="file" id="file" name="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 8px;">
                        <small style="color: #666;">Supported: PDF, DOC, DOCX, JPG, PNG (Max 10MB)</small>
                    </div>

                    <button type="submit" class="btn-submit" {{ $hasAnswered ? 'disabled' : '' }}>
                        <i class="fas fa-paper-plane"></i> Submit Answer
                    </button>
                </form>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menu-toggle');
            const navLinks = document.getElementById('nav-links');
            const profileIcon = document.getElementById('profile-icon');
            const dropdownMenu = document.getElementById('dropdown-menu');

            if (menuToggle && navLinks) {
                menuToggle.addEventListener('click', function() {
                    navLinks.classList.toggle('active');
                });
            }

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

            // Initialize currency display
            initializeCurrencyDisplay();
            loadCurrencyData();
        });

        function viewWallet() {
            window.location.href = "{{ route('tutor.wallet') }}";
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
            fetch('{{ route("tutor.wallet.balance") }}')
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

