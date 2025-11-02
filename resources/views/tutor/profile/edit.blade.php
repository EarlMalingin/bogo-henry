<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - MentorHub Tutor</title>
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

        /* Subject Selection Styles */
        .subject-selection-container {
            width: 100%;
        }

        .selected-subjects {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 12px;
            min-height: 20px;
        }

        .subject-tag {
            background: linear-gradient(135deg, #4a90e2, #357abd);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
            animation: fadeIn 0.3s ease;
            box-shadow: 0 2px 4px rgba(74, 144, 226, 0.2);
            transition: all 0.3s ease;
        }

        .subject-tag:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(74, 144, 226, 0.3);
        }

        .subject-tag .remove-subject {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.2s ease;
        }

        .subject-tag .remove-subject:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
        }

        .subject-input-group {
            position: relative;
        }

        .searchable-dropdown {
            position: relative;
            width: 100%;
        }

        .subject-search-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 1rem;
            background-color: white;
            color: #333;
            transition: all 0.3s ease;
            padding-right: 40px;
        }

        .subject-search-input:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
        }

        .subject-search-input:hover {
            border-color: #4a90e2;
        }

        .dropdown-arrow {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            font-size: 12px;
            pointer-events: none;
            transition: transform 0.3s ease;
        }

        .searchable-dropdown.active .dropdown-arrow {
            transform: translateY(-50%) rotate(180deg);
        }

        .dropdown-options {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 2px solid #4a90e2;
            border-top: none;
            border-radius: 0 0 8px 8px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .searchable-dropdown.active .dropdown-options {
            display: block;
            animation: fadeIn 0.2s ease;
        }

        .option {
            padding: 10px 16px;
            cursor: pointer;
            transition: background-color 0.2s ease;
            border-bottom: 1px solid #f0f0f0;
        }

        .option:last-child {
            border-bottom: none;
        }

        .option:hover {
            background-color: #f8f9fa;
        }

        .option.selected {
            background-color: #e3f2fd;
            color: #1976d2;
        }

        .option.hidden {
            display: none;
        }

        .option.custom-option {
            background-color: #f8f9fa;
            color: #4a90e2;
            font-weight: 600;
            border-top: 2px solid #e1e5e9;
        }

        .option.custom-option:hover {
            background-color: #e3f2fd;
        }

        .no-results {
            padding: 12px 16px;
            color: #6c757d;
            font-style: italic;
            text-align: center;
        }

        .custom-subject-input {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            background: white;
            border: 2px solid #4a90e2;
            border-radius: 8px;
            padding: 8px;
            display: flex;
            gap: 8px;
            align-items: center;
            z-index: 10;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.3s ease;
        }

        .custom-subject-input input {
            flex: 1;
            padding: 8px 12px;
            border: 1px solid #e1e5e9;
            border-radius: 6px;
            font-size: 0.9rem;
        }

        .custom-subject-input input:focus {
            outline: none;
            border-color: #4a90e2;
        }

        .add-custom-btn, .cancel-custom-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .add-custom-btn {
            background: #4a90e2;
            color: white;
        }

        .add-custom-btn:hover {
            background: #357abd;
            transform: translateY(-1px);
        }

        .cancel-custom-btn {
            background: #6c757d;
            color: white;
        }

        .cancel-custom-btn:hover {
            background: #5a6268;
            transform: translateY(-1px);
        }

        .no-subjects-message {
            color: #6c757d;
            font-style: italic;
            font-size: 0.9rem;
            padding: 8px 0;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .custom-subject-input {
                flex-direction: column;
                gap: 8px;
            }
            
            .custom-subject-input input {
                width: 100%;
            }
            
            .add-custom-btn, .cancel-custom-btn {
                width: 100%;
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
    <!-- Header -->
    <header>
        <div class="navbar">
            <a href="#" class="logo">
                <img src="{{asset('images/MentorHub.png')}}" alt="UCTutor Logo" class="logo-img">
                
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
                        @if(Auth::guard('tutor')->user()->profile_picture)
                            <img src="{{ asset('storage/' . Auth::guard('tutor')->user()->profile_picture) }}?{{ time() }}" alt="Profile Picture" class="profile-icon-img">
                        @else
                            {{ strtoupper(substr(Auth::guard('tutor')->user()->first_name, 0, 1) . substr(Auth::guard('tutor')->user()->last_name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="dropdown-menu" id="dropdown-menu">
                        <a href="{{ route('tutor.profile.edit') }}">My Profile</a>
                        <a href="#">Settings</a>
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
        <form action="{{ route('tutor.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-section">
                <h3 class="form-section-title">Profile Picture</h3>
                <div class="form-group text-center">
                    <input type="file" name="profile_picture" id="profile_picture_input" accept="image/*">
                    <label for="profile_picture_input">
                        <div class="profile-picture-container">
                            @if($tutor->profile_picture)
                                <img src="{{ asset('storage/' . $tutor->profile_picture) }}?v={{ file_exists(public_path('storage/' . $tutor->profile_picture)) ? filemtime(public_path('storage/' . $tutor->profile_picture)) : time() }}" alt="Profile Picture" class="profile-picture">
                            @else
                                <div style="background-color: #4a90e2; color: white; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 3rem; font-weight: 600;">
                                    {{ substr($tutor->first_name, 0, 1) }}{{ substr($tutor->last_name, 0, 1) }}
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
                    <input type="text" id="first_name" name="first_name" class="form-control" value="{{ old('first_name', $tutor->first_name) }}" required>
                    @error('first_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" class="form-control" value="{{ old('last_name', $tutor->last_name) }}" required>
                    @error('last_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $tutor->email) }}" required>
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="tutor_id">Tutor ID</label>
                    <input type="text" id="tutor_id" name="tutor_id" class="form-control" value="{{ old('tutor_id', $tutor->tutor_id) }}" required>
                    @error('tutor_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $tutor->phone) }}">
                    @error('phone')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="form-section">
                <h3 class="form-section-title">Professional Information</h3>
                <div class="form-group">
                    <label for="specialization">Specialization</label>
                    <div class="subject-selection-container">
                        <div class="selected-subjects" id="selected-subjects">
                            <!-- Selected subjects will appear here -->
                        </div>
                        <div class="subject-input-group">
                            <div class="searchable-dropdown">
                                <input type="text" id="subject-search" class="subject-search-input" placeholder="Type to search subjects..." autocomplete="off">
                                <div class="dropdown-arrow">▼</div>
                                <div class="dropdown-options" id="dropdown-options">
                                    <div class="option" data-value="Mathematics">Mathematics</div>
                                    <div class="option" data-value="Physics">Physics</div>
                                    <div class="option" data-value="Chemistry">Chemistry</div>
                                    <div class="option" data-value="Biology">Biology</div>
                                    <div class="option" data-value="Computer Science">Computer Science</div>
                                    <div class="option" data-value="English">English</div>
                                    <div class="option" data-value="Spanish">Spanish</div>
                                    <div class="option" data-value="French">French</div>
                                    <div class="option" data-value="History">History</div>
                                    <div class="option" data-value="Geography">Geography</div>
                                    <div class="option" data-value="Economics">Economics</div>
                                    <div class="option" data-value="Accounting">Accounting</div>
                                    <div class="option" data-value="Finance">Finance</div>
                                    <div class="option" data-value="Marketing">Marketing</div>
                                    <div class="option" data-value="Psychology">Psychology</div>
                                    <div class="option" data-value="Philosophy">Philosophy</div>
                                    <div class="option" data-value="Literature">Literature</div>
                                    <div class="option" data-value="Art">Art</div>
                                    <div class="option" data-value="Music">Music</div>
                                    <div class="option" data-value="Physical Education">Physical Education</div>
                                    <div class="option" data-value="Statistics">Statistics</div>
                                    <div class="option" data-value="Calculus">Calculus</div>
                                    <div class="option" data-value="Algebra">Algebra</div>
                                    <div class="option" data-value="Geometry">Geometry</div>
                                    <div class="option" data-value="Trigonometry">Trigonometry</div>
                                    <div class="option" data-value="Programming">Programming</div>
                                    <div class="option" data-value="Web Development">Web Development</div>
                                    <div class="option" data-value="Data Science">Data Science</div>
                                    <div class="option" data-value="Machine Learning">Machine Learning</div>
                                    <div class="option" data-value="Artificial Intelligence">Artificial Intelligence</div>
                                    <div class="option" data-value="Database Management">Database Management</div>
                                    <div class="option" data-value="Software Engineering">Software Engineering</div>
                                    <div class="option" data-value="Network Security">Network Security</div>
                                    <div class="option" data-value="Cybersecurity">Cybersecurity</div>
                                    <div class="option" data-value="Digital Marketing">Digital Marketing</div>
                                    <div class="option" data-value="Business Management">Business Management</div>
                                    <div class="option" data-value="Entrepreneurship">Entrepreneurship</div>
                                    <div class="option" data-value="Public Speaking">Public Speaking</div>
                                    <div class="option" data-value="Creative Writing">Creative Writing</div>
                                    <div class="option" data-value="Technical Writing">Technical Writing</div>
                                    <div class="option" data-value="Research Methods">Research Methods</div>
                                    <div class="option" data-value="Academic Writing">Academic Writing</div>
                                    <div class="option custom-option" data-value="custom">+ Add Custom Subject</div>
                                </div>
                            </div>
                            <div class="custom-subject-input" id="custom-subject-input" style="display: none;">
                                <input type="text" id="custom-subject-text" placeholder="Enter custom subject name">
                                <button type="button" id="add-custom-subject" class="add-custom-btn">Add</button>
                                <button type="button" id="cancel-custom-subject" class="cancel-custom-btn">Cancel</button>
                            </div>
                        </div>
                        <input type="hidden" id="specialization" name="specialization" value="{{ old('specialization', $tutor->specialization) }}" required>
                    </div>
                    @error('specialization')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio" class="form-control" rows="3">{{ old('bio', $tutor->bio) }}</textarea>
                    @error('bio')
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
                <a href="{{ route('tutor.dashboard') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </main>
    @include('layouts.footer-modals')

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
    <script>
        // Toggle mobile menu
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

            // Subject Selection Functionality
            const selectedSubjectsContainer = document.getElementById('selected-subjects');
            const customSubjectInput = document.getElementById('custom-subject-input');
            const customSubjectText = document.getElementById('custom-subject-text');
            const addCustomSubjectBtn = document.getElementById('add-custom-subject');
            const cancelCustomSubjectBtn = document.getElementById('cancel-custom-subject');
            const specializationHiddenInput = document.getElementById('specialization');
            
            let selectedSubjects = [];

            // Initialize with old values if any
            const oldSpecialization = specializationHiddenInput.value;
            if (oldSpecialization) {
                const subjects = oldSpecialization.split(',').map(s => s.trim()).filter(s => s);
                subjects.forEach(subject => {
                    if (!selectedSubjects.includes(subject)) {
                        selectedSubjects.push(subject);
                    }
                });
                updateSelectedSubjectsDisplay();
            }

            // Show custom subject input
            function showCustomSubjectInput() {
                customSubjectInput.style.display = 'flex';
                customSubjectText.focus();
                document.querySelector('.searchable-dropdown').style.display = 'none';
            }

            // Hide custom subject input
            function hideCustomSubjectInput() {
                customSubjectInput.style.display = 'none';
                customSubjectText.value = '';
                document.querySelector('.searchable-dropdown').style.display = 'block';
                document.getElementById('subject-search').focus();
            }

            // Add custom subject
            addCustomSubjectBtn.addEventListener('click', function() {
                const customSubject = customSubjectText.value.trim();
                if (customSubject && !selectedSubjects.includes(customSubject)) {
                    selectedSubjects.push(customSubject);
                    updateSelectedSubjectsDisplay();
                    hideCustomSubjectInput();
                }
            });

            // Cancel custom subject
            cancelCustomSubjectBtn.addEventListener('click', hideCustomSubjectInput);

            // Handle Enter key in custom subject input
            customSubjectText.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    addCustomSubjectBtn.click();
                }
            });

            // Handle Escape key
            customSubjectText.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    hideCustomSubjectInput();
                }
            });

            // Remove subject
            function removeSubject(subject) {
                const index = selectedSubjects.indexOf(subject);
                if (index > -1) {
                    selectedSubjects.splice(index, 1);
                    updateSelectedSubjectsDisplay();
                }
            }

            // Update the display of selected subjects
            function updateSelectedSubjectsDisplay() {
                if (selectedSubjects.length === 0) {
                    selectedSubjectsContainer.innerHTML = '<div class="no-subjects-message">No subjects selected yet. Choose from the dropdown below.</div>';
                } else {
                    selectedSubjectsContainer.innerHTML = selectedSubjects.map(subject => `
                        <div class="subject-tag">
                            <span>${subject}</span>
                            <button type="button" class="remove-subject" onclick="removeSubject('${subject}')" title="Remove ${subject}">×</button>
                        </div>
                    `).join('');
                }
                
                // Update hidden input for form submission
                specializationHiddenInput.value = selectedSubjects.join(', ');
            }

            // Make removeSubject function globally available
            window.removeSubject = removeSubject;

            // Form validation
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                if (selectedSubjects.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one subject specialization.');
                    document.getElementById('subject-search').focus();
                    return false;
                }
            });

            // Searchable Dropdown Functionality
            const searchableDropdown = document.querySelector('.searchable-dropdown');
            const subjectSearchInput = document.getElementById('subject-search');
            const dropdownOptions = document.getElementById('dropdown-options');
            const options = dropdownOptions.querySelectorAll('.option');
            let selectedOptionIndex = -1;

            // Show dropdown on input focus
            subjectSearchInput.addEventListener('focus', function() {
                searchableDropdown.classList.add('active');
                filterOptions('');
            });

            // Hide dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!searchableDropdown.contains(e.target)) {
                    searchableDropdown.classList.remove('active');
                    selectedOptionIndex = -1;
                }
            });

            // Handle search input
            subjectSearchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                filterOptions(searchTerm);
                selectedOptionIndex = -1;
            });

            // Filter options based on search term
            function filterOptions(searchTerm) {
                let visibleCount = 0;
                let hasResults = false;

                options.forEach((option, index) => {
                    const text = option.textContent.toLowerCase();
                    const isCustomOption = option.classList.contains('custom-option');
                    
                    if (searchTerm === '' || text.includes(searchTerm) || isCustomOption) {
                        option.classList.remove('hidden');
                        visibleCount++;
                        if (!isCustomOption) hasResults = true;
                    } else {
                        option.classList.add('hidden');
                    }
                });

                // Show/hide no results message
                let noResultsElement = dropdownOptions.querySelector('.no-results');
                if (!hasResults && searchTerm !== '') {
                    if (!noResultsElement) {
                        noResultsElement = document.createElement('div');
                        noResultsElement.className = 'no-results';
                        noResultsElement.textContent = 'No subjects found. Try adding a custom subject.';
                        dropdownOptions.appendChild(noResultsElement);
                    }
                    noResultsElement.style.display = 'block';
                } else if (noResultsElement) {
                    noResultsElement.style.display = 'none';
                }
            }

            // Handle option clicks
            options.forEach(option => {
                option.addEventListener('click', function() {
                    const value = this.getAttribute('data-value');
                    
                    if (value === 'custom') {
                        showCustomSubjectInput();
                        subjectSearchInput.value = '';
                        searchableDropdown.classList.remove('active');
                    } else if (value && !selectedSubjects.includes(value)) {
                        selectedSubjects.push(value);
                        updateSelectedSubjectsDisplay();
                        subjectSearchInput.value = '';
                        searchableDropdown.classList.remove('active');
                    }
                });
            });

            // Keyboard navigation
            subjectSearchInput.addEventListener('keydown', function(e) {
                const visibleOptions = Array.from(options).filter(option => !option.classList.contains('hidden'));
                
                switch(e.key) {
                    case 'ArrowDown':
                        e.preventDefault();
                        selectedOptionIndex = Math.min(selectedOptionIndex + 1, visibleOptions.length - 1);
                        updateSelectedOption(visibleOptions);
                        break;
                    case 'ArrowUp':
                        e.preventDefault();
                        selectedOptionIndex = Math.max(selectedOptionIndex - 1, -1);
                        updateSelectedOption(visibleOptions);
                        break;
                    case 'Enter':
                        e.preventDefault();
                        if (selectedOptionIndex >= 0 && selectedOptionIndex < visibleOptions.length) {
                            visibleOptions[selectedOptionIndex].click();
                        } else if (this.value.trim() && !selectedSubjects.includes(this.value.trim())) {
                            // Add as custom subject if not in list
                            selectedSubjects.push(this.value.trim());
                            updateSelectedSubjectsDisplay();
                            this.value = '';
                            searchableDropdown.classList.remove('active');
                        }
                        break;
                    case 'Escape':
                        searchableDropdown.classList.remove('active');
                        selectedOptionIndex = -1;
                        break;
                }
            });

            // Update selected option visual
            function updateSelectedOption(visibleOptions) {
                options.forEach(option => option.classList.remove('selected'));
                if (selectedOptionIndex >= 0 && selectedOptionIndex < visibleOptions.length) {
                    visibleOptions[selectedOptionIndex].classList.add('selected');
                    // Scroll to selected option
                    visibleOptions[selectedOptionIndex].scrollIntoView({ block: 'nearest' });
                }
            }

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
        });
    </script>
    @include('layouts.footer-js')
</body>
</html> 