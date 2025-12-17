<?php

namespace App\Repositories;

use App\Models\Appointment;

class AppointmentRepository
{
    public function getAllForAdmin($perPage = 5)
    {
        return Appointment::with([
            'user:id,name,email,picture',        // Load data Client
            'expert.user:id,name,email,picture'  // Load data Expert
        ])
            ->latest()
            ->paginate($perPage, ['*'], 'appointments_page');
    }

    // Ambil appointment untuk Client (User biasa)
    public function getForClient($userId, $perPage = 5)
    {
        return Appointment::where('user_id', $userId)
            ->with(['expert.user:id,name,email,picture']) // Eager load efisien
            ->latest()
            ->paginate($perPage, ['*'], 'appointments_page');
    }

    // Ambil appointment untuk Expert
    public function getForExpert($expertId, $perPage = 5)
    {
        return Appointment::where('expert_id', $expertId)
            ->with(['user:id,name,email,picture'])
            ->latest()
            ->paginate($perPage, ['*'], 'appointments_page');
    }

    // Ambil data raw untuk Calendar (Tanpa pagination)
    public function getAllForCalendar($userId, $expertId = null, $isAdmin = false)
    {
        $query = Appointment::select('id', 'user_id', 'expert_id', 'date_time', 'status', 'appointment');

        if ($isAdmin) {
            // ADMIN: Ambil Semua + Load relasi User & Expert (untuk judul event)
            $query->with([
                'user:id,name',
                'expert.user:id,name'
            ]);
        } else if ($expertId) {
            // EXPERT: Filter by Expert ID
            $query->where('expert_id', $expertId)->with('user:id,name');
        } else {
            // CLIENT: Filter by User ID
            $query->where('user_id', $userId)->with('expert.user:id,name');
        }

        return $query->get();
    }

    // Hitung total appointment
    public function countForUser($userId, $expertId = null)
    {
        if ($expertId) {
            return Appointment::where('expert_id', $expertId)->count();
        }
        return Appointment::where('user_id', $userId)->count();
    }
}
