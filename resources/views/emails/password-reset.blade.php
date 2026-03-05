
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Code</title>
    <style>
body {
    font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
    max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
    background-color: #4f46e5;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
    padding: 30px;
        }
        .code {
    background-color: #f0f0f0;
            padding: 15px;
            border-radius: 5px;
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 5px;
            margin: 20px 0;
            color: #333;
        }
        .footer {
    background-color: #f8f8f8;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .button {
    display: inline-block;
    padding: 12px 24px;
            background-color: #4f46e5;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .note {
    font-size: 14px;
            color: #888;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Password Reset Request</h1>
        </div>
        <div class="content">
            <p>Hello {{ $user->name ?? 'User' }},</p>
<p>We received a request to reset your password. Use the following 6-digit code to complete the password reset process:</p>

<div class="code">{{ $code }}</div>

<p>This code will expire in <strong>15 minutes</strong>.</p>

<p>If you didn't request a password reset, please ignore this email or contact support if you have concerns.</p>

<div class="note">
    <p>For security reasons, never share this code with anyone.</p>
</div>
</div>
<div class="footer">
    <p>&copy; {{ date('Y') }} Ministry of Education, Liberia. All rights reserved.</p>
</div>
</div>
</body>
</html>
