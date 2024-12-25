<!DOCTYPE html>
<html>
<head>
    <title>Leave Request Status</title>
    <style>
        /* To ensure compatibility with email clients */
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
            background-color: #4caf50; /* Green header */
            color: #ffffff;
            padding: 20px;
            text-align: center;
            font-size: 1.8em;
            font-weight: bold;
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
            font-size: 1em;
        }

        .status {
            font-size: 1.2em;
            font-weight: bold;
            color: #4caf50; /* Approved status color */
        }

        .status.rejected {
            color: #f44336; /* Rejected status color */
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
            Leave Request Status
        </div>
        <div class="content">
            <h1>Hi {{ $leaveRequest->user->name }},</h1>
            <p>Your leave request has been 
                <span class="status {{ $leaveRequest->status == 'rejected' ? 'rejected' : '' }}">
                    {{ ucfirst($leaveRequest->status) }}
                </span>.
            </p>
            <p><strong>Leave Details:</strong></p>
            <ul>
                <li><strong>Start Date:</strong> {{ $leaveRequest->start_date }}</li>
                <li><strong>End Date:</strong> {{ $leaveRequest->end_date }}</li>
                <li><strong>Reason:</strong> {{ $leaveRequest->reason }}</li>
            </ul>
            @if ($leaveRequest->status == 'approved')
                <p>Your leave has been approved. Enjoy your time off!</p>
            @else
                <p>Unfortunately, your leave request has been rejected. Please contact HR for further details.</p>
            @endif
        </div>
        <div class="footer">
            <p>Thank you for your attention!</p>
            <p>Best regards,<br>The NTM Company</p>
            <img src="{{ env('APP_URL') }}/images/seal.png" alt="Certification Seal">
        </div>
    </div>
</body>
</html>

