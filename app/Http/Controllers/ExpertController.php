<?php
namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Expert;
use App\Services\GoogleCalendarService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpertController extends Controller
{
    //
    public function make_appointment($expert_id)
    {
        $expert = Expert::with('user')->findOrFail($expert_id);

        return view('appointment', compact('expert'));
    }

    public function make_appointment_post(Request $request, $expert_id, GoogleCalendarService $calendarService)
    {
        // (opsional tapi sangat disarankan)
        $request->validate([
            'date' => 'required|date',            // 2025-06-14
            'time' => 'required|date_format:H:i', // 23:28
        ]);

        $dateTime = Carbon::createFromFormat(
            'Y-m-d H:i',
            "{$request->date} {$request->time}",
            config('app.timezone') // mis. Asia/Jakarta
        );

        $expert = Expert::with('user')->findOrFail($expert_id);

        $appointment = Appointment::create([
            'user_id'     => Auth::user()->id,
            'expert_id'   => $expert_id,
            'appointment' => $request->appointment,
            'date_time'   => $dateTime,
            'hours'       => $request->hours,
            'price'       => $expert->price,
            'status'      => 'need_confirmation',
        ]);

        $eventData = [
            'summary' => 'Appointment with ' . $expert->user->name,
            'description' => $request->appointment,
            'start' => ['dateTime' => $dateTime->toRfc3339String()],
            'end' => ['dateTime' => $dateTime->copy()->addHours((int)$request->hours)->toRfc3339String()],
            'attendees' => [
                ['email' => $expert->user->email],
                ['email' => Auth::user()->email],
            ],
        ];

        try {
            $createdEvent = $calendarService->createEvent(Auth::user(), $eventData);
            $appointment->calendar_token = $createdEvent->getId();
            $appointment->save();
        } catch (\Exception $e) {
            // Optionally, handle the error, e.g., log it or show a message to the user
            return redirect()->back()->withErrors(['calendar_error' => 'Failed to create Google Calendar event: ' . $e->getMessage()]);
        }

        return redirect()->route('profile');
    }
}
