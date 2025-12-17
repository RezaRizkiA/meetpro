<?php

namespace App\Services;

use App\Repositories\AppointmentRepository;

class AppointmentService
{
    protected $repo;

    public function __construct(AppointmentRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getAdminList($perPage = 10)
    {
        // Di sini bisa taruh logic tambahan.
        // Misal: Filter berdasarkan request, validasi akses, dll.
        return $this->repo->getAllForAdmin($perPage);
    }
}
