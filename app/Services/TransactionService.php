<?php

namespace App\Services;

use App\Repositories\TransactionRepository;

class TransactionService
{
    protected $repo;

    public function __construct(TransactionRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getAdminHistory($perPage = 10)
    {
        return $this->repo->getAllForAdmin($perPage);
    }
}
