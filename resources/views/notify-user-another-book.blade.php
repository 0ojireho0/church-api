<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Booking Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            padding: 20px;
        }
        .highlight {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <p>Dear <span class="highlight">{{ $username }}</span>,</p>

        <p>This is to confirm your booking with the following details:</p>

        <ul>
            <li><strong>Reference No:</strong> {{ $refno }}</li>
            <li><strong>Church Name:</strong> {{ $churchname }}</li>
            <li><strong>Service Type:</strong> {{ $service_type }}</li>
            <li><strong>Date:</strong> {{ $date }}</li>
            <li><strong>Time Slot:</strong> {{ $time_slot}}</li>
        </ul>

        <p>Thank you for choosing our service. If you have any questions or need to reschedule, feel free to contact us.</p>

        <p>Best regards,<br>
        <strong>{{ config('app.name') }}</strong></p>
    </div>
</body>
</html>
