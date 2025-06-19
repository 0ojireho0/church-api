<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <title>Document</title> --}}
</head>
<body>

{{-- Rejected Booking

Dear (name)
We regret to inform you that your request for a (Type of service) submitted through ChurchConnect has been rejected due to the following reason(s):

(remarks)

You may contact the parish office for clarification.

Here are your booking details:
Type of service
Reference Number
Date & Time
Location --}}

    <p>Dear <b>{{ $username }}</b>,</p>
    <p>We regret to inform you that your request for a {{ $service_type }} submitted through ChurchConnect has been <b>Rejected</b> due to the following reason(s): </p>
    <p><b>{{ $remarks }}</b></p>
    <p>You may contact the parish office for clarification.</p>
    <p>Here are your booking details: </p>
    <ul>
        <li>Type of Service: <b>{{ $service_type }}</b> </li>
        <li>Reference Number: <b>{{ $ref_no }}</b> </li>
        <li>Date & Time: <b>{{ $date }} {{ $timeslot }}</b> </li>
        <li>Location: <b>{{ $churchname }}</b> </li>
    </ul>

    <br />
    <p>Kind regards,</p>
    <p>ChurchConnect Team</p>

</body>
</html>
