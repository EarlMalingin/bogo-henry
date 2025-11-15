<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MentorHub Tutor Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('style/dashboard.css')}}">
    <link rel="stylesheet" href="{{asset('style/session-modal.css')}}">
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
            align-items: center;
            justify-content: center;
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
                            <img src="{{ route('tutor.profile.picture') }}?v={{ time() }}" alt="Profile Picture" class="profile-icon-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center; background-color: #f5f5f5; color: #666; font-weight: bold; font-size: 1.2rem; border-radius: 50%;">{{ strtoupper(substr($tutor->first_name, 0, 1) . substr($tutor->last_name, 0, 1)) }}</div>
                        @else
                            {{ strtoupper(substr($tutor->first_name, 0, 1) . substr($tutor->last_name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="dropdown-menu" id="dropdown-menu">
                        <a href="{{ route('tutor.profile.edit') }}">My Profile</a>
                        <a href="{{ route('tutor.settings') }}">Achievements</a>
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

    <!-- Footer Modals -->
    <!-- Privacy Policy Modal -->
    <div id="privacy-modal" class="footer-modal">
        <div class="footer-modal-content">
            <div class="footer-modal-header">
                <h2>Privacy Policy</h2>
                <button class="footer-modal-close">&times;</button>
            </div>
            <div class="footer-modal-body">
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
                
                <h3>3. Data Security</h3>
                <p>We implement industry-standard security measures to protect your personal information, including encryption, regular security audits, and access controls.</p>
                
                <h3>4. Your Rights</h3>
                <p>You have the right to access, correct, delete your personal information, and opt-out of marketing communications.</p>
                
                <h3>5. Contact Us</h3>
                <p>For questions about this Privacy Policy, contact us at:</p>
                <ul>
                    <li>Email: <a href="mailto:MentorHub.Website@gmail.com">MentorHub.Website@gmail.com</a></li>
                    <li>Phone: +63958667092</li>
                    <li>Address: University of Cebu, Cebu City, Philippines</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Terms of Service Modal -->
    <div id="terms-modal" class="footer-modal">
        <div class="footer-modal-content">
            <div class="footer-modal-header">
                <h2>Terms of Service</h2>
                <button class="footer-modal-close">&times;</button>
            </div>
            <div class="footer-modal-body">
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
                
                <h3>5. Contact Information</h3>
                <p>For questions about these Terms and Conditions, please contact us at <a href="mailto:MentorHub.Website@gmail.com">MentorHub.Website@gmail.com</a> or through our support channels.</p>
            </div>
        </div>
    </div>

    <!-- FAQ Modal -->
    <div id="faq-modal" class="footer-modal">
        <div class="footer-modal-content">
            <div class="footer-modal-header">
                <h2>Frequently Asked Questions</h2>
                <button class="footer-modal-close">&times;</button>
            </div>
            <div class="footer-modal-body">
                <h3>How do I manage my bookings?</h3>
                <p>You can view and manage all your bookings in the "My Bookings" section. From there, you can accept, reject, or complete sessions.</p>
                
                <h3>How do I get paid for tutoring sessions?</h3>
                <p>Earnings from completed sessions are automatically added to your wallet. You can withdraw funds to your bank account through the Cash Out feature.</p>
                
                <h3>What happens if a student cancels a session?</h3>
                <p>Cancellations made more than 24 hours in advance are fully refunded. Cancellations within 24 hours may still compensate tutors depending on the circumstances.</p>
                
                <h3>How do I communicate with my students?</h3>
                <p>You can send messages directly to students through the messaging feature on the platform.</p>
                
                <h3>What should I do if I have a technical issue?</h3>
                <p>Please use the "Report a Problem" feature in your dashboard to submit a detailed report of any technical issues you encounter.</p>
            </div>
        </div>
    </div>

    <!-- Contact Modal -->
    <div id="contact-modal" class="footer-modal">
        <div class="footer-modal-content">
            <div class="footer-modal-header">
                <h2>Contact Us</h2>
                <button class="footer-modal-close">&times;</button>
            </div>
            <div class="footer-modal-body">
                <h3>Get in Touch</h3>
                <p>We're here to help! Reach out to us through any of the following channels:</p>
                
                <h3>Email</h3>
                <p>
                    <strong>Email Us:</strong> <a href="mailto:MentorHub.Website@gmail.com">MentorHub.Website@gmail.com</a><br>
                    <small style="color: #666;">We typically respond within 24 hours</small>
                </p>
                
                <h3>Phone</h3>
                <p>+63958667092</p>
                
                <h3>Address</h3>
                <p>University of Cebu<br>Cebu City, Philippines</p>
                
                <h3>Business Hours</h3>
                <p>Monday - Friday: 8:00 AM - 6:00 PM<br>Saturday: 9:00 AM - 3:00 PM<br>Sunday: Closed</p>
            </div>
        </div>
    </div>

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

    <!-- Session Details Modal -->
    <div id="session-modal" class="session-modal">
        <div class="session-modal-content">
            <span class="session-modal-close-btn">&times;</span>
            <h2>Session Details</h2>
            <div class="session-modal-body">
                <!-- Session details will be populated here -->
            </div>
            <div class="session-modal-footer" id="modal-footer">
                <button id="modal-message-btn">
                    <i class="fas fa-comments"></i> Message
                </button>
                <button id="modal-view-booking-btn">
                    <i class="fas fa-calendar-check"></i> View Booking
                </button>
                <button id="modal-close-btn">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>

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
                                        <button onclick='showSessionModal(${JSON.stringify(session)})' class="btn btn-primary" style="padding: 8px 16px; border-radius: 50px; text-decoration: none; font-size: 0.9rem; cursor: pointer; border: none; transition: all 0.3s; color: white; background-color: #28a745;">View</button>
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

        // Footer Modal Functions
        function getScrollbarWidth() {
            // Create a temporary div to measure scrollbar width
            const outer = document.createElement('div');
            outer.style.visibility = 'hidden';
            outer.style.overflow = 'scroll';
            outer.style.msOverflowStyle = 'scrollbar';
            document.body.appendChild(outer);
            
            const inner = document.createElement('div');
            outer.appendChild(inner);
            
            const scrollbarWidth = outer.offsetWidth - inner.offsetWidth;
            outer.parentNode.removeChild(outer);
            
            return scrollbarWidth;
        }

        function openFooterModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                // Calculate scrollbar width before hiding it
                const scrollbarWidth = getScrollbarWidth();
                
                // Add padding to prevent layout shift
                document.body.style.paddingRight = scrollbarWidth + 'px';
                document.body.style.overflow = 'hidden';
                
                modal.style.display = 'flex';
                // Force reflow to ensure display is set before adding class
                void modal.offsetWidth;
                modal.classList.add('show');
            }
        }

        function closeFooterModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('show');
                
                // Wait for animation to complete before restoring scrollbar
                setTimeout(() => {
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';
                    modal.style.display = 'none';
                }, 300);
            }
        }

        // Add event listeners for footer links
        document.getElementById('footer-privacy-link')?.addEventListener('click', function(e) {
            e.preventDefault();
            openFooterModal('privacy-modal');
        });

        document.getElementById('footer-terms-link')?.addEventListener('click', function(e) {
            e.preventDefault();
            openFooterModal('terms-modal');
        });

        document.getElementById('footer-faq-link')?.addEventListener('click', function(e) {
            e.preventDefault();
            openFooterModal('faq-modal');
        });

        document.getElementById('footer-contact-link')?.addEventListener('click', function(e) {
            e.preventDefault();
            openFooterModal('contact-modal');
        });

        // Close buttons for footer modals
        document.querySelectorAll('.footer-modal-close').forEach(btn => {
            btn.addEventListener('click', function() {
                const modal = this.closest('.footer-modal');
                if (modal) {
                    closeFooterModal(modal.id);
                }
            });
        });

        // Close footer modals when clicking outside
        document.querySelectorAll('.footer-modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeFooterModal(this.id);
                }
            });
        });

        // Session Modal Functions
        function showSessionModal(session) {
            const modal = document.getElementById('session-modal');
            const modalBody = modal.querySelector('.session-modal-body');

            if (!modal || !modalBody) return;

            const sessionDate = new Date(session.date);
            const formattedDate = !isNaN(sessionDate) ? sessionDate.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) : 'Invalid Date';
            
            const startTime = new Date(`1970-01-01T${session.start_time}`);
            const formattedStartTime = !isNaN(startTime) ? startTime.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true }) : 'Invalid Time';

            const endTime = new Date(`1970-01-01T${session.end_time}`);
            const formattedEndTime = !isNaN(endTime) ? endTime.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true }) : 'Invalid Time';

            const statusClass = session.status ? session.status.toLowerCase() : '';
            const sessionType = session.session_type ? session.session_type.replace(/_/g, ' ') : 'N/A';
            const formattedSessionType = sessionType.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');

            let studentProfilePicHtml = '';
            if (session.student && session.student.profile_picture) {
                const profilePicUrl = `{{ asset('storage') }}/${session.student.profile_picture}`;
                studentProfilePicHtml = `
                    <div class="tutor-profile-pic-container" style="text-align: center; margin-bottom: 1rem;">
                        <img src="${profilePicUrl}" alt="Student Profile Picture" class="tutor-profile-pic" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; margin: 0 auto;">
                    </div>
                `;
            } else if (session.student) {
                const initials = (session.student.first_name ? session.student.first_name.charAt(0) : '') + (session.student.last_name ? session.student.last_name.charAt(0) : '');
                studentProfilePicHtml = `
                    <div class="tutor-profile-pic-container" style="text-align: center; margin-bottom: 1rem;">
                        <div class="profile-icon" style="width: 80px; height: 80px; font-size: 2rem; margin: 0 auto; display: flex; align-items: center; justify-content: center; background-color: #4a90e2; color: white; border-radius: 50%;">
                            ${initials}
                        </div>
                    </div>
                `;
            }

            modalBody.innerHTML = `
                ${studentProfilePicHtml}
                <p><strong>Student:</strong> ${session.student ? `${session.student.first_name} ${session.student.last_name}` : 'N/A'}</p>
                <p><strong>Email:</strong> ${session.student && session.student.email ? session.student.email : 'N/A'}</p>
                <p><strong>Date:</strong> ${formattedDate}</p>
                <p><strong>Time:</strong> ${formattedStartTime} - ${formattedEndTime}</p>
                <p><strong>Type:</strong> ${formattedSessionType}</p>
                <p><strong>Status:</strong> <span class="status-badge ${statusClass}">${session.status}</span></p>
                ${session.notes ? `<p><strong>Notes:</strong> ${session.notes}</p>` : ''}
            `;

            modal.style.display = 'flex';

            const closeBtn = modal.querySelector('.session-modal-close-btn');
            const closeFooterBtn = modal.querySelector('#modal-close-btn');
            const messageBtn = modal.querySelector('#modal-message-btn');
            const viewBookingBtn = modal.querySelector('#modal-view-booking-btn');

            if(closeBtn) closeBtn.onclick = () => modal.style.display = 'none';
            if(closeFooterBtn) closeFooterBtn.onclick = () => modal.style.display = 'none';
            
            if(messageBtn && session.student) {
                messageBtn.onclick = () => {
                    window.location.href = `{{ route('tutor.messages') }}?student_id=${session.student.id}`;
                };
            } else if(messageBtn) {
                messageBtn.style.display = 'none';
            }

            if(viewBookingBtn) {
                viewBookingBtn.onclick = () => {
                    window.location.href = `/tutor/bookings/${session.id}`;
                };
            }
            
            window.onclick = (event) => {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            };
        }
    </script>
</body>
</html>