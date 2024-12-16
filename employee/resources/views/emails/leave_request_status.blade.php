<!DOCTYPE html>
<html>
<head>
    <title>Leave Request Status Update</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
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
        .content {
            padding: 20px;
            color: #333;
        }
        .footer {
            margin-top: 20px;
            padding: 10px;
            background-color: #f1f1f1;
            text-align: center;
            font-size: 12px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Leave Request Status Update</h1>
        </div>
        <div class="content">
            <p>Dear {{ $leaveRequest->user->name }},</p>
            <p>{{ $message }}</p>
            <p><strong>Details of your leave request:</strong></p>
            <ul>
                <li><strong>Start Date:</strong> {{ $leaveRequest->start_date }}</li>
                <li><strong>End Date:</strong> {{ $leaveRequest->end_date }}</li>
                <li><strong>Status:</strong> {{ ucfirst($leaveRequest->status) }}</li>
            </ul>
            <p>Thank you for using our service.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} NTM Company. All rights reserved.
        </div>
    </div>
</body>
</html>
