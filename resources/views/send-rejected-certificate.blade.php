<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

    <p>Dear <b>{{ $fullname }}</b>,</p>

    <p>We regret to inform you that your request for a
        <b>
            @foreach ($cert_type as $type)
                {{ $loop->first ? '' : ', ' }}{{ $type }}
            @endforeach
        </b>
        submitted through ChurchConnect has been rejected due to the following reason(s):
    </p>

    <p><b> {{ $remarks }} </b></p>

    <p>You may contact the parish office for clarification. </p>

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
        <li>Status: <b>Rejected</b></li>
    </ul>

    <p>For assistance, please reach out to us at (email@gmail.com). Thank you for your understanding. </p>
    <p>Kind Regards,</p>
    <p>ChurchConnect Team</p>

</body>
</html>
