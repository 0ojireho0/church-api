<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <title>Document</title> --}}
</head>
<body>

    <p>Dear <b>{{ $username }}</b></p>,
    <p>Thank you for booking through ChurchConnect. Your request has been successfully received.</p>
    <br />
    <p>Here are your booking details: </p><br />
    <ul>
        <li>Type of Service: <b>{{ $service_type }}</b> </li>
        <li>Reference Number: <b>{{ $ref_no }}</b> </li>
        <li>Date & Time: <b>{{ $date }} {{ $timeslot }}</b> </li>
        <li>Location: <b>{{ $churchname }}</b> </li>
    </ul>

    <br />

    <p>Please keep this reference number for any future updates or inquiries related to your booking.</p>

    <br />

    <p>If you have any questions, feel free to reply this message (churchconnect@gmail.com) or contact our support team.</p>

    <br />

    <p>Kind regards,</p>
    <p>ChurchConnect Team</p>

</body>
</html>
