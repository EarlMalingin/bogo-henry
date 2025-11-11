<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cash In - MentorHub Wallet</title>
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
                url('{{ asset('images/Uc-background.jpg') }}');
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
            max-width: 600px;
            margin: 0 auto;
            padding: 0 1rem 2rem 1rem;
            margin-top: 100px;
        }


        .form-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .form-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-icon {
            font-size: 3rem;
            color: #4CAF50;
            margin-bottom: 1rem;
        }

        .form-title {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .form-subtitle {
            color: #666;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 1rem;
            border: 2px solid #eee;
            border-radius: 10px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #4CAF50;
        }

        .amount-input {
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
        }

        .amount-presets {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .amount-preset {
            padding: 0.75rem;
            border: 2px solid #eee;
            border-radius: 10px;
            background: white;
            cursor: pointer;
            text-align: center;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .amount-preset:hover {
            border-color: #4CAF50;
            background: #f8f9fa;
        }

        .amount-preset.active {
            border-color: #4CAF50;
            background: #4CAF50;
            color: white;
        }

        .payment-method {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .payment-method-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .payment-method-icon {
            font-size: 1.5rem;
            color: #4CAF50;
        }

        .payment-method-title {
            font-weight: 600;
            color: #333;
        }

        .payment-method-desc {
            color: #666;
            font-size: 0.9rem;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
        }

        .btn {
            flex: 1;
            padding: 1rem;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
            display: inline-block;
        }

                .btn-primary {
                    background: #4CAF50;
                    color: white;
                }

                .btn-primary:hover {
                    background: #45a049;
                    transform: translateY(-2px);
                }

                .btn-success {
                    background: #28a745;
                    color: white;
                }

                .btn-success:hover {
                    background: #218838;
                    transform: translateY(-2px);
                }

                .btn-secondary {
                    background: #6c757d;
                    color: white;
                }

                .btn-secondary:hover {
                    background: #5a6268;
                }

        .info-box {
            background: #e3f2fd;
            border-left: 4px solid #2196F3;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 0 10px 10px 0;
        }

        .info-box-title {
            font-weight: 600;
            color: #1976D2;
            margin-bottom: 0.5rem;
        }

        .info-box-text {
            color: #666;
            font-size: 0.9rem;
        }

        .loading {
            display: none;
            text-align: center;
            padding: 2rem;
        }

        .loading i {
            font-size: 2rem;
            color: #4CAF50;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 0.5rem;
            }

            .form-card {
                padding: 1.5rem;
            }

            .amount-presets {
                grid-template-columns: repeat(2, 1fr);
            }

            .form-actions {
                flex-direction: column;
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
            <a href="{{ $userType === 'student' ? route('student.dashboard') : route('tutor.dashboard') }}" class="logo">
                <img src="{{ asset('images/MentorHub.png') }}" alt="MentorHub" class="logo-img">
                MentorHub
            </a>
            <nav class="nav-links">
                <a href="{{ $userType === 'student' ? route('student.dashboard') : route('tutor.dashboard') }}">Dashboard</a>
                @if($userType === 'student')
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
                <div class="currency-display" onclick="window.location.href='{{ $userType === 'student' ? route('student.wallet') : route('tutor.wallet') }}'">
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
                        @if($userType === 'student')
                            @if($user->profile_picture)
                                <img src="{{ asset('storage/' . $user->profile_picture) }}?v={{ file_exists(public_path('storage/' . $user->profile_picture)) ? filemtime(public_path('storage/' . $user->profile_picture)) : time() }}" alt="Profile Picture" class="profile-icon-img">
                            @else
                                {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                            @endif
                        @else
                            @if($user->profile_picture)
                                <img src="{{ asset('storage/' . $user->profile_picture) }}?{{ time() }}" alt="Profile Picture" class="profile-icon-img">
                            @else
                                {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                            @endif
                        @endif
                    </div>
                    <div class="dropdown-menu" id="dropdown-menu">
                        <a href="{{ $userType === 'student' ? route('student.profile.edit') : route('tutor.profile.edit') }}">My Profile</a>
                        <a href="{{ $userType === 'student' ? route('student.settings') : route('tutor.settings') }}">Achievements</a>
                        <a href="#">Report a Problem</a>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" method="POST" action="{{ $userType === 'student' ? route('student.logout') : route('tutor.logout') }}" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container">

        <div class="form-card">
            <div class="form-header">
                <div class="form-icon">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <h1 class="form-title">Cash In</h1>
                <p class="form-subtitle">Add funds to your wallet using GCash</p>
            </div>

            <div class="info-box">
                <div class="info-box-title">
                    <i class="fas fa-info-circle"></i>
                    How it works
                </div>
            <div class="info-box-text">
                <strong>Smart Cash-In System:</strong><br>
                • <strong>Pay with GCash:</strong> Processed via GCash payment gateway (any amount)
            </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 10px; margin-bottom: 1rem; border: 1px solid #c3e6cb;">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error" style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 10px; margin-bottom: 1rem; border: 1px solid #f5c6cb;">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            @endif

            <form id="cashInForm" method="POST" action="{{ $userType === 'student' ? route('student.wallet.cash-in.submit') : route('tutor.wallet.cash-in.submit') }}">
                @csrf
                <input type="hidden" name="payment_method" id="paymentMethod" value="gcash">
                
                <div class="form-group">
                    <label for="amount" class="form-label">Amount (PHP)</label>
                    <input type="number" 
                           id="amount" 
                           name="amount" 
                           class="form-input amount-input" 
                           placeholder="0.00" 
                           min="0.01" 
                           max="50000" 
                           step="0.01" 
                           required>
                    
                    <div class="amount-presets">
                        <div class="amount-preset" data-amount="1">₱1</div>
                        <div class="amount-preset" data-amount="5">₱5</div>
                        <div class="amount-preset" data-amount="10">₱10</div>
                        <div class="amount-preset" data-amount="50">₱50</div>
                        <div class="amount-preset" data-amount="100">₱100</div>
                        <div class="amount-preset" data-amount="500">₱500</div>
                        <div class="amount-preset" data-amount="1000">₱1,000</div>
                        <div class="amount-preset" data-amount="2000">₱2,000</div>
                        <div class="amount-preset" data-amount="5000">₱5,000</div>
                        <div class="amount-preset" data-amount="10000">₱10,000</div>
                    </div>
                </div>

                <div class="payment-method">
                    <div class="payment-method-header">
                        <i class="fas fa-mobile-alt payment-method-icon"></i>
                        <span class="payment-method-title">GCash Payment</span>
                    </div>
                    <div class="payment-method-desc">
                        Pay securely using your GCash account via QR code or mobile app
                    </div>
                </div>

                <div style="text-align: center; margin: 2rem 0; padding: 1.5rem; background: #f8f9fa; border-radius: 10px;">
                    <p style="font-weight: 600; color: #333; margin-bottom: 1rem;">Scan QR Code to Pay</p>
                    <div style="display: flex; justify-content: center; margin-bottom: 1rem;">
                        <img src="{{ asset('images/QR-Code.png') }}" alt="GCash QR Code" style="max-width: 300px; width: 100%; height: auto; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    </div>
                    <p style="font-size: 0.9rem; color: #666; margin-top: 1rem;">
                        Transfer fees may apply.
                    </p>
                </div>

                <div class="form-actions">
                    <a href="{{ $userType === 'student' ? route('student.wallet') : route('tutor.wallet') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-credit-card"></i>
                        Pay with GCash
                    </button>
                </div>
            </form>

            <div class="loading" id="loading">
                <i class="fas fa-spinner"></i>
                <p>Processing payment...</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('cashInForm');
            const amountInput = document.getElementById('amount');
            const presets = document.querySelectorAll('.amount-preset');
            const submitBtn = document.getElementById('submitBtn');
            const loading = document.getElementById('loading');

            // Handle preset amount clicks
            presets.forEach(preset => {
                preset.addEventListener('click', function() {
                    // Remove active class from all presets
                    presets.forEach(p => p.classList.remove('active'));
                    
                    // Add active class to clicked preset
                    this.classList.add('active');
                    
                    // Set amount input value
                    amountInput.value = this.dataset.amount;
                });
            });

            // Handle form submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const amount = parseFloat(amountInput.value);
                
                
                if (!amount || amount < 0.01) {
                    alert('Please enter a valid amount (minimum ₱0.01)');
                    amountInput.focus();
                    return;
                }
                
                if (amount > 50000) {
                    alert('Maximum amount is ₱50,000');
                    amountInput.focus();
                    return;
                }
                
                // Show loading
                form.style.display = 'none';
                loading.style.display = 'block';
                
                // Submit form after a short delay to show loading
                setTimeout(() => {
                    this.submit();
                }, 500);
            });

            // Handle amount input change
            amountInput.addEventListener('input', function() {
                const value = parseFloat(this.value);
                
                // Remove active class from all presets
                presets.forEach(p => p.classList.remove('active'));
                
                // Check if input matches any preset
                presets.forEach(preset => {
                    if (parseFloat(preset.dataset.amount) === value) {
                        preset.classList.add('active');
                    }
                });
            });

            // Handle submit button click (GCash payment)
            submitBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const amount = parseFloat(amountInput.value);
                
                if (!amount || amount < 0.01) {
                    alert('Please enter a valid amount (minimum ₱0.01)');
                    amountInput.focus();
                    return;
                }
                
                if (amount > 50000) {
                    alert('Maximum amount is ₱50,000');
                    amountInput.focus();
                    return;
                }
                
                // Set payment method to GCash
                document.getElementById('paymentMethod').value = 'gcash';
                
                // Show loading
                form.style.display = 'none';
                loading.style.display = 'block';
                loading.querySelector('p').textContent = 'Processing GCash payment...';
                
                // Submit form after a short delay to show loading
                setTimeout(() => {
                    form.submit();
                }, 500);
            });
        });

        // Profile dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            const profileIcon = document.getElementById('profile-icon');
            const dropdownMenu = document.getElementById('dropdown-menu');

            if (profileIcon && dropdownMenu) {
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
            }
        });
    </script>
</body>
</html>
