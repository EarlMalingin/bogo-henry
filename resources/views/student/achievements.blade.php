<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Achievements - MentorHub</title>
    <link rel="stylesheet" href="{{ asset('style/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @include('layouts.footer-modals')
    <style>
        body {
            background: linear-gradient(rgba(255,255,255,0.85), rgba(255,255,255,0.85)), url('../images/Uc-background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        
        .settings-container {
            max-width: 1200px;
            margin: 100px auto 2rem;
            padding: 0 1rem;
        }
        
        .settings-header {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }
        
        .settings-title {
            font-size: 2rem;
            color: #2d7dd2;
            margin-bottom: 0.5rem;
        }
        
        .gamification-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            text-align: center;
        }
        
        .stat-value {
            font-size: 2.5rem;
            font-weight: bold;
            color: #2d7dd2;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
        
        .level-card {
            background: linear-gradient(135deg, #2d7dd2, #4a3dd9);
            color: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 20px rgba(45, 125, 210, 0.3);
        }
        
        .level-number {
            font-size: 4rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        
        .level-progress {
            background: rgba(255,255,255,0.3);
            border-radius: 10px;
            height: 20px;
            margin-top: 1rem;
            overflow: hidden;
        }
        
        .level-progress-bar {
            background: white;
            height: 100%;
            border-radius: 10px;
            transition: width 0.3s ease;
        }
        
        .level-progress-text {
            margin-top: 0.5rem;
            font-size: 0.9rem;
            color: white;
            text-align: center;
        }
        
        .achievements-section {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }
        
        .section-title {
            font-size: 1.5rem;
            color: #2d7dd2;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .achievements-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
        }
        
        .achievement-card {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .achievement-card.unlocked {
            border-color: #28a745;
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(40, 167, 69, 0.05));
        }
        
        .achievement-card.locked {
            opacity: 0.6;
        }
        
        .achievement-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #2d7dd2;
        }
        
        .achievement-card.unlocked .achievement-icon {
            color: #28a745;
        }
        
        .achievement-name {
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: #333;
        }
        
        .achievement-description {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 1rem;
        }
        
        .achievement-progress {
            background: #e0e0e0;
            border-radius: 10px;
            height: 8px;
            margin-bottom: 0.5rem;
            overflow: hidden;
        }
        
        .achievement-progress-bar {
            background: #2d7dd2;
            height: 100%;
            border-radius: 10px;
            transition: width 0.3s ease;
        }
        
        .achievement-card.unlocked .achievement-progress-bar {
            background: #28a745;
        }
        
        .achievement-progress-text {
            font-size: 0.8rem;
            color: #666;
        }
        
        .achievement-points {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #ffd700;
            color: #333;
            padding: 0.3rem 0.6rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        
        .unlocked-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #28a745;
            color: white;
            padding: 0.3rem 0.6rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: bold;
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
            cursor: pointer;
        }
        
        .currency-icon {
            font-size: 1.2rem;
            margin-right: 0.5rem;
            color: #ffd700;
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
            line-height: 1;
        }
        
        .currency-label {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.8);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 2px;
        }
        
        @media (max-width: 1200px) {
            .achievements-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        @media (max-width: 900px) {
            .achievements-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .gamification-stats {
                grid-template-columns: 1fr;
            }
            
            .achievements-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="navbar">
            <a href="{{ route('student.dashboard') }}" class="logo">
                <img src="{{asset('images/MentorHub.png')}}" alt="MentorHub Logo" class="logo-img">
            </a>
            <button class="menu-toggle" id="menu-toggle">☰</button>
            <nav class="nav-links" id="nav-links">
                <a href="{{ route('student.dashboard') }}">Dashboard</a>
                <a href="{{route('student.book-session')}}">Book Session</a>
                <a href="{{route('student.my-sessions')}}">Activities</a>
                <a href="{{route('student.schedule')}}">Schedule</a>
            </nav>
            <div class="header-right-section">
                <div class="currency-display">
                    <div class="currency-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="currency-info">
                        <div class="currency-amount" id="currency-amount">₱0.00</div>
                        <div class="currency-label">Balance</div>
                    </div>
                </div>
                <div class="profile-dropdown-container" style="position: relative;">
                    <div class="profile-icon" id="profile-icon">
                        @if($student->profile_picture)
                            <img src="{{ route('student.profile.picture') }}?v={{ time() }}" alt="Profile Picture" class="profile-icon-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center; background-color: #f5f5f5; color: #666; font-weight: bold; font-size: 1.2rem; border-radius: 50%;">{{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}</div>
                        @else
                            {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                        @endif
                    </div>
                    <div class="dropdown-menu" id="dropdown-menu">
                        <a href="{{ route('student.profile.edit') }}">My Profile</a>
                        <a href="{{ route('student.settings') }}">Achievements</a>
                        <a href="{{ route('student.report-problem') }}">Report a Problem</a>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" method="POST" action="{{ route('student.logout') }}" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>
    
    <div class="settings-container">
        <div class="settings-header">
            <h1 class="settings-title"><i class="fas fa-trophy"></i> Achievements</h1>
            <p>Track your progress, unlock achievements, and level up!</p>
        </div>
        
        <!-- Gamification Stats -->
        <div class="gamification-stats">
            <div class="stat-card">
                <div class="stat-value">{{ $totalPoints }}</div>
                <div class="stat-label">Total Points</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $unlockedCount }}</div>
                <div class="stat-label">Achievements Unlocked</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ count($userAchievements) }}</div>
                <div class="stat-label">Total Achievements</div>
            </div>
        </div>
        
        <!-- Level Card -->
        <div class="level-card">
            <div class="level-number">Level {{ $level }}</div>
            <p style="margin-bottom: 0.5rem;">Keep learning to level up!</p>
            <div class="level-progress">
                <div class="level-progress-bar" style="width: {{ min(100, (($totalPoints % 100) / 100) * 100) }}%"></div>
            </div>
            <div class="level-progress-text">{{ $pointsForNextLevel }} points to next level</div>
        </div>
        
        <!-- Achievements Section -->
        <div class="achievements-section">
            <h2 class="section-title">
                <i class="fas fa-trophy"></i>
                Achievements
            </h2>
            
            <div class="achievements-grid">
                @foreach($userAchievements as $item)
                    @php
                        $achievement = $item['achievement'];
                        $userAchievement = $item['user_achievement'];
                        $isUnlocked = $userAchievement->is_unlocked ?? false;
                    @endphp
                    <div class="achievement-card {{ $isUnlocked ? 'unlocked' : 'locked' }}">
                        @if($isUnlocked)
                            <div class="unlocked-badge"><i class="fas fa-check"></i> Unlocked</div>
                        @endif
                        <div class="achievement-points">+{{ $achievement->points }} pts</div>
                        <div class="achievement-icon">
                            <i class="{{ $achievement->icon ?? 'fas fa-star' }}"></i>
                        </div>
                        <div class="achievement-name">{{ $achievement->name }}</div>
                        <div class="achievement-description">{{ $achievement->description }}</div>
                        @if(!$isUnlocked)
                            <div class="achievement-progress">
                                <div class="achievement-progress-bar" style="width: {{ $userAchievement->progress ?? 0 }}%"></div>
                            </div>
                            <div class="achievement-progress-text">{{ $userAchievement->progress ?? 0 }}% Complete</div>
                        @else
                            <div style="color: #28a745; font-weight: bold; margin-top: 0.5rem;">
                                <i class="fas fa-check-circle"></i> Completed!
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    
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
    
    @include('layouts.footer-js')
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Profile dropdown
            const profileIcon = document.getElementById('profile-icon');
            const dropdownMenu = document.getElementById('dropdown-menu');
            
            if (profileIcon && dropdownMenu) {
                profileIcon.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdownMenu.classList.toggle('active');
                });
                
                document.addEventListener('click', function(e) {
                    if (!profileIcon.contains(e.target)) {
                        dropdownMenu.classList.remove('active');
                    }
                });
            }
            
            // Mobile menu toggle
            const menuToggle = document.getElementById('menu-toggle');
            const navLinks = document.getElementById('nav-links');
            
            if (menuToggle && navLinks) {
                menuToggle.addEventListener('click', function() {
                    navLinks.classList.toggle('active');
                });
            }
            
            // Currency display functionality
            initializeCurrencyDisplay();
            loadCurrencyData();
        });
        
        function initializeCurrencyDisplay() {
            const currencyDisplay = document.querySelector('.currency-display');
            if (currencyDisplay) {
                currencyDisplay.addEventListener('click', function() {
                    window.location.href = "{{ route('student.wallet') }}";
                });
            }
        }
        
        // Load currency data from API
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
    </script>
</body>
</html>

