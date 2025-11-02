<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Payouts | MentorHub</title>
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
        .btn{display:inline-block;padding:8px 16px;border-radius:6px;text-decoration:none;font-size:14px;font-weight:500;cursor:pointer;border:none}
        .btn-primary{background:#4a90e2;color:#fff}
        .btn-success{background:#10b981;color:#fff}
        .btn-danger{background:#ef4444;color:#fff}
        .btn-secondary{background:#6b7280;color:#fff}
        .btn:hover{opacity:0.9}
        .action-buttons{display:flex;gap:8px}
        .alert{padding:12px 16px;border-radius:8px;margin-bottom:16px}
        .alert-success{background:#d1fae5;color:#065f46;border:1px solid #a7f3d0}
        .alert-error{background:#fee2e2;color:#991b1b;border:1px solid #fca5a5}
        .pagination{display:flex;justify-content:center;gap:8px;margin-top:18px}
        .pagination a,.pagination span{padding:8px 12px;border-radius:6px;text-decoration:none;color:#374151;background:#f9fafb;border:1px solid #e5e7eb}
        .pagination .active{background:#4a90e2;color:#fff;border-color:#4a90e2}
        .modal{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:1000}
        .modal-content{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);background:white;padding:24px;border-radius:12px;min-width:400px}
        .modal h3{margin:0 0 16px}
        .modal textarea{width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;resize:vertical;margin-bottom:16px}
        .modal-buttons{display:flex;gap:8px;justify-content:flex-end}
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
                <a class="tab active" href="{{ route('admin.wallet.pending-payouts') }}">Pending Payouts</a>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="logout">Logout</button>
            </form>
        </div>
    </div>

    <main>
        <h2>Pending Payout Requests</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>User</th>
                        <th>Account Details</th>
                        <th>Request Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingPayouts as $payout)
                        <tr>
                            <td>#{{ $payout->id }}</td>
                            <td>{{ $payout->description ?? 'Payout Request' }}</td>
                            <td>₱{{ number_format($payout->amount, 2) }}</td>
                            <td>
                                @if($payout->wallet)
                                    {{ $payout->wallet->user_full_name ?? ucfirst($payout->wallet->user_type) . ' #' . $payout->wallet->user_id }}
                                    <br><small style="color:#6b7280;">({{ ucfirst($payout->wallet->user_type) }})</small>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if($payout->metadata && isset($payout->metadata['account_number']))
                                    {{ substr($payout->metadata['account_number'], 0, 4) }}****{{ substr($payout->metadata['account_number'], -4) }}
                                    <br><small>{{ $payout->metadata['account_name'] ?? 'N/A' }}</small>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $payout->created_at->format('M d, Y h:i A') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <form method="POST" action="{{ route('admin.wallet.approve-payout', $payout->id) }}" style="display:inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to approve this payout?')">Approve</button>
                                    </form>
                                    <button onclick="showRejectModal({{ $payout->id }})" class="btn btn-danger">Reject</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center;padding:24px;color:#6b7280">No pending payouts</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pendingPayouts->hasPages())
            <div class="pagination">
                @if($pendingPayouts->onFirstPage())
                    <span>&laquo;</span>
                @else
                    <a href="{{ $pendingPayouts->previousPageUrl() }}">&laquo;</a>
                @endif

                @foreach($pendingPayouts->getUrlRange(1, $pendingPayouts->lastPage()) as $page => $url)
                    @if($page == $pendingPayouts->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach

                @if($pendingPayouts->hasMorePages())
                    <a href="{{ $pendingPayouts->nextPageUrl() }}">&raquo;</a>
                @else
                    <span>&raquo;</span>
                @endif
            </div>
        @endif

        <div style="margin-top:18px">
            <a href="{{ route('admin.wallet.index') }}" class="btn btn-secondary">Back to Wallet</a>
        </div>
    </main>

    <!-- Reject Modal -->
    <div id="rejectModal" class="modal">
        <div class="modal-content">
            <h3>Reject Payout</h3>
            <form id="rejectForm" method="POST">
                @csrf
                <textarea name="reason" required rows="3" placeholder="Enter reason for rejection..."></textarea>
                <div class="modal-buttons">
                    <button type="button" onclick="hideRejectModal()" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Payout</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showRejectModal(payoutId) {
            document.getElementById('rejectForm').action = '{{ route("admin.wallet.reject-payout", ":id") }}'.replace(':id', payoutId);
            document.getElementById('rejectModal').style.display = 'block';
        }

        function hideRejectModal() {
            document.getElementById('rejectModal').style.display = 'none';
            document.getElementById('rejectForm').reset();
        }

        // Close modal when clicking outside
        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideRejectModal();
            }
        });
    </script>
</body>
</html>
