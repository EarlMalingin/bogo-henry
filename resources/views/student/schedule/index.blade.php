<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Schedule | MentorHub</title>
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

        .schedule-container {
            max-width: 1400px;
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

        .calendar-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .month-navigation {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .nav-btn {
            background: #2d7dd2;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 0.9rem;
            text-decoration: none;
        }

        .nav-btn:hover {
            background: #1e5bb8;
            transform: translateY(-2px);
        }

        .current-month {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2c3e50;
            min-width: 200px;
            text-align: center;
        }

        .view-toggle {
            display: flex;
            gap: 0.5rem;
        }

        .view-btn {
            padding: 0.5rem 1rem;
            border: 2px solid #2d7dd2;
            background: white;
            color: #2d7dd2;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 0.9rem;
        }

        .view-btn.active {
            background: #2d7dd2;
            color: white;
        }

        .view-btn:hover {
            background: #2d7dd2;
            color: white;
        }

        .calendar {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .calendar-header {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            background: #2d7dd2;
            color: white;
        }

        .calendar-header-cell {
            padding: 1rem;
            text-align: center;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .calendar-body {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            min-height: 500px;
        }

        .calendar-day {
            border: 1px solid #e9ecef;
            min-height: 120px;
            padding: 0.5rem;
            position: relative;
            transition: all 0.3s;
        }

        .calendar-day:hover {
            background: #f8f9fa;
        }

        .calendar-day.other-month {
            background: #f8f9fa;
            color: #adb5bd;
        }

        .calendar-day.today {
            background: #e3f2fd;
            border-color: #2d7dd2;
        }

        .calendar-day.has-sessions {
            background: #f0f8ff;
            border-color: #4a90e2;
        }

        .day-number {
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .day-number.today {
            background: #2d7dd2;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.5rem;
        }

        .session-indicator {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background: #28a745;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: bold;
        }

        .session-list {
            margin-top: 0.5rem;
        }

        .session-item {
            background: #e8f4fd;
            border-radius: 4px;
            padding: 0.25rem 0.5rem;
            margin-bottom: 0.25rem;
            font-size: 0.7rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 3px solid #2d7dd2;
            position: relative;
            overflow: hidden;
        }

        .session-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(45, 125, 210, 0.1), transparent);
            transition: left 0.5s;
        }

        .session-item:hover {
            background: #d1ecf1;
            transform: translateX(4px) scale(1.02);
            box-shadow: 0 4px 12px rgba(45, 125, 210, 0.2);
            border-left-color: #1e5bb8;
        }

        .session-item:hover::before {
            left: 100%;
        }

        .session-item:active {
            transform: translateX(2px) scale(1.01);
            transition: all 0.1s;
        }

        .session-time {
            font-weight: 600;
            color: #2d7dd2;
        }

        .session-tutor {
            color: #666;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .session-type {
            display: inline-block;
            padding: 0.1rem 0.3rem;
            border-radius: 3px;
            font-size: 0.6rem;
            font-weight: 500;
            margin-top: 0.1rem;
        }

        .session-type.online {
            background: #e3f2fd;
            color: #1976d2;
        }

        .session-type.face-to-face {
            background: #f3e5f5;
            color: #7b1fa2;
        }

        .session-details-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .session-details-modal.show {
            display: flex;
            opacity: 1;
        }

        .session-details-content {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            transform: scale(0.8) translateY(20px);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .session-details-modal.show .session-details-content {
            transform: scale(1) translateY(0);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e9ecef;
        }

        .modal-title {
            font-size: 1.5rem;
            color: #2d7dd2;
            font-weight: 600;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
            padding: 0.5rem;
            border-radius: 50%;
            transition: all 0.3s;
        }

        .close-modal:hover {
            background: #f8f9fa;
            color: #333;
        }

        .session-info {
            margin-bottom: 1rem;
        }

        .session-info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            padding: 0.5rem;
            background: #f8f9fa;
            border-radius: 6px;
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(10px);
            animation: slideInUp 0.4s ease forwards;
        }

        .session-info-item:nth-child(1) { animation-delay: 0.1s; }
        .session-info-item:nth-child(2) { animation-delay: 0.2s; }
        .session-info-item:nth-child(3) { animation-delay: 0.3s; }
        .session-info-item:nth-child(4) { animation-delay: 0.4s; }
        .session-info-item:nth-child(5) { animation-delay: 0.5s; }
        .session-info-item:nth-child(6) { animation-delay: 0.6s; }
        .session-info-item:nth-child(7) { animation-delay: 0.7s; }

        @keyframes slideInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .session-info-label {
            font-weight: 600;
            color: #2c3e50;
        }

        .session-info-value {
            color: #666;
        }

        .session-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 2px solid #e9ecef;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2d7dd2, #4a90e2);
            color: white;
            box-shadow: 0 4px 15px rgba(45, 125, 210, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1e5bb8, #357abd);
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 25px rgba(45, 125, 210, 0.4);
        }

        .btn-primary:active {
            transform: translateY(-1px) scale(1.02);
            transition: all 0.1s;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d, #868e96);
            color: white;
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #5a6268, #6c757d);
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 25px rgba(108, 117, 125, 0.4);
        }

        .btn-secondary:active {
            transform: translateY(-1px) scale(1.02);
            transition: all 0.1s;
        }

        .btn i {
            transition: transform 0.3s ease;
        }

        .btn:hover i {
            transform: scale(1.2);
        }

        .no-sessions {
            text-align: center;
            padding: 3rem;
            color: #666;
        }

        .no-sessions i {
            font-size: 3rem;
            color: #ddd;
            margin-bottom: 1rem;
        }

        .no-sessions h3 {
            margin-bottom: 0.5rem;
            color: #2c3e50;
        }

        @media (max-width: 768px) {
            .calendar-controls {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .month-navigation {
                order: 2;
            }

            .current-month {
                order: 1;
                min-width: auto;
            }

            .calendar-header-cell {
                padding: 0.5rem;
                font-size: 0.8rem;
            }

            .calendar-day {
                min-height: 80px;
                padding: 0.25rem;
            }

            .session-item {
                font-size: 0.6rem;
                padding: 0.2rem 0.4rem;
            }

            .session-details-content {
                padding: 1rem;
                width: 95%;
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
                <a href="{{ route('student.dashboard') }}">Dashboard</a>
                <a href="{{ route('student.book-session') }}">Book Session</a>
                <a href="{{ route('student.my-sessions') }}">Activities</a>
                <a href="{{ route('student.schedule') }}" class="active">Schedule</a>
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
                        @if($student->profile_picture)
                            <img src="{{ asset('storage/' . $student->profile_picture) }}?{{ time() }}" alt="Profile Picture" class="profile-icon-img">
                        @else
                            {{ strtoupper(substr($student->first_name, 0, 1) . substr($student->last_name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="dropdown-menu" id="dropdown-menu">
                        <a href="{{ route('student.profile.edit') }}">My Profile</a>
                        <a href="#">Settings</a>
                        <a href="#">Help Center</a>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" method="POST" action="{{ route('student.logout') }}" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main style="margin-top: 80px;">
        <div class="schedule-container">
            <div class="page-header">
                <h1 class="page-title">My Schedule</h1>
                <p class="page-subtitle">View your booked tutoring sessions</p>
            </div>

            <!-- Calendar Controls -->
            <div class="calendar-controls">
                <div class="month-navigation">
                    @php
                        $prevMonth = $month - 1;
                        $prevYear = $year;
                        if ($prevMonth < 1) {
                            $prevMonth = 12;
                            $prevYear = $year - 1;
                        }
                        
                        $nextMonth = $month + 1;
                        $nextYear = $year;
                        if ($nextMonth > 12) {
                            $nextMonth = 1;
                            $nextYear = $year + 1;
                        }
                    @endphp
                    <a href="{{ route('student.schedule', ['year' => $prevYear, 'month' => $prevMonth]) }}" class="nav-btn">
                        <i class="fas fa-chevron-left"></i> Previous
                    </a>
                    <div class="current-month">
                        {{ now()->setYear($year)->setMonth($month)->format('F Y') }}
                    </div>
                    <a href="{{ route('student.schedule', ['year' => $nextYear, 'month' => $nextMonth]) }}" class="nav-btn">
                        Next <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
                <div class="view-toggle">
                    <button class="view-btn active" onclick="showCalendarView()">
                        <i class="fas fa-calendar-alt"></i> Calendar
                    </button>
                    <button class="view-btn" onclick="showListView()">
                        <i class="fas fa-list"></i> List
                    </button>
                </div>
            </div>

            <!-- Calendar View -->
            <div id="calendar-view" class="calendar">
                <div class="calendar-header">
                    <div class="calendar-header-cell">Sunday</div>
                    <div class="calendar-header-cell">Monday</div>
                    <div class="calendar-header-cell">Tuesday</div>
                    <div class="calendar-header-cell">Wednesday</div>
                    <div class="calendar-header-cell">Thursday</div>
                    <div class="calendar-header-cell">Friday</div>
                    <div class="calendar-header-cell">Saturday</div>
                </div>
                <div class="calendar-body">
                    @foreach($calendarData as $week)
                        @foreach($week as $day)
                            <div class="calendar-day {{ !$day['isCurrentMonth'] ? 'other-month' : '' }} {{ $day['isToday'] ? 'today' : '' }} {{ $day['sessionCount'] > 0 ? 'has-sessions' : '' }}">
                                <div class="day-number {{ $day['isToday'] ? 'today' : '' }}">
                                    {{ $day['date']->format('j') }}
                                </div>
                                
                                @if($day['sessionCount'] > 0)
                                    <div class="session-indicator">
                                        {{ $day['sessionCount'] }}
                                    </div>
                                @endif
                                
                                @if($day['isCurrentMonth'] && $day['sessions']->count() > 0)
                                    <div class="session-list">
                                        @foreach($day['sessions']->take(3) as $session)
                                            <div class="session-item" onclick="showSessionDetails({{ $session->id }})">
                                                <div class="session-time">
                                                    {{ \Carbon\Carbon::parse($session->start_time)->format('g:i A') }}
                                                </div>
                                                <div class="session-tutor">
                                                    With {{ $session->tutor->first_name }} {{ $session->tutor->last_name }}
                                                </div>
                                                <div class="session-type {{ $session->session_type }}">
                                                    {{ ucfirst(str_replace('_', ' ', $session->session_type)) }}
                                                </div>
                                            </div>
                                        @endforeach
                                        @if($day['sessions']->count() > 3)
                                            <div class="session-item" style="background: #f8f9fa; color: #666; font-style: italic;">
                                                +{{ $day['sessions']->count() - 3 }} more...
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>

            <!-- List View -->
            <div id="list-view" class="calendar" style="display: none;">
                <div style="padding: 2rem;">
                    <h3 style="margin-bottom: 1.5rem; color: #2d7dd2;">All Sessions for {{ now()->setYear($year)->setMonth($month)->format('F Y') }}</h3>
                    
                    @if($sessions->count() > 0)
                        @foreach($sessions->groupBy('date') as $date => $daySessions)
                            <div style="margin-bottom: 2rem;">
                                <h4 style="color: #2c3e50; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #e9ecef;">
                                    {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}
                                </h4>
                                
                                @foreach($daySessions as $session)
                                    <div style="background: #f8f9fa; padding: 1rem; margin-bottom: 0.5rem; border-radius: 8px; border-left: 4px solid #2d7dd2;">
                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                            <div style="font-weight: 600; color: #2d7dd2;">
                                                {{ \Carbon\Carbon::parse($session->start_time)->format('g:i A') }} - 
                                                {{ \Carbon\Carbon::parse($session->end_time)->format('g:i A') }}
                                            </div>
                                            <div class="session-type {{ $session->session_type }}">
                                                {{ ucfirst(str_replace('_', ' ', $session->session_type)) }}
                                            </div>
                                        </div>
                                        <div style="color: #2c3e50; font-weight: 500;">
                                            With {{ $session->tutor->first_name }} {{ $session->tutor->last_name }}
                                        </div>
                                        <div style="color: #666; font-size: 0.9rem;">
                                            {{ $session->tutor->email }}
                                        </div>
                                        <div style="margin-top: 0.5rem;">
                                            <button onclick="showSessionDetails({{ $session->id }})" class="btn btn-primary" style="text-decoration: none;">
                                                <i class="fas fa-eye"></i> View Details
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    @else
                        <div class="no-sessions">
                            <i class="fas fa-calendar-times"></i>
                            <h3>No Sessions Scheduled</h3>
                            <p>You don't have any sessions scheduled for {{ now()->setYear($year)->setMonth($month)->format('F Y') }}.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <!-- Session Details Modal -->
    <div id="session-details-modal" class="session-details-modal">
        <div class="session-details-content">
            <div class="modal-header">
                <h3 class="modal-title">Session Details</h3>
                <button class="close-modal" onclick="closeSessionDetails()">&times;</button>
            </div>
            <div id="session-details-body">
                <!-- Session details will be loaded here -->
            </div>
        </div>
    </div>

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

            // Initialize currency display
            initializeCurrencyDisplay();
            loadCurrencyData();
        });

        function initializeCurrencyDisplay() {
            const currencyDisplay = document.querySelector('.currency-display');
            if (currencyDisplay) {
                currencyDisplay.addEventListener('click', function() {
                    viewWallet();
                });
            }
        }

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

        function viewWallet() {
            window.location.href = "{{ route('student.wallet') }}";
        }

        function showCalendarView() {
            document.getElementById('calendar-view').style.display = 'block';
            document.getElementById('list-view').style.display = 'none';
            
            // Update button states
            document.querySelectorAll('.view-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
        }

        function showListView() {
            document.getElementById('calendar-view').style.display = 'none';
            document.getElementById('list-view').style.display = 'block';
            
            // Update button states
            document.querySelectorAll('.view-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
        }

        function showSessionDetails(sessionId) {
            // Find the session data from the page
            const sessionData = getSessionData(sessionId);
            const modal = document.getElementById('session-details-modal');
            const body = document.getElementById('session-details-body');
            
            if (sessionData) {
                body.innerHTML = `
                    <div class="session-info">
                        <div class="session-info-item">
                            <span class="session-info-label">Tutor:</span>
                            <span class="session-info-value">${sessionData.tutorName}</span>
                        </div>
                        <div class="session-info-item">
                            <span class="session-info-label">Email:</span>
                            <span class="session-info-value">${sessionData.tutorEmail}</span>
                        </div>
                        <div class="session-info-item">
                            <span class="session-info-label">Subject:</span>
                            <span class="session-info-value">${sessionData.subject}</span>
                        </div>
                        <div class="session-info-item">
                            <span class="session-info-label">Date:</span>
                            <span class="session-info-value">${sessionData.date}</span>
                        </div>
                        <div class="session-info-item">
                            <span class="session-info-label">Time:</span>
                            <span class="session-info-value">${sessionData.time}</span>
                        </div>
                        <div class="session-info-item">
                            <span class="session-info-label">Type:</span>
                            <span class="session-info-value">${sessionData.type}</span>
                        </div>
                        <div class="session-info-item">
                            <span class="session-info-label">Status:</span>
                            <span class="session-info-value">${sessionData.status}</span>
                        </div>
                        ${sessionData.notes ? `
                        <div class="session-info-item">
                            <span class="session-info-label">Notes:</span>
                            <span class="session-info-value">${sessionData.notes}</span>
                        </div>
                        ` : ''}
                    </div>
                    <div class="session-actions">
                        <a href="{{ route('student.my-sessions') }}" class="btn btn-primary">
                            <i class="fas fa-list"></i> View All Sessions
                        </a>
                        <button class="btn btn-secondary" onclick="closeSessionDetails()">
                            <i class="fas fa-times"></i> Close
                        </button>
                    </div>
                `;
            } else {
                body.innerHTML = `
                    <div class="session-info">
                        <div class="session-info-item">
                            <span class="session-info-label">Error:</span>
                            <span class="session-info-value">Session not found</span>
                        </div>
                    </div>
                    <div class="session-actions">
                        <button class="btn btn-secondary" onclick="closeSessionDetails()">
                            <i class="fas fa-times"></i> Close
                        </button>
                    </div>
                `;
            }
            
            // Show modal with animation
            modal.style.display = 'flex';
            setTimeout(() => {
                modal.classList.add('show');
            }, 10);
        }

        function getSessionData(sessionId) {
            // Get session data from the sessions array passed to the view
            const sessions = @json($sessions);
            const session = sessions.find(s => s.id == sessionId);
            
            if (session) {
                return {
                    tutorName: `${session.tutor.first_name} ${session.tutor.last_name}`,
                    tutorEmail: session.tutor.email,
                    subject: session.tutor.specialization ? session.tutor.specialization.split(',')[0] : 'General Tutoring',
                    date: new Date(session.date).toLocaleDateString('en-US', { 
                        weekday: 'long', 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric' 
                    }),
                    time: `${new Date('1970-01-01T' + session.start_time).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})} - ${new Date('1970-01-01T' + session.end_time).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}`,
                    type: session.session_type.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase()),
                    status: session.status.charAt(0).toUpperCase() + session.status.slice(1),
                    notes: session.notes || ''
                };
            }
            return null;
        }

        function closeSessionDetails() {
            const modal = document.getElementById('session-details-modal');
            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }

        // Close modal when clicking outside
        document.getElementById('session-details-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeSessionDetails();
            }
        });
    </script>
</body>
</html>
