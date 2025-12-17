<?php

namespace App\Repositories;

use App\Models\Expertise;

class ExpertiseRepository
{
    public function getTreeForAdmin()
    {
        return Expertise::whereNull('parent_id')
            ->orderBy('order')
            ->with('childrensRecursive') // Pastikan relasi ini ada di Model
            ->get();
    }

    // Nanti bisa tambah method lain: find(), create(), update(), delete()
}
