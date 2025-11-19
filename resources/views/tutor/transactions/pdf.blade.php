<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Log - {{ $tutor->getFullName() }}</title>
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

        .tutor-info {
            margin-bottom: 25px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }

        .tutor-info p {
            margin: 5px 0;
        }

        .tutor-info strong {
            color: #2d7dd2;
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

        .filters-section {
            margin-bottom: 25px;
            padding: 15px;
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            border-radius: 5px;
        }

        .filters-section p {
            margin: 3px 0;
            font-size: 11px;
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

        tbody tr:hover {
            background: #e9ecef;
        }

        .transaction-type {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            display: inline-block;
        }

        .type-session_earnings {
            background: #cfe2ff;
            color: #084298;
        }

        .type-assignment_earnings {
            background: #fff3cd;
            color: #856404;
        }

        .type-cash_in {
            background: #d1e7dd;
            color: #0f5132;
        }

        .type-cash_out {
            background: #f8d7da;
            color: #842029;
        }

        .amount-positive {
            color: #28a745;
            font-weight: bold;
        }

        .amount-negative {
            color: #dc3545;
            font-weight: bold;
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

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Transaction Log</h1>
        <div class="subtitle">MentorHub - Tutor Financial Report</div>
        <div class="subtitle" style="margin-top: 5px;">Generated on {{ now()->format('F d, Y h:i A') }}</div>
    </div>

    <div class="tutor-info">
        <p><strong>Tutor Name:</strong> {{ $tutor->getFullName() }}</p>
        <p><strong>Tutor ID:</strong> {{ $tutor->tutor_id }}</p>
        <p><strong>Email:</strong> {{ $tutor->email }}</p>
        <p><strong>Current Balance:</strong> ₱{{ number_format($wallet->balance, 2) }}</p>
    </div>

    <!-- Summary Section -->
    <div class="summary-section">
        <div class="summary-title">Earnings Summary</div>
        <table class="summary-table">
            <tr>
                <td class="summary-label">Total Earnings:</td>
                <td class="summary-value">₱{{ number_format($totalEarnings, 2) }}</td>
            </tr>
            <tr>
                <td class="summary-label">From Sessions:</td>
                <td class="summary-value">₱{{ number_format($sessionEarnings, 2) }}</td>
            </tr>
            <tr>
                <td class="summary-label">From Assignments:</td>
                <td class="summary-value">₱{{ number_format($assignmentEarnings, 2) }}</td>
            </tr>
            <tr>
                <td class="summary-label">Total Cash In:</td>
                <td class="summary-value">₱{{ number_format($totalCashIn, 2) }}</td>
            </tr>
            <tr>
                <td class="summary-label">Total Cash Out:</td>
                <td class="summary-value">₱{{ number_format($totalCashOut, 2) }}</td>
            </tr>
        </table>
    </div>

    <!-- Filters Applied -->
    @if($typeFilter !== 'all' || $statusFilter !== 'all' || $dateFrom || $dateTo)
        <div class="filters-section">
            <strong>Filters Applied:</strong>
            @if($typeFilter !== 'all')
                <p>Type: {{ ucfirst(str_replace('_', ' ', $typeFilter)) }}</p>
            @endif
            @if($statusFilter !== 'all')
                <p>Status: {{ ucfirst(str_replace('_', ' ', $statusFilter)) }}</p>
            @endif
            @if($dateFrom)
                <p>Date From: {{ \Carbon\Carbon::parse($dateFrom)->format('F d, Y') }}</p>
            @endif
            @if($dateTo)
                <p>Date To: {{ \Carbon\Carbon::parse($dateTo)->format('F d, Y') }}</p>
            @endif
        </div>
    @endif

    <!-- Transactions Table -->
    <div class="transactions-section">
        <div class="section-title">Transaction Details</div>
        
        @if($transactions->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th style="width: 15%;">Date & Time</th>
                        <th style="width: 12%;">Type</th>
                        <th style="width: 25%;">Description</th>
                        <th style="width: 10%;">Amount</th>
                        <th style="width: 12%;">Balance Before</th>
                        <th style="width: 12%;">Balance After</th>
                        <th style="width: 10%;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->created_at->format('M d, Y h:i A') }}</td>
                            <td>
                                <span class="transaction-type type-{{ $transaction->type }}">
                                    @if($transaction->type === 'session_earnings')
                                        Session
                                    @elseif($transaction->type === 'assignment_earnings')
                                        Assignment
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
                                    <br><small>Session ID: {{ $transaction->metadata['session_id'] }}</small>
                                @endif
                                @if(isset($transaction->metadata['assignment_id']))
                                    <br><small>Assignment ID: {{ $transaction->metadata['assignment_id'] }}</small>
                                @endif
                            </td>
                            <td class="{{ in_array($transaction->type, ['session_earnings', 'assignment_earnings', 'cash_in']) ? 'amount-positive' : 'amount-negative' }}">
                                {{ in_array($transaction->type, ['cash_out']) ? '-' : '+' }}₱{{ number_format($transaction->amount, 2) }}
                            </td>
                            <td>₱{{ number_format($transaction->balance_before, 2) }}</td>
                            <td>₱{{ number_format($transaction->balance_after, 2) }}</td>
                            <td>
                                <span class="status-badge status-{{ $transaction->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $transaction->status)) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 20px; font-size: 11px; color: #666;">
                <strong>Total Transactions:</strong> {{ $transactions->count() }}
            </div>
        @else
            <div class="no-transactions">
                <p>No transactions found for the selected filters.</p>
            </div>
        @endif
    </div>

    <div class="footer">
        <p>This is an automated report generated by MentorHub.</p>
        <p>For inquiries, please contact: MentorHub.Website@gmail.com</p>
        <p style="margin-top: 10px;">© {{ date('Y') }} MentorHub. All rights reserved.</p>
    </div>
</body>
</html>

