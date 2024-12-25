<!DOCTYPE html>
<html>
<head>
    <title>Schedule Updated</title>
    <style>
        /* To ensure compatibility with most email clients */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #007bff;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            font-size: 1.5em;
        }

        .content {
            padding: 20px;
            color: #333333;
            line-height: 1.6;
        }

        .content ul {
            list-style: none;
            padding: 0;
        }

        .content ul li {
            background: #f9f9f9;
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #dddddd;
            border-radius: 4px;
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
            Schedule Updated
        </div>
        <div class="content">
            <h1>Dear {{ $scheduleData['user_name'] }},</h1>
            <p>The schedule has been updated with the following details:</p>
            <ul>
                <li><strong>Check-in Time:</strong> {{ $scheduleData['check_in_time'] }}</li>
                <li><strong>Check-out Time:</strong> {{ $scheduleData['check_out_time'] }}</li>
            </ul>
            
        </div>
        <div class="footer">
            <p>Thank you for your attention!</p>
            <p>Best regards,<br>The NTM Company</p>
            <img src="{{ env('APP_URL') }}/images/seal.png" alt="Certification Seal">
        </div>
    </div>
</body>
</html>

