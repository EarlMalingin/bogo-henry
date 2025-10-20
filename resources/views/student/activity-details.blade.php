<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('style/dashboard.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>{{ $activity->title }} | MentorHub</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .activity-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .activity-header {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .activity-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .activity-meta {
            display: flex;
            gap: 2rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            color: #666;
        }

        .activity-type {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .type-activity { background-color: #e3f2fd; color: #1976d2; }
        .type-exam { background-color: #fff3e0; color: #f57c00; }
        .type-assignment { background-color: #f3e5f5; color: #7b1fa2; }
        .type-quiz { background-color: #e8f5e8; color: #388e3c; }

        .activity-description {
            color: #666;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .instructions-section {
            background-color: #f8f9fa;
            border-left: 4px solid #4a90e2;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-radius: 0 5px 5px 0;
        }

        .instructions-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 1rem;
        }

        .instructions-content {
            color: #666;
            line-height: 1.6;
        }

        .questions-section {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .question-item {
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid #e9ecef;
        }

        .question-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .question-number {
            font-weight: 600;
            color: #4a90e2;
            margin-bottom: 0.5rem;
        }

        .question-text {
            color: #333;
            margin-bottom: 1rem;
            line-height: 1.6;
        }

        .answer-input {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .answer-input:focus {
            outline: none;
            border-color: #4a90e2;
        }

        .answer-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .attachments-section {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .attachment-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        .attachment-item:last-child {
            margin-bottom: 0;
        }

        .attachment-icon {
            color: #4a90e2;
            font-size: 1.5rem;
        }

        .attachment-info {
            flex: 1;
        }

        .attachment-name {
            font-weight: 600;
            color: #333;
        }

        .attachment-size {
            font-size: 0.9rem;
            color: #666;
        }

        .download-btn {
            background-color: #4a90e2;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .download-btn:hover {
            background-color: #3a7ccc;
        }

        .activity-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.8rem 2rem;
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

        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }

        .back-btn {
            margin-bottom: 1rem;
            margin-top: 3rem;
        }

        .progress-info {
            background-color: #e3f2fd;
            border: 1px solid #bbdefb;
            border-radius: 5px;
            padding: 1rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .progress-icon {
            color: #1976d2;
            font-size: 1.5rem;
        }

        .progress-text {
            color: #1976d2;
            font-weight: 500;
        }

        /* File Upload Styles */
        .file-upload-area {
            border: 2px dashed #ddd;
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            background-color: #fafafa;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }

        .file-upload-area:hover {
            border-color: #4a90e2;
            background-color: #f0f8ff;
        }

        .file-upload-area.dragover {
            border-color: #4a90e2;
            background-color: #e3f2fd;
        }

        .upload-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .upload-icon {
            font-size: 3rem;
            color: #4a90e2;
        }

        .upload-text {
            font-size: 1.1rem;
            color: #333;
            margin: 0;
        }

        .upload-hint {
            font-size: 0.9rem;
            color: #666;
            margin: 0;
        }

        .file-list {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .file-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 5px;
            border: 1px solid #e9ecef;
        }

        .file-icon {
            color: #4a90e2;
            font-size: 1.5rem;
        }

        .file-info {
            flex: 1;
        }

        .file-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.25rem;
        }

        .file-size {
            font-size: 0.9rem;
            color: #666;
        }

        .remove-file-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .remove-file-btn:hover {
            background-color: #c82333;
        }

        @media (max-width: 768px) {
            .activity-meta {
                flex-direction: column;
                gap: 0.5rem;
            }

            .activity-actions {
                flex-direction: column;
            }

            .file-upload-area {
                padding: 1.5rem;
            }

            .upload-icon {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="navbar">
            <a href="#" class="logo">
                <img src="{{asset('images/MentorHub.png')}}" alt="MentorHub Logo" class="logo-img">
            </a>
            <button class="menu-toggle" id="menu-toggle">☰</button>
            <nav class="nav-links" id="nav-links">
                <a href="{{route('student.dashboard')}}">Dashboard</a>
                <a href="{{route('student.book-session')}}">Tutors</a>
                <a href="{{route('student.my-sessions')}}" class="active">Sessions</a>
                <a href="#">Resources</a>
            </nav>
            <div class="profile-icon" id="profile-icon">
                @auth('student')
                    @if(Auth::guard('student')->user()->profile_picture)
                        <img src="{{ asset('storage/' . Auth::guard('student')->user()->profile_picture) }}?{{ time() }}" alt="Profile Picture" class="profile-icon-img">
                    @else
                        {{ substr(Auth::guard('student')->user()->first_name, 0, 1) }}{{ substr(Auth::guard('student')->user()->last_name, 0, 1) }}
                    @endif
                    <div class="dropdown-menu" id="dropdown-menu">
                        <a href="{{ route('student.profile.edit') }}">My Profile</a>
                        <a href="#">Settings</a>
                        <a href="#">Help Center</a>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" method="POST" action="{{ route('student.logout') }}" style="display: none;">
                            @csrf
                        </form>
                    </div>
                @else
                    <a href="{{ route('login.student') }}" class="login-link">Login</a>
                @endauth
            </div>
        </div>
    </header>
    
    <!-- Main Content -->
    <main>
        <div class="activity-container">
            <div class="back-btn">
                <a href="{{ route('student.tutor.activities', $activity->tutor) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Activities
                </a>
            </div>

            <!-- Activity Header -->
            <div class="activity-header">
                <h1 class="activity-title">{{ $activity->title }}</h1>
                <div class="activity-type type-{{ $activity->type }}">{{ $activity->type }}</div>
                
                <div class="activity-meta">
                    <span><i class="fas fa-user"></i> Tutor: {{ $activity->tutor->first_name }} {{ $activity->tutor->last_name }}</span>
                    <span><i class="fas fa-calendar"></i> Created: {{ $activity->created_at->format('M d, Y') }}</span>
                    @if($activity->due_date)
                        <span><i class="fas fa-clock"></i> Due: {{ $activity->due_date->format('M d, Y g:i A') }}</span>
                    @endif
                    @if($activity->time_limit)
                        <span><i class="fas fa-stopwatch"></i> Time Limit: {{ $activity->time_limit }} minutes</span>
                    @endif
                </div>

                <div class="activity-description">
                    {{ $activity->description }}
                </div>

                @if($activity->instructions)
                    <div class="instructions-section">
                        <div class="instructions-title">
                            <i class="fas fa-info-circle"></i> Instructions
                        </div>
                        <div class="instructions-content">
                            {{ $activity->instructions }}
                        </div>
                    </div>
                @endif
            </div>

            @if($submission && $submission->status === 'graded')
                <!-- Graded Results -->
                <div class="progress-info">
                    <i class="fas fa-check-circle progress-icon"></i>
                    <div class="progress-text">
                        Activity completed! Your score: {{ $submission->score }}/{{ $activity->total_points }} 
                        ({{ round(($submission->score / $activity->total_points) * 100) }}%)
                    </div>
                </div>
            @elseif($submission && $submission->status === 'submitted')
                <!-- Submitted Status -->
                <div class="progress-info">
                    <i class="fas fa-clock progress-icon"></i>
                    <div class="progress-text">
                        Activity submitted and waiting for grading.
                    </div>
                </div>
            @else
                <!-- In Progress Status -->
                <div class="progress-info">
                    <i class="fas fa-edit progress-icon"></i>
                    <div class="progress-text">
                        {{ $submission ? 'Continue working on your answers below.' : 'Start answering the questions below.' }}
                    </div>
                </div>
            @endif

            <!-- Main Form -->
            <form id="activity-form" method="POST" action="{{ route('student.activities.save-draft', $activity) }}">
                @csrf
                
                @if($activity->questions && count($activity->questions) > 0)
                    <!-- Questions Section -->
                    <div class="questions-section">
                        <h2 style="margin-bottom: 2rem; color: #333;">Questions</h2>
                        
                        @foreach($activity->questions as $index => $question)
                            <div class="question-item">
                                <div class="question-number">Question {{ $index + 1 }}</div>
                                <div class="question-text">{{ $question['question'] ?? $question }}</div>
                                
                                @if(isset($question['type']) && $question['type'] === 'multiple_choice')
                                    <!-- Multiple Choice -->
                                    @if(isset($question['options']))
                                        @foreach($question['options'] as $optionIndex => $option)
                                            <label style="display: block; margin-bottom: 0.5rem; cursor: pointer;">
                                                <input type="radio" 
                                                       name="answers[{{ $index }}]" 
                                                       value="{{ $optionIndex }}"
                                                       {{ ($submission && isset($submission->answers[$index]) && $submission->answers[$index] == $optionIndex) ? 'checked' : '' }}
                                                       style="margin-right: 0.5rem;">
                                                {{ $option }}
                                            </label>
                                        @endforeach
                                    @endif
                                @else
                                    <!-- Text Answer -->
                                    <textarea name="answers[{{ $index }}]" 
                                              class="answer-input answer-textarea" 
                                              placeholder="Enter your answer here...">{{ $submission && isset($submission->answers[$index]) ? $submission->answers[$index] : '' }}</textarea>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
                
                <!-- Notes Section -->
                <div class="notes-section" style="margin-top: 2rem;">
                    <h3 style="margin-bottom: 1rem; color: #333;">Additional Notes (Optional)</h3>
                    <textarea name="notes" 
                              class="answer-input answer-textarea" 
                              placeholder="Add any additional notes or comments here...">{{ $submission && $submission->notes ? $submission->notes : '' }}</textarea>
                </div>
            </form>

            @if($activity->attachments && count($activity->attachments) > 0)
                <!-- Tutor Attachments Section -->
                <div class="attachments-section">
                    <h2 style="margin-bottom: 1.5rem; color: #333;">Tutor Attachments</h2>
                    
                    @foreach($activity->attachments as $attachment)
                        <div class="attachment-item">
                            <i class="fas fa-file attachment-icon"></i>
                            <div class="attachment-info">
                                <div class="attachment-name">{{ basename($attachment) }}</div>
                                <div class="attachment-size">Download file</div>
                            </div>
                            <a href="{{ asset('storage/' . $attachment) }}" 
                               class="download-btn" 
                               download>
                                <i class="fas fa-download"></i> Download
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif

            @if(!$submission || $submission->status !== 'graded')
                <!-- Student Attachments Section -->
                <div class="attachments-section">
                    <h2 style="margin-bottom: 1.5rem; color: #333;">Your Attachments</h2>
                    
                    <!-- File Upload Area -->
                    <div class="file-upload-area" id="file-upload-area">
                        <div class="upload-content">
                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                            <p class="upload-text">Drag and drop files here or click to browse</p>
                            <p class="upload-hint">Supported formats: PDF, DOC, DOCX, TXT, JPG, JPEG, PNG (Max 10MB each)</p>
                            <input type="file" id="student-attachments" name="student_attachments[]" multiple accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png" style="display: none;">
                            <button type="button" class="btn btn-secondary" onclick="document.getElementById('student-attachments').click()">
                                <i class="fas fa-plus"></i> Choose Files
                            </button>
                        </div>
                    </div>
                    
                    <!-- File List -->
                    <div class="file-list" id="file-list">
                        @if($submission && $submission->attachments)
                            @foreach($submission->attachments as $attachment)
                                <div class="file-item" data-filename="{{ basename($attachment) }}">
                                    <i class="fas fa-file file-icon"></i>
                                    <div class="file-info">
                                        <div class="file-name">{{ basename($attachment) }}</div>
                                        <div class="file-size">Uploaded file</div>
                                    </div>
                                    <button type="button" class="remove-file-btn" onclick="removeFile('{{ basename($attachment) }}')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endif

            <!-- Activity Actions -->
            <div class="activity-actions">
                @if($submission && $submission->status === 'graded')
                    <a href="{{ route('student.tutor.activities', $activity->tutor) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Activities
                    </a>
                    @if($submission->feedback)
                        <button class="btn btn-success" onclick="showFeedback()">
                            <i class="fas fa-comment"></i> View Feedback
                        </button>
                    @endif
                @elseif($submission && $submission->status === 'submitted')
                    <a href="{{ route('student.tutor.activities', $activity->tutor) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Activities
                    </a>
                @else
                    <button type="button" class="btn btn-secondary" onclick="saveDraft()">
                        <i class="fas fa-save"></i> Save Draft
                    </button>
                    <button type="button" class="btn btn-primary" onclick="submitActivity()">
                        <i class="fas fa-paper-plane"></i> Submit Activity
                    </button>
                @endif
            </div>
        </div>
    </main>
    
    <!-- Footer -->
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
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const menuToggle = document.getElementById('menu-toggle');
            const navLinks = document.getElementById('nav-links');
            
            menuToggle.addEventListener('click', function() {
                navLinks.classList.toggle('active');
            });
            
            // Profile dropdown
            const profileIcon = document.getElementById('profile-icon');
            const dropdownMenu = document.getElementById('dropdown-menu');
            
            profileIcon.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdownMenu.classList.toggle('active');
            });
            
            document.addEventListener('click', function() {
                if (dropdownMenu.classList.contains('active')) {
                    dropdownMenu.classList.remove('active');
                }
            });

            // File upload functionality
            initializeFileUpload();
            
            // Initialize existing files if any
            initializeExistingFiles();
        });

        function initializeExistingFiles() {
            // This function will be called to handle existing files from the server
            // For now, we'll just ensure the uploadedFiles array is ready
            console.log('Initializing existing files...');
        }

        function initializeFileUpload() {
            const fileInput = document.getElementById('student-attachments');
            const uploadArea = document.getElementById('file-upload-area');
            const fileList = document.getElementById('file-list');

            // Drag and drop functionality
            uploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                uploadArea.classList.add('dragover');
            });

            uploadArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                uploadArea.classList.remove('dragover');
            });

            uploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                uploadArea.classList.remove('dragover');
                const files = e.dataTransfer.files;
                handleFiles(files);
            });

            // File input change
            fileInput.addEventListener('change', function(e) {
                handleFiles(e.target.files);
            });

            // Click to upload
            uploadArea.addEventListener('click', function(e) {
                if (e.target === uploadArea || e.target.closest('.upload-content')) {
                    fileInput.click();
                }
            });
        }

        // Store uploaded files globally
        let uploadedFiles = [];

        function handleFiles(files) {
            const fileList = document.getElementById('file-list');
            
            Array.from(files).forEach(file => {
                // Validate file size (10MB max)
                if (file.size > 10 * 1024 * 1024) {
                    alert(`File "${file.name}" is too large. Maximum size is 10MB.`);
                    return;
                }

                // Validate file type
                const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain', 'image/jpeg', 'image/jpg', 'image/png'];
                if (!allowedTypes.includes(file.type)) {
                    alert(`File "${file.name}" is not a supported format.`);
                    return;
                }

                // Check if file already exists
                const existingFile = fileList.querySelector(`[data-filename="${file.name}"]`);
                if (existingFile) {
                    alert(`File "${file.name}" is already uploaded.`);
                    return;
                }

                // Add file to global array and list
                uploadedFiles.push(file);
                addFileToList(file);
            });
        }

        function addFileToList(file) {
            const fileList = document.getElementById('file-list');
            const fileItem = document.createElement('div');
            fileItem.className = 'file-item';
            fileItem.setAttribute('data-filename', file.name);
            
            const fileSize = formatFileSize(file.size);
            
            fileItem.innerHTML = `
                <i class="fas fa-file file-icon"></i>
                <div class="file-info">
                    <div class="file-name">${file.name}</div>
                    <div class="file-size">${fileSize}</div>
                </div>
                <button type="button" class="remove-file-btn" onclick="removeFile('${file.name}')">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            fileList.appendChild(fileItem);
        }

        function removeFile(filename) {
            const fileItem = document.querySelector(`[data-filename="${filename}"]`);
            if (fileItem) {
                fileItem.remove();
                // Remove from global array
                uploadedFiles = uploadedFiles.filter(file => file.name !== filename);
            }
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function saveDraft() {
            const form = document.getElementById('activity-form');
            const formData = new FormData(form);
            
            // Add student attachments to form data
            const fileInput = document.getElementById('student-attachments');
            if (fileInput.files.length > 0) {
                Array.from(fileInput.files).forEach(file => {
                    formData.append('student_attachments[]', file);
                });
            }
            
            // Add uploaded files from drag & drop
            uploadedFiles.forEach(file => {
                formData.append('student_attachments[]', file);
            });
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Draft saved successfully!');
                } else {
                    alert('Error saving draft: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error saving draft. Please try again.');
            });
        }

        function submitActivity() {
            if (confirm('Are you sure you want to submit this activity? You won\'t be able to make changes after submission.')) {
                console.log('Starting activity submission...');
                const form = document.getElementById('activity-form');
                const formData = new FormData(form);
                
                // Add student attachments to form data
                const fileInput = document.getElementById('student-attachments');
                console.log('File input files:', fileInput.files.length);
                if (fileInput.files.length > 0) {
                    Array.from(fileInput.files).forEach(file => {
                        console.log('Adding file from input:', file.name);
                        formData.append('student_attachments[]', file);
                    });
                }
                
                // Add uploaded files from drag & drop
                console.log('Uploaded files from drag & drop:', uploadedFiles.length);
                uploadedFiles.forEach(file => {
                    console.log('Adding file from drag & drop:', file.name);
                    formData.append('student_attachments[]', file);
                });
                
                console.log('Submitting to:', '{{ route("student.activities.submit", $activity) }}');
                
                fetch('{{ route("student.activities.submit", $activity) }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    if (data.success) {
                        alert('Activity submitted successfully!');
                        location.reload();
                    } else {
                        alert('Error submitting activity: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error submitting activity. Please try again.');
                });
            }
        }

        function showFeedback() {
            // Placeholder for showing feedback modal
            alert('Feedback: {{ $submission->feedback ?? "No feedback available." }}');
        }
    </script>
</body>
</html>
