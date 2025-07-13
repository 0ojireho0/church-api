<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Event Notification</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">
    <div style="max-width: 600px; margin: auto; padding: 20px; border: 1px solid #eee; border-radius: 8px;">
        <h2 style="color: #2c3e50;">📣 New Event Added!</h2>

        <p>Dear valued member,</p>

        <p>We are excited to inform you that a new event has been added by <strong>{{ $church_name }}</strong>.</p>

        <p><strong>Event:</strong> {{ $event_name }}</p>
        <p><strong>Date:</strong> {{ $fulldates }}</p>

        <p>We encourage you to mark your calendar and participate. Stay tuned for more details and updates regarding this event.</p>

        <p style="margin-top: 30px;">Thank you and God bless!</p>

        <p style="font-style: italic; color: #7f8c8d;">— {{ $church_name }} Team</p>
    </div>
</body>
</html>
