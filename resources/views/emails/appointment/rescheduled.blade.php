<!DOCTYPE html>
<html>
<head>
    <title>Pemberitahuan Perubahan Jadwal</title>
</head>
<body>
    <h1>Halo, {{ $appointment->user->name }}!</h1>
    <p>
        Jadwal janji temu Anda dengan ahli kami, <strong>{{ $appointment->expert->user->name }}</strong>, telah diubah.
    </p>
    <p>Berikut adalah detail jadwal yang baru:</p>
    <ul>
        <li><strong>Detail:</strong> {{ $appointment->detail_appointment }}</li>
        <li><strong>Tanggal & Waktu Baru:</strong> {{ \Carbon\Carbon::parse($appointment->date_time)->translatedFormat('l, d F Y \p\u\k\u\l H:i') }} WIB</li>
    </ul>
    <p>
        Mohon periksa kembali jadwal Anda. Terima kasih.
    </p>
</body>
</html>
