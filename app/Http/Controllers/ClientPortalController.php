<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Expert;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientPortalController extends Controller
{
    /**
     * 1. Landing Page: Menampilkan Daftar Kategori milik Client
     * Alur Data: Client -> Skills -> SubCategory -> Category
     */
    public function index($slug_page)
    {
        // 1. Ambil Client
        $client = Client::where('slug_page', $slug_page)->firstOrFail();

        // 2. Ambil Category secara AJAIB menggunakan Eloquent
        // "Carikan Kategori... yang punya SubCategory... yang punya Skill... 
        // yang dimiliki oleh Client ini."
        $categories = Category::whereHas('subCategories.skills.clients', function ($query) use ($client) {
            $query->where('clients.id', $client->id);
        })
        ->withCount(['subCategories as skills_count' => function ($query) use ($client) {
            // Opsional: Menghitung berapa skill yang dimiliki client di kategori ini
            $query->whereHas('skills.clients', function ($q) use ($client) {
                $q->where('clients.id', $client->id);
            });
        }])
        ->get();

        return Inertia::render('Client/Portal/Home', [
            'client'     => $client,
            'categories' => $categories,
        ]);
    }

    /**
     * 2. List Experts: Menampilkan Daftar Expert dalam Kategori tertentu
     * URL: /portal/{client_slug}/category/{category_slug}
     */
    public function experts($slug_page, $category_slug)
    {
        $client = Client::where('slug_page', $slug_page)->firstOrFail();

        // 1. Validasi: Pastikan Kategori ini benar-benar ada di daftar akses Client
        // (Mencegah user mengintip kategori yang tidak dibeli client)
        $category = Category::where('slug', $category_slug)
            ->whereHas('subCategories.skills.clients', function ($query) use ($client) {
                $query->where('clients.id', $client->id);
            })
            ->firstOrFail();

        // 2. Query Expert
        // "Carikan Expert... yang punya setidaknya satu Skill...
        // yang Skill tersebut ada di dalam Kategori ini."
        $experts = Expert::with('user') // Eager load user profile
            ->whereHas('skills.subCategory.category', function ($query) use ($category) {
                $query->where('id', $category->id);
            })
            // Opsional: Filter hanya expert yang statusnya aktif (jika ada kolom status)
            // ->where('status', 'active') 
            ->get()
            ->map(function ($expert) {
                return [
                    'id'      => $expert->id,
                    'name'    => $expert->user->name,
                    'picture' => $expert->user->picture_url ?? "https://ui-avatars.com/api/?name={$expert->user->name}",
                    'title'   => $expert->title ?? 'Expert Consultant',
                    'price'   => $expert->price,
                ];
            });

        return Inertia::render('Client/Portal/ExpertList', [
            'client'    => $client,
            'category'  => $category,
            'experts'   => $experts,
        ]);
    }

    /**
     * 3. Expert Detail (Tetap sama)
     */
    public function show($expert_id)
    {
        $expert = Expert::with(['user', 'skills.subCategory'])->findOrFail($expert_id);

        return Inertia::render('Client/Portal/ExpertDetail', [
            'expert'  => $expert,
            'backUrl' => request('back', url()->previous()),
        ]);
    }
}
