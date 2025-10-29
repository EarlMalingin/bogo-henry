<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | MentorHub</title>
    <style>
        :root { --primary:#4a90e2; --secondary:#5637d9; --card:#ffffff; --muted:#6b7280; }
        *{box-sizing:border-box}
        body{margin:0;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;background:linear-gradient(rgba(255,255,255,.92),rgba(255,255,255,.92)),url('{{ asset('images/Uc-background.jpg') }}') no-repeat center/cover fixed;min-height:100vh}
        .topbar{background:linear-gradient(90deg,var(--primary),var(--secondary));color:#fff}
        .nav{max-width:1200px;margin:0 auto;display:flex;align-items:center;gap:16px;padding:14px 16px}
        .logo{display:flex;align-items:center;gap:10px;color:#fff;text-decoration:none}
        .logo-img{height:70px}
        .tabs{display:flex;gap:8px;margin-left:auto;margin-right:12px}
        .tab{color:#eaf2ff;text-decoration:none;padding:8px 14px;border-radius:999px}
        .tab.active{background:rgba(255,255,255,.22);backdrop-filter:blur(4px)}
        .logout{border:none;border-radius:20px;background:#e74c3c;color:#fff;padding:8px 14px;cursor:pointer}
        main{max-width:1200px;margin:28px auto;padding:0 16px}
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
        .badge{display:inline-flex;align-items:center;padding:4px 10px;border-radius:999px;font-size:12px}
        .badge.green{background:#e7f8ef;color:#0f9d58}
        .badge.orange{background:#fff1e6;color:#f47c1f}
    </style>
    </head>
<body>
    <div class="topbar">
        <div class="nav">
            <a class="logo" href="#">
                <img src="{{ asset('images/MentorHub.png') }}" alt="MentorHub" class="logo-img">
            </a>
            <div class="tabs">
                <a class="tab active" href="{{ route('admin.dashboard') }}">Dashboard</a>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="logout">Logout</button>
            </form>
        </div>
    </div>

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
            <div class="card">
                <h4>Sessions</h4>
                <p>Review and moderate sessions.</p>
                <div class="stat">
                    <div class="num">{{ number_format($upcomingToday ?? 0) }}</div>
                    <div class="label">upcoming today</div>
                </div>
            </div>
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
                                <td><span class="badge {{ $w->type === 'cash_in' ? 'green' : 'orange' }}">{{ $w->type }}</span></td>
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


