<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    /**
     * Mencari user berdasarkan ID
     */
    public function find($id)
    {
        return User::findOrFail($id);
    }

    /**
     * Update data user
     * Mengembalikan object User agar bisa dicheck valuenya jika perlu
     */
    public function update(User $user, array $data)
    {
        $user->update($data);
        return $user->refresh(); // Return data user terbaru
    }
}
