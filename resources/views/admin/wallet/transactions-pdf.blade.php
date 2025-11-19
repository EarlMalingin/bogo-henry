<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Transaction Log - MentorHub</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #2d7dd2;
        }

        .header h1 {
            font-size: 24px;
            color: #2d7dd2;
            margin-bottom: 10px;
        }

        .header .subtitle {
            font-size: 14px;
            color: #666;
        }

        .summary-section {
            margin-bottom: 25px;
        }

        .summary-title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #dee2e6;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .summary-table td {
            padding: 10px;
            border: 1px solid #dee2e6;
            background: #f8f9fa;
        }

        .summary-label {
            font-weight: bold;
            color: #666;
            width: 40%;
        }

        .summary-value {
            color: #333;
            text-align: right;
            font-weight: bold;
        }

        .transactions-section {
            margin-top: 30px;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #2d7dd2;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
        }

        thead {
            background: #2d7dd2;
            color: white;
        }

        th {
            padding: 10px 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #1a5fa0;
        }

        td {
            padding: 8px;
            border: 1px solid #dee2e6;
        }

        tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        .transaction-type {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            display: inline-block;
        }

        .type-cash_in {
            background: #d1e7dd;
            color: #0f5132;
        }

        .type-cash_out {
            background: #f8d7da;
            color: #842029;
        }

        .type-session_booking {
            background: #cfe2ff;
            color: #084298;
        }

        .type-session_earnings {
            background: #cfe2ff;
            color: #084298;
        }

        .type-assignment_payment {
            background: #fff3cd;
            color: #856404;
        }

        .type-assignment_earnings {
            background: #fff3cd;
            color: #856404;
        }

        .type-refund {
            background: #e7f3ff;
            color: #004085;
        }

        .type-manual_add {
            background: #d1e7dd;
            color: #0f5132;
        }

        .type-manual_deduct {
            background: #f8d7da;
            color: #842029;
        }

        .status-badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            display: inline-block;
        }

        .status-completed {
            background: #d1e7dd;
            color: #0f5132;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-failed {
            background: #f8d7da;
            color: #842029;
        }

        .status-pending_approval {
            background: #cfe2ff;
            color: #084298;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #dee2e6;
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .no-transactions {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Admin Transaction Log</h1>
        <div class="subtitle">MentorHub - All Wallet Transactions</div>
        <div class="subtitle" style="margin-top: 5px;">Generated on {{ now()->format('F d, Y h:i A') }}</div>
    </div>

    <!-- Summary Section -->
    <div class="summary-section">
        <div class="summary-title">Transaction Summary</div>
        <table class="summary-table">
            <tr>
                <td class="summary-label">Total Transactions:</td>
                <td class="summary-value">{{ number_format($totalTransactions) }}</td>
            </tr>
            <tr>
                <td class="summary-label">Total Cash In:</td>
                <td class="summary-value">₱{{ number_format($totalCashIn, 2) }}</td>
            </tr>
            <tr>
                <td class="summary-label">Total Cash Out:</td>
                <td class="summary-value">₱{{ number_format($totalCashOut, 2) }}</td>
            </tr>
            <tr>
                <td class="summary-label">Total Session Bookings:</td>
                <td class="summary-value">₱{{ number_format($totalSessionBookings, 2) }}</td>
            </tr>
            <tr>
                <td class="summary-label">Total Session Earnings:</td>
                <td class="summary-value">₱{{ number_format($totalSessionEarnings, 2) }}</td>
            </tr>
            <tr>
                <td class="summary-label">Total Assignment Payments:</td>
                <td class="summary-value">₱{{ number_format($totalAssignmentPayments, 2) }}</td>
            </tr>
            <tr>
                <td class="summary-label">Total Assignment Earnings:</td>
                <td class="summary-value">₱{{ number_format($totalAssignmentEarnings, 2) }}</td>
            </tr>
        </table>
    </div>

    <!-- Transactions Table -->
    <div class="transactions-section">
        <div class="section-title">Transaction Details</div>
        
        @if($transactions->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th style="width: 8%;">ID</th>
                        <th style="width: 15%;">Date & Time</th>
                        <th style="width: 12%;">Type</th>
                        <th style="width: 20%;">Description</th>
                        <th style="width: 10%;">Amount</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 15%;">User</th>
                        <th style="width: 10%;">Balance After</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                        <tr>
                            <td>#{{ $transaction->id }}</td>
                            <td>{{ $transaction->created_at->format('M d, Y h:i A') }}</td>
                            <td>
                                <span class="transaction-type type-{{ $transaction->type }}">
                                    {{ ucfirst(str_replace('_', ' ', $transaction->type)) }}
                                </span>
                            </td>
                            <td>
                                {{ $transaction->description ?? 'Transaction' }}
                            </td>
                            <td>₱{{ number_format($transaction->amount, 2) }}</td>
                            <td>
                                <span class="status-badge status-{{ $transaction->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $transaction->status)) }}
                                </span>
                            </td>
                            <td>
                                @if($transaction->wallet)
                                    {{ $transaction->wallet->user_full_name ?? ucfirst($transaction->wallet->user_type) . ' #' . $transaction->wallet->user_id }}
                                    <br><small style="color:#666;">({{ ucfirst($transaction->wallet->user_type) }})</small>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>₱{{ number_format($transaction->balance_after ?? 0, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 20px; font-size: 11px; color: #666;">
                <strong>Total Transactions:</strong> {{ $transactions->count() }}
            </div>
        @else
            <div class="no-transactions">
                <p>No transactions found.</p>
            </div>
        @endif
    </div>

    <div class="footer">
        <p>This is an automated report generated by MentorHub Admin Panel.</p>
        <p>For inquiries, please contact: MentorHub.Website@gmail.com</p>
        <p style="margin-top: 10px;">© {{ date('Y') }} MentorHub. All rights reserved.</p>
    </div>
</body>
</html>

