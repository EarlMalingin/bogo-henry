<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Wallet Details | MentorHub</title>
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
        .alert{padding:12px 16px;border-radius:8px;margin-bottom:16px}
        .alert-success{background:#d1fae5;color:#065f46;border:1px solid #a7f3d0}
        .alert-error{background:#fee2e2;color:#991b1b;border:1px solid #fca5a5}
        .modal{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:1000}
        .modal-content{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);background:white;padding:24px;border-radius:12px;min-width:400px}
        .modal h3{margin:0 0 16px}
        .modal input,.modal textarea{width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;margin-bottom:16px}
        .modal-buttons{display:flex;gap:8px;justify-content:flex-end}
        .user-info{background:var(--card);border-radius:14px;box-shadow:0 12px 32px rgba(20,33,61,.07);padding:18px;margin-bottom:18px}
        .user-info h3{margin:0 0 12px;color:#111827}
        .user-details{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px}
        .user-detail{display:flex;flex-direction:column;gap:4px}
        .user-detail label{font-size:12px;font-weight:500;color:#6b7280}
        .user-detail span{font-size:14px;color:#111827}
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
                <a href="{{ route('admin.wallet.index') }}" class="active">Wallet</a>
            </nav>
            <form method="POST" action="{{ route('admin.logout') }}" style="margin:0;">
                @csrf
                <button class="logout">Logout</button>
            </form>
        </div>
    </header>

    <main>
        <h2>User Wallet Details</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <div class="user-info">
            <h3>User Information</h3>
            <div class="user-details">
                <div class="user-detail">
                    <label>Name</label>
                    <span>{{ $user->getFullName() }}</span>
                </div>
                <div class="user-detail">
                    <label>Email</label>
                    <span>{{ $user->email }}</span>
                </div>
                <div class="user-detail">
                    <label>Type</label>
                    <span>
                        <span class="badge {{ $wallet->user_type === 'student' ? 'green' : 'orange' }}">
                            {{ ucfirst($wallet->user_type) }}
                        </span>
                    </span>
                </div>
                <div class="user-detail">
                    <label>User ID</label>
                    <span>{{ $user->id }}</span>
                </div>
            </div>
        </div>

        <div class="grid">
            <div class="card">
                <h4>Current Balance</h4>
                <p>Available funds</p>
                <div class="stat">
                    <div class="num">₱{{ number_format($wallet->balance, 2) }}</div>
                    <div class="label">{{ $wallet->currency }}</div>
                </div>
            </div>
            <div class="card">
                <h4>Total Transactions</h4>
                <p>All wallet activity</p>
                <div class="stat">
                    <div class="num">{{ $wallet->transactions->count() }}</div>
                    <div class="label">transactions</div>
                </div>
            </div>
            <div class="card">
                <h4>Wallet Created</h4>
                <p>Account creation date</p>
                <div class="stat">
                    <div class="num">{{ $wallet->created_at->format('M d') }}</div>
                    <div class="label">{{ $wallet->created_at->format('Y') }}</div>
                </div>
            </div>
            <div class="card">
                <h4>Last Updated</h4>
                <p>Most recent activity</p>
                <div class="stat">
                    <div class="num">{{ $wallet->updated_at->format('M d') }}</div>
                    <div class="label">{{ $wallet->updated_at->format('Y') }}</div>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top:18px">
            <div class="table" style="grid-column:1/-1">
                <table>
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($wallet->transactions as $transaction)
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
                                <td>{{ $transaction->created_at->format('M d, Y h:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center;padding:24px;color:#6b7280">No transactions found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div style="margin-top:18px">
            <button onclick="showManualTransactionModal()" class="btn btn-primary">Manual Transaction</button>
            <a href="{{ route('admin.wallet.user-wallets') }}" class="btn btn-secondary">Back to User Wallets</a>
        </div>
    </main>

    <!-- Manual Transaction Modal -->
    <div id="manualTransactionModal" class="modal">
        <div class="modal-content">
            <h3>Manual Transaction</h3>
            <form method="POST" action="{{ route('admin.wallet.manual-transaction') }}">
                @csrf
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <input type="hidden" name="user_type" value="{{ $wallet->user_type }}">
                
                <div>
                    <label>Transaction Type</label>
                    <select name="type" required style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:6px;margin-bottom:16px">
                        <option value="add">Add Funds</option>
                        <option value="deduct">Deduct Funds</option>
                    </select>
                </div>
                
                <input type="number" name="amount" step="0.01" min="0.01" required placeholder="Amount" />
                <textarea name="description" required rows="3" placeholder="Description"></textarea>
                
                <div class="modal-buttons">
                    <button type="button" onclick="hideManualTransactionModal()" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Process Transaction</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showManualTransactionModal() {
            document.getElementById('manualTransactionModal').style.display = 'block';
        }

        function hideManualTransactionModal() {
            document.getElementById('manualTransactionModal').style.display = 'none';
            document.querySelector('#manualTransactionModal form').reset();
        }

        // Close modal when clicking outside
        document.getElementById('manualTransactionModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideManualTransactionModal();
            }
        });
    </script>
</body>
</html>
