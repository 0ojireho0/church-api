<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Overlap Notification</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">
    <div style="max-width: 600px; margin: auto; padding: 20px; border: 1px solid #eee; border-radius: 8px;">
        <h2 style="color: #e74c3c;">⚠️ Booking Overlap Detected</h2>

        <p>Dear {{ $email }},</p>

        <p>
            We regret to inform you that your booking request with
            <strong>Reference No. {{ $reference_no }}</strong> at
            <strong>{{ $church }}</strong> overlaps with a newly added event.
        </p>

        <p><strong>Event:</strong> {{ $event_name }}</p>
        <p><strong>Conflicting Date:</strong> {{ $date }}</p>

        <p>
            Due to this conflict, your booking on the specified date may not be accommodated.
            We recommend selecting an alternative date or contacting us for assistance.
        </p>

        <p>
            <strong>If you have already paid, please contact the church email for refund processing.</strong>
        </p>

        <p style="margin-top: 30px;">Thank you for your understanding.</p>

        <p style="font-style: italic; color: #7f8c8d;">— {{ $church }} Team</p>
    </div>
</body>
</html>
