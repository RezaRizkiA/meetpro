<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Payment Notification Test</h1>
    <p>This is a test page for payment notifications.</p>
    <p>Data received:</p>
    {{-- <pre>{{ print_r($data, true) }}</pre> --}}

    <form action="{{ route('payment.notify') }}" method="POST">
        @csrf
        <input type="text" name="trx_id" value="{{ $transaction['trx_id'] ?? '' }}">
        <button type="submit">Send Transaction ID</button>
    </form>
</body>
</html>
