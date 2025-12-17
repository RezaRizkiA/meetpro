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
        $user = Auth::user();
        $roles = $user->roles ?? []; // Asumsi user punya atribut/relasi roles

        // --- 1. LOGIKA UNTUK ADMINISTRATOR ---
        if (in_array('administrator', $roles)) {

            // Panggil method yang BENAR di service
            $stats = $this->dashboardService->getAdminStats();

            // Render ke folder frontend Administrator yang baru
            return Inertia::render('Administrator/Dashboard/Index', [
                'stats' => $stats,
                // 'user' tidak perlu dikirim manual jika sudah ada di HandleInertiaRequests (Shared Props)
            ]);
        }
    }
}
