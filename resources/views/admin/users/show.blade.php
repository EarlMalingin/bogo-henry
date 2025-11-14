<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details | MentorHub Admin</title>
    <style>
        :root{--primary:#4a90e2;--secondary:#5637d9}
        *{box-sizing:border-box;margin:0;padding:0}
        body{margin:0;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;background:linear-gradient(rgba(255,255,255,.92),rgba(255,255,255,.92)),url('{{ asset('images/Uc-background.jpg') }}') no-repeat center/cover fixed;min-height:100vh}
        header{background:linear-gradient(135deg,#4a90e2,#5637d9);color:#fff;padding:1rem 0;width:100%;position:fixed;top:0;z-index:100;box-shadow:0 4px 20px rgba(0,0,0,0.1)}
        .navbar{display:flex;justify-content:space-between;align-items:center;padding:0 5%;max-width:1400px;margin:0 auto;flex-wrap:wrap}
        .logo{display:flex;align-items:center;font-size:2rem;font-weight:bold;color:#fff;text-decoration:none;text-shadow:0 2px 8px rgba(44,62,80,0.12)}
        .logo:hover{transform:scale(1.05);transition:transform 0.3s}
        .logo-img{margin-right:0.5rem;height:70px}
        .nav-links{display:flex;gap:1rem}
        .nav-links a{color:#fff;text-decoration:none;font-weight:500;transition:all 0.3s;padding:0.5rem 1rem;border-radius:25px}
        .nav-links a:hover,.nav-links a.active{background-color:rgba(255,255,255,0.2);transform:translateY(-2px)}
        .logout{border:none;border-radius:8px;background:linear-gradient(135deg,#e74c3c,#c0392b);color:#fff;padding:10px 20px;cursor:pointer;font-weight:600;transition:all 0.3s;box-shadow:0 2px 6px rgba(231,76,60,0.3)}
        .logout:hover{background:linear-gradient(135deg,#c0392b,#a93226);transform:translateY(-2px);box-shadow:0 4px 12px rgba(231,76,60,0.4)}
        main{max-width:1100px;margin:100px auto 24px;padding:0 16px}
        .card{background:#fff;border-radius:14px;box-shadow:0 12px 32px rgba(20,33,61,.07);padding:18px}
        .grid{display:grid;grid-template-columns:1fr 1fr;gap:18px}
        .row{display:flex;gap:12px;margin:8px 0}
        .label{width:160px;color:#6b7280}
        .value{color:#111827}
        .badge{display:inline-flex;align-items:center;padding:4px 10px;border-radius:999px;font-size:12px}
        .green{background:#e7f8ef;color:#0f9d58}
        .red{background:#fde7ea;color:#b00020}
    </style>
</head>
<body>
    <header>
        <div class="navbar">
            <a class="logo" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('images/MentorHub.png') }}" alt="MentorHub" class="logo-img">
            </a>
            <nav class="nav-links">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a href="{{ route('admin.pending-tutors') }}">Pending users</a>
                <a href="{{ route('admin.ratings') }}">Ratings</a>
                <a href="{{ route('admin.problem-reports.index') }}">Reports</a>
                <a href="{{ route('admin.wallet.index') }}">Wallet</a>
            </nav>
            <form method="POST" action="{{ route('admin.logout') }}" style="margin:0;">
                @csrf
                <button class="logout">Logout</button>
            </form>
        </div>
    </header>

    <main>
        <div style="margin-bottom:18px">
            <a href="{{ route('admin.users.index') }}" style="color:#4a90e2;text-decoration:none;font-weight:500">← Back to Users</a>
        </div>
        <div class="grid">
            <div class="card">
                <h3 style="margin:0 0 10px">Profile</h3>
                <div class="row"><div class="label">Name</div><div class="value">{{ $user->getFullName() }}</div></div>
                <div class="row"><div class="label">Email</div><div class="value">{{ $user->email }}</div></div>
                @if($type==='student')
                    <div class="row"><div class="label">Student ID</div><div class="value">{{ $user->student_id }}</div></div>
                    <div class="row"><div class="label">Course</div><div class="value">{{ $user->course }}</div></div>
                    <div class="row"><div class="label">Year Level</div><div class="value">{{ $user->year_level }}</div></div>
                @else
                    <div class="row"><div class="label">Tutor ID</div><div class="value">{{ $user->tutor_id }}</div></div>
                    <div class="row"><div class="label">Specialization</div><div class="value">{{ $user->specialization }}</div></div>
                    <div class="row"><div class="label">Monthly Rate</div><div class="value">₱{{ number_format((float)($user->session_rate ?? 0),2) }}/month</div></div>
                    <div class="row"><div class="label">Hourly Rate</div><div class="value">₱{{ number_format((float)($user->hourly_rate ?? 0),2) }}/hour</div></div>
                @endif
                <div class="row"><div class="label">Phone</div><div class="value">{{ $user->phone ?? '—' }}</div></div>
                <div class="row"><div class="label">Status</div><div class="value"><span class="badge {{ ($user->is_active ?? true) ? 'green':'red' }}">{{ ($user->is_active ?? true) ? 'Active':'Deactivated' }}</span></div></div>
            </div>
            <div class="card">
                <h3 style="margin:0 0 10px">Wallet</h3>
                <div class="row"><div class="label">Current Balance</div><div class="value">₱{{ number_format((float)$balance,2) }}</div></div>
            </div>
        </div>
    </main>
</body>
</html>


