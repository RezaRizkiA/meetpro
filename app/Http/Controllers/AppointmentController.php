<?php

namespace App\Http\Controllers;

use App\Mail\AppointmentConfirmed;
use App\Mail\AppointmentStatusChanged;
use App\Models\Appointment;
use App\Services\GoogleCalendarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AppointmentController extends Controller
{
    public function updateStatus(Request $request, $id, GoogleCalendarService $calendarService)
    {
        $request->validate([
            'status' => 'required|in:progress,declined,completed,canceled',
        ]);

        $appointment = Appointment::with(['user', 'expert.user'])->findOrFail($id);
        $user = Auth::user();

        // Authorization: Ensure the user can update the appointment.
        $isExpert = $user->id === $appointment->expert->user_id;
        $isClient = $user->id === $appointment->user_id;

        if (!$isExpert && !$isClient) {
            return redirect()->back()->withErrors(['authorization' => 'You are not authorized to update this appointment.']);
        }

        $appointment->status = $request->status;
        $appointment->save();

        // Google Calendar Sync & Email Notifications
        try {
            if ($appointment->calendar_token) {
                $event = $calendarService->getEvent($appointment->user, $appointment->calendar_token);

                if ($request->status === 'progress') {
                    $event->setSummary('Appointment with ' . $appointment->expert->user->name . ' (Confirmed)');
                    $calendarService->updateEvent($appointment->user, $appointment->calendar_token, $event);
                    Mail::to($appointment->user->email)->send(new AppointmentConfirmed($appointment));
                } elseif (in_array($request->status, ['declined', 'canceled'])) {
                    $calendarService->deleteEvent($appointment->user, $appointment->calendar_token);
                    $appointment->calendar_token = null; // Clear the token
                    $appointment->save();

                    $recipient = $isExpert ? $appointment->user : $appointment->expert->user;
                    Mail::to($recipient->email)->send(new AppointmentStatusChanged($appointment, $request->status));
                } elseif ($request->status === 'completed') {
                    $event->setSummary('Appointment with ' . $appointment->expert->user->name . ' (Completed)');
                    $calendarService->updateEvent($appointment->user, $appointment->calendar_token, $event);
                }
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['calendar_error' => 'Failed to update Google Calendar or send email: ' . $e->getMessage()]);
        }

        return redirect()->route('profile')->with('success', 'Appointment status updated successfully.');
    }
}
