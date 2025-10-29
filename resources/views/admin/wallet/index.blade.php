<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Wallet | MentorHub</title>
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
        .badge.red{background:#fee2e2;color:#dc2626}
        .btn{display:inline-block;padding:8px 16px;border-radius:6px;text-decoration:none;font-size:14px;font-weight:500;cursor:pointer;border:none}
        .btn-primary{background:#4a90e2;color:#fff}
        .btn-success{background:#10b981;color:#fff}
        .btn-danger{background:#ef4444;color:#fff}
        .btn-warning{background:#f59e0b;color:#fff}
        .btn:hover{opacity:0.9}
        .action-buttons{display:flex;gap:8px}
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
                <a class="tab active" href="{{ route('admin.wallet.index') }}">Wallet</a>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="logout">Logout</button>
            </form>
        </div>
    </div>

    <main>
        <h2>Wallet Management</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <div class="grid">
            <div class="card">
                <h4>Total Wallets</h4>
                <p>Active user wallets</p>
                <div class="stat">
                    <div class="num">{{ number_format($totalWallets) }}</div>
                    <div class="label">wallets</div>
                </div>
            </div>
            <div class="card">
                <h4>Total Balance</h4>
                <p>Combined wallet balance</p>
                <div class="stat">
                    <div class="num">₱{{ number_format($totalBalance, 2) }}</div>
                    <div class="label">PHP</div>
                </div>
            </div>
            <div class="card">
                <h4>Pending Cash-ins</h4>
                <p>Awaiting admin approval</p>
                <div class="stat">
                    <div class="num">{{ number_format($pendingCashIns) }}</div>
                    <div class="label">requests</div>
                </div>
            </div>
            <div class="card">
                <h4>Pending Payouts</h4>
                <p>Awaiting approval</p>
                <div class="stat">
                    <div class="num">{{ number_format($pendingPayouts) }}</div>
                    <div class="label">requests</div>
                </div>
            </div>
        </div>

        <div class="grid" style="margin-top:18px">
            <div class="card">
                <h4>Cash In Total</h4>
                <p>Total funds added</p>
                <div class="stat">
                    <div class="num">₱{{ number_format($totalCashIn, 2) }}</div>
                    <div class="label">PHP</div>
                </div>
            </div>
            <div class="card">
                <h4>Cash Out Total</h4>
                <p>Total funds withdrawn</p>
                <div class="stat">
                    <div class="num">₱{{ number_format($totalCashOut, 2) }}</div>
                    <div class="label">PHP</div>
                </div>
            </div>
            <div class="card">
                <h4>Completed Payouts</h4>
                <p>Successfully processed</p>
                <div class="stat">
                    <div class="num">{{ number_format($completedPayouts) }}</div>
                    <div class="label">payouts</div>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top:18px">
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>Recent Transactions</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions as $transaction)
                        <tr>
                            <td>{{ $transaction->description ?? 'Transaction' }}</td>
                            <td>
                                <span class="badge {{ $transaction->type === 'cash_in' ? 'green' : 'orange' }}">
                                    {{ ucfirst(str_replace('_', ' ', $transaction->type)) }}
                                </span>
                            </td>
                            <td>₱{{ number_format($transaction->amount, 2) }}</td>
                            <td>
                                <span class="badge {{ $transaction->status === 'completed' ? 'green' : ($transaction->status === 'pending' ? 'orange' : ($transaction->status === 'pending_approval' ? 'orange' : 'red')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $transaction->status)) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td>No transactions yet</td>
                                <td><span class="badge orange">—</span></td>
                                <td>—</td>
                                <td><span class="badge orange">—</span></td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>Pending Payout Requests</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingPayoutRequests as $payout)
                            <tr>
                                <td>{{ $payout->description ?? 'Payout Request' }}</td>
                                <td>₱{{ number_format($payout->amount, 2) }}</td>
                                <td>{{ $payout->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <form method="POST" action="{{ route('admin.wallet.approve-payout', $payout->id) }}" style="display:inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success">Approve</button>
                                        </form>
                                        <button onclick="showRejectModal({{ $payout->id }})" class="btn btn-danger">Reject</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td>No pending payouts</td>
                                <td>—</td>
                                <td>—</td>
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
                            <th>Quick Actions</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>View All Transactions</td>
                            <td>Browse and filter all wallet transactions</td>
                            <td><a href="{{ route('admin.wallet.transactions') }}" class="btn btn-primary">View</a></td>
                        </tr>
                        <tr>
                            <td>Manage Pending Cash-ins</td>
                            <td>Review and approve/reject cash-in requests</td>
                            <td><a href="{{ route('admin.wallet.pending-cash-ins') }}" class="btn btn-warning">Manage</a></td>
                        </tr>
                        <tr>
                            <td>Manage Pending Payouts</td>
                            <td>Review and approve/reject payout requests</td>
                            <td><a href="{{ route('admin.wallet.pending-payouts') }}" class="btn btn-warning">Manage</a></td>
                        </tr>
                        <tr>
                            <td>User Wallets</td>
                            <td>View individual user wallet details</td>
                            <td><a href="{{ route('admin.wallet.user-wallets') }}" class="btn btn-primary">View</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Reject Modal -->
    <div id="rejectModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:1000">
        <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);background:white;padding:24px;border-radius:12px;min-width:400px">
            <h3 style="margin:0 0 16px">Reject Payout</h3>
            <form id="rejectForm" method="POST">
                @csrf
                <div style="margin-bottom:16px">
                    <label style="display:block;margin-bottom:8px;font-weight:500">Reason for rejection:</label>
                    <textarea name="reason" required style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;resize:vertical" rows="3" placeholder="Enter reason for rejection..."></textarea>
                </div>
                <div style="display:flex;gap:8px;justify-content:flex-end">
                    <button type="button" onclick="hideRejectModal()" class="btn" style="background:#6b7280;color:#fff">Cancel</button>
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
