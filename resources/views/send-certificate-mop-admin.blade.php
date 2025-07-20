<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payment Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 8px;
            padding: 30px;
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
        }
        .label {
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>New Payment Notification</h2>

        <p><span class="label">Full Name:</span> {{ $fullname }}</p>
        <p><span class="label">Mode of Payment:</span> {{ $mop }}</p>
        <p><span class="label">Payment Status:</span> {{ $mop_status }}</p>
        <p><span class="label">Reference Number:</span> {{ $reference_no }}</p>

        <p>Please verify the payment details in the admin dashboard.</p>
    </div>

</body>
</html>
