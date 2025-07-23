<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>


    <p>Dear <b>{{ $fullname }}</b>, </p>
    <p>We have received your request for a
        <b>
            {{ $cert_type }}
        </b>
        through ChurchConnect. It is now under review by the parish office.
    </p>

    <p>Request Details: </p>
    <ul>
        <li>Certificate Type:
            <b>
                {{ $cert_type }}
            </b>
        </li>
        <li>Date Submitted: <b> {{ $created_at }} </b></li>
        <li>Church: <b> {{ $churchname }} </b></li>
        <li>Reference No.: <b> {{ $ref_no }} </b></li>
    </ul>

    <p>You will be notified once the request is processed or if additional information is needed. Please keep this reference number for any future updates or inquiries related to your request. </p>

    <p>For any questions, you may reply to this email (email@gmail.com).</p><br />
    <p>Kind Regards,</p>
    <p>ChurchConnect Team</p>


</body>
</html>
