<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - MentorHub Wallet</title>
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

        .payment-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
        }

        .payment-header {
            margin-bottom: 2rem;
        }

        .payment-icon {
            font-size: 3rem;
            color: #4CAF50;
            margin-bottom: 1rem;
        }

        .payment-title {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .payment-subtitle {
            color: #666;
        }

        .amount-display {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .amount-label {
            font-size: 1rem;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .amount-value {
            font-size: 2.5rem;
            font-weight: bold;
            color: #4CAF50;
        }

        .qr-section {
            margin: 2rem 0;
            text-align: center;
        }

        .qr-code {
            width: auto;
            height: auto;
            margin: 0 auto 1.5rem;
            background: transparent;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            position: relative;
            padding: 1rem;
        }

        .qr-code img {
            max-width: 300px;
            width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            display: block;
        }

        .qr-code canvas {
            border-radius: 8px;
        }

        .qr-placeholder {
            font-size: 3rem;
            color: #4CAF50;
        }

        .payment-instructions {
            background: #e3f2fd;
            border-left: 4px solid #2196F3;
            padding: 1rem;
            margin-bottom: 2rem;
            border-radius: 0 10px 10px 0;
            text-align: left;
        }

        .instructions-title {
            font-weight: 600;
            color: #1976D2;
            margin-bottom: 0.5rem;
        }

        .instructions-list {
            list-style: none;
            padding: 0;
        }

        .instructions-list li {
            padding: 0.25rem 0;
            color: #666;
        }

        .instructions-list li:before {
            content: "✓ ";
            color: #4CAF50;
            font-weight: bold;
        }

        .payment-actions {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
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

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .status-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 1rem;
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 10px;
            color: #856404;
            margin: 0 0 2rem 0;
        }

        .status-indicator i {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }

        .countdown {
            font-size: 1.2rem;
            font-weight: bold;
            color: #FF9800;
            margin-top: 1rem;
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

        /* Modal Styles */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 12px;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }

        .modal h3 {
            margin: 0 0 15px;
            color: #1f2d3d;
            font-size: 20px;
        }

        .modal p {
            margin: 0 0 20px;
            color: #6b7280;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #374151;
        }

        .form-group input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            background: #f9fafb;
            cursor: pointer;
        }

        .form-group input[type="file"]:hover {
            border-color: #4a90e2;
            background: #f0f7ff;
        }

        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            resize: vertical;
            font-family: inherit;
        }

        .form-group small {
            display: block;
            margin-top: 5px;
            color: #6b7280;
            font-size: 12px;
        }

        .modal-buttons {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 0.5rem;
            }

            .payment-card {
                padding: 1.5rem;
            }

            .qr-code {
                width: auto;
                height: auto;
                padding: 0.5rem;
            }

            .qr-code img {
                max-width: 250px;
            }

            .amount-value {
                font-size: 2rem;
            }

            .payment-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="payment-card">
            <div class="payment-header">
                <div class="payment-icon">
                    <i class="fas fa-qrcode"></i>
                </div>
                <h1 class="payment-title">Complete Payment</h1>
                <p class="payment-subtitle">Scan QR code with GCash to complete your payment</p>
            </div>

            <div class="amount-display">
                <div class="amount-label">Amount to Pay</div>
                <div class="amount-value">₱{{ number_format($transaction->amount, 2) }}</div>
            </div>

            <div class="status-indicator">
                <i class="fas fa-clock"></i>
                <span>Waiting for payment...</span>
            </div>

            <div class="qr-section">
                <div class="qr-code" id="qr-code">
                    <img src="{{ asset('images/QR-Code.png') }}" alt="GCash QR Code">
                </div>
                <p style="font-size: 1rem; color: #666; margin-bottom: 1rem;">Scan this QR code with your GCash app</p>
                @if(isset($checkout_url) && $checkout_url)
                    <div style="margin-top: 1rem;">
                        <a href="{{ $checkout_url }}" target="_blank" class="btn btn-primary" style="display: inline-block; padding: 0.5rem 1rem; font-size: 0.9rem;">
                            <i class="fas fa-external-link-alt"></i>
                            Open GCash Payment Page
                        </a>
                    </div>
                @endif
            </div>

            <div class="payment-instructions">
                <div class="instructions-title">
                    <i class="fas fa-info-circle"></i>
                    How to pay with GCash:
                </div>
                <ul class="instructions-list">
                    <li>Open your GCash mobile app</li>
                    <li>Tap "Scan QR" or "Pay Bills"</li>
                    <li>Scan the QR code above</li>
                    <li>Enter the amount: ₱{{ number_format($transaction->amount, 2) }}</li>
                    <li>Confirm your payment</li>
                    <li><strong>After payment:</strong> Upload a screenshot of your payment confirmation</li>
                </ul>
                
                <div style="background:#fff3cd;border:1px solid #ffeaa7;border-radius:8px;padding:12px;margin-top:15px;">
                    <strong>⚠️ Important:</strong> After completing your payment, you must upload a screenshot of your GCash payment confirmation. Your transaction will remain pending until admin approval.
                </div>
            </div>

            <div class="payment-actions">
                <a href="{{ $userType === 'student' ? route('student.wallet') : route('tutor.wallet') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Cancel Payment
                </a>
                <button class="btn btn-primary" onclick="showPaymentProofModal()">
                    <i class="fas fa-camera"></i>
                    Upload Payment Proof
                </button>
            </div>

            <!-- Payment Proof Upload Modal -->
            <div id="paymentProofModal" class="modal" style="display:none;">
                <div class="modal-content">
                    <h3>Upload Payment Proof</h3>
                    <p>Please upload a screenshot of your payment confirmation from GCash.</p>
                    
                    <form id="paymentProofForm" method="POST" action="{{ $userType === 'student' ? route('student.wallet.upload-payment-proof') : route('tutor.wallet.upload-payment-proof') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
                        
                        <div class="form-group">
                            <label for="payment_proof">Payment Screenshot:</label>
                            <input type="file" id="payment_proof" name="payment_proof" accept="image/*" required>
                            <small>Upload a screenshot showing your payment confirmation (Max: 5MB)</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Additional Notes (Optional):</label>
                            <textarea id="description" name="description" rows="3" placeholder="Any additional information about your payment..."></textarea>
                        </div>
                        
                        <div class="modal-buttons">
                            <button type="button" onclick="hidePaymentProofModal()" class="btn btn-secondary">Cancel</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-upload"></i>
                                Upload Proof
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="countdown" id="countdown">
                This page will auto-refresh in <span id="timer">30</span> seconds
            </div>

            <div class="loading" id="loading">
                <i class="fas fa-spinner"></i>
                <p>Checking payment status...</p>
            </div>
        </div>
    </div>

    <script>
        let countdown = 30;
        let timerInterval;

        document.addEventListener('DOMContentLoaded', function() {
            startCountdown();
            
            // Auto-refresh every 30 seconds
            setInterval(function() {
                checkPaymentStatus();
            }, 30000);
        });

        function startCountdown() {
            timerInterval = setInterval(function() {
                countdown--;
                document.getElementById('timer').textContent = countdown;
                
                if (countdown <= 0) {
                    clearInterval(timerInterval);
                    checkPaymentStatus();
                }
            }, 1000);
        }

        function checkPaymentStatus() {
            const loading = document.getElementById('loading');
            const submitBtn = document.querySelector('.btn-primary');
            
            loading.style.display = 'block';
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Checking...';
            
            // Simulate checking payment status
            // In a real implementation, you would make an AJAX call to check the payment status
            setTimeout(function() {
                // For demo purposes, redirect to success page after 3 seconds
                // In production, this would check the actual payment status
                window.location.href = "{{ route('wallet.payment.success') }}?payment_intent_id={{ $transaction->paymongo_payment_intent_id }}";
            }, 3000);
        }

        function showPaymentProofModal() {
            document.getElementById('paymentProofModal').style.display = 'flex';
        }

        function hidePaymentProofModal() {
            document.getElementById('paymentProofModal').style.display = 'none';
            document.getElementById('paymentProofForm').reset();
        }

        // Close modal when clicking outside
        document.getElementById('paymentProofModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hidePaymentProofModal();
            }
        });
    </script>
</body>
</html>
