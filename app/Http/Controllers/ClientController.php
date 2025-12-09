<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Expert;
use App\Models\Expertise;
use App\Models\ExpertiseCategory;
use Inertia\Inertia;

class ClientController extends Controller
{
    public function home_client($slug_page)
    {
        $client = Client::where('slug_page', $slug_page)->firstOrFail();

        // Convert string IDs to array if necessary
        $expertiseIds = is_array($client->expertise_id)
            ? $client->expertise_id
            : explode(',', $client->expertise_id ?? '');

        // Ambil data expertise beserta parent dan hitung expert-nya
        $expertises = Expertise::whereIn('id', $expertiseIds)
            ->with('parent')
            ->get();

        return Inertia::render('Client/Home', [
            'client' => $client,
            'expertises' => $expertises,
        ]);
    }

    public function list_conselor($slug_page, $slug)
    {
        // 1. Ambil data client berdasarkan slug_page
        $client = Client::where('slug_page', $slug_page)->firstOrFail();

        // 3. Ambil kategori berdasarkan slug
        $expertise = Expertise::where('slug', $slug)->firstOrFail();

        $experts = Expert::with('user') // <--- Load user di sini
            ->whereJsonContains('expertise_id', (string) $expertise->id)
            ->get();

        // dd($experts);
        return Inertia::render('Client/ListExpert', [
            'client' => $client,
            'expertise' => $expertise,
            // Kirim list experts secara terpisah agar lebih mudah diakses di Vue
            'experts' => $experts,
        ]);
    }

    public function expert_detail($expert_id)
    {
        $expert = Expert::with('user')->findOrFail($expert_id);
        $backUrl = request('back'); // Tangkap query string back

        return Inertia::render('Client/ExpertDetail', [
            'expert' => $expert,
            'backUrl' => $backUrl,
        ]);
    }
}
