<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('style/dashboard.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Answer Assignment | MentorHub</title>
    <style>
        .answer-container {
            max-width: 900px;
            margin: 100px auto 2rem;
            padding: 0 1rem;
        }

        .assignment-card, .answer-form-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            padding: 2rem;
            margin-bottom: 1.5rem;
        }

        .assignment-subject {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2d7dd2;
            margin-bottom: 1rem;
        }

        .assignment-question {
            color: #333;
            line-height: 1.8;
            margin-bottom: 1rem;
        }

        .earnings-info {
            background: #e8f4fd;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .earnings-info strong {
            color: #28a745;
            font-size: 1.2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }

        .form-group textarea {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            font-family: inherit;
            resize: vertical;
            min-height: 200px;
        }

        .form-group textarea:focus {
            outline: none;
            border-color: #2d7dd2;
        }

        .btn-submit {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s;
            width: 100%;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffc107;
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
                <a href="{{ route('tutor.assignments.index') }}" class="active">Assignments</a>
            </nav>
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
    </header>

    <div class="answer-container">
        <a href="{{ route('tutor.assignments.index') }}" style="color: #2d7dd2; text-decoration: none; margin-bottom: 1rem; display: inline-block;">
            <i class="fas fa-arrow-left"></i> Back to Assignments
        </a>

        @if($hasAnswered)
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> You have already submitted an answer for this assignment.
            </div>
        @endif

        @if($errors->any())
            <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                <ul style="margin: 0; padding-left: 1.2rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="assignment-card">
            <div class="assignment-subject">{{ $assignment->subject }}</div>
            <div class="assignment-question">{{ $assignment->question }}</div>
            
            @if($assignment->description)
                <div style="color: #666; margin-top: 1rem;">
                    <strong>Additional Details:</strong><br>
                    {{ $assignment->description }}
                </div>
            @endif

            @if($assignment->file_name)
                <div style="margin-top: 1rem;">
                    <a href="{{ route('tutor.assignments.download', $assignment->id) }}" style="color: #2d7dd2;">
                        <i class="fas fa-paperclip"></i> {{ $assignment->file_name }}
                    </a>
                </div>
            @endif

            <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #eee; color: #666; font-size: 0.9rem;">
                <strong>Student:</strong> {{ $assignment->student->first_name }} {{ $assignment->student->last_name }}<br>
                <strong>Posted:</strong> {{ $assignment->created_at->format('M d, Y') }}
            </div>
        </div>

        @if(!$hasAnswered)
            <div class="earnings-info">
                <i class="fas fa-money-bill-wave" style="font-size: 1.5rem; color: #28a745;"></i><br>
                <strong>You'll earn ₱{{ number_format($assignment->price * 0.70, 2) }}</strong> when the student purchases your answer!
            </div>

            <div class="answer-form-card">
                <h2 style="margin-bottom: 1.5rem; color: #333;">Your Answer</h2>

                <form action="{{ route('tutor.assignments.answer', $assignment->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="answer">Detailed Answer *</label>
                        <textarea id="answer" name="answer" required placeholder="Provide a detailed and helpful answer to the student's question...">{{ old('answer') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="file">Attachment (Optional)</label>
                        <input type="file" id="file" name="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 8px;">
                        <small style="color: #666;">Supported: PDF, DOC, DOCX, JPG, PNG (Max 10MB)</small>
                    </div>

                    <button type="submit" class="btn-submit" {{ $hasAnswered ? 'disabled' : '' }}>
                        <i class="fas fa-paper-plane"></i> Submit Answer
                    </button>
                </form>
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

