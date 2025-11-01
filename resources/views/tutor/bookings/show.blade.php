<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details | MentorHub</title>
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
                url('{{ asset('images/Uc-background.jpg') }}');
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

        /* Main Content Styles */
        main {
            flex: 1;
            padding: 0 1rem 2rem;
            margin-top: 80px;
            max-width: 1200px;
            width: 100%;
            align-self: center;
            min-height: calc(100vh - 200px);
        }
        .details-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }
        .details-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
        }
        .student-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .student-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: #4a90e2;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.5rem;
        }
        .student-name {
            font-size: 1.5rem;
            font-weight: 600;
        }
        .details-body .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 0.8rem 0;
            border-bottom: 1px solid #f5f5f5;
        }
        .detail-item strong {
            color: #333;
        }
        .detail-item span {
            color: #666;
        }
        .student-notes {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 1rem;
            margin-top: 1.5rem;
        }
        .actions {
            margin-top: 2rem;
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            flex-wrap: nowrap;
            align-items: center;
        }
        .btn {
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.3s;
            flex-shrink: 0;
            text-align: center;
            white-space: nowrap;
        }
        .btn-success { background-color: #28a745; color: white; }
        .btn-danger { background-color: #dc3545; color: white; }
        .btn-secondary { background-color: #6c757d; color: white; }
        .btn-primary { background-color: #4a90e2; color: white; }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
        }

        .close-button {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close-button:hover,
        .close-button:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 1rem;
        }

        textarea {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
            margin-top: 1rem;
            resize: vertical;
        }

        /* Footer */
        footer {
            background-color: #333;
            color: white;
            padding: 1.5rem 0;
            margin-top: auto;
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

            .actions {
                justify-content: center;
                flex-wrap: wrap;
            }

            .btn {
                flex: 1 1 auto;
                min-width: 100px;
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
    <header>
        <div class="navbar">
            <a href="#" class="logo">
                <img src="{{ asset('images/MentorHub.png') }}" alt="MentorHub Logo" class="logo-img">
            </a>
            <button class="menu-toggle" id="menu-toggle">☰</button>
            <nav class="nav-links" id="nav-links">
                <a href="{{ route('tutor.dashboard') }}">Dashboard</a>
                <a href="{{ route('tutor.bookings.index') }}" class="active">My Bookings</a>
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
                        <a href="#">Report a Problem</a>
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
        <div class="details-container">
            <div class="details-header">
                <div class="student-info">
                    <div class="student-avatar">
                        @if($booking->student->profile_picture)
                            <img src="{{ asset('storage/' . $booking->student->profile_picture) }}?{{ time() }}" alt="Student Profile Picture" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                        @else
                            {{ substr($booking->student->first_name, 0, 1) }}{{ substr($booking->student->last_name, 0, 1) }}
                        @endif
                    </div>
                    <div>
                        <div class="student-name">{{ $booking->student->first_name }} {{ $booking->student->last_name }}</div>
                        <a href="mailto:{{ $booking->student->email }}" style="color: #4a90e2;">{{ $booking->student->email }}</a>
                    </div>
                </div>
            </div>

            <div class="details-body">
                <h3>Session Details</h3>
                <div class="detail-item">
                    <strong>Status:</strong>
                    <span>{{ ucfirst($booking->status) }}</span>
                </div>
                <div class="detail-item">
                    <strong>Date:</strong>
                    <span>{{ $booking->formatted_date }}</span>
                </div>
                <div class="detail-item">
                    <strong>Time:</strong>
                    <span>{{ $booking->formatted_start_time }} - {{ $booking->formatted_end_time }} ({{ $booking->duration }})</span>
                </div>
                <div class="detail-item">
                    <strong>Session Type:</strong>
                    <span>{{ ucwords(str_replace('_', ' ', $booking->session_type)) }}</span>
                </div>
                <div class="detail-item">
                    <strong>Session Rate:</strong>
                    <span>₱{{ number_format($booking->rate, 2) }}/month</span>
                </div>

            </div>

            @if($booking->notes)
            <div class="student-notes">
                <strong>Student's Notes:</strong>
                <p>{{ $booking->notes }}</p>
            </div>
            @endif

            <div class="actions">
                @if($booking->status == 'pending')
                    <button type="button" class="btn btn-success" onclick="document.getElementById('acceptModal').style.display='block'">Accept</button>
                    <button type="button" class="btn btn-danger" onclick="document.getElementById('rejectModal').style.display='block'">Reject</button>
                @endif
                <a href="mailto:{{ $booking->student->email }}" class="btn btn-primary">Message</a>
                <a href="{{ route('tutor.bookings.index') }}" class="btn btn-secondary">Back to Bookings</a>
            </div>
        </div>
    </main>

    <!-- Accept Modal -->
    <div id="acceptModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="document.getElementById('acceptModal').style.display='none'">&times;</span>
            <h3>Accept Booking</h3>
            <p>You are about to accept this booking. You can add an optional message for the student below.</p>
            <form action="{{ route('tutor.bookings.accept', $booking->id) }}" method="POST">
                @csrf
                <textarea name="notes" placeholder="e.g., 'Looking forward to our session! Please come prepared with any questions you have.'">{{ $booking->notes }}</textarea>
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="document.getElementById('acceptModal').style.display='none'">Cancel</button>
                    <button type="submit" class="btn btn-success">Confirm Acceptance</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="document.getElementById('rejectModal').style.display='none'">&times;</span>
            <h3>Reject Booking</h3>
            <p>Please provide a reason for rejecting this booking. This will be shared with the student.</p>
            <form action="{{ route('tutor.bookings.reject', $booking->id) }}" method="POST">
                @csrf
                <textarea name="rejection_reason" placeholder="e.g., 'I apologize, but I have a schedule conflict at this time. Please feel free to book another available slot.'" required></textarea>
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="document.getElementById('rejectModal').style.display='none'">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm Rejection</button>
                </div>
            </form>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const menuToggle = document.getElementById('menu-toggle');
            const navLinks = document.getElementById('nav-links');

            if (menuToggle && navLinks) {
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
            fetch('{{ route('tutor.wallet.balance') }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                const currencyAmount = document.getElementById('currency-amount');
                if (currencyAmount && data.balance !== undefined) {
                    currencyAmount.textContent = '₱' + parseFloat(data.balance).toFixed(2);
                }
            })
            .catch(error => {
                console.error('Error loading currency data:', error);
            });
        }

        function viewWallet() {
            window.location.href = "{{ route('tutor.wallet') }}";
        }
    </script>
</body>
</html> 