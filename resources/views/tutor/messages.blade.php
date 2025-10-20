<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('style/dashboard.css') }}">
    <title>MentorHub - Messages</title>
    @livewireStyles
    <!-- Socket.IO Client -->
    <script src="https://cdn.socket.io/4.8.1/socket.io.min.js"></script>
    <script>
        // Pass user data to frontend - use unified user ID
        @php
            $unifiedUser = \App\Models\UnifiedUser::where('email', Auth::guard('tutor')->user()->email)->first();
        @endphp
        window.currentUserId = {{ $unifiedUser ? $unifiedUser->id : Auth::guard('tutor')->id() }};
        window.currentUserType = 'tutor';
    </script>
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
            background: linear-gradient(rgba(255, 255, 255, 0.85), rgba(255, 255, 255, 0.85)), url('{{ asset('images/Uc-background.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Main Content */
        main {
            flex: 1;
            margin-top: 80px;
            padding: 2rem 1rem;
            max-width: 1200px;
            width: 100%;
            align-self: center;
        }

        /* Footer */
        footer {
            margin-top: 2rem;
            background-color: #333;
            color: white;
            padding: 1.5rem 0;
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
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: white;
        }

        .copyright {
            font-size: 0.9rem;
            color: #aaa;
            text-align: center;
            width: 100%;
            margin-top: 0.5rem;
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
            font-size: 1.1rem;
            overflow: hidden;
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
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="navbar">
            <a href="#" class="logo">
                <img src="{{asset('images/MentorHub.png')}}" alt="UCTutor Logo" class="logo-img">
                <span>MentorHub</span>
            </a>
            <button class="menu-toggle" id="menu-toggle">☰</button>
            <nav class="nav-links" id="nav-links">
                <a href="{{ route('tutor.dashboard') }}">Dashboard</a>
                <a href="{{ route('tutor.bookings.index') }}">My Bookings</a>
                <a href="#">Students</a>
                <a href="#">Schedule</a>
                
            </nav>
            <div class="profile-dropdown-container" style="position: relative;">
                @auth('tutor')
                    <div class="profile-icon" id="profile-icon">
                        @if(Auth::guard('tutor')->user()->profile_picture)
                            <img src="{{ asset('storage/' . Auth::guard('tutor')->user()->profile_picture) }}?{{ time() }}" alt="Profile Picture" class="profile-icon-img">
                        @else
                            {{ substr(Auth::guard('tutor')->user()->first_name, 0, 1) }}{{ substr(Auth::guard('tutor')->user()->last_name, 0, 1) }}
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
                @else
                    <div class="profile-icon" id="profile-icon">
                        <a href="{{ route('login.tutor') }}" class="login-link">Login</a>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @livewire('tutor-chat')
        @livewire('call-manager')
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

    @livewireScripts
    <!-- Socket Client Script -->
    <script src="{{ asset('js/socket-client.js') }}"></script>
    <script>
        // Menu toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menu-toggle');
            const navLinks = document.getElementById('nav-links');
            
            if (menuToggle) {
                menuToggle.addEventListener('click', function() {
                    navLinks.classList.toggle('active');
                });
            }

            const profileIcon = document.getElementById('profile-icon');
            const dropdownMenu = document.getElementById('dropdown-menu');
            if (profileIcon) {
                profileIcon.addEventListener('click', function(e) {
                    e.stopPropagation();
                    if (dropdownMenu) {
                        dropdownMenu.classList.toggle('active');
                    }
                });
            }
            document.addEventListener('click', function() {
                if (dropdownMenu && dropdownMenu.classList.contains('active')) {
                    dropdownMenu.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html> 