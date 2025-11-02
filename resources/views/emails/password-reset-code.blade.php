<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Code - MentorHub</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #2d7dd2, #4a3dd9);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 40px 30px;
        }
        .verification-code {
            background: linear-gradient(135deg, #4a90e2, #357abd);
            color: white;
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            margin: 30px 0;
            letter-spacing: 5px;
            font-family: 'Courier New', monospace;
        }
        .instructions {
            background-color: #f8f9fa;
            border-left: 4px solid #4a90e2;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 5px 5px 0;
        }
        .instructions h3 {
            margin: 0 0 10px 0;
            color: #2d7dd2;
        }
        .instructions ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .instructions li {
            margin: 5px 0;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        .footer p {
            margin: 5px 0;
            font-size: 14px;
            color: #6c757d;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #4a90e2, #357abd);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            margin: 20px 0;
        }
        .button:hover {
            background: linear-gradient(135deg, #357abd, #2d7dd2);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>MentorHub</h1>
            <p>Password Reset Request</p>
        </div>
        
        <div class="content">
            <h2>Hello!</h2>
            <p>We received a request to reset your password for your MentorHub account. To proceed with the password reset, please use the verification code below:</p>
            
            <div class="verification-code">
                {{ $code }}
            </div>
            
            <div class="instructions">
                <h3>How to reset your password:</h3>
                <ul>
                    <li>Copy the verification code above</li>
                    <li>Go back to the password reset page</li>
                    <li>Enter the 6-digit code in the verification field</li>
                    <li>Set your new password</li>
                </ul>
            </div>
            
            <div class="warning">
                <strong>Important:</strong> This verification code will expire in 15 minutes for security reasons. If you don't use it within this time, you'll need to request a new code.
            </div>
            
            <p>If you didn't request a password reset, please ignore this email and your account will remain secure.</p>
            
            <p>Need help? Contact our support team at <a href="mailto:MentorHub.Website@gmail.com">MentorHub.Website@gmail.com</a></p>
        </div>
        
        <div class="footer">
            <p><strong>MentorHub</strong> - Connecting Students with Expert Tutors</p>
            <p>University of Cebu, Cebu City, Philippines</p>
            <p>© {{ date('Y') }} MentorHub. All rights reserved.</p>
        </div>
    </div>
</body>
</html> 