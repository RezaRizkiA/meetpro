<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Appointment Invitation</title>
  <style>
    body {
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
      background-color: #f8fafc;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 600px;
      margin: 40px auto;
      background-color: #ffffff;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .header {
      background-color: #7c3aed;
      padding: 30px;
      text-align: center;
      color: #ffffff;
    }

    .content {
      padding: 40px;
      color: #334155;
      line-height: 1.6;
    }

    .info-box {
      background-color: #f1f5f9;
      border-radius: 12px;
      padding: 20px;
      margin: 20px 0;
      border: 1px solid #e2e8f0;
    }

    .info-item {
      margin-bottom: 10px;
      font-size: 14px;
    }

    .label {
      font-weight: bold;
      color: #64748b;
      text-transform: uppercase;
      font-size: 11px;
      display: block;
      margin-bottom: 2px;
    }

    .value {
      color: #0f172a;
      font-weight: 600;
      font-size: 16px;
    }

    .btn {
      display: inline-block;
      background-color: #0f172a;
      color: #ffffff;
      padding: 12px 24px;
      text-decoration: none;
      border-radius: 8px;
      font-weight: bold;
      margin-top: 20px;
    }

    .footer {
      background-color: #f8fafc;
      padding: 20px;
      text-align: center;
      color: #94a3b8;
      font-size: 12px;
      border-top: 1px solid #e2e8f0;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="header">
      <h2 style="margin:0;">Appointment Confirmed</h2>
      <p style="margin:5px 0 0 0; opacity: 0.9;">Your session has been successfully scheduled.</p>
    </div>

    <div class="content">
      <p>Hello,</p>
      <p>
        @if($appointment->type === 'group')
        You have been invited to a <strong>Team Session</strong>.
        @else
        Your personal consultation session is confirmed.
        @endif
        Here are the details:
      </p>

      <div class="info-box">
        <div class="info-item">
          <span class="label">Topic</span>
          <span class="value">{{ $appointment->topic }}</span>
        </div>

        <div class="info-item">
          <span class="label">With Expert</span>
          <span class="value">{{ $appointment->expert->user->name }}</span>
        </div>

        <div class="info-item">
          <span class="label">Date & Time</span>
          <span class="value">
            {{ \Carbon\Carbon::parse($appointment->date_time)->locale('id')->translatedFormat('l, d F Y') }}
          </span>
          <br>
          <span class="value">
            {{ \Carbon\Carbon::parse($appointment->date_time)->format('H:i') }} -
            {{ \Carbon\Carbon::parse($appointment->date_time)->addHours($appointment->hours)->format('H:i') }} WIB
          </span>
        </div>

        @if($appointment->type === 'group')
        <div class="info-item">
          <span class="label">Session Type</span>
          <span class="value" style="color: #2563eb;">Group Session ({{ count($appointment->guests ?? []) + 1 }}
            Participants)</span>
        </div>
        @endif
      </div>

      <p>Please mark your calendar. You will receive a separate Google Calendar invitation shortly.</p>

      <div style="text-align: center;">
        @if($appointment->location_url)
        <a href="{{ $appointment->location_url }}" class="btn">Join Meeting Link</a>
        @else
        <a href="{{ route('dashboard.appointments.show', $appointment->id) }}" class="btn">View Appointment Details</a>
        @endif
      </div>
    </div>

    <div class="footer">
      <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
      <p>This is an automated message, please do not reply.</p>
    </div>
  </div>
</body>

</html>