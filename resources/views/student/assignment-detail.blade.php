<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('style/dashboard.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Assignment Details | MentorHub</title>
    <style>
        .detail-container {
            max-width: 900px;
            margin: 100px auto 2rem;
            padding: 0 1rem;
        }

        .assignment-detail-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            padding: 2rem;
            margin-bottom: 1.5rem;
        }

        .assignment-header {
            border-bottom: 2px solid #eee;
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
        }

        .assignment-subject {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2d7dd2;
            margin-bottom: 0.5rem;
        }

        .assignment-question {
            color: #333;
            line-height: 1.8;
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }

        .assignment-description {
            color: #666;
            margin-top: 1rem;
        }

        .answer-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 2rem;
        }

        .answer-locked {
            text-align: center;
            padding: 3rem;
        }

        .answer-locked i {
            font-size: 4rem;
            color: #ccc;
            margin-bottom: 1rem;
        }

        .btn-pay {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 1rem 2rem;
            border-radius: 8px;
            border: none;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .btn-pay:hover {
            transform: translateY(-2px);
        }

        .btn-pay:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .balance-warning {
            background: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <!-- Header (same as post-assignment) -->
    <header>
        <div class="navbar">
            <a href="{{ route('student.dashboard') }}" class="logo">
                <img src="{{asset('images/MentorHub.png')}}" alt="MentorHub Logo" class="logo-img">
            </a>
            <button class="menu-toggle" id="menu-toggle">☰</button>
            <nav class="nav-links" id="nav-links">
                <a href="{{ route('student.dashboard') }}">Dashboard</a>
                <a href="{{ route('student.assignments.my-assignments') }}" class="active">Assignments</a>
            </nav>
            <div class="header-right-section">
                <div class="currency-display">
                    <div class="currency-icon"><i class="fas fa-wallet"></i></div>
                    <div class="currency-info">
                        <div class="currency-amount" id="currency-amount">₱{{ number_format($wallet->balance, 2) }}</div>
                        <div class="currency-label">Balance</div>
                    </div>
                </div>
                <div class="profile-dropdown-container" style="position: relative;">
                    <div class="profile-icon" id="profile-icon">
                        @if($student->profile_picture)
                            <img src="{{ asset('storage/' . $student->profile_picture) }}" alt="Profile" class="profile-icon-img">
                        @else
                            {{ strtoupper(substr($student->first_name, 0, 1) . substr($student->last_name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="dropdown-menu" id="dropdown-menu">
                        <a href="{{ route('student.profile.edit') }}">My Profile</a>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" method="POST" action="{{ route('student.logout') }}" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="detail-container">
        <a href="{{ route('student.assignments.my-assignments') }}" style="color: #2d7dd2; text-decoration: none; margin-bottom: 1rem; display: inline-block;">
            <i class="fas fa-arrow-left"></i> Back to Assignments
        </a>

        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <div class="assignment-detail-card">
            <div class="assignment-header">
                <div class="assignment-subject">{{ $assignment->subject }}</div>
                <div style="color: #666; font-size: 0.9rem;">
                    <i class="far fa-calendar"></i> Posted on {{ $assignment->created_at->format('M d, Y') }}
                </div>
            </div>

            <div class="assignment-question">
                {{ $assignment->question }}
            </div>

            @if($assignment->description)
                <div class="assignment-description">
                    <strong>Additional Details:</strong><br>
                    {{ $assignment->description }}
                </div>
            @endif

            @if($assignment->file_name)
                <div style="margin-top: 1rem;">
                    <a href="{{ route('student.assignments.download', $assignment->id) }}" style="color: #2d7dd2;">
                        <i class="fas fa-paperclip"></i> {{ $assignment->file_name }}
                    </a>
                </div>
            @endif
        </div>

        @if($answer)
            <div class="assignment-detail-card answer-section">
                @if($assignment->status === 'paid')
                    <h3 style="color: #28a745; margin-bottom: 1rem;">
                        <i class="fas fa-unlock"></i> Answer (Purchased)
                    </h3>
                    <div style="color: #333; line-height: 1.8; white-space: pre-wrap;">{{ $answer->answer }}</div>
                    
                    @if($answer->file_name)
                        <div style="margin-top: 1rem;">
                            <a href="{{ asset('storage/' . $answer->file_path) }}" download style="color: #2d7dd2;">
                                <i class="fas fa-download"></i> Download Answer File: {{ $answer->file_name }}
                            </a>
                        </div>
                    @endif

                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #ddd; color: #666; font-size: 0.9rem;">
                        <strong>Answered by:</strong> {{ $answer->tutor->first_name }} {{ $answer->tutor->last_name }}<br>
                        <strong>Date:</strong> {{ $answer->created_at->format('M d, Y h:i A') }}
                    </div>
                @else
                    <div class="answer-locked">
                        <i class="fas fa-lock"></i>
                        <h3>Answer Available</h3>
                        <p>An answer has been provided for this assignment. Pay ₱{{ number_format($assignment->price, 2) }} to view it.</p>
                        
                        @if(!$canAfford)
                            <div class="balance-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                Insufficient balance. Current balance: ₱{{ number_format($wallet->balance, 2) }}. 
                                <a href="{{ route('student.wallet.cash-in') }}" style="color: #856404; text-decoration: underline;">Add funds</a>
                            </div>
                        @endif

                        <form action="{{ route('student.assignments.pay', $assignment->id) }}" method="POST" style="margin-top: 1.5rem;">
                            @csrf
                            <button type="submit" class="btn-pay" {{ !$canAfford ? 'disabled' : '' }}>
                                <i class="fas fa-credit-card"></i> Pay ₱{{ number_format($assignment->price, 2) }} to View Answer
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @else
            <div class="assignment-detail-card">
                <div style="text-align: center; padding: 2rem; color: #666;">
                    <i class="fas fa-clock" style="font-size: 3rem; margin-bottom: 1rem; color: #ccc;"></i>
                    <h3>Waiting for Answer</h3>
                    <p>No tutor has answered this assignment yet. Please check back later.</p>
                </div>
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
        });
    </script>
</body>
</html>

