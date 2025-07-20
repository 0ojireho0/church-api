<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

    <p>Dear <b> {{ $fullname }} </b>,</p>

    <p>We are pleased to inform you that your request for a
        <b>
            @foreach ($cert_type as $type)
                {{ $loop->first ? '' : ', ' }}{{ $type }}
            @endforeach
        </b>
        has been approved by {{ $churchname }}.
    </p>

    <p>You may now set a Mode of Payment in your <b>My Bookings</b> tab to process your Requested Certificate</p>

    <p>Details: </p>
    <ul>
        <li>Certificate Type:
            <b>
                @foreach ($cert_type as $type)
                    {{ $loop->first ? '' : ', ' }}{{ $type }}
                @endforeach
            </b>
        </li>
        <li>Reference No: <b>{{$reference_no}}</b></li>
        <li>Church: <b>{{$churchname}}</b></li>
        <li>Status: <b>Approved</b></li>
        {{-- <li>Claiming Hours: <b>[e.g., Mon–Fri, 9:00 AM–4:00 PM]</b></li> --}}
    </ul>

    <p>For any questions, feel free to contact us at (email@gmail.com)</p>
    <p>Kind Regards,</p>
    <p>ChurchConnect Team</p>

</body>
</html>
