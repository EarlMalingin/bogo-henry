<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Activity | MentorHub</title>
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
                url('../../images/Uc-background.jpg');
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

        /* Main Content Styles */
        main {
            flex: 1;
            padding: 0 1rem;
            margin-top: 80px;
            max-width: 1200px;
            width: 100%;
            align-self: center;
        }

        .form-container {
            max-width: 800px;
            margin: 2rem auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            padding: 2rem;
        }

        .form-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-title {
            font-size: 1.8rem;
            color: #2d7dd2;
            margin-bottom: 0.5rem;
        }

        .form-subtitle {
            color: #666;
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

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #4a90e2;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .btn {
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background-color: #4a90e2;
            color: white;
        }

        .btn-primary:hover {
            background-color: #3a7ccc;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
        }

        .file-upload {
            border: 2px dashed #ddd;
            border-radius: 5px;
            padding: 2rem;
            text-align: center;
            transition: border-color 0.3s;
        }

        .file-upload:hover {
            border-color: #4a90e2;
        }

        .file-upload input[type="file"] {
            display: none;
        }

        .file-upload-label {
            cursor: pointer;
            color: #666;
        }

        .file-upload-label:hover {
            color: #4a90e2;
        }

        .file-list {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 1rem;
        }

        .file-list h4 {
            margin: 0 0 0.5rem 0;
            color: #333;
            font-size: 0.9rem;
        }

        .file-list ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .file-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem;
            background-color: white;
            border: 1px solid #e9ecef;
            border-radius: 3px;
            margin-bottom: 0.5rem;
        }

        .file-item:last-child {
            margin-bottom: 0;
        }

        .file-name {
            color: #333;
            font-size: 0.9rem;
        }

        .file-size {
            color: #666;
            font-size: 0.8rem;
        }

        .remove-file {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 0.2rem 0.5rem;
            border-radius: 3px;
            cursor: pointer;
            font-size: 0.8rem;
        }

        .remove-file:hover {
            background-color: #c82333;
        }

        .question-item {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 1rem;
            margin-bottom: 1rem;
            background-color: #f9f9f9;
        }

        .question-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .question-number {
            font-weight: 600;
            color: #2d7dd2;
        }

        .remove-question {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 0.3rem 0.8rem;
            border-radius: 3px;
            cursor: pointer;
            font-size: 0.8rem;
        }

        .add-question {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 1rem;
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

            .form-row {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
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
                <a href="{{ route('tutor.bookings.index') }}">My Bookings</a>
                <a href="#">Students</a>
                <a href="#">Schedule</a>
                
            </nav>
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
    </header>

    <main>
        <div class="form-container">
            <div class="form-header">
                <h1 class="form-title">Create New Activity</h1>
                <p class="form-subtitle">Send an activity, exam, or assignment to your student</p>
            </div>

            @if($errors->any())
                <div style="background-color: #f8d7da; color: #721c24; padding: 1rem; border-radius: 5px; margin-bottom: 1rem; border: 1px solid #f5c6cb;">
                    <ul style="margin: 0; padding-left: 1rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('tutor.activities.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="title">Activity Title *</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="type">Activity Type *</label>
                        <select id="type" name="type" required>
                            <option value="">Select Type</option>
                            <option value="activity" {{ old('type') == 'activity' ? 'selected' : '' }}>Activity</option>
                            <option value="exam" {{ old('type') == 'exam' ? 'selected' : '' }}>Exam</option>
                            <option value="assignment" {{ old('type') == 'assignment' ? 'selected' : '' }}>Assignment</option>
                            <option value="quiz" {{ old('type') == 'quiz' ? 'selected' : '' }}>Quiz</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="student_id">Student *</label>
                        <select id="student_id" name="student_id" required>
                            <option value="">Select Student</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->first_name }} {{ $student->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="session_id">Related Session (Optional)</label>
                        <select id="session_id" name="session_id">
                            <option value="">No specific session</option>
                            @foreach($sessions as $session)
                                <option value="{{ $session->id }}" {{ old('session_id') == $session->id ? 'selected' : '' }}>
                                    {{ $session->date->format('M d, Y') }} - {{ $session->student->first_name }} {{ $session->student->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea id="description" name="description" required>{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="instructions">Instructions</label>
                    <textarea id="instructions" name="instructions" placeholder="Provide specific instructions for the student...">{{ old('instructions') }}</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="total_points">Total Points *</label>
                        <input type="number" id="total_points" name="total_points" value="{{ old('total_points', 100) }}" min="1" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="time_limit">Time Limit (minutes)</label>
                        <input type="number" id="time_limit" name="time_limit" value="{{ old('time_limit') }}" min="1" placeholder="Optional">
                    </div>
                </div>

                <div class="form-group">
                    <label for="due_date">Due Date</label>
                    <input type="datetime-local" id="due_date" name="due_date" value="{{ old('due_date') }}">
                </div>

                <div class="form-group">
                    <label>Attachments</label>
                    <div class="file-upload" id="file-upload-area">
                        <label for="attachments" class="file-upload-label">
                            <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; margin-bottom: 0.5rem; display: block;"></i>
                            Click to upload files or drag and drop
                            <br>
                            <small>PDF, DOC, DOCX, TXT, JPG, PNG (Max 10MB each)</small>
                        </label>
                        <input type="file" id="attachments" name="attachments[]" multiple accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png">
                    </div>
                    <div id="file-list" class="file-list" style="margin-top: 1rem; display: none;">
                        <h4>Selected Files:</h4>
                        <ul id="file-items"></ul>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('tutor.my-sessions') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i>
                        Send Activity
                    </button>
                </div>
            </form>
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

            // Enhanced file upload functionality
            const fileInput = document.getElementById('attachments');
            const fileUpload = document.getElementById('file-upload-area');
            const fileList = document.getElementById('file-list');
            const fileItems = document.getElementById('file-items');
            let selectedFiles = [];

            // File input change handler
            fileInput.addEventListener('change', function() {
                handleFiles(this.files);
            });

            // Drag and drop functionality
            fileUpload.addEventListener('dragover', function(e) {
                e.preventDefault();
                fileUpload.style.borderColor = '#4a90e2';
                fileUpload.style.backgroundColor = '#f0f8ff';
            });

            fileUpload.addEventListener('dragleave', function(e) {
                e.preventDefault();
                fileUpload.style.borderColor = '#ddd';
                fileUpload.style.backgroundColor = 'transparent';
            });

            fileUpload.addEventListener('drop', function(e) {
                e.preventDefault();
                fileUpload.style.borderColor = '#ddd';
                fileUpload.style.backgroundColor = 'transparent';
                handleFiles(e.dataTransfer.files);
            });

            function handleFiles(files) {
                const maxSize = 10 * 1024 * 1024; // 10MB
                const allowedTypes = ['.pdf', '.doc', '.docx', '.txt', '.jpg', '.jpeg', '.png'];
                
                for (let file of files) {
                    // Check file size
                    if (file.size > maxSize) {
                        alert(`File "${file.name}" is too large. Maximum size is 10MB.`);
                        continue;
                    }
                    
                    // Check file type
                    const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
                    if (!allowedTypes.includes(fileExtension)) {
                        alert(`File "${file.name}" is not a supported file type.`);
                        continue;
                    }
                    
                    // Add file to list
                    selectedFiles.push(file);
                }
                
                updateFileList();
            }

            function updateFileList() {
                fileItems.innerHTML = '';
                
                if (selectedFiles.length > 0) {
                    fileList.style.display = 'block';
                    
                    selectedFiles.forEach((file, index) => {
                        const fileItem = document.createElement('li');
                        fileItem.className = 'file-item';
                        fileItem.innerHTML = `
                            <div>
                                <div class="file-name">${file.name}</div>
                                <div class="file-size">${formatFileSize(file.size)}</div>
                            </div>
                            <button type="button" class="remove-file" onclick="removeFile(${index})">Remove</button>
                        `;
                        fileItems.appendChild(fileItem);
                    });
                } else {
                    fileList.style.display = 'none';
                }
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            // Global function to remove files
            window.removeFile = function(index) {
                selectedFiles.splice(index, 1);
                updateFileList();
            };
        });
    </script>
    @include('layouts.footer-js')
</body>
</html>
