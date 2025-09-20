<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Configuration Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #007bff;
        }

        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 28px;
        }

        .content {
            margin-bottom: 30px;
        }

        .success-message {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .info-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .footer {
            text-align: center;
            color: #6c757d;
            font-size: 14px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
        }

        .test-icon {
            font-size: 48px;
            color: #28a745;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="test-icon">✅</div>
            <h1>Email Configuration Test</h1>
        </div>

        <div class="content">
            <div class="success-message">
                <strong>🎉 Success!</strong> Your email configuration is working correctly!
            </div>

            <div class="info-box">
                <h3>Test Details:</h3>
                <ul>
                    <li><strong>Website:</strong> {{ $settings->website_name ?? 'Your Store' }}</li>
                    <li><strong>Test Time:</strong> {{ now()->format('Y-m-d H:i:s') }}</li>
                    <li><strong>Email Driver:</strong> {{ $settings->mail_mailer ?? 'smtp' }}</li>
                    <li><strong>Status:</strong> <span style="color: #28a745;">✅ Working</span></li>
                </ul>
            </div>

            <p>This is a test email to verify that your email configuration is properly set up. If you received this
                email, it means:</p>
            <ul>
                <li>✅ SMTP settings are correct</li>
                <li>✅ Authentication is working</li>
                <li>✅ Email delivery is functional</li>
                <li>✅ Order approval emails will work</li>
            </ul>

            <p>You can now safely use the email functionality in your application, including sending order approval
                emails to customers.</p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ $settings->website_name ?? 'Your Store' }}. All rights reserved.</p>
            <p>This is an automated test email. Please do not reply.</p>
        </div>
    </div>
</body>

</html>
