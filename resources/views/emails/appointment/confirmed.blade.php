<!DOCTYPE html>
<html>
<head>
    <title>Appointment Confirmed</title>
</head>
<body>
    <h1>Your Appointment is Confirmed!</h1>
    <p>Hi {{ $appointment->user->name }},</p>
    <p>Your appointment with {{ $appointment->expert->user->name }} on {{ $appointment->date_time->format('F d, Y \a\t H:i') }} has been confirmed.</p>
    <p>Thank you for using our service.</p>
</body>
</html>
