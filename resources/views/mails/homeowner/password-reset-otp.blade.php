<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background-color: #f9f9f9;
            border-radius: 10px;
            padding: 30px;
            border: 1px solid #ddd;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #2c3e50;
            margin: 0;
        }
        .otp-box {
            background-color: #fff;
            border: 2px dashed #3498db;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            color: #e74c3c;
            letter-spacing: 8px;
            margin: 10px 0;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔒 Password Reset Request</h1>
        </div>
        
        <p>Hello {{ $userName }},</p>
        
        <p>We received a request to reset your password. Use the following One-Time Password (OTP) to proceed with resetting your password:</p>
        
        <div class="otp-box">
            <p style="margin: 0; color: #666;">Your OTP Code:</p>
            <div class="otp-code">{{ $otp }}</div>
            <p style="margin: 10px 0 0 0; color: #666; font-size: 14px;">This code will expire in {{ $expiresIn }} minutes</p>
        </div>
        
        <div class="warning">
            <strong>⚠️ Security Notice:</strong>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>Never share this OTP with anyone</li>
                <li>We will never ask for this code via phone or email</li>
                <li>If you didn't request this, please ignore this email</li>
                <li>Your password will remain unchanged unless you complete the reset process</li>
            </ul>
        </div>
        
        <p>If you didn't request a password reset, you can safely ignore this email. Your account remains secure.</p>
        
        <div class="footer">
            <p>This is an automated email. Please do not reply to this message.</p>
            <p>&copy; {{ date('Y') }} BiziTrust. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
