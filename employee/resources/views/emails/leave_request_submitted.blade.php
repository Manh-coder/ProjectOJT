<!DOCTYPE html>
<html>
<head>
    <title>Leave Request Submitted</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background-color: #4caf50;
            color: #ffffff;
            text-align: center;
            padding: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
            color: #333333;
        }
        .content p {
            margin: 0 0 10px;
            line-height: 1.5;
        }
        .footer {
            padding: 15px;
            font-size: 12px;
            text-align: center;
            color: #666;
            background-color: #f9f9f9;
            border-top: 1px solid #ddd;
        }
        .footer p {
            margin: 5px 0;
            line-height: 1.4;
        }
        .footer img {
            margin-top: 60px;
            height: 200px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Leave Request Submitted</h1>
        </div>
        <div class="content">
            <p>Hello,</p>
            <p>Your leave request has been submitted successfully!</p>
            <p>We will review your request and notify you of any updates. Thank you for using our service.</p>
        </div>
        
        <div class="footer">
            <p>Thank you for your attention!</p>
            <p>Best regards,<br>The NTM Company</p>
            <img src="{{ env('APP_URL') }}/images/seal.png" alt="Certification Seal">
        </div>
    </div>
</body>
</html>
