<?php

namespace App\Services;

use App\Repositories\AppointmentRepository;
use App\Repositories\TransactionRepository;
use App\Models\User;
use App\Models\Expertise; // Jangan lupa import Model ini

class DashboardService
{
    protected $appointmentRepo;
    protected $transactionRepo;

    public function __construct(
        AppointmentRepository $appointmentRepo,
        TransactionRepository $transactionRepo
    ) {
        $this->appointmentRepo = $appointmentRepo;
        $this->transactionRepo = $transactionRepo;
    }

    public function getDashboardData(User $user)
    {
        // 1. Definisikan Role & Context ID
        $roles = $user->roles ?? [];

        // FIX: Tambahkan definisi isClient
        $isExpert = in_array('expert', $roles, true);
        $isAdmin  = in_array('administrator', $roles, true);
        $isClient = in_array('client', $roles, true);

        // Ambil Expert ID jika dia expert (untuk filter query)
        $expertId = $isExpert ? optional($user->expert)->id : null;

        // 2. Return Array (Harus match dengan Props di Vue)
        return [
            // Basic Data
            'user'              => $user,
            'roles'             => $roles,
            'isExpert'          => $isExpert,
            'isAdmin'           => $isAdmin,
            'isClient'          => $isClient, // FIX: Dikirim ke Vue

            // Data Berat (Lazy Load dengan fn())
            'appointments'      => fn() => $this->fetchAppointments($user->id, $expertId, $isAdmin),
            'transactions'      => fn() => $this->fetchTransactions($user->id, $expertId, $isAdmin),
            'calendarEvents'    => fn() => $this->formatCalendarEvents($user->id, $expertId, $isExpert, $isAdmin),

            // FIX: Tambahkan Expertises (Hanya jika Admin)
            'expertises'        => fn() => $this->fetchExpertises($isAdmin),

            // Data Ringan
            'appointmentsCount' => $this->appointmentRepo->countForUser($user->id, $expertId),

            // FIX: Tambahkan Social Medias
            // Pastikan helper getSocialMedias tersedia, atau pindahkan logic-nya ke sini
            'socialMedias'      => function_exists('getSocialMedias') ? getSocialMedias($user) : [],
        ];
    }

    // --- Private Helper Methods ---

    private function fetchAppointments($userId, $expertId, $isAdmin)
    {
        // 1. Cek Admin Dulu (Prioritas Tertinggi)
        if ($isAdmin) {
            return $this->appointmentRepo->getAllForAdmin();
        }

        // 2. Cek Expert
        if ($expertId) {
            return $this->appointmentRepo->getForExpert($expertId);
        }

        // 3. Fallback ke Client
        return $this->appointmentRepo->getForClient($userId);
    }

    private function fetchTransactions($userId, $expertId, $isAdmin)
    {
        // 1. Cek Admin (Prioritas Utama)
        if ($isAdmin) {
            return $this->transactionRepo->getAllForAdmin();
        }

        // 2. Cek Expert
        if ($expertId) {
            return $this->transactionRepo->getForExpert($expertId);
        }

        // 3. Fallback Client
        return $this->transactionRepo->getForClient($userId);
    }

    // FIX: Method baru untuk mengambil Expertise
    private function fetchExpertises($isAdmin)
    {
        if (!$isAdmin) return [];

        return Expertise::whereNull('parent_id')
            ->orderBy('order')
            ->with('childrensRecursive')
            ->get();
    }

    private function formatCalendarEvents($userId, $expertId, $isExpert, $isAdmin)
    {
        $rawEvents = $this->appointmentRepo->getAllForCalendar($userId, $expertId, $isAdmin);

        return $rawEvents->map(function ($app) use ($isExpert, $isAdmin) {

            // 2. Tentukan Judul Event (Title)
            if ($isAdmin) {
                // Format Admin: "Client Name w/ Expert Name"
                // Menggunakan optional() untuk jaga-jaga jika user terhapus
                $clientName = optional($app->user)->name ?? 'Unknown';
                $expertName = optional(optional($app->expert)->user)->name ?? 'Unknown';
                $title = "$clientName w/ $expertName";
            } else {
                // Format User Biasa
                $person = $isExpert ? $app->user : optional($app->expert)->user;
                $title = ($person->name ?? 'Unknown');
            }

            $title .= ' (' . ucfirst($app->status) . ')';

            // 3. Tentukan Warna (Sama seperti sebelumnya)
            $colors = [
                'confirmed' => '#10b981',
                'cancelled' => '#ef4444',
                'pending'   => '#f59e0b',
            ];
            $color = $colors[$app->status] ?? '#3b82f6';

            return [
                'id'              => $app->id,
                'title'           => $title,
                'start'           => $app->date_time,
                'backgroundColor' => $color,
                'borderColor'     => $color,
                'extendedProps'   => [
                    'status' => $app->status,
                    'appointment'  => $app->appointment
                ]
            ];
        });
    }
}
