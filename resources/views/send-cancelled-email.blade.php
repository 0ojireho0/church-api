<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <title>Document</title> --}}
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">

    <p>Dear <b>{{ $username }}</b>,</p>

    <p>This is to confirm that your booking has been cancelled as per your request. Thank you for informing us in advance.</p>

    <p>Here are your booking details:</p>

    <ul>
        <li>Reference Number: <b>{{ $ref_no }}</b></li>
    </ul>

    <p><strong>If you have already paid, please contact the church email for refund processing.</strong></p>

    <br />

    <p>Kind regards,</p>
    <p>ChurchConnect Team</p>

</body>
</html>
