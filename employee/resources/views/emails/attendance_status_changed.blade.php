<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Status Updated</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 15px;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
            margin: 8px 0;
        }

        .status {
            font-weight: bold;
            font-size: 18px;
            color: #333;
            margin-top: 10px;
        }

        .status-valid {
            color: #4CAF50; /* Green */
        }

        .status-invalid {
            color: #F44336; /* Red */
        }

        .explanation {
            font-style: italic;
            color: #555;
            margin-top: 10px;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 14px;
            text-align: center;
        }

        .footer p {
            margin: 0;
            color: #777;
        }

        .button {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 5px;
            margin-top: 20px;
            text-align: center;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .seal {
            width: 100px;
            height: auto;
            margin-top: 20px;
        }

    </style>
</head>
<body>

    <div class="container">
        <h2>Hello {{ $userName }}</h2>

        <p>Your attendance status has been updated.</p>

        <div class="status {{ $status === 'valid' ? 'status-valid' : 'status-invalid' }}">
            <strong>Status:</strong> {{ ucfirst($status) }}
        </div>

        @if ($status === 'invalid' && $explanation)
            <div class="explanation">
                <strong>Explanation:</strong> {{ $explanation }}
            </div>
        @endif

        <a href="{{ route('user-attendance.show', $userName) }}" class="button">Access the system</a>

        <div class="footer">
            <p>Thank you for your attention!</p>
            <p>Best regards,<br>The NTM Company</p>

            <div>
                <img src="{{ env('APP_URL') }}/images/seal.png" alt="Certification Seal" class="seal">
            </div>
        </div>
    </div>

</body>
</html>
