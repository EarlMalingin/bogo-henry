<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('style/dashboard.css')}}">
    <title>Available Tutors - MentorHub</title>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="navbar">
            <a href="{{route('student.dashboard')}}" class="logo">
                <img src="{{asset('images/MentorHub.png')}}" alt="MentorHub Logo" class="logo-img">
                <span>MentorHub</span>
            </a>
            <button class="menu-toggle" id="menu-toggle">☰</button>
            <nav class="nav-links" id="nav-links">
                <a href="{{route('student.dashboard')}}">Dashboard</a>
                <a href="{{route('Findtutor')}}" class="active">Tutors</a>
                <a href="#">Sessions</a>
                <a href="#">Resources</a>
            </nav>
            <div class="profile-icon" id="profile-icon">
                @auth('student')
                    @if(Auth::guard('student')->user()->profile_picture)
                        <img src="{{ route('student.profile.picture') }}?v={{ time() }}" alt="Profile Picture" class="profile-icon-img">
                    @else
                        {{ substr(Auth::guard('student')->user()->first_name, 0, 1) }}{{ substr(Auth::guard('student')->user()->last_name, 0, 1) }}
                    @endif
                    <div class="dropdown-menu" id="dropdown-menu">
                        <a href="{{ route('student.profile.edit') }}">My Profile</a>
                        <a href="{{ route('student.settings') }}">Achievements</a>
                        <a href="#">Report a Problem</a>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" method="POST" action="{{ route('student.logout') }}" style="display: none;">
                            @csrf
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <div class="tutors-container">
            <h1>Available Tutors</h1>
            
            <!-- Search and Filter Section -->
            <div class="search-filters">
                <form action="{{ route('student.tutors.index') }}" method="GET" class="search-form">
                    <div class="search-input">
                        <input type="text" name="search" placeholder="Search tutors..." value="{{ request('search') }}">
                        <button type="submit">Search</button>
                    </div>
                    
                    <div class="filter-options">
                        <select name="subject">
                            <option value="">All Subjects</option>
                            <option value="mathematics" {{ request('subject') == 'mathematics' ? 'selected' : '' }}>Mathematics</option>
                            <option value="physics" {{ request('subject') == 'physics' ? 'selected' : '' }}>Physics</option>
                            <option value="computer-science" {{ request('subject') == 'computer-science' ? 'selected' : '' }}>Computer Science</option>
                            <option value="chemistry" {{ request('subject') == 'chemistry' ? 'selected' : '' }}>Chemistry</option>
                            <option value="biology" {{ request('subject') == 'biology' ? 'selected' : '' }}>Biology</option>
                        </select>
                        
                        <select name="rating">
                            <option value="">All Ratings</option>
                            <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4+ Stars</option>
                            <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3+ Stars</option>
                        </select>
                    </div>
                </form>
            </div>

            <!-- Tutors Grid -->
            <div class="tutors-grid">
                <!-- Sample Tutor Cards -->
                <div class="tutor-card">
                    <div class="tutor-image">
                        <img src="{{asset('images/default-profile.png')}}" alt="Tutor Profile">
                    </div>
                    <div class="tutor-info">
                        <h3>Dr. Sarah Johnson</h3>
                        <p class="specialization">Mathematics - Calculus</p>
                        <p class="rating">⭐ 4.9 (120 reviews)</p>
                        <p class="experience">5 years experience</p>
                        <div class="tutor-actions">
                            <button class="view-profile-btn">View Profile</button>
                            <button class="book-session-btn">Book Session</button>
                        </div>
                    </div>
                </div>

                <div class="tutor-card">
                    <div class="tutor-image">
                        <img src="{{asset('images/default-profile.png')}}" alt="Tutor Profile">
                    </div>
                    <div class="tutor-info">
                        <h3>Mr. Michael Chen</h3>
                        <p class="specialization">Physics - Mechanics</p>
                        <p class="rating">⭐ 4.8 (95 reviews)</p>
                        <p class="experience">3 years experience</p>
                        <div class="tutor-actions">
                            <button class="view-profile-btn">View Profile</button>
                            <button class="book-session-btn">Book Session</button>
                        </div>
                    </div>
                </div>

                <div class="tutor-card">
                    <div class="tutor-image">
                        <img src="{{asset('images/default-profile.png')}}" alt="Tutor Profile">
                    </div>
                    <div class="tutor-info">
                        <h3>Ms. Emily Davis</h3>
                        <p class="specialization">Computer Science - Python</p>
                        <p class="rating">⭐ 4.7 (85 reviews)</p>
                        <p class="experience">4 years experience</p>
                        <div class="tutor-actions">
                            <button class="view-profile-btn">View Profile</button>
                            <button class="book-session-btn">Book Session</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <a href="#" class="active">1</a>
                <a href="#">2</a>
                <a href="#">3</a>
                <span>...</span>
                <a href="#">Next</a>
            </div>
        </div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menu-toggle');
            const navLinks = document.getElementById('nav-links');
            
            menuToggle.addEventListener('click', function() {
                navLinks.classList.toggle('active');
            });
            
            // Toggle profile dropdown
            const profileIcon = document.getElementById('profile-icon');
            const dropdownMenu = document.getElementById('dropdown-menu');
            
            profileIcon.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdownMenu.classList.toggle('active');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function() {
                if (dropdownMenu.classList.contains('active')) {
                    dropdownMenu.classList.remove('active');
                }
            });

            // Handle tutor card actions
            document.querySelectorAll('.view-profile-btn').forEach(button => {
                button.addEventListener('click', function() {
                    // Add view profile functionality
                });
            });

            document.querySelectorAll('.book-session-btn').forEach(button => {
                button.addEventListener('click', function() {
                    // Add book session functionality
                });
            });
        });
    </script>
</body>
</html> 