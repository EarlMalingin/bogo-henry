<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Log - MentorHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            line-height: 1.6;
            color: #333;
            background: linear-gradient(rgba(255, 255, 255, 0.85), rgba(255, 255, 255, 0.85)),
                url('{{ asset("images/Uc-background.jpg") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
        }

        /* Header Styles */
        header {
            background: linear-gradient(135deg, #2d7dd2, #4a3dd9);
            color: white;
            padding: 1rem 0;
            width: 100%;
            position: fixed;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 5%;
            max-width: 1200px;
            margin: 0 auto;
            flex-wrap: wrap;
        }

        .logo {
            display: flex;
            align-items: center;
            font-size: 2rem;
            font-weight: bold;
            color: white;
            text-decoration: none;
            text-shadow: 0 2px 8px rgba(44, 62, 80, 0.12);
        }

        .logo-img {
            margin-right: 0.5rem;
            height: 70px;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .nav-links {
            display: flex;
            gap: 1rem;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            padding: 0.5rem 1rem;
            border-radius: 25px;
        }

        .nav-links a:hover, .nav-links a.active {
            background-color: rgba(255,255,255,0.2);
            transform: translateY(-2px);
        }

        .header-right-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .currency-display {
            display: flex;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.15);
            padding: 0.5rem 1rem;
            border-radius: 25px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .currency-display:hover {
            background-color: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .currency-icon {
            font-size: 1.2rem;
            margin-right: 0.5rem;
            color: #ffd700;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        .currency-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .currency-amount {
            font-size: 1.1rem;
            font-weight: bold;
            color: white;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
            line-height: 1;
        }

        .currency-label {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.8);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 2px;
        }

        .profile-dropdown-container {
            position: relative;
        }

        .profile-icon {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #4a90e2;
            color: white;
            font-weight: bold;
            cursor: pointer;
            z-index: 1000;
            transition: transform 0.2s cubic-bezier(0.4,0,0.2,1), box-shadow 0.2s cubic-bezier(0.4,0,0.2,1);
        }

        .profile-icon:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 16px rgba(74, 144, 226, 0.15);
        }

        .profile-icon-img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            width: 180px;
            margin-top: 10px;
            z-index: 1001;
            overflow: hidden;
        }

        .dropdown-menu.active {
            display: block;
        }

        .dropdown-menu a {
            display: block;
            padding: 12px 15px;
            color: #333;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .dropdown-menu a:hover {
            background-color: #f5f5f5;
        }

        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }

            .nav-links {
                display: none;
                width: 100%;
                flex-direction: column;
                position: absolute;
                top: 100%;
                left: 0;
                background: linear-gradient(135deg, #2d7dd2, #4a3dd9);
                padding: 1rem;
                box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            }

            .nav-links.active {
                display: flex;
            }

            .currency-display {
                padding: 0.4rem 0.8rem;
            }
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 120px 2rem 2rem;
        }

        .page-header {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .page-title {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: #666;
        }

        /* Summary Cards */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .summary-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }

        .summary-card:hover {
            transform: translateY(-5px);
        }

        .summary-card.earnings {
            border-left: 4px solid #28a745;
        }

        .summary-card.sessions {
            border-left: 4px solid #007bff;
        }

        .summary-card.assignments {
            border-left: 4px solid #ffc107;
        }

        .summary-card.cash-in {
            border-left: 4px solid #17a2b8;
        }

        .summary-card.cash-out {
            border-left: 4px solid #dc3545;
        }

        .summary-card.recent {
            border-left: 4px solid #6f42c1;
        }

        .summary-label {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .summary-value {
            font-size: 1.8rem;
            font-weight: bold;
            color: #333;
        }

        .summary-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        /* Filters */
        .filters-section {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .filter-group select,
        .filter-group input {
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
        }

        .filter-actions {
            display: flex;
            gap: 1rem;
            align-items: flex-end;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background: #0056b3;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #545b62;
        }

        /* Transactions Table */
        .transactions-section {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .transactions-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .btn-download {
            background: #28a745;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-download:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
        }

        .btn-clean {
            background: #dc3545;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-clean:hover {
            background: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
        }

        .transactions-header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
        }

        .transactions-table {
            width: 100%;
            border-collapse: collapse;
        }

        .transactions-table th {
            background: #f8f9fa;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #dee2e6;
        }

        .transactions-table td {
            padding: 1rem;
            border-bottom: 1px solid #dee2e6;
        }

        .transactions-table tr:hover {
            background: #f8f9fa;
        }

        .transaction-type {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .transaction-type.session_earnings {
            background: #cfe2ff;
            color: #084298;
        }

        .transaction-type.assignment_earnings {
            background: #fff3cd;
            color: #856404;
        }

        .transaction-type.cash_in {
            background: #d1e7dd;
            color: #0f5132;
        }

        .transaction-type.cash_out {
            background: #f8d7da;
            color: #842029;
        }

        .transaction-amount {
            font-weight: bold;
            font-size: 1.1rem;
        }

        .transaction-amount.positive {
            color: #28a745;
        }

        .transaction-amount.negative {
            color: #dc3545;
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-badge.completed {
            background: #d1e7dd;
            color: #0f5132;
        }

        .status-badge.pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-badge.failed {
            background: #f8d7da;
            color: #842029;
        }

        .status-badge.pending_approval {
            background: #cfe2ff;
            color: #084298;
        }

        .no-transactions {
            text-align: center;
            padding: 3rem;
            color: #666;
        }

        .pagination-wrapper {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
        }

        .pagination {
            display: flex;
            gap: 0.5rem;
            list-style: none;
        }

        .pagination li {
            padding: 0.5rem 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .pagination li.active {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }

        .pagination li a {
            text-decoration: none;
            color: inherit;
        }

        @media (max-width: 768px) {
            .summary-grid {
                grid-template-columns: 1fr;
            }

            .filters-grid {
                grid-template-columns: 1fr;
            }

            .transactions-table {
                font-size: 0.9rem;
            }

            .transactions-table th,
            .transactions-table td {
                padding: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="navbar">
            <a href="{{ route('tutor.dashboard') }}" class="logo">
                <img src="{{ asset('images/MentorHub.png') }}" alt="MentorHub Logo" class="logo-img">
            </a>
            <button class="menu-toggle" id="menu-toggle">☰</button>
            <nav class="nav-links" id="nav-links">
                <a href="{{ route('tutor.dashboard') }}">Dashboard</a>
                <a href="{{ route('tutor.bookings.index') }}">My Bookings</a>
                <a href="{{ route('tutor.students') }}">Students</a>
                <a href="{{ route('tutor.schedule') }}">Schedule</a>
            </nav>
            <div class="header-right-section">
                <!-- Currency Display -->
                <div class="currency-display" onclick="window.location.href='{{ route('tutor.wallet') }}'">
                    <div class="currency-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="currency-info">
                        <div class="currency-amount" id="currency-amount">₱{{ number_format($wallet->balance, 2) }}</div>
                        <div class="currency-label">Balance</div>
                    </div>
                </div>
                
                <!-- Profile Dropdown -->
                <div class="profile-dropdown-container" style="position: relative;">
                    <div class="profile-icon" id="profile-icon">
                        @if($tutor->profile_picture)
                            <img src="{{ route('tutor.profile.picture') }}?v={{ time() }}" alt="Profile Picture" class="profile-icon-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center; background-color: #f5f5f5; color: #666; font-weight: bold; font-size: 1.2rem; border-radius: 50%;">{{ strtoupper(substr($tutor->first_name, 0, 1) . substr($tutor->last_name, 0, 1)) }}</div>
                        @else
                            {{ strtoupper(substr($tutor->first_name, 0, 1) . substr($tutor->last_name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="dropdown-menu" id="dropdown-menu">
                        <a href="{{ route('tutor.profile.edit') }}">My Profile</a>
                        <a href="{{ route('tutor.settings') }}">Achievements</a>
                        <a href="{{ route('tutor.report-problem') }}">Report a Problem</a>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" method="POST" action="{{ route('tutor.logout') }}" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #c3e6cb; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #f5c6cb; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Transaction Log</h1>
            <p class="page-subtitle">View your earnings, cash in, and cash out transactions</p>
        </div>

        <!-- Summary Cards -->
        <div class="summary-grid">
            <div class="summary-card earnings">
                <div class="summary-icon" style="color: #28a745;"><i class="fas fa-money-bill-wave"></i></div>
                <div class="summary-label">Total Earnings</div>
                <div class="summary-value">₱{{ number_format($totalEarnings, 2) }}</div>
            </div>

            <div class="summary-card sessions">
                <div class="summary-icon" style="color: #007bff;"><i class="fas fa-chalkboard-teacher"></i></div>
                <div class="summary-label">From Sessions</div>
                <div class="summary-value">₱{{ number_format($sessionEarnings, 2) }}</div>
            </div>

            <div class="summary-card assignments">
                <div class="summary-icon" style="color: #ffc107;"><i class="fas fa-file-alt"></i></div>
                <div class="summary-label">From Assignments</div>
                <div class="summary-value">₱{{ number_format($assignmentEarnings, 2) }}</div>
            </div>

            <div class="summary-card cash-in">
                <div class="summary-icon" style="color: #17a2b8;"><i class="fas fa-arrow-down"></i></div>
                <div class="summary-label">Total Cash In</div>
                <div class="summary-value">₱{{ number_format($totalCashIn, 2) }}</div>
            </div>

            <div class="summary-card cash-out">
                <div class="summary-icon" style="color: #dc3545;"><i class="fas fa-arrow-up"></i></div>
                <div class="summary-label">Total Cash Out</div>
                <div class="summary-value">₱{{ number_format($totalCashOut, 2) }}</div>
            </div>

            <div class="summary-card recent">
                <div class="summary-icon" style="color: #6f42c1;"><i class="fas fa-calendar-week"></i></div>
                <div class="summary-label">Last 30 Days</div>
                <div class="summary-value">₱{{ number_format($recentEarnings, 2) }}</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters-section">
            <form method="GET" action="{{ route('tutor.transactions.index') }}">
                <div class="filters-grid">
                    <div class="filter-group">
                        <label for="type">Transaction Type</label>
                        <select name="type" id="type">
                            <option value="all" {{ $typeFilter === 'all' ? 'selected' : '' }}>All Types</option>
                            <option value="session_earnings" {{ $typeFilter === 'session_earnings' ? 'selected' : '' }}>Session Earnings</option>
                            <option value="assignment_earnings" {{ $typeFilter === 'assignment_earnings' ? 'selected' : '' }}>Assignment Earnings</option>
                            <option value="cash_in" {{ $typeFilter === 'cash_in' ? 'selected' : '' }}>Cash In</option>
                            <option value="cash_out" {{ $typeFilter === 'cash_out' ? 'selected' : '' }}>Cash Out</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="status">Status</label>
                        <select name="status" id="status">
                            <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="completed" {{ $statusFilter === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="pending" {{ $statusFilter === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="pending_approval" {{ $statusFilter === 'pending_approval' ? 'selected' : '' }}>Pending Approval</option>
                            <option value="failed" {{ $statusFilter === 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="date_from">Date From</label>
                        <input type="date" name="date_from" id="date_from" value="{{ $dateFrom }}">
                    </div>

                    <div class="filter-group">
                        <label for="date_to">Date To</label>
                        <input type="date" name="date_to" id="date_to" value="{{ $dateTo }}">
                    </div>

                    <div class="filter-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Apply Filters
                        </button>
                        <a href="{{ route('tutor.transactions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Transactions Table -->
        <div class="transactions-section">
            <div class="transactions-header">
                <h2 class="section-title">Recent Transactions</h2>
                <div class="transactions-header-actions">
                    <a href="{{ route('tutor.transactions.download', request()->query()) }}" class="btn-download">
                        <i class="fas fa-download"></i> Download PDF
                    </a>
                    @if($transactions->count() > 0)
                        <form method="POST" action="{{ route('tutor.transactions.clean') }}" style="display: inline;" onsubmit="return confirmClean()">
                            @csrf
                            @if($typeFilter !== 'all')
                                <input type="hidden" name="type" value="{{ $typeFilter }}">
                            @endif
                            @if($statusFilter !== 'all')
                                <input type="hidden" name="status" value="{{ $statusFilter }}">
                            @endif
                            @if($dateFrom)
                                <input type="hidden" name="date_from" value="{{ $dateFrom }}">
                            @endif
                            @if($dateTo)
                                <input type="hidden" name="date_to" value="{{ $dateTo }}">
                            @endif
                            <button type="submit" class="btn-clean">
                                <i class="fas fa-trash-alt"></i> Clean Logs
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            @if($transactions->count() > 0)
                <table class="transactions-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Balance Before</th>
                            <th>Balance After</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->created_at->format('M d, Y h:i A') }}</td>
                                <td>
                                    <span class="transaction-type {{ $transaction->type }}">
                                        @if($transaction->type === 'session_earnings')
                                            Session Earnings
                                        @elseif($transaction->type === 'assignment_earnings')
                                            Assignment Earnings
                                        @elseif($transaction->type === 'cash_in')
                                            Cash In
                                        @elseif($transaction->type === 'cash_out')
                                            Cash Out
                                        @else
                                            {{ ucfirst(str_replace('_', ' ', $transaction->type)) }}
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    {{ $transaction->description ?? ($transaction->metadata['description'] ?? 'N/A') }}
                                    @if(isset($transaction->metadata['session_id']))
                                        <br><small style="color: #666;">Session ID: {{ $transaction->metadata['session_id'] }}</small>
                                    @endif
                                    @if(isset($transaction->metadata['assignment_id']))
                                        <br><small style="color: #666;">Assignment ID: {{ $transaction->metadata['assignment_id'] }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="transaction-amount {{ in_array($transaction->type, ['session_earnings', 'assignment_earnings', 'cash_in']) ? 'positive' : 'negative' }}">
                                        {{ in_array($transaction->type, ['cash_out']) ? '-' : '+' }}₱{{ number_format($transaction->amount, 2) }}
                                    </span>
                                </td>
                                <td>₱{{ number_format($transaction->balance_before, 2) }}</td>
                                <td>₱{{ number_format($transaction->balance_after, 2) }}</td>
                                <td>
                                    <span class="status-badge {{ $transaction->status }}">
                                        {{ ucfirst(str_replace('_', ' ', $transaction->status)) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="pagination-wrapper">
                    {{ $transactions->links() }}
                </div>
            @else
                <div class="no-transactions">
                    <i class="fas fa-inbox" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
                    <p>No transactions found</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const menuToggle = document.getElementById('menu-toggle');
            const navLinks = document.getElementById('nav-links');

            if (menuToggle && navLinks) {
                menuToggle.addEventListener('click', function() {
                    navLinks.classList.toggle('active');
                });
            }

            // Profile dropdown functionality
            const profileIcon = document.getElementById('profile-icon');
            const dropdownMenu = document.getElementById('dropdown-menu');

            if (profileIcon && dropdownMenu) {
                profileIcon.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdownMenu.classList.toggle('active');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!profileIcon.contains(e.target)) {
                        dropdownMenu.classList.remove('active');
                    }
                });
            }
        });

        function confirmClean() {
            const hasFilters = {{ ($typeFilter !== 'all' || $statusFilter !== 'all' || $dateFrom || $dateTo) ? 'true' : 'false' }};
            let message = 'Are you sure you want to clean/delete ';
            
            if (hasFilters) {
                message += 'the filtered transactions from your log? This action cannot be undone.';
            } else {
                message += 'ALL transactions from your log? This action cannot be undone.';
            }
            
            return confirm(message);
        }
    </script>
</body>
</html>

