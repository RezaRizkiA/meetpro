<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionController extends Controller
{
    protected $service;

    public function __construct(TransactionService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $transactions = $this->service->getAdminHistory();

        return Inertia::render('Administrator/Billing/Index', [
            'transactions' => $transactions
        ]);
    }
}
