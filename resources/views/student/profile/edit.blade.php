<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - MentorHub</title>
    <link rel="stylesheet" href="{{ asset('style/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('style/session-modal.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
        .profile-container {
            max-width: 800px;
            margin: 5rem auto 5rem auto;
            padding-top: 5rem !important;
            padding-bottom: 5rem !important;
            padding-left: 2.5rem;
            padding-right: 2.5rem;
            background: white;
            border-radius: 12px 12px 0 0;
            min-height: calc(100vh - 250px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }
        html, body {
            background: #f5f7fa;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(rgba(255, 255, 255, 0.85), rgba(255, 255, 255, 0.85)), url('{{ asset('images/Uc-background.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        footer {
            margin-top: -20px;
            border-top: none;
        }


        .profile-header {
            text-align: center;
            margin-bottom: 2.5rem;
            position: relative;
        }

        .profile-header h1 {
            color: #2c3e50;
            margin-bottom: 0.5rem;
            font-size: 2rem;
            font-weight: 700;
        }

        .profile-header p {
            color: #7f8c8d;
            font-size: 1rem;
        }

        .profile-picture-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto 1.5rem;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid #e0f2fe;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .profile-picture-container:hover {
            transform: translateY(-3px);
            border-color: #4a90e2;
        }

        .profile-picture {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-picture-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .profile-picture-container:hover .profile-picture-overlay {
            opacity: 1;
        }

        .form-group {
            margin-bottom: 1.75rem;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.75rem;
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.95rem;
        }

        .form-control {
            width: 100%;
            padding: 0.85rem 1rem;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
            background-color: #f9fafb;
        }

        .form-control:focus {
            border-color: #4a90e2;
            outline: none;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.2);
            background-color: white;
        }

        .btn {
            padding: 0.85rem 1.75rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 120px;
        }

        .btn-primary {
            background-color: #4a90e2;
            color: white;
            box-shadow: 0 4px 6px rgba(74, 144, 226, 0.2);
        }

        .btn-primary:hover {
            background-color: #3a7bc8;
            transform: translateY(-2px);
            box-shadow: 0 6px 10px rgba(74, 144, 226, 0.3);
        }

        .btn-secondary {
            background-color: white;
            color: #4a90e2;
            border: 1px solid #4a90e2;
            margin-right: 1rem;
        }

        .btn-secondary:hover {
            background-color: #f5f9ff;
            transform: translateY(-2px);
        }

        .password-fields {
            background: #f8fafc;
            padding: 1.75rem;
            border-radius: 10px;
            margin-top: 1.5rem;
            border: 1px solid #e0e0e0;
        }

        .password-fields h3 {
            color: #2c3e50;
            margin-bottom: 0.5rem;
            font-size: 1.25rem;
        }

        .password-fields p {
            color: #7f8c8d;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .alert {
            padding: 1rem 1.5rem;
            margin-bottom: 1.75rem;
            border-radius: 8px;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .text-danger {
            color: #ef4444;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: block;
            font-weight: 500;
        }

        .action-buttons {
            display: flex;
            justify-content: flex-end;
            margin-top: 2.5rem;
            gap: 1rem;
        }

        #profile_picture_input {
            display: none;
        }

        .form-section {
            margin-bottom: 2.5rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .form-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .form-section-title {
            font-size: 1.25rem;
            color: #2c3e50;
            margin-bottom: 1.5rem;
            font-weight: 600;
            position: relative;
            padding-bottom: 0.5rem;
        }

        .form-section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background-color: #4a90e2;
            border-radius: 3px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .profile-container {
                padding: 1.5rem;
                margin: 1rem;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 0.75rem;
            }
            
            .btn {
                width: 100%;
            }
            
            .btn-secondary {
                margin-right: 0;
                margin-bottom: 0.5rem;
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
    <main class="profile-container">
        <div class="profile-header">
            <h1>Edit Your Profile</h1>
            <p>Update your personal information and preferences</p>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-section">
                <h3 class="form-section-title">Profile Picture</h3>
                <div class="form-group text-center">
                    <input type="file" name="profile_picture" id="profile_picture_input" accept="image/*">
                    <label for="profile_picture_input">
                        <div class="profile-picture-container">
                            @if($student->profile_picture)
                                <img src="{{ route('student.profile.picture') }}?v={{ time() }}" alt="Profile Picture" class="profile-picture" 
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div style="background-color: #ff6b6b; color: white; width: 100%; height: 100%; display: none; align-items: center; justify-content: center; font-size: 0.8rem; text-align: center; padding: 10px;">
                                    Image file not found
                                    </div>
                            @else
                                <div style="background-color: #4a90e2; color: white; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 3rem; font-weight: 600;">
                                    {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                </div>
                            @endif
                            <div class="profile-picture-overlay">
                                Change Photo
                            </div>
                        </div>
                    </label>
                    @error('profile_picture')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-section">
                <h3 class="form-section-title">Personal Information</h3>
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" class="form-control" value="{{ old('first_name', $student->first_name) }}" required>
                    @error('first_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" class="form-control" value="{{ old('last_name', $student->last_name) }}" required>
                    @error('last_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $student->email) }}" required>
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="student_id">Student ID</label>
                    <input type="text" id="student_id" name="student_id" class="form-control" value="{{ old('student_id', $student->student_id) }}" readonly style="background-color: #f3f4f6; color: #6b7280; cursor: not-allowed;">
                    <small style="display:block; margin-top:0.5rem; color:#6b7280;">Your Student ID is automatically generated and cannot be changed.</small>
                    @error('student_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $student->phone) }}">
                    @error('phone')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-section">
                <h3 class="form-section-title">Academic Information</h3>
                <div class="form-group">
                    <label for="year_level">Year Level</label>
                    <select id="year_level" name="year_level" class="form-control" required>
                        <option value="">Select Year Level</option>
                        <option value="Pre-school" {{ old('year_level', $student->year_level) == 'Pre-school' ? 'selected' : '' }}>Pre-school</option>
                        <option value="Kindergarten" {{ old('year_level', $student->year_level) == 'Kindergarten' ? 'selected' : '' }}>Kindergarten</option>
                        <option value="Elementary" {{ old('year_level', $student->year_level) == 'Elementary' ? 'selected' : '' }}>Elementary</option>
                        <option value="Highschool" {{ old('year_level', $student->year_level) == 'Highschool' ? 'selected' : '' }}>Highschool</option>
                        <option value="Senior Highschool" {{ old('year_level', $student->year_level) == 'Senior Highschool' ? 'selected' : '' }}>Senior Highschool</option>
                        <option value="College" {{ old('year_level', $student->year_level) == 'College' ? 'selected' : '' }}>College</option>
                    </select>
                    @error('year_level')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="course">Course</label>
                    <input type="text" id="course" name="course" class="form-control" value="{{ old('course', $student->course) }}" required>
                    @error('course')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="subjects_interest">Subjects of Interest</label>
                    <textarea id="subjects_interest" name="subjects_interest" class="form-control" rows="3">{{ old('subjects_interest', $student->subjects_interest) }}</textarea>
                    @error('subjects_interest')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-section">
                <div class="password-fields">
                    <h3>Change Password</h3>
                    <p>Leave blank to keep current password</p>

                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" id="current_password" name="current_password" class="form-control" placeholder="Enter current password">
                        @error('current_password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Enter new password">
                        @error('new_password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="new_password_confirmation">Confirm New Password</label>
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control" placeholder="Confirm new password">
                    </div>
                </div>
            </div>

            <div class="action-buttons">
                <a href="{{ route('student.dashboard') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
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
        // Toggle mobile menu
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menu-toggle');
            const navLinks = document.getElementById('nav-links');
            
            menuToggle.addEventListener('click', function() {
                navLinks.classList.toggle('active');
            });
            
            // Toggle profile dropdown
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

            // Preview profile picture when selected
            const profilePictureInput = document.getElementById('profile_picture_input');
            const profilePictureContainer = document.querySelector('.profile-picture-container');
            
            profilePictureInput.addEventListener('change', function(e) {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(event) {
                        const img = profilePictureContainer.querySelector('img');
                        if (img) {
                            img.src = event.target.result;
                        } else {
                            const initialsDiv = profilePictureContainer.querySelector('div');
                            if (initialsDiv) {
                                initialsDiv.style.display = 'none';
                            }
                            const newImg = document.createElement('img');
                            newImg.src = event.target.result;
                            newImg.className = 'profile-picture';
                            profilePictureContainer.insertBefore(newImg, profilePictureContainer.firstChild);
                        }
                    }
                    
                    reader.readAsDataURL(e.target.files[0]);
                }
            });
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
    
    @include('layouts.footer-js')
</body>
</html>