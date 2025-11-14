<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | MentorHub</title>
    <style>
        :root { --primary:#4a90e2; --secondary:#5637d9; --card:#ffffff; --muted:#6b7280; }
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
        main{max-width:1200px;margin:100px auto 28px;padding:0 16px}
        h2{margin:0 0 16px;color:#1f2d3d}
        .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:18px}
        .card{background:var(--card);border-radius:14px;box-shadow:0 16px 40px rgba(20,33,61,.08);padding:18px 18px 16px;position:relative;overflow:hidden}
        .card::after{content:"";position:absolute;right:-40px;top:-40px;width:120px;height:120px;border-radius:50%;background:radial-gradient(closest-side,rgba(90,120,255,.25),transparent 70%)}
        .card h4{margin:0 0 6px;color:#111827}
        .card p{margin:0;color:var(--muted)}
        .stat{display:flex;align-items:center;gap:12px;margin-top:10px}
        .stat .num{font-size:22px;font-weight:800;color:#111827}
        .stat .label{color:var(--muted);font-size:12px}
        .row{display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:18px;margin-top:18px}
        .table{background:var(--card);border-radius:14px;box-shadow:0 12px 32px rgba(20,33,61,.07);overflow:auto}
        table{width:100%;border-collapse:collapse}
        th,td{padding:12px 14px;text-align:left;border-bottom:1px solid #f1f5f9}
        th{font-size:12px;letter-spacing:.02em;color:#6b7280;background:#f8fafc}
        .badge{display:inline-flex;align-items:center;padding:4px 10px;border-radius:999px;font-size:12px;white-space:nowrap}
        .badge.green{background:#e7f8ef;color:#0f9d58}
        .badge.orange{background:#fff1e6;color:#f47c1f}
        .badge.red{background:#fee2e2;color:#dc2626}
        .badge.yellow{background:#fef3c7;color:#d97706}
    </style>
    </head>
<body>
    <header>
        <div class="navbar">
            <a class="logo" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('images/MentorHub.png') }}" alt="MentorHub" class="logo-img">
            </a>
            <nav class="nav-links">
                <a href="{{ route('admin.dashboard') }}" class="active">Dashboard</a>
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
        <h2>Dashboard</h2>

        <div class="grid">
            <a href="{{ route('admin.users.index') }}" style="text-decoration:none;color:inherit">
            <div class="card">
                <h4>Users</h4>
                <p>Manage students and tutors.</p>
                <div class="stat">
                    <div class="num">{{ number_format($totalUsers ?? 0) }}</div>
                    <div class="label">total users</div>
                </div>
            </div>
            </a>
            <a href="{{ route('admin.sessions') }}" style="text-decoration:none;color:inherit">
            <div class="card">
                <h4>Sessions</h4>
                <p>Review and moderate sessions.</p>
                <div class="stat">
                    <div class="num">{{ number_format($upcomingToday ?? 0) }}</div>
                    <div class="label">upcoming today</div>
                </div>
            </div>
            </a>
            <a href="{{ route('admin.wallet.index') }}" style="text-decoration:none;color:inherit">
            <div class="card">
                <h4>Wallet</h4>
                <p>Inspect transactions and payouts.</p>
                <div class="stat">
                    <div class="num">{{ number_format(($pendingPayouts ?? 0) + ($pendingCashIns ?? 0)) }}</div>
                    <div class="label">pending requests</div>
                </div>
            </div>
            </a>
            <a href="{{ route('admin.pending-tutors') }}" style="text-decoration:none;color:inherit">
            <div class="card">
                <h4>Pending Tutors</h4>
                <p>Review and approve tutor registrations.</p>
                <div class="stat">
                    <div class="num">{{ number_format($pendingTutorRegistrations ?? 0) }}</div>
                    <div class="label">awaiting approval</div>
                </div>
            </div>
            </a>
            <a href="{{ route('admin.problem-reports.index') }}" style="text-decoration:none;color:inherit">
            <div class="card">
                <h4>Problem Reports</h4>
                <p>Review and respond to student issues.</p>
                <div class="stat">
                    <div class="num">{{ number_format($pendingReports ?? 0) }}</div>
                    <div class="label">pending reports</div>
                </div>
            </div>
            </a>
        </div>

        <div class="row">
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>Recent Sessions</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $recentSessions = $recentSessions ?? collect(); @endphp
                        @forelse($recentSessions as $s)
                            <tr>
                                <td>{{ optional($s->student)->getFullName() }} with {{ optional($s->tutor)->getFullName() }}</td>
                                <td>
                                    @php $status = $s->status; @endphp
                                    <span class="badge {{ $status === 'accepted' ? 'green' : 'orange' }}">{{ $status }}</span>
                                </td>
                                <td>{{ $s->created_at?->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td>No data yet</td>
                                <td><span class="badge orange">—</span></td>
                                <td>—</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>Recent Wallet Activity</th>
                            <th>Type</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $recentWallet = $recentWallet ?? collect(); @endphp
                        @forelse($recentWallet as $w)
                            <tr>
                                <td>{{ ucfirst(str_replace('_',' ',$w->description ?? 'transaction')) }}</td>
                                <td><span class="badge {{ $w->type === 'cash_in' ? 'green' : 'orange' }}">{{ strtolower(str_replace('_', ' ', $w->type)) }}</span></td>
                                <td>₱{{ number_format($w->amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td>No data yet</td>
                                <td><span class="badge green">—</span></td>
                                <td>—</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row" style="margin-top:18px">
            <div class="table" style="grid-column:1/-1">
                <table>
                    <thead>
                        <tr>
                            <th>Recent Users</th>
                            <th>Type</th>
                            <th>Email</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $recentUsers = $recentUsers ?? collect(); @endphp
                        @forelse($recentUsers as $u)
                            <tr>
                                <td>{{ $u['name'] }}</td>
                                <td>{{ $u['type'] }}</td>
                                <td>{{ $u['email'] }}</td>
                                <td>{{ \Carbon\Carbon::parse($u['created_at'])->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td>No users yet</td>
                                <td>—</td>
                                <td>—</td>
                                <td>—</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</body>
</html>


