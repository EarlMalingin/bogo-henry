<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MentorHub Wallet</title>
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
            background:
                linear-gradient(rgba(255, 255, 255, 0.85), rgba(255, 255, 255, 0.85)),
                url('../images/Uc-background.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header Styles */
        header {
            background: linear-gradient(135deg, #4a90e2, #5637d9);
            color: white;
            padding: 1rem 0;
            width: 100%;
            position: fixed;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            min-height: 60px;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 5%;
            max-width: 1200px;
            margin: 0 auto;
            flex-wrap: wrap;
            min-height: 60px;
        }

        .logo {
            display: flex;
            align-items: center;
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }

        .logo-img {
            margin-right: 0.5rem;
            height: 50px;
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

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem 2rem 1rem;
            margin-top: 100px;
        }

        .wallet-header {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
        }

        .wallet-icon {
            font-size: 3rem;
            color: #4CAF50;
            margin-bottom: 1rem;
        }

        .wallet-title {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .wallet-subtitle {
            color: #666;
            margin-bottom: 2rem;
        }

        .balance-card {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(76, 175, 80, 0.3);
        }

        .balance-label {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 0.5rem;
        }

        .balance-amount {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .balance-currency {
            font-size: 1.2rem;
            opacity: 0.8;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .action-btn {
            background: white;
            border: none;
            padding: 1.5rem;
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-decoration: none;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }

        .action-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .action-btn.cash-in {
            border-left: 4px solid #4CAF50;
        }

        .action-btn.cash-out {
            border-left: 4px solid #FF9800;
        }

        .action-btn-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .action-btn.cash-in .action-btn-icon {
            color: #4CAF50;
        }

        .action-btn.cash-out .action-btn-icon {
            color: #FF9800;
        }

        .action-btn-text {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .transactions-section {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .transaction-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #eee;
        }

        .transaction-item:last-child {
            border-bottom: none;
        }

        .transaction-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .transaction-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .transaction-icon.cash-in {
            background: #E8F5E8;
            color: #4CAF50;
        }

        .transaction-icon.cash-out {
            background: #FFF3E0;
            color: #FF9800;
        }

        .transaction-details h4 {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .transaction-details p {
            color: #666;
            font-size: 0.9rem;
        }

        .transaction-amount {
            text-align: right;
        }

        .transaction-amount.positive {
            color: #4CAF50;
            font-weight: bold;
        }

        .transaction-amount.negative {
            color: #FF9800;
            font-weight: bold;
        }

        .transaction-status {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-completed {
            background: #E8F5E8;
            color: #4CAF50;
        }

        .status-pending {
            background: #FFF3E0;
            color: #FF9800;
        }

        .status-pending_approval {
            background: #E3F2FD;
            color: #1976D2;
        }

        .status-failed {
            background: #FFEBEE;
            color: #F44336;
        }


        .no-transactions {
            text-align: center;
            padding: 3rem;
            color: #666;
        }

        .no-transactions i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }


        @media (max-width: 768px) {
            .container {
                padding: 0 0.5rem;
            }

            .wallet-header {
                padding: 1.5rem;
            }

            .balance-amount {
                font-size: 2.5rem;
            }

            .action-buttons {
                grid-template-columns: 1fr 1fr;
                gap: 0.5rem;
            }

            .action-btn {
                padding: 1rem;
                font-size: 0.9rem;
            }

            .action-btn-icon {
                font-size: 1.5rem;
            }

            .transaction-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .transaction-amount {
                text-align: left;
            }

            .header-right-section {
                gap: 0.5rem;
            }

            .currency-display {
                padding: 0.4rem 0.8rem;
            }

            .currency-amount {
                font-size: 1rem;
            }

            .currency-label {
                font-size: 0.7rem;
            }

            .nav-links {
                display: none;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="navbar">
            <a href="{{ Auth::guard('student')->check() ? route('student.dashboard') : route('tutor.dashboard') }}" class="logo">
                <img src="{{ asset('images/MentorHub.png') }}" alt="MentorHub" class="logo-img">
                MentorHub
            </a>
            <nav class="nav-links">
                <a href="{{ Auth::guard('student')->check() ? route('student.dashboard') : route('tutor.dashboard') }}">Dashboard</a>
                @if(Auth::guard('student')->check())
                    <a href="{{ route('student.book-session') }}">Book Session</a>
                    <a href="{{ route('student.my-sessions') }}">Sessions</a>
                    <a href="{{ route('student.schedule') }}">Schedule</a>
                @else
                    <a href="{{ route('tutor.bookings.index') }}">My Bookings</a>
                    <a href="{{ route('tutor.students') }}">Students</a>
                    <a href="{{ route('tutor.schedule') }}">Schedule</a>
                @endif
            </nav>
            <div class="header-right-section">
                <!-- Currency Display -->
                <div class="currency-display" onclick="window.location.href='{{ Auth::guard('student')->check() ? route('student.wallet') : route('tutor.wallet') }}'">
                    <div class="currency-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="currency-info">
                        <div class="currency-amount" id="currency-amount">₱{{ number_format($wallet->balance, 2) }}</div>
                        <div class="currency-label">Balance</div>
                    </div>
                </div>
                
                <!-- Profile Dropdown -->
                <div class="profile-dropdown-container">
                    <div class="profile-icon" id="profile-icon">
                        @if(Auth::guard('student')->check())
                            @if(Auth::guard('student')->user()->profile_picture)
                                <img src="{{ asset('storage/' . Auth::guard('student')->user()->profile_picture) }}?v={{ file_exists(public_path('storage/' . Auth::guard('student')->user()->profile_picture)) ? filemtime(public_path('storage/' . Auth::guard('student')->user()->profile_picture)) : time() }}" alt="Profile Picture" class="profile-icon-img">
                            @else
                                {{ substr(Auth::guard('student')->user()->first_name, 0, 1) }}{{ substr(Auth::guard('student')->user()->last_name, 0, 1) }}
                            @endif
                        @else
                            @if(Auth::guard('tutor')->user()->profile_picture)
                                <img src="{{ asset('storage/' . Auth::guard('tutor')->user()->profile_picture) }}?{{ time() }}" alt="Profile Picture" class="profile-icon-img">
                            @else
                                {{ strtoupper(substr(Auth::guard('tutor')->user()->first_name, 0, 1) . substr(Auth::guard('tutor')->user()->last_name, 0, 1)) }}
                            @endif
                        @endif
                    </div>
                    <div class="dropdown-menu" id="dropdown-menu">
                        <a href="{{ Auth::guard('student')->check() ? route('student.profile.edit') : route('tutor.profile.edit') }}">My Profile</a>
                        <a href="#">Settings</a>
                        <a href="#">Help Center</a>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" method="POST" action="{{ Auth::guard('student')->check() ? route('student.logout') : route('tutor.logout') }}" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container">

        <div class="wallet-header">
            <div class="wallet-icon">
                <i class="fas fa-wallet"></i>
            </div>
            <h1 class="wallet-title">My Wallet</h1>
            <p class="wallet-subtitle">Manage your funds and transactions</p>
        </div>

        <div class="balance-card">
            <div class="balance-label">Current Balance</div>
            <div class="balance-amount">₱{{ number_format($wallet->balance, 2) }}</div>
            <div class="balance-currency">PHP</div>
        </div>

        <div class="action-buttons">
            <a href="{{ Auth::guard('student')->check() ? route('student.wallet.cash-in') : route('tutor.wallet.cash-in') }}" class="action-btn cash-in">
                <div class="action-btn-icon">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <div class="action-btn-text">Cash In</div>
            </a>
            <a href="{{ Auth::guard('student')->check() ? route('student.wallet.cash-out') : route('tutor.wallet.cash-out') }}" class="action-btn cash-out">
                <div class="action-btn-icon">
                    <i class="fas fa-minus-circle"></i>
                </div>
                <div class="action-btn-text">Cash Out</div>
            </a>
        </div>

        <div class="transactions-section">
            <h2 class="section-title">
                <i class="fas fa-history"></i>
                Recent Transactions
            </h2>

            @if($transactions->count() > 0)
                @foreach($transactions as $transaction)
                    <div class="transaction-item">
                        <div class="transaction-info">
                            <div class="transaction-icon {{ $transaction->type }}">
                                @if($transaction->type === 'cash_in')
                                    <i class="fas fa-arrow-down"></i>
                                @else
                                    <i class="fas fa-arrow-up"></i>
                                @endif
                            </div>
                            <div class="transaction-details">
                                <h4>{{ ucfirst(str_replace('_', ' ', $transaction->type)) }}</h4>
                                <p>{{ $transaction->description ?? 'Wallet transaction' }}</p>
                                <p>{{ $transaction->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        <div class="transaction-amount {{ $transaction->type === 'cash_in' ? 'positive' : 'negative' }}">
                            @if($transaction->type === 'cash_in')
                                +₱{{ number_format($transaction->amount, 2) }}
                            @else
                                -₱{{ number_format($transaction->amount, 2) }}
                            @endif
                            <br>
                            <span class="transaction-status status-{{ $transaction->status }}">
                                {{ $transaction->status === 'pending_approval' ? 'Pending Approval' : ucfirst(str_replace('_', ' ', $transaction->status)) }}
                            </span>
                        </div>
                    </div>
                @endforeach

                @if($transactions->hasPages())
                    <div class="mt-4">
                        {{ $transactions->links() }}
                    </div>
                @endif
            @else
                <div class="no-transactions">
                    <i class="fas fa-receipt"></i>
                    <h3>No transactions yet</h3>
                    <p>Your transaction history will appear here</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Profile dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            const profileIcon = document.getElementById('profile-icon');
            const dropdownMenu = document.getElementById('dropdown-menu');

            profileIcon.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdownMenu.classList.toggle('active');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!profileIcon.contains(e.target) && !dropdownMenu.contains(e.target)) {
                    dropdownMenu.classList.remove('active');
                }
            });

            // Close dropdown when clicking on a menu item
            dropdownMenu.addEventListener('click', function(e) {
                if (e.target.tagName === 'A') {
                    dropdownMenu.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>
