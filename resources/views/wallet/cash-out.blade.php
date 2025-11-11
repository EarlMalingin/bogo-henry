<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cash Out - MentorHub Wallet</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: white;
            text-decoration: none;
            margin-bottom: 2rem;
            transition: color 0.3s ease;
        }

        .back-btn:hover {
            color: #f0f0f0;
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
            color: #FF9800;
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

        .balance-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .balance-label {
            font-size: 1rem;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .balance-amount {
            font-size: 2rem;
            font-weight: bold;
            color: #4CAF50;
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
            border-color: #FF9800;
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
            border-color: #FF9800;
            background: #f8f9fa;
        }

        .amount-preset.active {
            border-color: #FF9800;
            background: #FF9800;
            color: white;
        }

        .amount-preset.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .gcash-info {
            background: #e3f2fd;
            border-left: 4px solid #2196F3;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 0 10px 10px 0;
        }

        .gcash-info-title {
            font-weight: 600;
            color: #1976D2;
            margin-bottom: 0.5rem;
        }

        .gcash-info-text {
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
            background: #FF9800;
            color: white;
        }

        .btn-primary:hover {
            background: #f57c00;
            transform: translateY(-2px);
        }

        .btn-primary:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 0 10px 10px 0;
        }

        .warning-title {
            font-weight: 600;
            color: #856404;
            margin-bottom: 0.5rem;
        }

        .warning-text {
            color: #856404;
            font-size: 0.9rem;
        }

        .loading {
            display: none;
            text-align: center;
            padding: 2rem;
        }

        .loading i {
            font-size: 2rem;
            color: #FF9800;
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
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ $userType === 'student' ? route('student.wallet') : route('tutor.wallet') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Wallet
        </a>

        <div class="form-card">
            <div class="form-header">
                <div class="form-icon">
                    <i class="fas fa-minus-circle"></i>
                </div>
                <h1 class="form-title">Cash Out</h1>
                <p class="form-subtitle">Withdraw funds to your GCash account</p>
            </div>

            <div class="balance-info">
                <div class="balance-label">Available Balance</div>
                <div class="balance-amount">₱{{ number_format($wallet->balance, 2) }}</div>
            </div>

            <div class="warning-box">
                <div class="warning-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    Important Notice
                </div>
                <div class="warning-text">
                    <strong>Processing Fee:</strong> A 10% deduction will be applied to every cash out request.<br>
                    Cash out requests are processed within 24 hours. Please ensure your GCash account details are correct.
                </div>
            </div>

            <form id="cashOutForm" method="POST" action="{{ $userType === 'student' ? route('student.wallet.cash-out.submit') : route('tutor.wallet.cash-out.submit') }}">
                @csrf
                
                <div class="form-group">
                    <label for="amount" class="form-label">Amount to Withdraw (PHP)</label>
                    <input type="number" 
                           id="amount" 
                           name="amount" 
                           class="form-input amount-input" 
                           placeholder="0.00" 
                           min="1" 
                           max="{{ $wallet->balance }}" 
                           step="0.01" 
                           required>
                    
                    <div class="amount-presets">
                        <div class="amount-preset" data-amount="100">₱100</div>
                        <div class="amount-preset" data-amount="500">₱500</div>
                        <div class="amount-preset" data-amount="1000">₱1,000</div>
                        <div class="amount-preset" data-amount="2000">₱2,000</div>
                        <div class="amount-preset" data-amount="5000">₱5,000</div>
                        <div class="amount-preset" data-amount="{{ $wallet->balance }}">All</div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="account_number" class="form-label">GCash Mobile Number</label>
                    <input type="tel" 
                           id="account_number" 
                           name="account_number" 
                           class="form-input" 
                           placeholder="09XXXXXXXXX" 
                           pattern="09[0-9]{9}" 
                           maxlength="11" 
                           required>
                </div>

                <div class="form-group">
                    <label for="account_name" class="form-label">Account Name</label>
                    <input type="text" 
                           id="account_name" 
                           name="account_name" 
                           class="form-input" 
                           placeholder="Enter the name registered with GCash" 
                           required>
                </div>

                <div class="gcash-info">
                    <div class="gcash-info-title">
                        <i class="fas fa-mobile-alt"></i>
                        GCash Account Requirements
                    </div>
                    <div class="gcash-info-text">
                        Make sure your GCash account is fully verified and active. The mobile number should match your GCash account.
                    </div>
                </div>

                <div style="text-align: center; margin: 2rem 0; padding: 1.5rem; background: #f8f9fa; border-radius: 10px;">
                    <p style="font-weight: 600; color: #333; margin-bottom: 1rem;">GCash QR Code Reference</p>
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
                        <i class="fas fa-paper-plane"></i>
                        Submit Request
                    </button>
                </div>
            </form>

            <div class="loading" id="loading">
                <i class="fas fa-spinner"></i>
                <p>Processing cash out request...</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('cashOutForm');
            const amountInput = document.getElementById('amount');
            const presets = document.querySelectorAll('.amount-preset');
            const submitBtn = document.getElementById('submitBtn');
            const loading = document.getElementById('loading');
            const maxAmount = {{ $wallet->balance }};

            // Update preset availability based on balance
            presets.forEach(preset => {
                const amount = parseFloat(preset.dataset.amount);
                if (amount > maxAmount) {
                    preset.classList.add('disabled');
                    preset.style.opacity = '0.5';
                    preset.style.cursor = 'not-allowed';
                }
            });

            // Handle preset amount clicks
            presets.forEach(preset => {
                preset.addEventListener('click', function() {
                    if (this.classList.contains('disabled')) {
                        return;
                    }
                    
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
                const accountNumber = document.getElementById('account_number').value;
                const accountName = document.getElementById('account_name').value;
                
                if (!amount || amount < 1) {
                    alert('Please enter a valid amount (minimum ₱1)');
                    return;
                }
                
                if (amount > maxAmount) {
                    alert('Amount cannot exceed your available balance');
                    return;
                }
                
                if (!accountNumber.match(/^09[0-9]{9}$/)) {
                    alert('Please enter a valid mobile number (09XXXXXXXXX)');
                    return;
                }
                
                if (!accountName.trim()) {
                    alert('Please enter the account name');
                    return;
                }
                
                // Show loading
                form.style.display = 'none';
                loading.style.display = 'block';
                
                // Submit form
                this.submit();
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

            // Format mobile number input
            document.getElementById('account_number').addEventListener('input', function() {
                let value = this.value.replace(/\D/g, '');
                if (value.length > 11) {
                    value = value.substring(0, 11);
                }
                this.value = value;
            });
        });
    </script>
</body>
</html>
