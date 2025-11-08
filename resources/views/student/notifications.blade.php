<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('style/dashboard.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Notifications | MentorHub</title>
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

        .notifications-container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .notifications-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .notifications-header h2 {
            color: #333;
            margin: 0;
        }

        .mark-all-read {
            color: #4a90e2;
            text-decoration: none;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: background-color 0.3s;
        }

        .mark-all-read:hover {
            background-color: #f0f7ff;
        }

        .notification-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1rem;
            display: flex;
            gap: 1rem;
            transition: transform 0.2s, box-shadow 0.2s;
            position: relative;
        }

        .notification-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .notification-card.unread {
            border-left: 4px solid #4a90e2;
            background-color: #f8fbff;
        }

        .notification-delete {
            background: transparent;
            border: none;
            color: #999;
            cursor: pointer;
            font-size: 1.2rem;
            padding: 0.5rem;
            transition: all 0.2s;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .notification-delete:hover {
            background: #fee;
            color: #d32f2f;
            transform: scale(1.1);
        }

        .notification-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .notification-icon.booking {
            background-color: #e3f2fd;
            color: #1976d2;
        }

        .notification-icon.activity {
            background-color: #f3e5f5;
            color: #7b1fa2;
        }

        .notification-icon.payment {
            background-color: #e8f5e9;
            color: #388e3c;
        }

        .notification-icon.message {
            background-color: #fff3e0;
            color: #f57c00;
        }

        .notification-icon.alert {
            background-color: #ffebee;
            color: #d32f2f;
        }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .notification-message {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .notification-time {
            color: #999;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .no-notifications {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .no-notifications i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 1rem;
        }

        .no-notifications p {
            color: #666;
            font-size: 1.1rem;
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
                <a href="{{route('student.my-sessions')}}">Activities</a>
                <a href="{{route('student.schedule')}}">Schedule</a>
            </nav>
            <div class="header-right-section">
                <!-- Currency Display -->
                <div class="currency-display" onclick="window.location.href='{{ route('student.wallet') }}'">
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
                        @auth('student')
                            @if(Auth::guard('student')->user()->profile_picture)
                                <img src="{{ asset('storage/' . Auth::guard('student')->user()->profile_picture) }}?{{ time() }}" alt="Profile Picture" class="profile-icon-img">
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
                        <a href="{{ route('student.settings') }}">Achievements</a>
                        <a href="{{ route('student.report-problem') }}">Report a Problem</a>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" method="POST" action="{{ route('student.logout') }}" style="display: none;">
                            @csrf
                        </form>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>
    
    <!-- Main Content -->
    <main>
        <div class="notifications-container">
            <div class="notifications-header">
                <h2><i class="fas fa-bell"></i> Notifications</h2>
                <a href="#" class="mark-all-read" onclick="markAllAsRead(); return false;">
                    <i class="fas fa-check-double"></i> Mark all as read
                </a>
            </div>

            <div id="notifications-list">
                @forelse($notifications as $notification)
                    <div class="notification-card {{ !$notification->is_read ? 'unread' : '' }}" data-id="{{ $notification->id }}">
                        <div class="notification-icon alert">
                            @if($notification->type === 'problem_report_response')
                                <i class="fas fa-exclamation-circle"></i>
                            @elseif($notification->type === 'booking_confirmed')
                                <i class="fas fa-calendar-check"></i>
                            @elseif($notification->type === 'activity_posted')
                                <i class="fas fa-tasks"></i>
                            @elseif($notification->type === 'payment_received')
                                <i class="fas fa-money-bill-wave"></i>
                            @elseif($notification->type === 'new_message')
                                <i class="fas fa-envelope"></i>
                            @else
                                <i class="fas fa-bell"></i>
                            @endif
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">{{ $notification->title }}</div>
                            <div class="notification-message">
                                {{ $notification->message }}
                            </div>
                            <div class="notification-time">
                                <i class="fas fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                            </div>
                        </div>
                        <button class="notification-delete" onclick="deleteNotification({{ $notification->id }})" title="Delete notification">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @empty
                    <div style="text-align: center; padding: 4rem 2rem; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
                        <i class="fas fa-bell-slash" style="font-size: 4rem; color: #ddd; margin-bottom: 1rem;"></i>
                        <p style="color: #666; font-size: 1.1rem;">No notifications yet</p>
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
            const menuToggle = document.getElementById('menu-toggle');
            const navLinks = document.getElementById('nav-links');
            
            if (menuToggle) {
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

                document.addEventListener('click', function(e) {
                    if (!profileIcon.contains(e.target)) {
                        dropdownMenu.classList.remove('active');
                    }
                });
            }

            // Load currency data
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
        });

        function markAllAsRead() {
            // Remove unread class from all notifications
            document.querySelectorAll('.notification-card.unread').forEach(card => {
                card.classList.remove('unread');
            });
            
            // Optional: Send AJAX request to backend to mark as read
            // fetch('/student/notifications/mark-all-read', { method: 'POST' })
        }

        function deleteNotification(id) {
            if (!confirm('Are you sure you want to delete this notification?')) {
                return;
            }

            fetch(`/student/notifications/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const card = document.querySelector(`[data-id="${id}"]`);
                    if (card) {
                        card.style.transition = 'opacity 0.3s';
                        card.style.opacity = '0';
                        setTimeout(() => {
                            card.remove();
                            
                            // Check if no more notifications
                            const notificationsList = document.getElementById('notifications-list');
                            if (!notificationsList.querySelector('.notification-card')) {
                                notificationsList.innerHTML = `
                                    <div style="text-align: center; padding: 4rem 2rem; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
                                        <i class="fas fa-bell-slash" style="font-size: 4rem; color: #ddd; margin-bottom: 1rem;"></i>
                                        <p style="color: #666; font-size: 1.1rem;">No notifications yet</p>
                                    </div>
                                `;
                            }
                        }, 300);
                    }
                } else {
                    alert('Failed to delete notification');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the notification');
            });
        }
    </script>
    
    @include('layouts.footer-js')
</body>
</html>

