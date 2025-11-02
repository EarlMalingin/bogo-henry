<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('style/dashboard.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Post Assignment | MentorHub</title>
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

        .post-assignment-container {
            max-width: 900px;
            margin: 100px auto 2rem;
            padding: 0 1rem;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: bold;
            color: #2d7dd2;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: #666;
            font-size: 1rem;
        }

        .assignment-form {
            background: white;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            padding: 2rem;
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

        .form-group input[type="text"],
        .form-group textarea {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
            font-family: inherit;
        }

        .form-group input[type="text"]:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #2d7dd2;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 150px;
        }

        .form-group input[type="file"] {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 0.9rem;
        }

        .form-help {
            font-size: 0.85rem;
            color: #666;
            margin-top: 0.3rem;
        }

        .price-info {
            background: #e8f4fd;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .price-info i {
            font-size: 1.5rem;
            color: #2d7dd2;
        }

        .price-info-text {
            flex: 1;
        }

        .price-info-text strong {
            color: #2d7dd2;
        }

        .btn-submit {
            background: linear-gradient(135deg, #2d7dd2, #4a3dd9);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
            width: 100%;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(45, 125, 210, 0.3);
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @media (max-width: 768px) {
            .post-assignment-container {
                margin-top: 80px;
                padding: 0 0.5rem;
            }

            .assignment-form {
                padding: 1.5rem;
            }

            .page-title {
                font-size: 1.5rem;
            }
        }

        /* Tab Styles */
        .tab-btn {
            padding: 0.75rem 1.5rem;
            background: white;
            border: 2px solid #ddd;
            border-bottom: none;
            border-radius: 8px 8px 0 0;
            font-size: 1rem;
            font-weight: 600;
            color: #666;
            cursor: pointer;
            transition: all 0.3s;
            margin-right: 0.5rem;
        }

        .tab-btn:hover {
            background: #f8f9fa;
            color: #2d7dd2;
        }

        .tab-btn.active {
            background: #2d7dd2;
            color: white;
            border-color: #2d7dd2;
        }

        .assignment-card {
            padding: 1.5rem;
            border-bottom: 1px solid #eee;
            transition: background-color 0.3s;
            cursor: pointer;
        }

        .assignment-card:hover {
            background-color: #f8f9fa;
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
                <a href="{{route('student.book-session')}}">Book Session</a>
                <a href="{{route('student.my-sessions')}}">Sessions</a>
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

    <div class="post-assignment-container">
        <div class="page-header">
            <h1 class="page-title">Post Assignment</h1>
            <p class="page-subtitle">Get help with your assignments from qualified tutors</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <ul style="margin: 0; padding-left: 1.2rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="price-info">
            <i class="fas fa-info-circle"></i>
            <div class="price-info-text">
                <strong>How it works:</strong> Post your assignment question. Multiple tutors will provide answers. You can compare answers and choose based on ratings. Pay <strong>₱70.00</strong> to view your selected answer.
            </div>
        </div>

        <!-- Tabs for Post New and My Assignments -->
        <div style="margin-bottom: 2rem;">
            <button class="tab-btn active" onclick="showTab('post')">Post New Assignment</button>
            <button class="tab-btn" onclick="showTab('my-assignments')">My Assignments</button>
        </div>

        <!-- Post New Assignment Tab -->
        <div id="post-tab">
        <form action="{{ route('student.assignments.store') }}" method="POST" enctype="multipart/form-data" class="assignment-form" style="background: white; border-radius: 12px; box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08); padding: 2rem;">
            @csrf

            <div class="form-group">
                <label for="subject">Subject *</label>
                <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required placeholder="e.g., Mathematics, Physics, Chemistry">
                <div class="form-help">Enter the subject or course name</div>
            </div>

            <div class="form-group">
                <label for="question">Question *</label>
                <textarea id="question" name="question" required placeholder="Enter your assignment question here...">{{ old('question') }}</textarea>
                <div class="form-help">Minimum 10 characters required</div>
            </div>

            <div class="form-group">
                <label for="description">Additional Details (Optional)</label>
                <textarea id="description" name="description" placeholder="Any additional context or requirements...">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label for="file">Attachment (Optional)</label>
                <input type="file" id="file" name="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                <div class="form-help">Supported formats: PDF, DOC, DOCX, JPG, PNG (Max 10MB)</div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-paper-plane"></i> Post Assignment
            </button>
        </form>
        </div>

        <!-- My Assignments Tab -->
        <div id="my-assignments-tab" style="display: none;">
            <div class="assignment-form">
                <h2 style="margin-bottom: 1.5rem; color: #2d7dd2;">My Recent Assignments</h2>
                
                @php
                    $assignments = $recentAssignments ?? collect();
                @endphp
                @if($assignments->count() > 0)
                    @foreach($assignments as $assignment)
                        <div style="padding: 1.5rem; border-bottom: 1px solid #eee; transition: background-color 0.3s; cursor: pointer;" onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor='white'" onclick="window.location.href='{{ route('student.assignments.show', $assignment->id) }}'">
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                                <h3 style="margin: 0; color: #333;">{{ $assignment->subject }}</h3>
                                <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600;
                                    @if($assignment->status === 'pending') background: #fff3cd; color: #856404;
                                    @elseif($assignment->status === 'answered') background: #d1ecf1; color: #0c5460;
                                    @elseif($assignment->status === 'paid') background: #d4edda; color: #155724;
                                    @endif">
                                    {{ ucfirst($assignment->status) }}
                                </span>
                            </div>
                            <p style="margin: 0.5rem 0; color: #666; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $assignment->question }}
                            </p>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem; font-size: 0.9rem; color: #999;">
                                <span><i class="fas fa-clock"></i> {{ $assignment->created_at->diffForHumans() }}</span>
                                @if($assignment->answers->count() > 0)
                                    <span><i class="fas fa-users"></i> {{ $assignment->answers->count() }} {{ Str::plural('answer', $assignment->answers->count()) }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div style="text-align: center; padding: 3rem;">
                        <i class="fas fa-inbox" style="font-size: 3rem; color: #ddd; margin-bottom: 1rem;"></i>
                        <p style="color: #666;">You haven't posted any assignments yet</p>
                    </div>
                @endif

                @if(($recentAssignments ?? collect())->count() >= 5)
                <div style="text-align: center; margin-top: 2rem;">
                    <a href="{{ route('student.assignments.my-assignments') }}" style="display: inline-block; padding: 0.75rem 1.5rem; background: #2d7dd2; color: white; border-radius: 8px; text-decoration: none; font-weight: 600;">
                        View All Assignments
                    </a>
                </div>
                @endif
            </div>
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
            
            // Check if there's a success message and switch to My Assignments tab
            @if(session('success'))
                // Switch to My Assignments tab after posting
                document.getElementById('post-tab').style.display = 'none';
                document.getElementById('my-assignments-tab').style.display = 'block';
                
                // Update button states
                document.querySelectorAll('.tab-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                document.querySelectorAll('.tab-btn')[1].classList.add('active');
            @endif
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
        
        // Tab switching function
        function showTab(tabName) {
            // Hide all tabs
            document.getElementById('post-tab').style.display = 'none';
            document.getElementById('my-assignments-tab').style.display = 'none';
            
            // Remove active class from all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected tab
            if (tabName === 'post') {
                document.getElementById('post-tab').style.display = 'block';
                event.target.classList.add('active');
            } else if (tabName === 'my-assignments') {
                document.getElementById('my-assignments-tab').style.display = 'block';
                event.target.classList.add('active');
            }
        }
    </script>
    
    @include('layouts.footer-js')
</body>
</html>

