<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('style/dashboard.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>My Answers | MentorHub</title>
    <style>
        .answers-container {
            max-width: 1200px;
            margin: 100px auto 2rem;
            padding: 0 1rem;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: bold;
            color: #2d7dd2;
        }

        .answer-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .answer-header {
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

        .status-answered {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-paid {
            background: #d4edda;
            color: #155724;
        }

        .answer-preview {
            color: #666;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .answer-meta {
            display: flex;
            gap: 1.5rem;
            font-size: 0.9rem;
            color: #666;
            flex-wrap: wrap;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="navbar">
            <a href="{{ route('tutor.dashboard') }}" class="logo">
                <img src="{{asset('images/MentorHub.png')}}" alt="MentorHub Logo" class="logo-img">
            </a>
            <button class="menu-toggle" id="menu-toggle">☰</button>
            <nav class="nav-links" id="nav-links">
                <a href="{{ route('tutor.dashboard') }}">Dashboard</a>
                <a href="{{ route('tutor.assignments.index') }}">Assignments</a>
                <a href="{{ route('tutor.assignments.my-answers') }}" class="active">My Answers</a>
            </nav>
            <div class="header-right-section">
                <div class="currency-display">
                    <div class="currency-icon"><i class="fas fa-wallet"></i></div>
                    <div class="currency-info">
                        <div class="currency-amount" id="currency-amount">₱0.00</div>
                        <div class="currency-label">Balance</div>
                    </div>
                </div>
                <div class="profile-dropdown-container" style="position: relative;">
                    <div class="profile-icon" id="profile-icon">
                        @if($tutor->profile_picture)
                            <img src="{{ asset('storage/' . $tutor->profile_picture) }}" alt="Profile" class="profile-icon-img">
                        @else
                            {{ strtoupper(substr($tutor->first_name, 0, 1) . substr($tutor->last_name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="dropdown-menu" id="dropdown-menu">
                        <a href="{{ route('tutor.profile.edit') }}">My Profile</a>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" method="POST" action="{{ route('tutor.logout') }}" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="answers-container">
        <div class="page-header">
            <h1 class="page-title">My Answers</h1>
            <p style="color: #666;">View all assignments you've answered</p>
        </div>

        @if($answers->count() > 0)
            @foreach($answers as $answer)
                <div class="answer-card">
                    <div class="answer-header">
                        <div class="assignment-subject">{{ $answer->assignment->subject }}</div>
                        <span class="status-badge status-{{ $answer->assignment->status }}">
                            @if($answer->assignment->status === 'answered')
                                <i class="fas fa-clock"></i> Waiting for Payment
                            @else
                                <i class="fas fa-check-circle"></i> Paid & Earned
                            @endif
                        </span>
                    </div>

                    <div style="margin-bottom: 0.5rem; color: #333;">
                        <strong>Question:</strong> {{ Str::limit($answer->assignment->question, 150) }}
                    </div>

                    <div class="answer-preview">
                        <strong>Your Answer:</strong> {{ Str::limit($answer->answer, 200) }}
                    </div>

                    <div class="answer-meta">
                        <span><i class="far fa-user"></i> Student: {{ $answer->assignment->student->first_name }} {{ $answer->assignment->student->last_name }}</span>
                        <span><i class="far fa-calendar"></i> Answered: {{ $answer->created_at->format('M d, Y') }}</span>
                        <span><i class="fas fa-money-bill-wave"></i> Potential Earnings: ₱{{ number_format($answer->assignment->price * 0.70, 2) }}</span>
                    </div>

                    @if($answer->file_name)
                        <div style="margin-top: 1rem;">
                            <a href="{{ asset('storage/' . $answer->file_path) }}" download style="color: #2d7dd2;">
                                <i class="fas fa-download"></i> Download Answer File
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach

            <div style="display: flex; justify-content: center; margin-top: 2rem;">
                {{ $answers->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-file-alt" style="font-size: 4rem; color: #ccc; margin-bottom: 1rem;"></i>
                <h3>No answers submitted yet</h3>
                <p>Start helping students by answering their assignments!</p>
                <a href="{{ route('tutor.assignments.index') }}" style="margin-top: 1rem; display: inline-block; color: #2d7dd2;">
                    View Pending Assignments
                </a>
            </div>
        @endif
    </div>

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

            fetch('{{ route("tutor.wallet.balance") }}')
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
</body>
</html>

