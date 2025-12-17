<?php

namespace App\Services;

use App\Repositories\AppointmentRepository;

class CalendarService
{
    protected $appointmentRepo;

    public function __construct(AppointmentRepository $appointmentRepo)
    {
        $this->appointmentRepo = $appointmentRepo;
    }

    public function getAdminCalendarEvents()
    {
        // 1. Ambil Data Raw dari Repo
        $rawEvents = $this->appointmentRepo->getAllForCalendar(null, null, true); // isAdmin = true

        // 2. Format Data (Business Logic)
        return $rawEvents->map(function ($app) {
            $clientName = optional($app->user)->name ?? 'Unknown';
            $expertName = optional(optional($app->expert)->user)->name ?? 'Unknown';

            // Logic Warna
            $colors = [
                'confirmed' => '#10b981', // Green
                'cancelled' => '#ef4444', // Red
                'pending'   => '#f59e0b', // Orange
                'completed' => '#3b82f6', // Blue
            ];

            return [
                'id' => $app->id,
                'title' => "$clientName - $expertName", // Format Khusus Admin
                'start' => $app->date_time,
                'backgroundColor' => $colors[$app->status] ?? '#64748b',
                'borderColor' => $colors[$app->status] ?? '#64748b',
                'extendedProps' => [
                    'status' => $app->status,
                    'appointment' => $app->appointment
                ]
            ];
        });
    }
}
