<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    protected $dashboardService;

    // Inject Service ke Controller
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        // 1. Ambil User
        $user = Auth::user();

        // 2. Load relasi awal yg penting (opsional, bisa juga di service)
        $user->load(['client', 'expert']);

        // 3. Minta data ke Service
        $data = $this->dashboardService->getDashboardData($user);

        // 4. Return View
        return Inertia::render('Dashboard/Index', $data);
    }
}
