<!DOCTYPE html>
<html>
<head>
    <title>Appointment Status Changed</title>
</head>
<body>
    <h1>Appointment Status Update</h1>
    <p>Hi,</p>
    <p>The status of your appointment with {{ $appointment->expert->user->name }} scheduled for {{ $appointment->date_time->format('F d, Y \a\t H:i') }} has been updated to: <strong>{{ ucfirst($status) }}</strong>.</p>
    <p>Thank you.</p>
</body>
</html>
