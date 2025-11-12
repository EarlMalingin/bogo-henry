<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details | MentorHub Admin</title>
    <style>
        :root{--primary:#4a90e2;--secondary:#5637d9}
        *{box-sizing:border-box}
        body{margin:0;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;background:linear-gradient(rgba(255,255,255,.92),rgba(255,255,255,.92)),url('{{ asset('images/Uc-background.jpg') }}') no-repeat center/cover fixed;min-height:100vh}
        .topbar{background:linear-gradient(135deg,var(--primary),var(--secondary));color:#fff;box-shadow:0 2px 8px rgba(0,0,0,0.1)}
        .nav{max-width:1400px;margin:0 auto;display:flex;align-items:center;gap:20px;padding:16px 20px}
        .nav a{color:#fff;text-decoration:none;font-weight:500;transition:all 0.3s;padding:8px 16px;border-radius:8px}
        .nav a:hover{background:rgba(255,255,255,0.15)}
        main{max-width:1100px;margin:24px auto;padding:0 16px}
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
    <div class="topbar">
        <div class="nav">
            <a href="{{ route('admin.users.index') }}">← Users</a>
            <span>/</span>
            <span>{{ ucfirst($type) }}</span>
        </div>
    </div>

    <main>
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


