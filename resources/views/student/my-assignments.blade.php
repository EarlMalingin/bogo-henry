<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('style/dashboard.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>My Assignments | MentorHub</title>
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

        /* Responsive Styles */
        @media (max-width: 768px) {
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
        }

        .assignments-container {
            max-width: 1200px;
            margin: 100px auto 2rem;
            padding: 0 1rem;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: bold;
            color: #2d7dd2;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2d7dd2, #4a3dd9);
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
        }

        .assignment-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: transform 0.3s;
        }

        .assignment-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .assignment-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .assignment-subject {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2d7dd2;
        }

        .status-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-answered {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-paid {
            background: #d4edda;
            color: #155724;
        }

        .assignment-question {
            color: #333;
            margin-bottom: 1rem;
            line-height: 1.6;
        }

        .assignment-meta {
            display: flex;
            gap: 1.5rem;
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        .assignment-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-view {
            background: #2d7dd2;
            color: white;
            padding: 0.6rem 1.2rem;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: background 0.3s;
        }

        .btn-view:hover {
            background: #1e6bb8;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        }

        .empty-state i {
            font-size: 4rem;
            color: #ccc;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            color: #666;
            margin-bottom: 0.5rem;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }

        .pagination a, .pagination span {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            color: #2d7dd2;
            background: white;
            border: 1px solid #ddd;
        }

        .pagination .active span {
            background: #2d7dd2;
            color: white;
            border-color: #2d7dd2;
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
                <a href="{{ route('student.dashboard') }}">Dashboard</a>
                <a href="{{route('student.book-session')}}">Book Session</a>
                <a href="{{route('student.my-sessions')}}">Activities</a>
                <a href="{{route('student.schedule')}}">Schedule</a>
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
                            <img src="{{ asset('storage/' . $student->profile_picture) }}?v={{ file_exists(public_path('storage/' . $student->profile_picture)) ? filemtime(public_path('storage/' . $student->profile_picture)) : time() }}" alt="Profile Picture" class="profile-icon-img">
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

    <div class="assignments-container">
        <div class="page-header">
            <h1 class="page-title">My Assignments</h1>
            <a href="{{ route('student.assignments.post') }}" class="btn-primary">
                <i class="fas fa-plus"></i> Post New Assignment
            </a>
        </div>

        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if($assignments->count() > 0)
            @foreach($assignments as $assignment)
                <div class="assignment-card">
                    <div class="assignment-header">
                        <div class="assignment-subject">{{ $assignment->subject }}</div>
                        <span class="status-badge status-{{ $assignment->status }}">
                            @if($assignment->status === 'pending')
                                <i class="fas fa-clock"></i> Pending
                            @elseif($assignment->status === 'answered')
                                <i class="fas fa-check-circle"></i> Answered
                            @else
                                <i class="fas fa-unlock"></i> Paid & Viewed
                            @endif
                        </span>
                    </div>

                    <div class="assignment-question">
                        {{ Str::limit($assignment->question, 200) }}
                    </div>

                    <div class="assignment-meta">
                        <span><i class="far fa-calendar"></i> {{ $assignment->created_at->format('M d, Y') }}</span>
                        <span><i class="fas fa-tag"></i> ₱{{ number_format($assignment->price, 2) }}</span>
                        @if($assignment->file_name)
                            <span><i class="fas fa-paperclip"></i> Has attachment</span>
                        @endif
                        @if($assignment->answers->count() > 0)
                            <span><i class="fas fa-comments"></i> {{ $assignment->answers->count() }} answer(s)</span>
                        @endif
                    </div>

                    <div class="assignment-actions">
                        <a href="{{ route('student.assignments.show', $assignment->id) }}" class="btn-view">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                        @if($assignment->file_name)
                            <a href="{{ route('student.assignments.download', $assignment->id) }}" class="btn-view" style="background: #6c757d;">
                                <i class="fas fa-download"></i> Download File
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach

            <div class="pagination">
                {{ $assignments->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-file-alt"></i>
                <h3>No assignments posted yet</h3>
                <p>Start by posting your first assignment!</p>
                <a href="{{ route('student.assignments.post') }}" class="btn-primary" style="margin-top: 1rem;">
                    <i class="fas fa-plus"></i> Post Assignment
                </a>
            </div>
        @endif
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
    
    @include('layouts.footer-modals')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menu-toggle');
            const navLinks = document.getElementById('nav-links');
            const profileIcon = document.getElementById('profile-icon');
            const dropdownMenu = document.getElementById('dropdown-menu');

            if (menuToggle && navLinks) {
                menuToggle.addEventListener('click', function() {
                    navLinks.classList.toggle('active');
                });
            }

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

            fetch('{{ route("student.wallet.balance") }}')
                .then(response => response.json())
                .then(data => {
                    const currencyAmount = document.getElementById('currency-amount');
                    if (currencyAmount) {
                        currencyAmount.textContent = '₱' + parseFloat(data.balance || 0).toFixed(2);
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
    
    @include('layouts.footer-js')
</body>
</html>

