<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <title>Document</title> --}}
</head>
<body>

    <p>Dear <b>{{ $username }}</b>,</p>
    <p>This is to confirm that your booking has been cancelled as per your request. Thank you for informing us in advance.</p>
    <br />
    <p>Here are your booking details: </p><br />
    <ul>
        <li>Reference Number: <b>{{ $ref_no }}</b> </li>
    </ul>

    <br />

    <p>Kind regards,</p>
    <p>ChurchConnect Team</p>

</body>
</html>
