<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MentorHub Tutor Dashboard</title>
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
                url('../images/Uc-background.jpg');
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

        /* Status Banner Styles */
        .status-banner {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .status-icon {
            font-size: 2rem;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .status-icon.pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-icon.approved {
            background-color: #d4edda;
            color: #155724;
        }

        .status-icon.freelancer {
            background-color: #e2d4f0;
            color: #4a148c;
        }

        .status-content {
            flex: 1;
        }

        .status-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .status-message {
            color: #666;
        }

        .status-actions {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.9rem;
            cursor: pointer;
            border: none;
            transition: all 0.3s;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .btn-primary {
            background-color: #2d7dd2;
            color: white;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
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

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin: 1.5rem 0;
            flex-wrap: wrap;
            gap: 1rem;
            width: 100%;
        }

        .greeting {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .date-time {
            font-size: 0.9rem;
            color: #666;
            align-self: flex-start;
        }

        .badge {
            display: inline-block;
            padding: 0.3em 0.7em;
            font-size: 0.75em;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
            margin-top: 0.5rem;
        }

        .badge-verified {
            color: #fff;
            background-color: #28a745;
        }

        .badge-freelancer {
            color: #fff;
            background-color: #4a148c;
        }

        .badge-pending {
            color: #fff;
            background-color: #ffc107;
        }

        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            font-size: 1.8rem;
            margin-bottom: 1rem;
            color: #2d7dd2;
        }

        .stat-title {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .stat-info {
            font-size: 0.85rem;
            color: #666;
            margin-top: auto;
        }

        /* Section Titles */
        .section-title {
            margin: 2rem 0 1rem;
            font-size: 1.3rem;
            color: #2d7dd2;
            position: relative;
            display: inline-block;
        }

        .section-title::after {
            content: '';
            position: absolute;
            width: 50%;
            height: 3px;
            background-color: #28a745;
            bottom: -5px;
            left: 0;
        }

        /* Today's Sessions */
        .sessions-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .session-card {
            display: flex;
            padding: 1rem;
            border-bottom: 1px solid #eee;
            align-items: center;
            transition: all 0.3s;
        }

        .session-card:hover {
            background-color: #f9f9f9;
            transform: translateY(-2px);
        }

        .session-card:last-child {
            border-bottom: none;
        }

        .session-time {
            background-color: #e8f4fd;
            padding: 0.8rem;
            border-radius: 5px;
            min-width: 80px;
            text-align: center;
            margin-right: 1rem;
        }

        .session-hour {
            font-weight: bold;
            color: #2d7dd2;
            font-size: 0.9rem;
        }

        .session-status {
            font-size: 0.7rem;
            color: #666;
        }

        .session-details {
            flex: 1;
        }

        .session-subject {
            font-weight: 600;
            margin-bottom: 0.2rem;
        }

        .session-student {
            font-size: 0.9rem;
            color: #666;
        }

        .session-actions {
            display: flex;
            gap: 0.5rem;
        }

        .session-actions button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s;
        }

        .session-actions button:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }

        .session-actions button.secondary {
            background-color: #6c757d;
        }

        .session-actions button.secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }

        /* Notifications Overview */
        .notifications-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .notification-card {
            display: flex;
            padding: 1rem;
            border-bottom: 1px solid #eee;
            align-items: center;
            transition: all 0.3s;
            cursor: pointer;
        }

        .notification-card:hover {
            background-color: #f9f9f9;
            transform: translateY(-2px);
        }

        .notification-card:last-child {
            border-bottom: none;
        }

        .notification-icon {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-right: 1rem;
            transition: transform 0.3s;
        }

        .notification-icon.booking-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .notification-icon.booking-accepted {
            background-color: #d4edda;
            color: #155724;
        }

        .notification-icon.booking-rejected {
            background-color: #f8d7da;
            color: #721c24;
        }

        .notification-icon.message {
            background-color: #e2d4f0;
            color: #4a148c;
        }

        .notification-card:hover .notification-icon {
            transform: scale(1.1);
        }

        .notification-info {
            flex: 1;
        }

        .notification-title {
            font-weight: 600;
            margin-bottom: 0.2rem;
            color: #333;
        }

        .notification-message {
            font-size: 0.9rem;
            color: #666;
        }

        .notification-time {
            text-align: right;
            font-size: 0.8rem;
            color: #999;
        }

        .no-notifications {
            padding: 2rem;
            text-align: center;
            color: #666;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        @media (min-width: 769px) {
            .quick-actions {
                grid-template-columns: repeat(5, 1fr);
            }
        }

        .action-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            padding: 1.2rem 1rem;
            text-align: center;
            transition: all 0.3s;
            text-decoration: none;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: pointer;
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .action-icon {
            font-size: 1.5rem;
            margin-bottom: 0.8rem;
            color: #2d7dd2;
            transition: transform 0.3s;
        }

        .action-card:hover .action-icon {
            transform: scale(1.2);
        }

        /* Teaching Schedule */
        .schedule-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .schedule-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 1rem;
            margin-top: 1rem;
        }

        .schedule-day {
            text-align: center;
            padding: 1rem 0.5rem;
            border-radius: 5px;
            background-color: #f8f9fa;
            transition: all 0.3s;
        }

        .schedule-day.has-session {
            background-color: #e8f4fd;
            border: 2px solid #2d7dd2;
        }

        .schedule-day:hover {
            transform: translateY(-3px);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .day-name {
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: #2d7dd2;
        }

        .day-sessions {
            font-size: 0.8rem;
            color: #666;
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
            justify-content: space-between;
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

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .modal-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: #2d7dd2;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .form-group input[type="file"] {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
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

            .dashboard-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .status-banner {
                flex-direction: column;
                text-align: center;
            }

            .status-actions {
                justify-content: center;
                width: 100%;
                margin-top: 1rem;
            }

            .stats-container {
                grid-template-columns: 1fr;
            }

            .quick-actions {
                grid-template-columns: repeat(2, 1fr);
            }

            .session-card {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.8rem;
            }

            .session-time {
                margin-right: 0;
            }

            .schedule-grid {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }

            .schedule-day {
                display: flex;
                justify-content: space-between;
                align-items: center;
                text-align: left;
                padding: 0.8rem;
            }
        }

        @media (max-width: 480px) {
            .quick-actions {
                grid-template-columns: 1fr;
            }
        }
        
        /* Modal Overlay Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s, visibility 0.3s;
        }
        
        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .modal-overlay .modal {
            background-color: white;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            transform: translateY(-20px);
            transition: transform 0.3s;
        }
        
        .modal-overlay.active .modal {
            transform: translateY(0);
        }
        
        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: #4a90e2;
        }
        
        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
        }
        
        .modal-body {
            padding: 1.5rem;
        }
        
        .booking-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }
        
        .btn-secondary {
            background-color: transparent;
            border: 1px solid #ddd;
            color: #333;
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-secondary:hover {
            background-color: #f5f5f5;
        }
        
        .btn-primary {
            background-color: #4a90e2;
            border: none;
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background-color: #3a7ccc;
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
                <a href="#" class="active">Dashboard</a>
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
                        @if($tutor->profile_picture)
                            <img src="{{ asset('storage/' . $tutor->profile_picture) }}?{{ time() }}" alt="Profile Picture" class="profile-icon-img">
                        @else
                            {{ strtoupper(substr($tutor->first_name, 0, 1) . substr($tutor->last_name, 0, 1)) }}
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

    <main>
        <div class="dashboard-header">
            <div>
                <div class="greeting">Welcome, {{ ucwords($tutor->first_name . ' ' . $tutor->last_name) }}!</div>
                <div class="badge badge-verified">Tutor</div>
            </div>
            <div class="date-time" id="current-date-time">Tuesday, May 13, 2025</div>
        </div>

        <h2 class="section-title">Today's Sessions</h2>
        <div class="sessions-container" id="sessions-container">
            <!-- Sessions will be loaded here dynamically -->
        </div>

        <h2 class="section-title">Quick Actions</h2>
        <div class="quick-actions">
            <div class="action-card" onclick="window.location.href='{{ route('tutor.notifications') }}'">
                <div class="action-icon"><i class="fas fa-bell"></i></div>
                <div>Notifications</div>
            </div>
            <div class="action-card" onclick="viewMessages()">
                <div class="action-icon"><i class="fas fa-comments"></i></div>
                <div>Messages</div>
            </div>
            <div class="action-card" onclick="viewMySessions()">
                <div class="action-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                <div>My Sessions</div>
            </div>
            <a href="{{ route('tutor.assignments.index') }}" class="action-card">
                <div class="action-icon"><i class="fas fa-tasks"></i></div>
                <div>See Assignments</div>
            </a>
            <div class="action-card" onclick="viewWallet()">
                <div class="action-icon"><i class="fas fa-wallet"></i></div>
                <div>Wallet</div>
            </div>
        </div>

        <h2 class="section-title">Notifications</h2>
        <div class="notifications-container" id="notifications-container">
            <!-- Notifications will be loaded here dynamically -->
        </div>

        <h2 class="section-title">Weekly Schedule</h2>
        <div class="schedule-container">
            <div class="schedule-grid">
                <div class="schedule-day has-session">
                    <div class="day-name">MON</div>
                    <div class="day-sessions">3 sessions</div>
                </div>
                <div class="schedule-day has-session">
                    <div class="day-name">TUE</div>
                    <div class="day-sessions">4 sessions</div>
                </div>
                <div class="schedule-day has-session">
                    <div class="day-name">WED</div>
                    <div class="day-sessions">2 sessions</div>
                </div>
                <div class="schedule-day has-session">
                    <div class="day-name">THU</div>
                    <div class="day-sessions">3 sessions</div>
                </div>
                <div class="schedule-day">
                    <div class="day-name">FRI</div>
                    <div class="day-sessions">Available</div>
                </div>
                <div class="schedule-day">
                    <div class="day-name">SAT</div>
                    <div class="day-sessions">Available</div>
                </div>
                <div class="schedule-day">
                    <div class="day-name">SUN</div>
                    <div class="day-sessions">Off</div>
                </div>
            </div>
        </div>
    </main>

    <!-- Upload Success Modal -->
    <div class="modal" id="uploadSuccessModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Upload Successful</h3>
                <button class="close-modal" onclick="hideSuccessModal()">&times;</button>
            </div>
            <div style="padding: 1rem;">
                <p>Your CV has been submitted for review. You will be notified once approved.</p>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-primary" onclick="hideSuccessModal()">OK</button>
            </div>
        </div>
    </div>

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

    <!-- Report a Problem Modal -->
    <div class="modal-overlay" id="report-problem-modal">
        <div class="modal">
            <div class="modal-header">
                <div class="modal-title">Report a Problem</div>
                <button class="modal-close" onclick="closeReportProblemModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="report-problem-form" method="POST" action="{{ route('tutor.report-problem.store') }}">
                    @csrf
                    
                    <div style="margin-bottom: 1.5rem;">
                        <h4 style="margin-bottom: 0.5rem;">Problem Type</h4>
                        <select name="problem_type" id="problem-type" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem;">
                            <option value="">Select a problem type...</option>
                            <option value="technical">Technical Issue</option>
                            <option value="payment">Payment Issue</option>
                            <option value="student">Student Related</option>
                            <option value="booking">Booking Issue</option>
                            <option value="account">Account Issue</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div style="margin-bottom: 1.5rem;">
                        <h4 style="margin-bottom: 0.5rem;">Subject</h4>
                        <input type="text" name="subject" id="problem-subject" required placeholder="Brief description of the problem" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem;">
                    </div>
                    
                    <div style="margin-bottom: 1.5rem;">
                        <h4 style="margin-bottom: 0.5rem;">Description</h4>
                        <textarea name="description" id="problem-description" required placeholder="Please provide detailed information about the problem you're experiencing..." style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem; resize: vertical; min-height: 150px;"></textarea>
                    </div>
                    
                    <div class="booking-actions" style="margin-top: 1.5rem;">
                        <button type="button" class="btn-secondary" onclick="closeReportProblemModal()">Cancel</button>
                        <button type="submit" class="btn-primary">Submit Report</button>
                    </div>
                </form>
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

            // Update current date and time
            const dateTimeElement = document.getElementById('current-date-time');
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const currentDate = new Date();
            dateTimeElement.textContent = currentDate.toLocaleDateString('en-US', options);

            // Load and display today's sessions
            loadTodaysSessions();
            
            // Load notifications
            loadNotifications();
            
            // Initialize currency display
            initializeCurrencyDisplay();
            loadCurrencyData();
        });

        function showUploadCVModal() {
            alert('Upload CV functionality is removed as per the instructions.');
        }

        function hideSuccessModal() {
            document.getElementById('uploadSuccessModal').style.display = 'none';
            location.reload();
        }

        function loadTodaysSessions() {
            const today = new Date().toISOString().slice(0, 10); // Get YYYY-MM-DD
            const url = `{{ route('tutor.sessions.today') }}?date=${today}`;

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(sessions => {
                    const sessionsContainer = document.getElementById('sessions-container');
                    sessionsContainer.innerHTML = ''; // Clear existing content

                    if (sessions.length > 0) {
                        sessions.forEach(session => {
                            const sessionCard = `
                                <div class="session-card">
                                    <div class="session-time">
                                        <div class="session-hour">${new Date('1970-01-01T' + session.start_time).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</div>
                                        <div class="session-status">${session.status}</div>
                                    </div>
                                    <div class="session-details">
                                        <div class="session-subject">Session with ${session.student.first_name} ${session.student.last_name}</div>
                                        <div class="session-student">Type: ${session.session_type.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase())}</div>
                                    </div>
                                    <div class="session-actions">
                                        <a href="/tutor/bookings/${session.id}" class="btn btn-primary" style="padding: 8px 16px; border-radius: 50px; text-decoration: none; font-size: 0.9rem; cursor: pointer; border: none; transition: all 0.3s; color: white;">View</a>
                                    </div>
                                </div>
                            `;
                            sessionsContainer.innerHTML += sessionCard;
                        });
                    } else {
                        sessionsContainer.innerHTML = `
                            <div style="padding: 2rem; text-align: center; color: #666; background-color: white; border-radius: 8px;">
                                No sessions scheduled for today
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error loading today\'s sessions:', error);
                    const sessionsContainer = document.getElementById('sessions-container');
                    sessionsContainer.innerHTML = `
                        <div style="padding: 2rem; text-align: center; color: #dc3545; background-color: white; border-radius: 8px;">
                            Error loading sessions. Please try again later.
                        </div>
                    `;
                });
        }

        function loadNotifications() {
            fetch('{{ route("tutor.notifications.pending") }}')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(notifications => {
                    const notificationsContainer = document.getElementById('notifications-container');
                    notificationsContainer.innerHTML = ''; // Clear existing content

                    if (notifications.length > 0) {
                        notifications.forEach(notification => {
                            const timeString = new Date('1970-01-01T' + notification.start_time).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                            const sessionType = notification.session_type.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
                            
                            // Format the date nicely
                            const dateObj = new Date(notification.date);
                            const formattedDate = dateObj.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
                            
                            const notificationCard = `
                                <div class="notification-card" onclick="window.location.href='/tutor/bookings/${notification.id}'">
                                    <div class="notification-icon booking-pending">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                    <div class="notification-info">
                                        <div class="notification-title">New Booking Request from ${notification.student_name}</div>
                                        <div class="notification-message">${sessionType} - ${formattedDate} at ${timeString}</div>
                                    </div>
                                    <div class="notification-time">
                                        <i class="fas fa-clock"></i> ${notification.created_at}
                                    </div>
                                </div>
                            `;
                            notificationsContainer.innerHTML += notificationCard;
                        });
                    } else {
                        notificationsContainer.innerHTML = `
                            <div class="no-notifications">
                                <i class="fas fa-bell-slash" style="font-size: 2rem; color: #ccc; margin-bottom: 0.5rem;"></i>
                                <p>No new notifications</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                    const notificationsContainer = document.getElementById('notifications-container');
                    notificationsContainer.innerHTML = `
                        <div class="no-notifications">
                            <i class="fas fa-exclamation-triangle" style="font-size: 2rem; color: #dc3545; margin-bottom: 0.5rem;"></i>
                            <p>Error loading notifications</p>
                        </div>
                    `;
                });
        }

        function viewMySessions() {
            window.location.href = "{{ route('tutor.my-sessions') }}";
        }

        function viewAssignments() {
            window.location.href = "{{ route('tutor.my-sessions') }}";
        }

        function scrollToNotifications() {
            document.querySelector('.notifications-container').scrollIntoView({ behavior: 'smooth' });
        }

        function uploadResources() {
            alert('Resources functionality coming soon!');
        }

        function viewMessages() {
            window.location.href = "{{ route('tutor.messages') }}";
        }

        function viewWallet() {
            window.location.href = "{{ route('tutor.wallet') }}";
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
            fetch('{{ route("tutor.wallet.balance") }}')
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
        
        // Report Problem Modal Functions
        function openReportProblemModal() {
            document.getElementById('report-problem-modal').classList.add('active');
            // Close the dropdown menu
            const dropdownMenu = document.getElementById('dropdown-menu');
            if (dropdownMenu) {
                dropdownMenu.classList.remove('active');
            }
        }
        
        function closeReportProblemModal() {
            document.getElementById('report-problem-modal').classList.remove('active');
            document.getElementById('report-problem-form').reset();
        }
        
        // Close report problem modal when clicking outside
        window.addEventListener('click', function(e) {
            if (e.target === document.getElementById('report-problem-modal')) {
                closeReportProblemModal();
            }
        });
    </script>
</body>
</html>