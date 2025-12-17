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

        // --- 2. LOGIKA UNTUK EXPERT / CLIENT (Fallback) ---
        // Karena kita refactor bertahap, jika bukan admin, kita bisa:
        // A. Arahkan ke dashboard lama (jika file vue lama masih ada)
        // B. Atau buatkan logic stats sederhana untuk mereka juga

        // Contoh sementara (Return kosong atau redirect):
        return Inertia::render('Dashboard/Index', [
            // Kirim data minimal agar tidak error
            'user' => $user,
            'isExpert' => in_array('expert', $roles),
            'isAdmin' => false,
            // ... data dummy lain agar component lama tidak crash
        ]);
    }
}
