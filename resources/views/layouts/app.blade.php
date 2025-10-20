<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MentorHub</title>
    <link rel="stylesheet" href="{{ asset('style/dashboard.css') }}">
</head>
<body>
    <header>
        <div class="navbar">
            <a href="{{ route('home') }}" class="logo">
                <img src="{{ asset('images/MentorHub.png') }}" alt="MentorHub Logo" class="logo-img">
            </a>
            <nav class="nav-links">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('home') }}#features">About</a>
                <a href="{{ route('home') }}#subjects">Subjects</a>
                <a href="{{ route('home') }}#contact">Contact</a>
            </nav>
        </div>
    </header>
    <main>
        @yield('content')
    </main>
    <footer>
        <div class="footer-content">
            &copy; 2025 MentorHub. All rights reserved.
        </div>
    </footer>
</body>
</html> 