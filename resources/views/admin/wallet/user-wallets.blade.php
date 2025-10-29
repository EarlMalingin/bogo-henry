<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Wallets | MentorHub</title>
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
        .table{background:var(--card);border-radius:14px;box-shadow:0 12px 32px rgba(20,33,61,.07);overflow:auto}
        table{width:100%;border-collapse:collapse}
        th,td{padding:12px 14px;text-align:left;border-bottom:1px solid #f1f5f9}
        th{font-size:12px;letter-spacing:.02em;color:#6b7280;background:#f8fafc}
        .badge{display:inline-flex;align-items:center;padding:4px 10px;border-radius:999px;font-size:12px}
        .badge.green{background:#e7f8ef;color:#0f9d58}
        .badge.orange{background:#fff1e6;color:#f47c1f}
        .badge.red{background:#fee2e2;color:#dc2626}
        .btn{display:inline-block;padding:8px 16px;border-radius:6px;text-decoration:none;font-size:14px;font-weight:500;cursor:pointer;border:none}
        .btn-primary{background:#4a90e2;color:#fff}
        .btn-secondary{background:#6b7280;color:#fff}
        .btn:hover{opacity:0.9}
        .filters{background:var(--card);border-radius:14px;box-shadow:0 12px 32px rgba(20,33,61,.07);padding:18px;margin-bottom:18px}
        .filter-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;align-items:end}
        .filter-group{display:flex;flex-direction:column;gap:8px}
        .filter-group label{font-size:12px;font-weight:500;color:#374151}
        .filter-group select{padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:14px}
        .pagination{display:flex;justify-content:center;gap:8px;margin-top:18px}
        .pagination a,.pagination span{padding:8px 12px;border-radius:6px;text-decoration:none;color:#374151;background:#f9fafb;border:1px solid #e5e7eb}
        .pagination .active{background:#4a90e2;color:#fff;border-color:#4a90e2}
        .alert{padding:12px 16px;border-radius:8px;margin-bottom:16px}
        .alert-success{background:#d1fae5;color:#065f46;border:1px solid #a7f3d0}
        .alert-error{background:#fee2e2;color:#991b1b;border:1px solid #fca5a5}
    </style>
</head>
<body>
    <div class="topbar">
        <div class="nav">
            <a class="logo" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('images/MentorHub.png') }}" alt="MentorHub" class="logo-img">
            </a>
            <div class="tabs">
                <a class="tab" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a class="tab" href="{{ route('admin.wallet.index') }}">Wallet</a>
                <a class="tab active" href="{{ route('admin.wallet.user-wallets') }}">User Wallets</a>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="logout">Logout</button>
            </form>
        </div>
    </div>

    <main>
        <h2>User Wallets</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <div class="filters">
            <form method="GET" action="{{ route('admin.wallet.user-wallets') }}">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="user_type">User Type</label>
                        <select name="user_type" id="user_type">
                            <option value="">All Types</option>
                            <option value="student" {{ request('user_type') === 'student' ? 'selected' : '' }}>Students</option>
                            <option value="tutor" {{ request('user_type') === 'tutor' ? 'selected' : '' }}>Tutors</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.wallet.user-wallets') }}" class="btn btn-secondary">Clear</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>Wallet ID</th>
                        <th>User Type</th>
                        <th>User ID</th>
                        <th>Balance</th>
                        <th>Currency</th>
                        <th>Created</th>
                        <th>Last Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($wallets as $wallet)
                        <tr>
                            <td>#{{ $wallet->id }}</td>
                            <td>
                                <span class="badge {{ $wallet->user_type === 'student' ? 'green' : 'orange' }}">
                                    {{ ucfirst($wallet->user_type) }}
                                </span>
                            </td>
                            <td>{{ $wallet->user_id }}</td>
                            <td>₱{{ number_format($wallet->balance, 2) }}</td>
                            <td>{{ $wallet->currency }}</td>
                            <td>{{ $wallet->created_at->format('M d, Y') }}</td>
                            <td>{{ $wallet->updated_at->format('M d, Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.wallet.user-wallet-detail', [$wallet->user_type, $wallet->user_id]) }}" class="btn btn-primary">View Details</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;padding:24px;color:#6b7280">No wallets found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($wallets->hasPages())
            <div class="pagination">
                @if($wallets->onFirstPage())
                    <span>&laquo;</span>
                @else
                    <a href="{{ $wallets->previousPageUrl() }}">&laquo;</a>
                @endif

                @foreach($wallets->getUrlRange(1, $wallets->lastPage()) as $page => $url)
                    @if($page == $wallets->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach

                @if($wallets->hasMorePages())
                    <a href="{{ $wallets->nextPageUrl() }}">&raquo;</a>
                @else
                    <span>&raquo;</span>
                @endif
            </div>
        @endif

        <div style="margin-top:18px">
            <a href="{{ route('admin.wallet.index') }}" class="btn btn-secondary">Back to Wallet</a>
        </div>
    </main>
</body>
</html>
