<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

    <h2>Hello {{ $user->name }},</h2>
    <p>Thank you for registering. Please verify your email by clicking the button below:</p>
    <a href="{{ $url }}" style="padding: 10px 20px; background-color: #38bdf8; color: #fff; text-decoration: none; border-radius: 5px;">
        Verify Email
    </a>
    <p>This link will expire in 60 minutes.</p>


</body>
</html>
