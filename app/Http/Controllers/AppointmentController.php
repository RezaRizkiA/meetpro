<?php
namespace App\Http\Controllers;

use App\Mail\AppointmentConfirmed;
use App\Mail\AppointmentRescheduled;
use App\Mail\AppointmentStatusChanged;
use App\Models\Appointment;
use App\Services\AppointmentService;
use App\Services\GoogleCalendarService;
use Google\Service\Calendar\EventDateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class AppointmentController extends Controller
{
    protected $service;

    public function __construct(AppointmentService $service) // Inject Service
    {
        $this->service = $service;
    }

    public function index()
    {
        // Controller terima data matang dari Service
        $appointments = $this->service->getAdminList();

        return Inertia::render('Administrator/Appointments/Index', [
            'appointments' => $appointments
        ]);
    }


    public function updateStatus(Request $request, $id, GoogleCalendarService $calendarService)
    {
        $request->validate([
            'status' => 'required|in:progress,declined,completed,edited',
        ]);

        $appointment = Appointment::with(['user', 'expert.user'])->findOrFail($id);
        $user        = Auth::user();

        // Authorization: Ensure the user can update the appointment.
        $isExpert = $user->id === $appointment->expert->user_id;
        $isClient = $user->id === $appointment->user_id;

        if (! $isExpert && ! $isClient) {
            return redirect()->back()->withErrors(['authorization' => 'You are not authorized to update this appointment.']);
        }

        $appointment->status = $request->status;
        $appointment->save();

        // Google Calendar Sync & Email Notifications
        try {
            if ($appointment->google_calendar_event_id) {
                $event = $calendarService->getEvent($appointment->user, $appointment->google_calendar_event_id);

                if ($request->status === 'progress') {
                    $event->setSummary('Appointment with ' . $appointment->expert->user->name . ' (Confirmed)');
                    $calendarService->updateEvent($appointment->user, $appointment->google_calendar_event_id, $event);
                    Mail::to($appointment->user->email)->send(new AppointmentConfirmed($appointment));
                } elseif ($request->status === 'declined') {
                    $calendarService->deleteEvent($appointment->user, $appointment->google_calendar_event_id);
                    $appointment->google_calendar_event_id = null; // Clear the token
                    $appointment->save();

                    $recipient = $isExpert ? $appointment->user : $appointment->expert->user;
                    Mail::to($recipient->email)->send(new AppointmentStatusChanged($appointment, $request->status));
                } elseif ($request->status === 'completed') {
                    $event->setSummary('Appointment with ' . $appointment->expert->user->name . ' (Completed)');
                    $calendarService->updateEvent($appointment->user, $appointment->google_calendar_event_id, $event);
                }
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['calendar_error' => 'Failed to update Google Calendar or send email: ' . $e->getMessage()]);
        }

        return redirect()->route('profile')->with('success', 'Appointment status updated successfully.');
    }

    /**
     * Edit the schedule of an appointment.
     *
     * @param Request $request
     * @param int $id
     * @param GoogleCalendarService $calendarService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editSchedule(Request $request, $id, GoogleCalendarService $calendarService)
    {
        // ... (Validasi dan Otorisasi tetap sama) ...
        $request->validate([
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
        ]);
        $appointment = Appointment::with(['user', 'expert.user'])->findOrFail($id);
        $user        = Auth::user();
        if ($user->id !== $appointment->expert->user_id) {
            return redirect()->back()->withErrors(['authorization' => 'You are not authorized to edit this appointment.']);
        }

        try {
            $newDateTime = \Carbon\Carbon::parse($request->date . ' ' . $request->time);

            if ($appointment->google_calendar_event_id) {

                $event = $calendarService->getEvent($appointment->user, $appointment->google_calendar_event_id);

                $startTime = new EventDateTime(); // <- Berubah
                $startTime->setDateTime($newDateTime->toRfc3339String());
                $event->setStart($startTime);

                $endTime = new EventDateTime(); // <- Berubah
                $endTime->setDateTime($newDateTime->copy()->addHours($appointment->hours)->toRfc3339String());
                $event->setEnd($endTime);

                $event->setSummary('Appointment with ' . $appointment->expert->user->name . ' (Rescheduled)');

                $calendarService->updateEvent($appointment->user, $appointment->google_calendar_event_id, $event);
            }

            // ... (Update database tetap sama) ...
            $appointment->date_time = $newDateTime;
            $appointment->status    = $request->status ?? $appointment->status;
            $appointment->save();
            Mail::to($appointment->user->email)->send(new AppointmentRescheduled($appointment));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to update schedule: ' . $e->getMessage()]);
        }

        return redirect()->back()->with('success', 'Appointment schedule has been updated successfully.');
    }
}
