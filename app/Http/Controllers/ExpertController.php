<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Expert;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ExpertController extends Controller
{
    public function make_appointment($expert_id)
    {
        $expert = Expert::with('user')->findOrFail($expert_id);

        $appointments = Appointment::where('expert_id', $expert_id)
            ->where('status', '!=', 'declined')
            ->whereDate('date_time', '>=', Carbon::today())
            ->get(['date_time', 'hours']);

        $bookedSlots = [];

        foreach ($appointments as $app) {
            $start = Carbon::parse($app->date_time);
            for ($i = 0; $i < $app->hours; $i++) {
                $current = $start->copy()->addHours($i);
                $dateKey = $current->format('Y-m-d');
                $timeKey = $current->format('H:i');

                if (!isset($bookedSlots[$dateKey])) {
                    $bookedSlots[$dateKey] = [];
                }
                $bookedSlots[$dateKey][] = $timeKey;
            }
        }

        // Tangkap URL sebelumnya (jika ada)
        $backUrl = request('back');

        return Inertia::render('Client/MakeAppointment', [
            'expert' => $expert,
            'backUrl' => $backUrl,
            'bookedSlots' => $bookedSlots,
        ]);
    }

    public function make_appointment_post(Request $request, $expert_id)
    {
        // 1. Validasi
        // BAGUS: Logic H+1 (after:today) sudah diterapkan di backend. 
        // Ini sabuk pengaman jika user mengakali DatePicker frontend.
        $request->validate([
            'date' => 'required|date|after:today',
            'time' => 'required|date_format:H:i',
            'hours' => 'required|integer|min:1|max:8',
            'topic' => 'required|string|max:1000',
        ], [
            'date.after' => 'Booking harus dilakukan minimal 1 hari sebelumnya (H+1).'
        ]);

        try {
            return DB::transaction(function () use ($request, $expert_id) {

                // MASTERPIECE: Locking Parent
                // Ini memastikan tidak ada race condition untuk Expert ini.
                $expert = Expert::lockForUpdate()->findOrFail($expert_id);

                // Perhitungan Waktu (Menggunakan Carbon)
                $startDateTime = Carbon::createFromFormat('Y-m-d H:i', "{$request->date} {$request->time}");
                $endDateTime = $startDateTime->copy()->addHours($request->hours);

                // LOGIC CHECK CONFLICT
                $conflict = Appointment::where('expert_id', $expert_id)
                    ->where('status', '!=', 'declined')
                    ->where(function ($query) use ($startDateTime, $endDateTime) {
                        $query->where('date_time', '<', $endDateTime)
                            ->whereRaw("DATE_ADD(date_time, INTERVAL hours HOUR) > ?", [$startDateTime]);
                    })
                    ->exists();

                if ($conflict) {
                    throw new \Exception('Maaf, slot waktu ini baru saja diambil orang lain.');
                }

                // Create Appointment
                $appointment = Appointment::create([
                    'user_id'      => Auth::id(), 
                    'expert_id'    => $expert_id,
                    'appointment'  => $request->topic,
                    'date_time'    => $startDateTime,
                    'hours'        => $request->hours,
                    'price'        => $expert->price * $request->hours,
                    'status'       => 'need_confirmation',
                    'payment_status' => 'pending',
                ]);

                // Redirect ke payment
                return redirect()->route('payment.show', ['appointment' => $appointment->id]);
            });
        } catch (\Exception $e) {
            // Error handling yang bagus, input lama (date, topic) tidak hilang.
            return back()->withErrors(['time' => $e->getMessage()])->withInput();
        }
    }
}
