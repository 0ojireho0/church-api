<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payment Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f9f9f9;
        }
        .receipt-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 600px;
            margin: auto;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .info {
            margin-top: 20px;
        }
        .info p {
            font-size: 16px;
            margin: 10px 0;
        }
        .label {
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="receipt-container">
        <h2>Payment Receipt</h2>

        <div class="info">
            <p><span class="label">Full Name:</span> {{ $fullname }}</p>
            <p><span class="label">Mode of Payment:</span> {{ $mop }}</p>
            <p><span class="label">Payment Status:</span> {{ $mop_status }}</p>
            <p><span class="label">Reference Number:</span> {{ $reference_no }}</p>
        </div>
    </div>

</body>
</html>
