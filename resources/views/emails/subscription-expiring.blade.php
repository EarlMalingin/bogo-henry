<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Expiring Soon</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #4a90e2;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #4a90e2;
            margin-bottom: 10px;
        }
        .alert-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .alert-box h2 {
            color: #856404;
            margin-top: 0;
            font-size: 24px;
        }
        .info-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #666;
        }
        .info-value {
            color: #333;
        }
        .cta-button {
            display: inline-block;
            background-color: #4a90e2;
            color: #ffffff;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
            font-weight: 600;
        }
        .cta-button:hover {
            background-color: #357abd;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            color: #666;
            font-size: 14px;
        }
        .days-remaining {
            font-size: 32px;
            font-weight: bold;
            color: #ff6b6b;
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">🎓 MentorHub</div>
            <p style="color: #666; margin: 0;">Your Learning Platform</p>
        </div>

        <div class="alert-box">
            <h2>⏰ Monthly Subscription Expiring Soon!</h2>
            <div class="days-remaining">
                {{ $daysRemaining }} {{ $daysRemaining == 1 ? 'Day' : 'Days' }} Remaining
            </div>
            <p style="margin: 0; color: #856404;">
                Your monthly subscription is about to expire. Renew now to continue your learning journey without interruption!
            </p>
        </div>

        <div class="info-section">
            <div class="info-row">
                <span class="info-label">Student:</span>
                <span class="info-value">{{ $student->first_name }} {{ $student->last_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tutor:</span>
                <span class="info-value">{{ $tutor->first_name }} {{ $tutor->last_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Subject:</span>
                <span class="info-value">{{ $tutor->specialization ? explode(',', $tutor->specialization)[0] : 'General Tutoring' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Subscription Ends:</span>
                <span class="info-value" style="color: #ff6b6b; font-weight: 600;">{{ $endDate->format('l, F j, Y') }}</span>
            </div>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('/student/book-session') }}" class="cta-button">
                Renew Subscription Now
            </a>
        </div>

        <div style="background-color: #e3f2fd; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <p style="margin: 0; color: #1976d2;">
                <strong>💡 Tip:</strong> Renewing your subscription before it expires ensures uninterrupted access to your tutor's expertise and learning materials.
            </p>
        </div>

        <div class="footer">
            <p style="margin: 5px 0;">This is an automated notification from MentorHub.</p>
            <p style="margin: 5px 0;">If you have any questions, please contact our support team.</p>
            <p style="margin: 10px 0; color: #999; font-size: 12px;">
                © {{ date('Y') }} MentorHub. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>

