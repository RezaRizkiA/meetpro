<?php

namespace App\Services;

use App\Repositories\ExpertiseRepository;

class ExpertiseService
{
    protected $repo;

    public function __construct(ExpertiseRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getExpertiseTree()
    {
        // Di sini bisa tambah logic bisnis jika perlu
        return $this->repo->getTreeForAdmin();
    }
}
