<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Students | MentorHub</title>
    <link rel="stylesheet" href="{{asset('style/dashboard.css')}}">
    <link rel="stylesheet" href="{{asset('style/session-modal.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

        .students-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            width: 100%;
            overflow-x: hidden;
        }

        .page-header {
            margin: 2rem 0;
            text-align: center;
        }

        .page-title {
            font-size: 2.5rem;
            color: #2d7dd2;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .page-subtitle {
            color: #666;
            font-size: 1.1rem;
        }

        .students-section {
            margin-bottom: 3rem;
        }

        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e9ecef;
        }

        .section-title {
            font-size: 1.5rem;
            color: #2d7dd2;
            margin-right: 1rem;
            font-weight: 600;
        }

        .section-count {
            background-color: #2d7dd2;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .students-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 1.5rem;
            width: 100%;
        }

        .student-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            padding: 1.5rem;
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }

        .student-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .student-header {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .student-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .student-avatar img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .student-info h3 {
            margin: 0 0 0.25rem 0;
            color: #2c3e50;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .student-info p {
            margin: 0;
            color: #7f8c8d;
            font-size: 0.9rem;
        }

        .student-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin: 1rem 0;
        }

        .stat-item {
            text-align: center;
            padding: 0.75rem;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2d7dd2;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.8rem;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .student-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background-color: #2d7dd2;
            color: white;
        }

        .btn-primary:hover {
            background-color: #1e5bb8;
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

        .last-session {
            font-size: 0.8rem;
            color: #666;
            margin-top: 0.5rem;
            padding-top: 0.5rem;
            border-top: 1px solid #e9ecef;
        }

        .no-students {
            text-align: center;
            padding: 3rem;
            color: #666;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .no-students i {
            font-size: 3rem;
            color: #ddd;
            margin-bottom: 1rem;
        }

        .no-students h3 {
            margin-bottom: 0.5rem;
            color: #2c3e50;
        }

        .tabs {
            display: flex;
            margin-bottom: 2rem;
            background: white;
            border-radius: 8px;
            padding: 0.25rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .tab {
            flex: 1;
            padding: 0.75rem 1rem;
            text-align: center;
            cursor: pointer;
            border-radius: 6px;
            transition: all 0.3s;
            font-weight: 500;
        }

        .tab.active {
            background-color: #2d7dd2;
            color: white;
        }

        .tab:not(.active) {
            color: #666;
        }

        .tab:not(.active):hover {
            background-color: #f8f9fa;
        }

        .rejected-student-card {
            border-left: 4px solid #dc3545;
        }

        .rejected-student-card .student-avatar {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        }

        .session-type-info {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.5rem;
            flex-wrap: wrap;
        }

        .session-type-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .session-type-badge.online {
            background-color: #e3f2fd;
            color: #1976d2;
        }

        .session-type-badge.face-to-face {
            background-color: #f3e5f5;
            color: #7b1fa2;
        }

        .session-type-badge i {
            font-size: 0.7rem;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        @media (max-width: 768px) {
            .students-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .student-card {
                max-width: 100%;
                margin: 0;
            }
            
            .student-stats {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.5rem;
            }
            
            .stat-item {
                padding: 0.5rem;
            }
            
            .stat-value {
                font-size: 1.2rem;
            }
            
            .stat-label {
                font-size: 0.7rem;
            }
            
            .student-actions {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .students-grid {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }
            
            .student-card {
                padding: 1rem;
            }
            
            .student-stats {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.25rem;
            }
            
            .stat-item {
                padding: 0.25rem;
            }
            
            .stat-value {
                font-size: 1rem;
            }
            
            .stat-label {
                font-size: 0.6rem;
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
                <a href="{{ route('tutor.dashboard') }}">Dashboard</a>
                <a href="{{ route('tutor.bookings.index') }}">My Bookings</a>
                <a href="{{ route('tutor.students') }}" class="active">Students</a>
                <a href="#">Schedule</a>
            </nav>
            <div class="profile-dropdown-container" style="position: relative;">
                <div class="profile-icon" id="profile-icon">
                    @if($tutor->profile_picture)
                        <img src="{{ asset('storage/' . $tutor->profile_picture) }}?v={{ file_exists(public_path('storage/' . $tutor->profile_picture)) ? filemtime(public_path('storage/' . $tutor->profile_picture)) : time() }}" alt="Profile Picture" class="profile-icon-img">
                    @else
                        {{ strtoupper(substr($tutor->first_name, 0, 1) . substr($tutor->last_name, 0, 1)) }}
                    @endif
                </div>
                <div class="dropdown-menu" id="dropdown-menu">
                    <a href="{{ route('tutor.profile.edit') }}">My Profile</a>
                    <a href="#">Settings</a>
                    <a href="#">Help Center</a>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    <form id="logout-form" method="POST" action="{{ route('tutor.logout') }}" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main style="margin-top: 80px;">
        <div class="students-container">
            <div class="page-header">
                <h1 class="page-title">My Students</h1>
                <p class="page-subtitle">Manage and track your current, past, and rejected students</p>
            </div>

            <!-- Tabs -->
            <div class="tabs">
                <div class="tab active" onclick="showTab('current')">
                    <i class="fas fa-users"></i> Current Students
                    <span class="section-count">{{ $currentStudents->count() }}</span>
                </div>
                <div class="tab" onclick="showTab('past')">
                    <i class="fas fa-user-graduate"></i> Past Students
                    <span class="section-count">{{ $pastStudents->count() }}</span>
                </div>
                <div class="tab" onclick="showTab('rejected')">
                    <i class="fas fa-user-times"></i> Rejected Students
                    <span class="section-count">{{ $rejectedStudents->count() }}</span>
                </div>
            </div>

            <!-- Current Students Tab -->
            <div id="current-tab" class="tab-content active">
                <div class="students-section">
                    <div class="section-header">
                        <h2 class="section-title">Current Students</h2>
                        <span class="section-count">{{ $currentStudents->count() }}</span>
                    </div>
                    
                    @if($currentStudents->count() > 0)
                        <div class="students-grid">
                            @foreach($currentStudents as $student)
                                <div class="student-card">
                                    <div class="student-header">
                                        <div class="student-avatar">
                                            @if($student->profile_picture)
                                                <img src="{{ asset('storage/' . $student->profile_picture) }}" alt="{{ $student->first_name }}">
                                            @else
                                                {{ strtoupper(substr($student->first_name, 0, 1) . substr($student->last_name, 0, 1)) }}
                                            @endif
                                        </div>
                                        <div class="student-info">
                                            <h3>{{ $student->first_name }} {{ $student->last_name }}</h3>
                                            <p>{{ $student->email }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="student-stats">
                                        <div class="stat-item">
                                            <div class="stat-value">{{ $student->stats['total_sessions'] }}</div>
                                            <div class="stat-label">Sessions</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-value">{{ $student->stats['completed_sessions'] }}</div>
                                            <div class="stat-label">Completed</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-value">{{ $student->stats['total_activities'] }}</div>
                                            <div class="stat-label">Activities</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-value">{{ number_format($student->stats['average_score'], 1) }}%</div>
                                            <div class="stat-label">Avg Score</div>
                                        </div>
                                    </div>
                                    
                                    @if($student->stats['last_session'])
                                        <div class="last-session">
                                            <i class="fas fa-clock"></i> Last session: {{ $student->stats['last_session']->format('M d, Y') }}
                                            @if($student->stats['last_session_type'])
                                                <span class="session-type-badge {{ $student->stats['last_session_type'] }}">
                                                    <i class="fas fa-{{ $student->stats['last_session_type'] == 'online' ? 'video' : 'user-friends' }}"></i>
                                                    {{ ucfirst(str_replace('_', ' ', $student->stats['last_session_type'])) }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                    
                                    <div class="session-type-info">
                                        @if($student->stats['online_sessions'] > 0)
                                            <span class="session-type-badge online">
                                                <i class="fas fa-video"></i>
                                                {{ $student->stats['online_sessions'] }} Online
                                            </span>
                                        @endif
                                        @if($student->stats['face_to_face_sessions'] > 0)
                                            <span class="session-type-badge face-to-face">
                                                <i class="fas fa-user-friends"></i>
                                                {{ $student->stats['face_to_face_sessions'] }} Face-to-Face
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="student-actions">
                                        <a href="{{ route('tutor.students.progress', $student->id) }}" class="btn btn-primary">
                                            <i class="fas fa-chart-line"></i> Progress
                                        </a>
                                        <a href="#" class="btn btn-secondary">
                                            <i class="fas fa-comments"></i> Message
                                        </a>
                                        <a href="#" class="btn btn-success">
                                            <i class="fas fa-plus"></i> Activity
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="no-students">
                            <i class="fas fa-users"></i>
                            <h3>No Current Students</h3>
                            <p>You don't have any active students at the moment. Students will appear here once they book sessions with you.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Past Students Tab -->
            <div id="past-tab" class="tab-content">
                <div class="students-section">
                    <div class="section-header">
                        <h2 class="section-title">Past Students</h2>
                        <span class="section-count">{{ $pastStudents->count() }}</span>
                    </div>
                    
                    @if($pastStudents->count() > 0)
                        <div class="students-grid">
                            @foreach($pastStudents as $student)
                                <div class="student-card">
                                    <div class="student-header">
                                        <div class="student-avatar">
                                            @if($student->profile_picture)
                                                <img src="{{ asset('storage/' . $student->profile_picture) }}" alt="{{ $student->first_name }}">
                                            @else
                                                {{ strtoupper(substr($student->first_name, 0, 1) . substr($student->last_name, 0, 1)) }}
                                            @endif
                                        </div>
                                        <div class="student-info">
                                            <h3>{{ $student->first_name }} {{ $student->last_name }}</h3>
                                            <p>{{ $student->email }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="student-stats">
                                        <div class="stat-item">
                                            <div class="stat-value">{{ $student->stats['total_sessions'] }}</div>
                                            <div class="stat-label">Sessions</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-value">{{ $student->stats['completed_sessions'] }}</div>
                                            <div class="stat-label">Completed</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-value">{{ $student->stats['total_activities'] }}</div>
                                            <div class="stat-label">Activities</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-value">{{ number_format($student->stats['average_score'], 1) }}%</div>
                                            <div class="stat-label">Avg Score</div>
                                        </div>
                                    </div>
                                    
                                    @if($student->stats['last_session'])
                                        <div class="last-session">
                                            <i class="fas fa-clock"></i> Last session: {{ $student->stats['last_session']->format('M d, Y') }}
                                            @if($student->stats['last_session_type'])
                                                <span class="session-type-badge {{ $student->stats['last_session_type'] }}">
                                                    <i class="fas fa-{{ $student->stats['last_session_type'] == 'online' ? 'video' : 'user-friends' }}"></i>
                                                    {{ ucfirst(str_replace('_', ' ', $student->stats['last_session_type'])) }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                    
                                    <div class="session-type-info">
                                        @if($student->stats['online_sessions'] > 0)
                                            <span class="session-type-badge online">
                                                <i class="fas fa-video"></i>
                                                {{ $student->stats['online_sessions'] }} Online
                                            </span>
                                        @endif
                                        @if($student->stats['face_to_face_sessions'] > 0)
                                            <span class="session-type-badge face-to-face">
                                                <i class="fas fa-user-friends"></i>
                                                {{ $student->stats['face_to_face_sessions'] }} Face-to-Face
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="student-actions">
                                        <a href="{{ route('tutor.students.progress', $student->id) }}" class="btn btn-primary">
                                            <i class="fas fa-chart-line"></i> Progress
                                        </a>
                                        <a href="#" class="btn btn-secondary">
                                            <i class="fas fa-comments"></i> Message
                                        </a>
                                        <a href="#" class="btn btn-success">
                                            <i class="fas fa-plus"></i> Activity
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="no-students">
                            <i class="fas fa-user-graduate"></i>
                            <h3>No Past Students</h3>
                            <p>Students who have completed all their sessions with you will appear here.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Rejected Students Tab -->
            <div id="rejected-tab" class="tab-content">
                <div class="students-section">
                    <div class="section-header">
                        <h2 class="section-title">Rejected Students</h2>
                        <span class="section-count">{{ $rejectedStudents->count() }}</span>
                    </div>
                    
                    @if($rejectedStudents->count() > 0)
                        <div class="students-grid">
                            @foreach($rejectedStudents as $student)
                                <div class="student-card rejected-student-card">
                                    <div class="student-header">
                                        <div class="student-avatar">
                                            @if($student->profile_picture)
                                                <img src="{{ asset('storage/' . $student->profile_picture) }}" alt="{{ $student->first_name }}">
                                            @else
                                                {{ strtoupper(substr($student->first_name, 0, 1) . substr($student->last_name, 0, 1)) }}
                                            @endif
                                        </div>
                                        <div class="student-info">
                                            <h3>{{ $student->first_name }} {{ $student->last_name }}</h3>
                                            <p>{{ $student->email }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="student-stats">
                                        <div class="stat-item">
                                            <div class="stat-value">{{ $student->stats['total_sessions'] }}</div>
                                            <div class="stat-label">Total Sessions</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-value">{{ $student->stats['rejected_sessions'] }}</div>
                                            <div class="stat-label">Rejected</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-value">{{ $student->stats['total_activities'] }}</div>
                                            <div class="stat-label">Activities</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-value">{{ number_format($student->stats['average_score'], 1) }}%</div>
                                            <div class="stat-label">Avg Score</div>
                                        </div>
                                    </div>
                                    
                                    @if($student->stats['last_session'])
                                        <div class="last-session">
                                            <i class="fas fa-clock"></i> Last session: {{ $student->stats['last_session']->format('M d, Y') }}
                                            @if($student->stats['last_session_type'])
                                                <span class="session-type-badge {{ $student->stats['last_session_type'] }}">
                                                    <i class="fas fa-{{ $student->stats['last_session_type'] == 'online' ? 'video' : 'user-friends' }}"></i>
                                                    {{ ucfirst(str_replace('_', ' ', $student->stats['last_session_type'])) }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                    
                                    <div class="session-type-info">
                                        @if($student->stats['online_sessions'] > 0)
                                            <span class="session-type-badge online">
                                                <i class="fas fa-video"></i>
                                                {{ $student->stats['online_sessions'] }} Online
                                            </span>
                                        @endif
                                        @if($student->stats['face_to_face_sessions'] > 0)
                                            <span class="session-type-badge face-to-face">
                                                <i class="fas fa-user-friends"></i>
                                                {{ $student->stats['face_to_face_sessions'] }} Face-to-Face
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="student-actions">
                                        <a href="{{ route('tutor.students.progress', $student->id) }}" class="btn btn-primary">
                                            <i class="fas fa-chart-line"></i> Progress
                                        </a>
                                        <a href="#" class="btn btn-secondary">
                                            <i class="fas fa-comments"></i> Message
                                        </a>
                                        <a href="#" class="btn btn-success">
                                            <i class="fas fa-redo"></i> Reconsider
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="no-students">
                            <i class="fas fa-user-times"></i>
                            <h3>No Rejected Students</h3>
                            <p>Students whose session requests you've rejected will appear here.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

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
        });

        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Remove active class from all tabs
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Show selected tab content
            document.getElementById(tabName + '-tab').classList.add('active');
            
            // Add active class to clicked tab
            event.target.classList.add('active');
        }
    </script>
</body>
</html>
