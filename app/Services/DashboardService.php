<?php

namespace App\Services;

use App\Repositories\AppointmentRepository;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Appointment;

class DashboardService
{
    protected $appointmentRepo;

    public function __construct(AppointmentRepository $appointmentRepo)
    {
        $this->appointmentRepo = $appointmentRepo;
    }

    /**
     * Mengambil statistik ringkas untuk halaman Dashboard Admin
     */
    public function getAdminStats()
    {
        return [
            // Hitung Total User di sistem
            'total_users' => User::count(),

            // Hitung Total Appointment (Semua)
            // Menggunakan repo atau query langsung juga boleh untuk count sederhana
            'total_appointments' => Appointment::count(),

            // Hitung Total Pendapatan (Status Paid)
            'total_revenue' => Transaction::where('status', 'berhasil')->sum('amount'),

            // Hitung Appointment yang butuh konfirmasi (Pending)
            'pending_appointments' => Appointment::where('status', 'need_confirmation')->count(),
        ];
    }

    /**
     * (Opsional) Mengambil statistik untuk Expert
     */
    public function getExpertStats($expertId)
    {
        return [
            'total_appointments' => Appointment::where('expert_id', $expertId)->count(),
            // Logika revenue expert (mungkin ada potongan platform fee)
            'total_revenue' => Transaction::whereHas('appointment', fn($q) => $q->where('expert_id', $expertId))
                ->where('status', 'paid')
                ->sum('amount'),
        ];
    }
}
