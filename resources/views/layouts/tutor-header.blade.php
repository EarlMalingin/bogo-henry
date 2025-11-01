<header>
    <div class="navbar">
        <a href="#" class="logo">
            <div class="logo-img">MH</div>
            <span>MentorHub</span>
        </a>
        <button class="menu-toggle" id="menu-toggle">☰</button>
        <nav class="nav-links" id="nav-links">
            <a href="#" class="active">Dashboard</a>
            <a href="{{ route('tutor.bookings.index') }}">My Bookings</a>
            <a href="#">Students</a>
            <a href="#">Schedule</a>
            <a href="#">Resources</a>
        </nav>
        <div class="profile-icon" id="profile-icon">
            @if(isset($tutor) && $tutor->profile_picture)
                <img src="{{ asset('storage/' . $tutor->profile_picture) }}?{{ time() }}" alt="Profile Picture" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
            @elseif(isset($tutor))
                {{ strtoupper(substr($tutor->first_name, 0, 1) . substr($tutor->last_name, 0, 1)) }}
            @else
                TU
            @endif
            <div class="dropdown-menu" id="dropdown-menu">
                <a href="{{ route('tutor.profile.edit') }}">My Profile</a>
                <a href="#">Settings</a>
                        <a href="#">Report a Problem</a>
                <a href="{{ route('home') }}" onclick="logout()">Logout</a>
            </div>
        </div>
    </div>
</header>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menu-toggle');
    const navLinks = document.getElementById('nav-links');
    if(menuToggle && navLinks) {
        menuToggle.addEventListener('click', function() {
            navLinks.classList.toggle('active');
        });
    }
    const profileIcon = document.getElementById('profile-icon');
    const dropdownMenu = document.getElementById('dropdown-menu');
    if(profileIcon && dropdownMenu) {
        profileIcon.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownMenu.classList.toggle('active');
        });
        document.addEventListener('click', function() {
            if (dropdownMenu.classList.contains('active')) {
                dropdownMenu.classList.remove('active');
            }
        });
    }
});
</script> 