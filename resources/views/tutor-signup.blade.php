<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MentorHub - Tutor Registration</title>
    <link rel="stylesheet" href="{{ asset('style/studentregister2.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Alert Styles */
        .alert {
            padding: 18px 24px;
            margin-bottom: 24px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            animation: fadeIn 0.4s ease;
            box-shadow: 0 2px 12px rgba(220, 38, 38, 0.07);
            border: 1.5px solid #fca5a5;
            background: #fff1f2;
            color: #b91c1c;
            position: relative;
            min-width: 320px;
            max-width: 900px;
        }
        .alert-danger {
            background-color: #fff1f2;
            border-color: #fca5a5;
            color: #b91c1c;
        }
        .alert-content {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            flex: 1;
        }
        .alert-icon {
            font-size: 2rem;
            margin-top: 2px;
            color: #f59e42;
            flex-shrink: 0;
        }
        .alert-text {
            flex: 1;
        }
        .alert-text strong {
            color: #b91c1c;
            font-weight: 700;
            font-size: 1.1rem;
            display: block;
            margin-bottom: 4px;
        }
        .alert-text ul {
            margin: 8px 0 0 0;
            padding-left: 20px;
        }
        .alert-text li {
            margin: 4px 0;
            font-size: 1rem;
            color: #b91c1c;
        }
        .alert-close {
            background: none;
            border: none;
            color: #b91c1c;
            font-size: 2rem;
            cursor: pointer;
            padding: 0 10px;
            opacity: 0.7;
            transition: opacity 0.2s, color 0.2s;
            line-height: 1;
            position: absolute;
            top: 12px;
            right: 16px;
        }
        .alert-close:hover {
            opacity: 1;
            color: #ef4444;
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

        .subject-dropdown {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 1rem;
            background-color: white;
            color: #333;
            cursor: pointer;
            transition: all 0.3s ease;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
            padding-right: 40px;
        }

        .subject-dropdown:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
        }

        .subject-dropdown:hover {
            border-color: #4a90e2;
        }

        /* Searchable Dropdown Styles */
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

        /* File Upload Styles */
        .file-upload-container {
            position: relative;
            width: 100%;
        }

        .file-input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
            overflow: hidden;
        }

        .file-upload-label {
            display: block;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-upload-content {
            display: flex;
            align-items: center;
            padding: 20px;
            border: 2px dashed #e1e5e9;
            border-radius: 10px;
            background: #f8f9fa;
            transition: all 0.3s ease;
            gap: 15px;
        }

        .file-upload-label:hover .file-upload-content {
            border-color: #4a90e2;
            background: #f0f7ff;
        }

        .file-upload-icon {
            font-size: 2rem;
            color: #4a90e2;
            flex-shrink: 0;
        }

        .file-upload-text {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .file-upload-title {
            font-weight: 600;
            color: #333;
            font-size: 1rem;
        }

        .file-upload-subtitle {
            color: #666;
            font-size: 0.9rem;
        }

        .file-upload-button {
            background: #4a90e2;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .file-upload-label:hover .file-upload-button {
            background: #357abd;
            transform: translateY(-1px);
        }

        .file-selected {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 20px;
            background: #e8f5e8;
            border: 2px solid #4CAF50;
            border-radius: 10px;
            margin-top: 10px;
            animation: fadeIn 0.3s ease;
        }

        .file-info {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
        }

        .file-icon {
            font-size: 1.5rem;
            color: #4CAF50;
        }

        .file-details {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .file-name {
            font-weight: 600;
            color: #333;
            font-size: 0.95rem;
        }

        .file-size {
            color: #666;
            font-size: 0.85rem;
        }

        .remove-file {
            background: #ff4757;
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.2rem;
            font-weight: bold;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .remove-file:hover {
            background: #ff3742;
            transform: scale(1.1);
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

            .file-upload-content {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }

            .file-upload-text {
                align-items: center;
            }

            .file-selected {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }

            .file-info {
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header>
        <div class="navbar">
            <a href="{{ route('home') }}" class="logo">
                <img src="{{ asset('images/MentorHub.png') }}" alt="MentorHub Logo" class="logo-img" style="image-rendering: crisp-edges; width: auto; height: 80px;">
            </a>
            <nav class="nav-links" id="nav-links">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('home') }}#features">About</a>
                <a href="{{ route('home') }}#subjects">Subjects</a>
                <a href="{{ route('home') }}#contact">Contact</a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <div class="container">
            <div class="page-title">
                <h1>Tutor Registration</h1>
                <p>Join our community of expert tutors and help students achieve academic excellence</p>
            </div>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <div class="alert-content">
                        <div class="alert-icon">⚠️</div>
                        <div class="alert-text">
                            <strong>Please fix the following errors:</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <button class="alert-close" onclick="this.parentElement.style.display='none'">×</button>
                </div>
            @endif
            
            @if (session('success'))
                <div class="modal-overlay" id="success-modal-overlay"></div>
                <div class="success-popup modal-popup" id="success-popup">
                    <div class="popup-content">
                        <div class="popup-header">
                            <h3>Registration Successful!</h3>
                            <span class="close-popup" onclick="closePopup()">&times;</span>
                        </div>
                        <div class="popup-body">
                            <div class="success-icon">✓</div>
                            <p>{{ session('success') }}</p>
                            <p class="redirect-message">Redirecting to homepage...</p>
                        </div>
                    </div>
                </div>
                <style>
                    .modal-overlay {
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100vw;
                        height: 100vh;
                        background: rgba(0,0,0,0.5);
                        z-index: 9998;
                        display: block;
                    }
                    .modal-popup {
                        position: fixed;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        z-index: 9999;
                        background: #fff;
                        border-radius: 16px;
                        box-shadow: 0 4px 32px rgba(0,0,0,0.2);
                        min-width: 320px;
                        max-width: 90vw;
                        padding: 0;
                        animation: modalFadeIn 0.3s;
                    }
                    @keyframes modalFadeIn {
                        from { opacity: 0; transform: translate(-50%, -60%); }
                        to { opacity: 1; transform: translate(-50%, -50%); }
                    }
                    .popup-content { padding: 32px 24px 24px 24px; text-align: center; }
                    .popup-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
                    .close-popup { cursor: pointer; font-size: 1.5rem; color: #888; transition: color 0.2s; }
                    .close-popup:hover { color: #333; }
                    .success-icon { font-size: 2.5rem; color: #4BB543; margin-bottom: 12px; }
                    .redirect-message { color: #888; font-size: 0.95rem; margin-top: 10px; }
                    @media (max-width: 500px) {
                        .modal-popup { min-width: 90vw; padding: 0; }
                        .popup-content { padding: 20px 8px 16px 8px; }
                    }
                </style>
                <script>
                    function closePopup() {
                        const popup = document.getElementById('success-popup');
                        const overlay = document.getElementById('success-modal-overlay');
                        if (popup) popup.style.display = 'none';
                        if (overlay) overlay.style.display = 'none';
                        document.body.style.overflow = 'auto';
                        window.location.href = "{{ route('home') }}";
                    }
                    document.addEventListener('DOMContentLoaded', function() {
                        const popup = document.getElementById('success-popup');
                        const overlay = document.getElementById('success-modal-overlay');
                        if (popup && overlay) {
                            document.body.style.overflow = 'hidden';
                            overlay.addEventListener('click', closePopup);
                            setTimeout(closePopup, 3000);
                        }
                        const closeBtn = document.querySelector('.close-popup');
                        if (closeBtn) closeBtn.addEventListener('click', closePopup);
                    });
                </script>
            @endif

            @if(session('tutor_success'))
                <div class="modal-overlay" id="success-modal-overlay"></div>
                <div class="success-popup modal-popup" id="success-popup" style="display:block;">
                    <div class="popup-content">
                        <div class="popup-header">
                            <h3>Registration Successful!</h3>
                            <span class="close-popup" id="close-success-popup">&times;</span>
                        </div>
                        <div class="popup-body">
                            <div class="success-icon" style="font-size:2.5rem;color:#4BB543;margin-bottom:12px;">✓</div>
                            <p>{{ session('tutor_success') }}</p>
                            <p class="redirect-message" style="color:#888;font-size:0.95rem;margin-top:10px;">Redirecting to homepage...</p>
                        </div>
                    </div>
                </div>
                <style>
                    .modal-overlay {
                        position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
                        background: rgba(0,0,0,0.5); z-index: 9998; display: block;
                    }
                    .modal-popup {
                        position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);
                        z-index: 9999; background: #fff; border-radius: 16px;
                        box-shadow: 0 4px 32px rgba(0,0,0,0.2); min-width: 320px; max-width: 90vw;
                        padding: 0; animation: modalFadeIn 0.3s;
                    }
                    @keyframes modalFadeIn {
                        from { opacity: 0; transform: translate(-50%, -60%); }
                        to { opacity: 1; transform: translate(-50%, -50%); }
                    }
                    .popup-content { padding: 32px 24px 24px 24px; text-align: center; }
                    .popup-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
                    .close-popup { cursor: pointer; font-size: 1.5rem; color: #888; transition: color 0.2s; }
                    .close-popup:hover { color: #333; }
                </style>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const popup = document.getElementById('success-popup');
                        const overlay = document.getElementById('success-modal-overlay');
                        const closeBtn = document.getElementById('close-success-popup');
                        function closeModalAndRedirect() {
                            if (popup) popup.style.display = 'none';
                            if (overlay) overlay.style.display = 'none';
                            document.body.style.overflow = 'auto';
                            window.location.href = "{{ route('home') }}";
                        }
                        if (popup && overlay) {
                            document.body.style.overflow = 'hidden';
                            overlay.addEventListener('click', closeModalAndRedirect);
                            if (closeBtn) closeBtn.addEventListener('click', closeModalAndRedirect);
                            setTimeout(closeModalAndRedirect, 3000);
                        }
                    });
                </script>
            @endif

            <div class="registration-container">
                <div class="registration-content">
                    <h2>Why become a MentorHub Tutor?</h2>
                    <p>Share your knowledge and make a difference in students' lives while earning on your own schedule.</p>
                    
                    <div class="benefits">
                        <h3>Tutor Benefits</h3>
                        
                        <div class="benefit-item">
                            <div class="benefit-icon">💰</div>
                            <div>
                                <h4>Competitive Earnings</h4>
                                <p>Set your own rates and earn based on your expertise and experience.</p>
                            </div>
                        </div>
                        
                        <div class="benefit-item">
                            <div class="benefit-icon">⏰</div>
                            <div>
                                <h4>Flexible Schedule</h4>
                                <p>Choose your own hours and teach when it's convenient for you.</p>
                            </div>
                        </div>
                        
                        <div class="benefit-item">
                            <div class="benefit-icon">🌐</div>
                            <div>
                                <h4>Online Platform</h4>
                                <p>Access our advanced teaching tools and resources for effective online tutoring.</p>
                            </div>
                        </div>
                        
                        <div class="benefit-item">
                            <div class="benefit-icon">📈</div>
                            <div>
                                <h4>Career Growth</h4>
                                <p>Build your teaching portfolio and gain valuable experience.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="registration-form">
                    <div class="form-header">
                        <h2>Create Your Tutor Account</h2>
                        <p>Start your journey as a MentorHub tutor</p>
                    </div>
                    
                    <form action="{{ route('register.tutor') }}" method="POST" class="register-form" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="first_name">First Name *</label>
                                <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required pattern="[a-zA-Z\s\-\']+" title="First name can only contain letters, spaces, hyphens, and apostrophes" onkeypress="return /[a-zA-Z\s\-\']/.test(event.key)">
                                @error('first_name')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="last_name">Last Name *</label>
                                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required pattern="[a-zA-Z\s\-\']+" title="Last name can only contain letters, spaces, hyphens, and apostrophes" onkeypress="return /[a-zA-Z\s\-\']/.test(event.key)">
                                @error('last_name')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="tutor_id">Tutor ID *</label>
                            <input type="text" id="tutor_id" name="tutor_id" value="{{ old('tutor_id') }}" required>
                            @error('tutor_id')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="password">Password *</label>
                                <input type="password" id="password" name="password" required>
                                @error('password')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password *</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="specialization">Specialization *</label>
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
                                <input type="hidden" id="specialization" name="specialization" value="{{ old('specialization') }}" required>
                            </div>
                            @error('specialization')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required>
                            @error('phone')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="rate">Rate (₱/hour) *</label>
                            <input type="number" id="rate" name="rate" value="{{ old('rate') }}" min="0" step="0.01" required>
                            @error('rate')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="bio">Bio</label>
                            <textarea id="bio" name="bio" rows="3" placeholder="Tell us about yourself and your teaching experience">{{ old('bio') }}</textarea>
                            @error('bio')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="cv">CV/Resume *</label>
                            <div class="file-upload-container">
                                <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx" class="file-input" required>
                                <label for="cv" class="file-upload-label">
                                    <div class="file-upload-content">
                                        <div class="file-upload-icon">📄</div>
                                        <div class="file-upload-text">
                                            <span class="file-upload-title">Upload your CV/Resume</span>
                                            <span class="file-upload-subtitle">PDF, DOC, or DOCX files only (Max 5MB)</span>
                                        </div>
                                        <div class="file-upload-button">Choose File</div>
                                    </div>
                                </label>
                                <div class="file-selected" id="file-selected" style="display: none;">
                                    <div class="file-info">
                                        <div class="file-icon">📄</div>
                                        <div class="file-details">
                                            <div class="file-name" id="file-name"></div>
                                            <div class="file-size" id="file-size"></div>
                                        </div>
                                    </div>
                                    <button type="button" class="remove-file" id="remove-file">×</button>
                                </div>
                            </div>
                            @error('cv')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group checkbox-group">
                            <input type="checkbox" id="terms" name="terms" {{ old('terms') ? 'checked' : '' }} required>
                            <label for="terms">I agree to the <a href="#" class="modal-link" id="terms-link">Terms and Conditions</a> and <a href="#" class="modal-link" id="privacy-link">Privacy Policy</a></label>
                            @error('terms')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <button type="submit" class="register-btn">Create Tutor Account</button>
                    </form>
                    
                    <div class="form-footer">
                        <p>Already have an account? <a href="/login">Login here</a></p>
                        <p><a href="{{ route('select-role') }}">← Back to Registration Selection</a></p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Terms and Conditions Modal -->
    <div id="terms-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Terms and Conditions</h2>
                <button class="close" id="terms-close">&times;</button>
            </div>
            <div class="modal-body">
                <p><strong>Last updated:</strong> May 30, 2025</p>
                
                <h3>1. Acceptance of Terms</h3>
                <p>By accessing and using MentorHub's services, you agree to be bound by these Terms and Conditions. If you do not agree to these terms, please do not use our platform.</p>
                
                <h3>2. Description of Service</h3>
                <p>MentorHub is an online tutoring platform that connects students with qualified tutors. We provide:</p>
                <ul>
                    <li>One-on-one tutoring sessions</li>
                    <li>Group study sessions</li>
                    <li>Educational resources and materials</li>
                    <li>Progress tracking and reporting</li>
                </ul>
                
                <h3>3. User Responsibilities</h3>
                <p>As a user, you agree to:</p>
                <ul>
                    <li>Provide accurate and complete registration information</li>
                    <li>Maintain the confidentiality of your account credentials</li>
                    <li>Treat tutors and other users with respect</li>
                    <li>Use the platform only for educational purposes</li>
                </ul>
                
                <h3>4. Payment and Refunds</h3>
                <p>Payment for tutoring sessions is required in advance. Refunds may be provided in accordance with our refund policy, typically for sessions cancelled with at least 24 hours notice.</p>
                
                <h3>5. Tutor Compensation and Cashouts</h3>
                <p>Tutors on MentorHub receive compensation for completed tutoring sessions according to their set rates. When requesting a cashout of earned funds:</p>
                <ul>
                    <li>A 10% service fee will be deducted from the total cashout amount</li>
                    <li>This fee covers platform maintenance, payment processing, and service costs</li>
                    <li>Tutors are responsible for any additional fees imposed by their payment provider</li>
                </ul>
                
                <h3>6. Intellectual Property</h3>
                <p>All content, materials, and resources provided through MentorHub remain the property of MentorHub or its content providers. Users may not reproduce, distribute, or create derivative works without express permission.</p>
                
                <h3>7. Privacy and Data Protection</h3>
                <p>Your privacy is important to us. Please review our Privacy Policy to understand how we collect, use, and protect your personal information.</p>
                
                <h3>8. Platform Availability</h3>
                <p>While we strive to maintain continuous service, MentorHub does not guarantee uninterrupted access to the platform. We reserve the right to perform maintenance and updates as needed.</p>
                
                <h3>9. User Conduct</h3>
                <p>Users must not:</p>
                <ul>
                    <li>Violate any applicable laws or regulations</li>
                    <li>Harass, abuse, or harm other users</li>
                    <li>Share inappropriate or offensive content</li>
                    <li>Attempt to gain unauthorized access to our systems</li>
                </ul>
                
                <h3>10. Termination</h3>
                <p>MentorHub reserves the right to suspend or terminate user accounts that violate these terms or engage in inappropriate behavior.</p>
                
                <h3>11. Limitation of Liability</h3>
                <p>MentorHub's liability is limited to the maximum extent permitted by law. We are not responsible for indirect, incidental, or consequential damages arising from use of our services.</p>
                
                <h3>12. Changes to Terms</h3>
                <p>We reserve the right to modify these terms at any time. Users will be notified of significant changes, and continued use of the platform constitutes acceptance of updated terms.</p>
                
                <h3>13. Contact Information</h3>
                <p>For questions about these Terms and Conditions, please contact us at legal@MentorHub.com or through our support channels.</p>
            </div>
        </div>
    </div>

    <!-- Privacy Policy Modal -->
    <div id="privacy-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Privacy Policy</h2>
                <button class="close" id="privacy-close">&times;</button>
            </div>
            <div class="modal-body">
                <p><strong>Last updated:</strong> May 30, 2025</p>
                
                <h3>1. Information We Collect</h3>
                <p>We collect the following types of information:</p>
                <ul>
                    <li><strong>Personal Information:</strong> Name, email address, phone number, academic information</li>
                    <li><strong>Usage Data:</strong> Session attendance, progress metrics, platform interactions</li>
                    <li><strong>Technical Data:</strong> IP address, browser type, device information, cookies</li>
                    <li><strong>Communication Data:</strong> Messages, feedback, and support requests</li>
                </ul>
                
                <h3>2. How We Use Your Information</h3>
                <p>We use your information to:</p>
                <ul>
                    <li>Provide tutoring services and match you with appropriate tutors</li>
                    <li>Process payments and manage your account</li>
                    <li>Track your academic progress and generate reports</li>
                    <li>Communicate with you about sessions and platform updates</li>
                    <li>Improve our services and user experience</li>
                    <li>Comply with legal obligations</li>
                </ul>
                
                <h3>3. Information Sharing</h3>
                <p>We may share your information with:</p>
                <ul>
                    <li><strong>Tutors:</strong> Necessary academic and contact information for session delivery</li>
                    <li><strong>Service Providers:</strong> Third-party vendors who assist in platform operations</li>
                    <li><strong>Educational Institutions:</strong> With your consent, for academic reporting purposes</li>
                    <li><strong>Legal Authorities:</strong> When required by law or to protect our rights</li>
                </ul>
                
                <h3>4. Data Security</h3>
                <p>We implement industry-standard security measures to protect your personal information, including:</p>
                <ul>
                    <li>Encryption of sensitive data in transit and at rest</li>
                    <li>Regular security audits and updates</li>
                    <li>Access controls and authentication requirements</li>
                    <li>Staff training on data protection practices</li>
                </ul>
                
                <h3>5. Your Rights</h3>
                <p>You have the right to:</p>
                <ul>
                    <li>Access and review your personal information</li>
                    <li>Correct inaccurate or incomplete data</li>
                    <li>Request deletion of your personal information</li>
                    <li>Opt-out of marketing communications</li>
                    <li>Port your data to another service</li>
                </ul>
                
                <h3>6. Cookies and Tracking</h3>
                <p>We use cookies and similar technologies to enhance your user experience, analyze platform usage, and provide personalized content. You can manage cookie preferences through your browser settings.</p>
                
                <h3>7. Data Retention</h3>
                <p>We retain your personal information for as long as necessary to provide services and comply with legal obligations. Academic progress data may be retained longer for educational continuity purposes.</p>
                
                <h3>8. International Data Transfers</h3>
                <p>Your information may be processed and stored in countries other than your own. We ensure appropriate safeguards are in place for international data transfers.</p>
                
                <h3>9. Children's Privacy</h3>
                <p>We take special care to protect the privacy of users under 18. Parental consent may be required for certain data collection and processing activities.</p>
                
                <h3>10. Third-Party Links</h3>
                <p>Our platform may contain links to third-party websites. We are not responsible for the privacy practices of external sites and encourage you to review their privacy policies.</p>
                
                <h3>11. Updates to This Policy</h3>
                <p>We may update this Privacy Policy periodically. We will notify users of significant changes and post the updated policy on our platform.</p>
                
                <h3>12. Contact Us</h3>
                <p>For questions about this Privacy Policy or to exercise your rights, contact us at:</p>
                <ul>
                    <li>Email: privacy@MentorHub.com</li>
                    <li>Phone: +63958667092</li>
                    <li>Address: University of Cebu, Cebu City, Philippines</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-links">
                <a href="#" id="footer-privacy">Privacy Policy</a>
                <a href="#" id="footer-terms">Terms of Service</a>
                <a href="#">FAQ</a>
                <a href="#">Contact</a>
            </div>
            <div class="copyright">
                &copy; {{ date('Y') }} MentorHub. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu functionality removed

            // Modal functionality
            const termsModal = document.getElementById('terms-modal');
            const privacyModal = document.getElementById('privacy-modal');
            
            const termsLinks = [
                document.getElementById('terms-link'),
                document.getElementById('footer-terms')
            ];
            
            const privacyLinks = [
                document.getElementById('privacy-link'),
                document.getElementById('footer-privacy')
            ];
            
            const termsClose = document.getElementById('terms-close');
            const privacyClose = document.getElementById('privacy-close');

            // Track animation state to prevent conflicts
            let isAnimating = false;

            // Functions to open/close modals with smooth animations
            function openModal(modal) {
                if (isAnimating || !modal) return;
                isAnimating = true;
                
                modal.style.display = 'flex';
                // Force reflow to ensure display is set before adding class
                void modal.offsetWidth;
                modal.classList.add('show');
                document.body.style.overflow = 'hidden';
                
                setTimeout(() => {
                    isAnimating = false;
                }, 300);
            }

            function closeModal(modal) {
                if (isAnimating || !modal) return;
                isAnimating = true;
                
                modal.classList.remove('show');
                document.body.style.overflow = 'auto';
                
                // Wait for transition to complete before hiding
                modal.addEventListener('transitionend', function handler() {
                    modal.removeEventListener('transitionend', handler);
                    if (!modal.classList.contains('show')) {
                        modal.style.display = 'none';
                    }
                    isAnimating = false;
                }, { once: true });
            }

            // Add event listeners for terms modal
            termsLinks.forEach(link => {
                if (link) {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        openModal(termsModal);
                    });
                }
            });

            // Add event listeners for privacy modal
            privacyLinks.forEach(link => {
                if (link) {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        openModal(privacyModal);
                    });
                }
            });

            // Close button event listeners
            if (termsClose) {
                termsClose.addEventListener('click', function() {
                    closeModal(termsModal);
                });
            }

            if (privacyClose) {
                privacyClose.addEventListener('click', function() {
                    closeModal(privacyModal);
                });
            }

            // Close modal when clicking outside
            if (termsModal) {
                termsModal.addEventListener('click', function(e) {
                    if (e.target === termsModal) {
                        closeModal(termsModal);
                    }
                });
            }

            if (privacyModal) {
                privacyModal.addEventListener('click', function(e) {
                    if (e.target === privacyModal) {
                        closeModal(privacyModal);
                    }
                });
            }

            // Close modals with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeModal(termsModal);
                    closeModal(privacyModal);
                }
            });

            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        if (alert.parentNode) {
                            alert.parentNode.removeChild(alert);
                        }
                    }, 300);
                }, 5000);
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

            // File Upload Functionality
            const fileInput = document.getElementById('cv');
            const fileSelected = document.getElementById('file-selected');
            const fileName = document.getElementById('file-name');
            const fileSize = document.getElementById('file-size');
            const removeFile = document.getElementById('remove-file');
            const fileUploadLabel = document.querySelector('.file-upload-label');

            // Handle file selection
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Validate file type
                    const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                    if (!allowedTypes.includes(file.type)) {
                        alert('Please select a valid file type (PDF, DOC, or DOCX).');
                        fileInput.value = '';
                        return;
                    }

                    // Validate file size (5MB max)
                    const maxSize = 5 * 1024 * 1024; // 5MB in bytes
                    if (file.size > maxSize) {
                        alert('File size must be less than 5MB.');
                        fileInput.value = '';
                        return;
                    }

                    // Display file info
                    fileName.textContent = file.name;
                    fileSize.textContent = formatFileSize(file.size);
                    fileSelected.style.display = 'flex';
                    fileUploadLabel.style.display = 'none';
                }
            });

            // Handle file removal
            removeFile.addEventListener('click', function() {
                fileInput.value = '';
                fileSelected.style.display = 'none';
                fileUploadLabel.style.display = 'block';
            });

            // Format file size
            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            // Form validation
            const form = document.querySelector('.register-form');
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
        });
    </script>
</body>
</html>