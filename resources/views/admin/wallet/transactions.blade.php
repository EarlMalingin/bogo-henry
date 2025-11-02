<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wallet Transactions | MentorHub</title>
    <style>
        :root { --primary:#4a90e2; --secondary:#5637d9; --card:#ffffff; --muted:#6b7280; }
        *{box-sizing:border-box}
        body{margin:0;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;background:linear-gradient(rgba(255,255,255,.92),rgba(255,255,255,.92)),url('{{ asset('images/Uc-background.jpg') }}') no-repeat center/cover fixed;min-height:100vh}
        .topbar{background:linear-gradient(135deg,var(--primary),var(--secondary));color:#fff;box-shadow:0 2px 8px rgba(0,0,0,0.1)}
        .nav{max-width:1400px;margin:0 auto;display:flex;align-items:center;gap:20px;padding:16px 20px}
        .logo{display:flex;align-items:center;gap:12px;color:#fff;text-decoration:none;transition:transform 0.3s}
        .logo:hover{transform:scale(1.05)}
        .logo-img{height:70px;filter:drop-shadow(0 2px 4px rgba(0,0,0,0.2))}
        .tabs{display:flex;gap:4px;margin-left:auto;margin-right:16px;background:rgba(255,255,255,0.1);padding:4px;border-radius:10px;backdrop-filter:blur(10px)}
        .tab{color:#fff;text-decoration:none;padding:10px 18px;border-radius:8px;font-weight:500;transition:all 0.3s;position:relative}
        .tab:hover{background:rgba(255,255,255,0.15);transform:translateY(-2px)}
        .tab.active{background:rgba(255,255,255,0.25);font-weight:600;box-shadow:0 2px 8px rgba(0,0,0,0.15)}
        .tab.active::before{content:'';position:absolute;bottom:-2px;left:50%;transform:translateX(-50%);width:80%;height:3px;background:#fff;border-radius:2px}
        .logout{border:none;border-radius:8px;background:linear-gradient(135deg,#e74c3c,#c0392b);color:#fff;padding:10px 20px;cursor:pointer;font-weight:600;transition:all 0.3s;box-shadow:0 2px 6px rgba(231,76,60,0.3)}
        .logout:hover{background:linear-gradient(135deg,#c0392b,#a93226);transform:translateY(-2px);box-shadow:0 4px 12px rgba(231,76,60,0.4)}
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
        .btn{display:inline-block;padding:8px 16px;border-radius:6px;text-decoration:none;font-size:14px;font-weight:500;cursor:pointer;border:none;text-align:center}
        .btn-primary{background:#4a90e2;color:#fff}
        .btn-secondary{background:#6b7280;color:#fff}
        .btn:hover{opacity:0.9}
        .filters{background:var(--card);border-radius:14px;box-shadow:0 12px 32px rgba(20,33,61,.07);padding:18px;margin-bottom:18px}
        .filter-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;align-items:end}
        .filter-group{display:flex;flex-direction:column;gap:8px}
        .filter-group label{font-size:12px;font-weight:500;color:#374151}
        .filter-group select,.filter-group input{padding:8px 12px;border:1px solid #d1d5db;border-radius:6px;font-size:14px}
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
                <a class="tab active" href="{{ route('admin.wallet.transactions') }}">Transactions</a>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="logout">Logout</button>
            </form>
        </div>
    </div>

    <main>
        <h2>Wallet Transactions</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <div class="filters">
            <form method="GET" action="{{ route('admin.wallet.transactions') }}">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="type">Transaction Type</label>
                        <select name="type" id="type">
                            <option value="">All Types</option>
                            <option value="cash_in" {{ request('type') === 'cash_in' ? 'selected' : '' }}>Cash In</option>
                            <option value="cash_out" {{ request('type') === 'cash_out' ? 'selected' : '' }}>Cash Out</option>
                            <option value="refund" {{ request('type') === 'refund' ? 'selected' : '' }}>Refund</option>
                            <option value="manual_add" {{ request('type') === 'manual_add' ? 'selected' : '' }}>Manual Add</option>
                            <option value="manual_deduct" {{ request('type') === 'manual_deduct' ? 'selected' : '' }}>Manual Deduct</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="status">Status</label>
                        <select name="status" id="status">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="date_from">From Date</label>
                        <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}">
                    </div>
                    <div class="filter-group">
                        <label for="date_to">To Date</label>
                        <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}">
                    </div>
                    <div class="filter-group">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <button type="button" onclick="clearFilters()" class="btn btn-secondary">Clear</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>User</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>#{{ $transaction->id }}</td>
                            <td>{{ $transaction->description ?? 'Transaction' }}</td>
                            <td>
                                <span class="badge {{ $transaction->type === 'cash_in' ? 'green' : 'orange' }}">
                                    {{ ucfirst(str_replace('_', ' ', $transaction->type)) }}
                                </span>
                            </td>
                            <td>₱{{ number_format($transaction->amount, 2) }}</td>
                            <td>
                                <span class="badge {{ $transaction->status === 'completed' ? 'green' : ($transaction->status === 'pending' ? 'orange' : 'red') }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td>
                                @if($transaction->wallet)
                                    {{ $transaction->wallet->user_full_name ?? ucfirst($transaction->wallet->user_type) . ' #' . $transaction->wallet->user_id }}
                                    <br><small style="color:#6b7280;">({{ ucfirst($transaction->wallet->user_type) }})</small>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $transaction->created_at->format('M d, Y h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center;padding:24px;color:#6b7280">No transactions found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="pagination">
                @if($transactions->onFirstPage())
                    <span>&laquo;</span>
                @else
                    <a href="{{ $transactions->previousPageUrl() }}">&laquo;</a>
                @endif

                @foreach($transactions->getUrlRange(1, $transactions->lastPage()) as $page => $url)
                    @if($page == $transactions->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach

                @if($transactions->hasMorePages())
                    <a href="{{ $transactions->nextPageUrl() }}">&raquo;</a>
                @else
                    <span>&raquo;</span>
                @endif
            </div>
        @endif

        <div style="margin-top:18px">
            <a href="{{ route('admin.wallet.index') }}" class="btn btn-secondary">Back to Wallet</a>
        </div>
    </main>

    <script>
        function clearFilters() {
            // Redirect to the transactions page without any query parameters
            window.location.href = '{{ route('admin.wallet.transactions') }}';
        }
    </script>
</body>
</html>
