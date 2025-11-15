<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Cash-ins | MentorHub</title>
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
        <h2>Pending Cash-in Requests</h2>

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
                        <th>Payment Method</th>
                        <th>Payment Proof</th>
                        <th>Request Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingCashIns as $cashIn)
                        <tr>
                            <td>#{{ $cashIn->id }}</td>
                            <td>{{ $cashIn->description ?? 'Cash-in Request' }}</td>
                            <td>₱{{ number_format($cashIn->amount, 2) }}</td>
                            <td>
                                @if($cashIn->wallet)
                                    {{ $cashIn->wallet->user_full_name ?? ucfirst($cashIn->wallet->user_type) . ' #' . $cashIn->wallet->user_id }}
                                    <br><small style="color:#6b7280;">({{ ucfirst($cashIn->wallet->user_type) }})</small>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ ucfirst($cashIn->payment_method ?? 'GCash') }}</td>
                            <td>
                                @php
                                    // Check payment proof directly
                                    $hasProof = !empty($cashIn->payment_proof_path);
                                    $proofUrl = $hasProof ? route('admin.wallet.view-payment-proof', $cashIn->id) : null;
                                @endphp
                                @if($hasProof && $proofUrl)
                                    <button onclick="showPaymentProofModal('{{ $proofUrl }}', '{{ addslashes($cashIn->payment_proof_description ?? 'No description provided') }}')" class="btn btn-primary" style="font-size:12px;padding:4px 8px;">
                                        <i class="fas fa-image"></i> View Proof
                                    </button>
                                @else
                                    <span style="color:#6b7280;font-size:12px;">No proof uploaded</span>
                                @endif
                            </td>
                            <td>{{ $cashIn->created_at->format('M d, Y h:i A') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <form method="POST" action="{{ route('admin.wallet.approve-cash-in', $cashIn->id) }}" style="display:inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to approve this cash-in?')">Approve</button>
                                    </form>
                                    <button onclick="showRejectModal({{ $cashIn->id }})" class="btn btn-danger">Reject</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center;padding:24px;color:#6b7280">No pending cash-ins</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pendingCashIns->hasPages())
            <div class="pagination">
                @if($pendingCashIns->onFirstPage())
                    <span>&laquo;</span>
                @else
                    <a href="{{ $pendingCashIns->previousPageUrl() }}">&laquo;</a>
                @endif

                @foreach($pendingCashIns->getUrlRange(1, $pendingCashIns->lastPage()) as $page => $url)
                    @if($page == $pendingCashIns->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach

                @if($pendingCashIns->hasMorePages())
                    <a href="{{ $pendingCashIns->nextPageUrl() }}">&raquo;</a>
                @else
                    <span>&raquo;</span>
                @endif
            </div>
        @endif

        <div style="margin-top:18px">
            <a href="{{ route('admin.wallet.index') }}" class="btn btn-secondary">Back to Wallet</a>
        </div>
    </main>

    <!-- Payment Proof View Modal -->
    <div id="paymentProofViewModal" class="modal">
        <div class="modal-content" style="max-width:600px;">
            <h3>Payment Proof</h3>
            <div id="proofImageContainer" style="text-align:center;margin-bottom:15px;">
                <img id="proofImage" src="" alt="Payment Proof" style="max-width:100%;max-height:400px;border-radius:8px;box-shadow:0 4px 8px rgba(0,0,0,0.1);">
            </div>
            <div id="proofDescription" style="margin-bottom:15px;padding:10px;background:#f9fafb;border-radius:6px;">
                <strong>Description:</strong>
                <p id="proofDescriptionText" style="margin:5px 0 0;color:#6b7280;"></p>
            </div>
            <div class="modal-buttons">
                <button type="button" onclick="hidePaymentProofViewModal()" class="btn btn-secondary">Close</button>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="modal">
        <div class="modal-content">
            <h3>Reject Cash-in</h3>
            <form id="rejectForm" method="POST">
                @csrf
                <textarea name="reason" required rows="3" placeholder="Enter reason for rejection..."></textarea>
                <div class="modal-buttons">
                    <button type="button" onclick="hideRejectModal()" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Cash-in</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showPaymentProofModal(imageUrl, description) {
            document.getElementById('proofImage').src = imageUrl;
            document.getElementById('proofDescriptionText').textContent = description || 'No additional description provided.';
            document.getElementById('paymentProofViewModal').style.display = 'block';
        }

        function hidePaymentProofViewModal() {
            document.getElementById('paymentProofViewModal').style.display = 'none';
        }

        function showRejectModal(cashInId) {
            document.getElementById('rejectForm').action = '{{ route("admin.wallet.reject-cash-in", ":id") }}'.replace(':id', cashInId);
            document.getElementById('rejectModal').style.display = 'block';
        }

        function hideRejectModal() {
            document.getElementById('rejectModal').style.display = 'none';
            document.getElementById('rejectForm').reset();
        }

        // Close modals when clicking outside
        document.getElementById('paymentProofViewModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hidePaymentProofViewModal();
            }
        });

        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideRejectModal();
            }
        });
    </script>
</body>
</html>
