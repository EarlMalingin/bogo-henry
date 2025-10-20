<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('style/dashboard.css')}}">
    <link rel="stylesheet" href="{{asset('style/session-modal.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>My Activities | MentorHub</title>
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

        /* Additional styles for the my-sessions page */
        .activities-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            padding: 1.5rem;
            text-align: center;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: #4a90e2;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
        
        .tabs {
            display: flex;
            border-bottom: 2px solid #ddd;
            margin-bottom: 2rem;
        }
        
        .tab-link {
            padding: 1rem 1.5rem;
            cursor: pointer;
            border: none;
            background-color: transparent;
            font-size: 1rem;
            font-weight: 500;
            color: #666;
            position: relative;
            top: 2px;
        }
        
        .tab-link.active {
            color: #4a90e2;
            border-bottom: 2px solid #4a90e2;
            font-weight: 600;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }

        .tutors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .tutor-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            padding: 1.5rem;
            transition: transform 0.3s;
            cursor: pointer;
        }

        .tutor-card:hover {
            transform: translateY(-5px);
        }

        .tutor-header {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .tutor-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #4a90e2;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin-right: 1rem;
        }

        .tutor-info h3 {
            margin: 0;
            color: #333;
        }

        .tutor-specialization {
            color: #666;
            font-size: 0.9rem;
        }

        .tutor-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-top: 1rem;
        }

        .tutor-stat {
            text-align: center;
        }

        .tutor-stat-value {
            font-size: 1.2rem;
            font-weight: bold;
            color: #4a90e2;
        }

        .tutor-stat-label {
            font-size: 0.8rem;
            color: #666;
        }
        
        .activity-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .activity-details {
            flex: 1;
            min-width: 250px;
        }
        
        .activity-title {
            font-weight: 600;
            font-size: 1.1rem;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .activity-type {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .type-activity { background-color: #e3f2fd; color: #1976d2; }
        .type-exam { background-color: #fff3e0; color: #f57c00; }
        .type-assignment { background-color: #f3e5f5; color: #7b1fa2; }
        .type-quiz { background-color: #e8f5e8; color: #388e3c; }
        
        .activity-info {
            color: #666;
            margin-top: 0.5rem;
            line-height: 1.4;
        }

        .activity-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            color: #666;
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background-color: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .progress-fill {
            height: 100%;
            background-color: #28a745;
            transition: width 0.3s;
        }
        
        .activity-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            text-decoration: none;
            font-size: 0.9rem;
            cursor: pointer;
            border: none;
            transition: all 0.3s;
            display: inline-block;
            text-align: center;
        }
        
        .btn-primary {
            background-color: #4a90e2;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #3a7ccc;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        
        .btn-success {
            background-color: #28a745;
            color: white;
        }
        
        .btn-success:hover {
            background-color: #218838;
        }
        
        .btn-warning {
            background-color: #ffc107;
            color: #000;
        }
        
        .btn-warning:hover {
            background-color: #e0a800;
        }
        
        .status-badge {
            display: inline-block;
            padding: 0.3em 0.7em;
            font-size: 0.75em;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
            color: #fff;
        }
        
        .status-sent { background-color: #17a2b8; }
        .status-in_progress { background-color: #ffc107; color: #000; }
        .status-completed { background-color: #28a745; }
        .status-graded { background-color: #6f42c1; }
        
        .no-activities {
            text-align: center;
            padding: 3rem;
            color: #666;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .no-activities h3 {
            margin-bottom: 1rem;
            color: #4a90e2;
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
        
        @media (max-width: 768px) {
            .activity-card {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .activity-actions {
                width: 100%;
                justify-content: flex-start;
            }
            
            .tabs {
                flex-wrap: wrap;
            }

            .tutors-grid {
                grid-template-columns: 1fr;
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
                <a href="{{route('student.my-sessions')}}" class="active">Sessions</a>
                
            </nav>
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
    </header>
    
    <!-- Main Content -->
    <main>
        <div class="activities-container">
            <div class="dashboard-header" style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem;">
                <div style="display: flex; flex-direction: column; align-items: flex-start;">
                    <h1 class="greeting">My Activities</h1>
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
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Statistics -->
            <div class="stats-grid" id="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-tasks"></i></div>
                    <div class="stat-value" id="total-activities">0</div>
                    <div class="stat-label">Total Activities</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-paper-plane"></i></div>
                    <div class="stat-value" id="submitted-activities">0</div>
                    <div class="stat-label">Submitted</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-graduation-cap"></i></div>
                    <div class="stat-value" id="graded-activities">0</div>
                    <div class="stat-label">Graded</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-clock"></i></div>
                    <div class="stat-value" id="pending-activities">0</div>
                    <div class="stat-label">Pending</div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="tabs">
                <button class="tab-link active" onclick="openTab(event, 'tutors')">My Tutors</button>
                <button class="tab-link" onclick="openTab(event, 'pending')">Pending Activities</button>
                <button class="tab-link" onclick="openTab(event, 'completed')">Completed Activities</button>
            </div>

            <!-- My Tutors Tab -->
            <div id="tutors" class="tab-content active">
                <h2>My Tutors</h2>
                <div class="tutors-grid">
                    @forelse($tutors as $tutor)
                        <div class="tutor-card" onclick="viewTutorActivities({{ $tutor->id }})">
                            <div class="tutor-header">
                                <div class="tutor-avatar">
                                    @if($tutor->profile_picture)
                                        <img src="{{ asset('storage/' . $tutor->profile_picture) }}" alt="Tutor" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                                    @else
                                        {{ strtoupper(substr($tutor->first_name, 0, 1) . substr($tutor->last_name, 0, 1)) }}
                                    @endif
                                </div>
                                <div class="tutor-info">
                                    <h3>{{ $tutor->first_name }} {{ $tutor->last_name }}</h3>
                                    <div class="tutor-specialization">{{ $tutor->specialization ?? 'General Tutoring' }}</div>
                                </div>
                            </div>
                            <div class="tutor-stats">
                                <div class="tutor-stat">
                                    <div class="tutor-stat-value">{{ $tutor->activities->count() }}</div>
                                    <div class="tutor-stat-label">Activities</div>
                                </div>
                                <div class="tutor-stat">
                                    <div class="tutor-stat-value">{{ $tutor->activities->where('status', 'completed')->count() }}</div>
                                    <div class="tutor-stat-label">Completed</div>
                                </div>
                                <div class="tutor-stat">
                                    <div class="tutor-stat-value">{{ $tutor->activities->where('status', 'graded')->count() }}</div>
                                    <div class="tutor-stat-label">Graded</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="no-activities" style="grid-column: 1 / -1;">
                            <h3>No tutors yet</h3>
                            <p>You haven't been assigned any activities by tutors yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Pending Activities Tab -->
            <div id="pending" class="tab-content">
                <h2>Pending Activities</h2>
                @php
                    $pendingActivities = $activities->filter(function($activity) {
                        $submission = $activity->submissions->first();
                        return !$submission || $submission->status === 'draft';
                    });
                @endphp
                
                @forelse($pendingActivities as $activity)
                    <div class="activity-card">
                        <div class="activity-details">
                            <div class="activity-title">{{ $activity->title }}</div>
                            <div class="activity-type type-{{ $activity->type }}">{{ $activity->type }}</div>
                            <div class="activity-info">
                                <strong>Tutor:</strong> {{ $activity->tutor->first_name }} {{ $activity->tutor->last_name }}<br>
                                <strong>Description:</strong> {{ Str::limit($activity->description, 100) }}<br>
                                @if($activity->due_date)
                                    <strong>Due:</strong> {{ $activity->due_date->format('M d, Y g:i A') }}<br>
                                @endif
                                <strong>Points:</strong> {{ $activity->total_points }}
                            </div>
                            <div class="activity-meta">
                                <span><i class="fas fa-calendar"></i> {{ $activity->created_at->format('M d, Y') }}</span>
                                <span class="status-badge status-{{ $activity->status }}">{{ ucfirst($activity->status) }}</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 0%"></div>
                            </div>
                        </div>
                        <div class="activity-actions">
                            <a href="{{ route('student.activities.show', $activity) }}" class="btn btn-primary">
                                <i class="fas fa-play"></i> Start Activity
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="no-activities">
                        <h3>No pending activities</h3>
                        <p>You don't have any pending activities to complete.</p>
                    </div>
                @endforelse
            </div>

            <!-- Completed Activities Tab -->
            <div id="completed" class="tab-content">
                <h2>Completed Activities</h2>
                @php
                    $completedActivities = $activities->filter(function($activity) {
                        $submission = $activity->submissions->first();
                        return $submission && in_array($submission->status, ['submitted', 'graded']);
                    });
                @endphp
                
                @forelse($completedActivities as $activity)
                    @php
                        $submission = $activity->submissions->first();
                    @endphp
                    <div class="activity-card">
                        <div class="activity-details">
                            <div class="activity-title">{{ $activity->title }}</div>
                            <div class="activity-type type-{{ $activity->type }}">{{ $activity->type }}</div>
                            <div class="activity-info">
                                <strong>Tutor:</strong> {{ $activity->tutor->first_name }} {{ $activity->tutor->last_name }}<br>
                                <strong>Description:</strong> {{ Str::limit($activity->description, 100) }}<br>
                                @if($submission && $submission->status === 'graded')
                                    <strong>Score:</strong> {{ $submission->score }}/{{ $activity->total_points }} ({{ $submission->getGradeLetter() }})<br>
                                @endif
                                <strong>Submitted:</strong> {{ $submission->submitted_at->format('M d, Y g:i A') }}
                            </div>
                            <div class="activity-meta">
                                <span><i class="fas fa-calendar"></i> {{ $activity->created_at->format('M d, Y') }}</span>
                                <span class="status-badge status-{{ $submission->status }}">{{ ucfirst($submission->status) }}</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 100%"></div>
                            </div>
                        </div>
                        <div class="activity-actions">
                            <a href="{{ route('student.activities.show', $activity) }}" class="btn btn-secondary">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                            @if($submission && $submission->status === 'graded' && $submission->feedback)
                                <button class="btn btn-success" onclick="showFeedback({{ $submission->id }})">
                                    <i class="fas fa-comment"></i> View Feedback
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="no-activities">
                        <h3>No completed activities</h3>
                        <p>You haven't completed any activities yet.</p>
                    </div>
                @endforelse
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
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const currentDate = new Date();
            dateTimeElement.textContent = currentDate.toLocaleDateString('en-US', options);

            // Load initial stats
            loadStats();
        });
        
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].classList.remove('active');
            }
            tablinks = document.getElementsByClassName("tab-link");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].classList.remove('active');
            }
            document.getElementById(tabName).classList.add('active');
            evt.currentTarget.classList.add('active');
        }

        function viewTutorActivities(tutorId) {
            window.location.href = `/student/tutor/${tutorId}/activities`;
        }

        function loadStats() {
            fetch('{{ route("student.activities.stats") }}')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('total-activities').textContent = data.total_activities;
                    document.getElementById('submitted-activities').textContent = data.submitted_activities;
                    document.getElementById('graded-activities').textContent = data.graded_activities;
                    document.getElementById('pending-activities').textContent = data.pending_activities;
                })
                .catch(error => {
                    console.error('Error loading stats:', error);
                });
        }

        function showFeedback(submissionId) {
            // Placeholder for showing feedback modal
            alert('Feedback feature will be implemented soon!');
        }
    </script>
</body>
</html>
