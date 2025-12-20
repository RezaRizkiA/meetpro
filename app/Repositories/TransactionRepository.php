<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository
{
    public function getAllForAdmin($perPage = 10)
    {
        return Transaction::with([
            'user:id,name,email,picture', // Data Client (Pembayar)
            'appointment.expert.user:id,name,email' // Data Expert (Penerima)
        ])
            ->latest()
            ->paginate($perPage, ['*'], 'transactions_page');
    }

    public function getForClient($userId, $perPage = 10)
    {
        return Transaction::where('user_id', $userId)
            ->latest()
            ->paginate($perPage, ['*'], 'transactions_page');
    }

    public function getForExpert($expertId, $perPage = 10)
    {
        // Query agak kompleks menggunakan whereHas appointment
        return Transaction::whereHas('appointment', function ($q) use ($expertId) {
            $q->where('expert_id', $expertId);
        })
            ->with(['appointment.user:id,name'])
            ->latest()
            ->paginate($perPage, ['*'], 'transactions_page');
    }

    public function create(array $data)
    {
        return Transaction::create($data);
    }
}
