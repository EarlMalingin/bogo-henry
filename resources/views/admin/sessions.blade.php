<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sessions | Admin | MentorHub</title>
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
        .stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:18px;margin-bottom:24px}
        .stat-card{background:var(--card);border-radius:14px;box-shadow:0 12px 32px rgba(20,33,61,.07);padding:18px}
        .stat-card h4{margin:0 0 6px;color:#111827;font-size:14px}
        .stat-card .num{font-size:24px;font-weight:800;color:#111827}
        .filters{display:flex;gap:10px;margin-bottom:18px;flex-wrap:wrap}
        .filter-btn{padding:10px 20px;border:none;border-radius:8px;cursor:pointer;font-weight:500;transition:all 0.3s;text-decoration:none;display:inline-block}
        .filter-btn.active{background:var(--primary);color:#fff}
        .filter-btn:not(.active){background:#f1f5f9;color:#374151}
        .filter-btn:hover:not(.active){background:#e5e7eb}
        .table{background:var(--card);border-radius:14px;box-shadow:0 12px 32px rgba(20,33,61,.07);overflow:auto}
        table{width:100%;border-collapse:collapse}
        th,td{padding:12px 14px;text-align:left;border-bottom:1px solid #f1f5f9}
        th{font-size:12px;letter-spacing:.02em;color:#6b7280;background:#f8fafc}
        .badge{display:inline-flex;align-items:center;padding:4px 10px;border-radius:999px;font-size:12px;white-space:nowrap}
        .badge.pending{background:#fef3c7;color:#d97706}
        .badge.accepted{background:#dbeafe;color:#2563eb}
        .badge.completed{background:#e7f8ef;color:#0f9d58}
        .badge.cancelled{background:#fee2e2;color:#dc2626}
        .badge.rejected{background:#fee2e2;color:#dc2626}
        .pagination{display:flex;gap:8px;margin-top:18px;justify-content:center;align-items:center}
        .pagination a,.pagination span{padding:8px 12px;border-radius:6px;text-decoration:none;color:#1f2d3d;background:#fff;border:1px solid #e5e7eb}
        .pagination a:hover{background:#f1f5f9}
        .pagination .active{background:var(--primary);color:#fff;border-color:var(--primary)}
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
        <h2>Sessions Management</h2>

        <div class="stats">
            <div class="stat-card">
                <h4>Total Sessions</h4>
                <div class="num">{{ number_format($totalSessions) }}</div>
            </div>
            <div class="stat-card">
                <h4>Current Sessions</h4>
                <div class="num">{{ number_format($currentSessions) }}</div>
            </div>
            <div class="stat-card">
                <h4>Finished Sessions</h4>
                <div class="num">{{ number_format($finishedSessions) }}</div>
            </div>
            <div class="stat-card">
                <h4>Upcoming Today</h4>
                <div class="num">{{ number_format($upcomingToday) }}</div>
            </div>
        </div>

        <div class="filters">
            <a href="{{ route('admin.sessions', ['filter' => 'all']) }}" class="filter-btn {{ $filter === 'all' ? 'active' : '' }}">All Sessions</a>
            <a href="{{ route('admin.sessions', ['filter' => 'current']) }}" class="filter-btn {{ $filter === 'current' ? 'active' : '' }}">Current Sessions</a>
            <a href="{{ route('admin.sessions', ['filter' => 'finished']) }}" class="filter-btn {{ $filter === 'finished' ? 'active' : '' }}">Finished Sessions</a>
        </div>

        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Tutor</th>
                        <th>Session Type</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Rate</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sessions as $session)
                        <tr>
                            <td>
                                @if($session->student)
                                    {{ $session->student->getFullName() }}
                                @else
                                    <span style="color:#6b7280">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($session->tutor)
                                    {{ $session->tutor->getFullName() }}
                                @else
                                    <span style="color:#6b7280">N/A</span>
                                @endif
                            </td>
                            <td>{{ ucfirst($session->session_type ?? 'N/A') }}</td>
                            <td>{{ $session->date ? $session->date->format('M d, Y') : 'N/A' }}</td>
                            <td>
                                @if($session->start_time && $session->end_time)
                                    {{ date('g:i A', strtotime($session->start_time)) }} - {{ date('g:i A', strtotime($session->end_time)) }}
                                @else
                                    <span style="color:#6b7280">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $session->status }}">{{ ucfirst($session->status) }}</span>
                            </td>
                            <td>₱{{ number_format((float)($session->rate ?? 0), 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center;padding:40px;color:#6b7280">
                                No sessions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sessions->hasPages())
            <div class="pagination">
                @if($sessions->onFirstPage())
                    <span>&laquo; Previous</span>
                @else
                    <a href="{{ $sessions->appends(['filter' => $filter])->previousPageUrl() }}">&laquo; Previous</a>
                @endif

                @foreach($sessions->getUrlRange(1, $sessions->lastPage()) as $page => $url)
                    @if($page == $sessions->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $sessions->appends(['filter' => $filter])->url($page) }}">{{ $page }}</a>
                    @endif
                @endforeach

                @if($sessions->hasMorePages())
                    <a href="{{ $sessions->appends(['filter' => $filter])->nextPageUrl() }}">Next &raquo;</a>
                @else
                    <span>Next &raquo;</span>
                @endif
            </div>
        @endif
    </main>
</body>
</html>

