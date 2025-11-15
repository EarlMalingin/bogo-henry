<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('style/dashboard.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>{{ $tutor->first_name }}'s Activities | MentorHub</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .activities-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .tutor-header {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            padding: 2rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .tutor-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #4a90e2;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.5rem;
        }

        .tutor-info h2 {
            margin: 0 0 0.5rem 0;
            color: #333;
        }

        .tutor-specialization {
            color: #666;
            font-size: 1.1rem;
        }

        .progress-stats {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
            flex-wrap: wrap;
        }

        .progress-stat {
            text-align: center;
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 5px;
            flex: 1;
            min-width: 120px;
        }

        .progress-stat-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #4a90e2;
        }

        .progress-stat-label {
            font-size: 0.9rem;
            color: #666;
        }

        .activities-list {
            display: grid;
            gap: 1.5rem;
        }

        .activity-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            padding: 1.5rem;
            transition: transform 0.3s;
        }

        .activity-card:hover {
            transform: translateY(-5px);
        }

        .activity-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .activity-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
        }

        .activity-type {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .type-activity { background-color: #e3f2fd; color: #1976d2; }
        .type-exam { background-color: #fff3e0; color: #f57c00; }
        .type-assignment { background-color: #f3e5f5; color: #7b1fa2; }
        .type-quiz { background-color: #e8f5e8; color: #388e3c; }

        .activity-details {
            color: #666;
            margin-bottom: 1rem;
            line-height: 1.5;
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

        .back-btn {
            margin-bottom: 1rem;
            margin-top: 3rem;
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
            .tutor-header {
                flex-direction: column;
                text-align: center;
            }

            .progress-stats {
                flex-direction: column;
            }
            
            .progress-stat {
                min-width: auto;
            }

            .activity-actions {
                flex-direction: column;
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
                        <img src="{{ route('student.profile.picture') }}?v={{ time() }}" alt="Profile Picture" class="profile-icon-img">
                    @else
                        {{ substr(Auth::guard('student')->user()->first_name, 0, 1) }}{{ substr(Auth::guard('student')->user()->last_name, 0, 1) }}
                    @endif
                    <div class="dropdown-menu" id="dropdown-menu">
                        <a href="{{ route('student.profile.edit') }}">My Profile</a>
                        <a href="{{ route('student.settings') }}">Achievements</a>
                        <a href="#">Report a Problem</a>
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
        <div class="activities-container">
            <div class="back-btn">
                <a href="{{ route('student.my-sessions') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to My Activities
                </a>
            </div>

            <!-- Tutor Header -->
            <div class="tutor-header">
                <div class="tutor-avatar">
                    @if($tutor->profile_picture)
                        <img src="{{ route('tutor.profile.picture.view', $tutor->id) }}" alt="Tutor" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                    @else
                        {{ strtoupper(substr($tutor->first_name, 0, 1) . substr($tutor->last_name, 0, 1)) }}
                    @endif
                </div>
                <div class="tutor-info">
                    <h2>{{ $tutor->first_name }} {{ $tutor->last_name }}</h2>
                    <div class="tutor-specialization">{{ $tutor->specialization ?? 'General Tutoring' }}</div>
                    <div class="progress-stats" id="progress-stats">
                        <div class="progress-stat">
                            <div class="progress-stat-value">{{ $activities->count() }}</div>
                            <div class="progress-stat-label">Total Activities</div>
                        </div>
                        <div class="progress-stat">
                            <div class="progress-stat-value" id="submitted-count">0</div>
                            <div class="progress-stat-label">Submitted</div>
                        </div>
                        <div class="progress-stat">
                            <div class="progress-stat-value" id="graded-count">0</div>
                            <div class="progress-stat-label">Graded</div>
                        </div>
                        <div class="progress-stat">
                            <div class="progress-stat-value" id="average-score">0</div>
                            <div class="progress-stat-label">Average Score</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activities List -->
            <div class="activities-list">
                @forelse($activities as $activity)
                    @php
                        $submission = $activity->submissions->first();
                    @endphp
                    <div class="activity-card">
                        <div class="activity-header">
                            <div class="activity-title">{{ $activity->title }}</div>
                            <div class="activity-type type-{{ $activity->type }}">{{ $activity->type }}</div>
                        </div>
                        
                        <div class="activity-details">
                            {{ $activity->description }}
                        </div>
                        
                        <div class="activity-meta">
                            <span><i class="fas fa-calendar"></i> {{ $activity->created_at->format('M d, Y') }}</span>
                            @if($activity->due_date)
                                <span><i class="fas fa-clock"></i> Due: {{ $activity->due_date->format('M d, Y g:i A') }}</span>
                            @endif
                            <span class="status-badge status-{{ $submission ? $submission->status : 'sent' }}">
                                {{ ucfirst($submission ? $submission->status : 'sent') }}
                            </span>
                        </div>
                        
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $submission ? ($submission->status === 'graded' ? 100 : 50) : 0 }}%"></div>
                        </div>
                        
                        <div class="activity-actions">
                            @if($submission && $submission->status === 'graded')
                                <a href="{{ route('student.activities.show', $activity) }}" class="btn btn-secondary">
                                    <i class="fas fa-eye"></i> View Results
                                </a>
                                @if($submission->feedback)
                                    <button class="btn btn-success" onclick="showFeedback({{ $submission->id }})">
                                        <i class="fas fa-comment"></i> View Feedback
                                    </button>
                                @endif
                            @elseif($submission && $submission->status === 'submitted')
                                <a href="{{ route('student.activities.show', $activity) }}" class="btn btn-secondary">
                                    <i class="fas fa-eye"></i> View Submission
                                </a>
                            @else
                                <a href="{{ route('student.activities.show', $activity) }}" class="btn btn-primary">
                                    <i class="fas fa-play"></i> {{ $submission ? 'Continue' : 'Start' }} Activity
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="activity-card" style="text-align: center; padding: 3rem;">
                        <h3 style="color: #666; margin-bottom: 1rem;">No activities yet</h3>
                        <p style="color: #999;">This tutor hasn't sent you any activities yet.</p>
                    </div>
                @endforelse
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

            // Load tutor progress
            loadTutorProgress({{ $tutor->id }});
        });

        function loadTutorProgress(tutorId) {
            fetch(`/student/tutor/${tutorId}/progress`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('submitted-count').textContent = data.submitted;
                    document.getElementById('graded-count').textContent = data.graded;
                    document.getElementById('average-score').textContent = Math.round(data.average_score);
                })
                .catch(error => {
                    console.error('Error loading tutor progress:', error);
                });
        }

        function showFeedback(submissionId) {
            // Placeholder for showing feedback modal
            alert('Feedback feature will be implemented soon!');
        }
    </script>
    
    @include('layouts.footer-js')
</body>
</html>
