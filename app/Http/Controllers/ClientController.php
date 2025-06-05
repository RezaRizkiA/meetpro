<?php
namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Expertise;
use App\Models\ExpertiseCategory;

class ClientController extends Controller
{
    public function home_client($slug_page)
    {
        $client = Client::where('slug_page', $slug_page)->firstOrFail();

        $expertiseIds = is_array($client->expertise_id)
        ? $client->expertise_id
        : explode(',', $client->expertise_id);

        // Jika $client->expertise_id sudah koleksi (Collection) atau array, langsung gunakan:
        $expertises = Expertise::whereIn('id', $expertiseIds)
            ->with('category') // eager load category
            ->get();

        // 2. Ambil daftar ID kategori unik
        $categoryIds = $expertises
            ->pluck('category_id') // koleksi ID kategori (boleh ada duplikat)
            ->filter()             // buang null (jika ada yang nullable)
            ->unique()             // ambil yang unik saja
            ->values()             // reset indeks
            ->all();               // jadi array

        // 3. Ambil model ExpertiseCategory yang terkait + eager load relasi expertises
        //  Kalau hanya ingin hitung semua expertises di setiap kategori (global), cukup:
        $categories = ExpertiseCategory::whereIn('id', $categoryIds)
            ->withCount('expertises') // menghitung total semua expertises di kategori itu
            ->get();

        return view('clients/home_client', compact('client', 'categories'));
    }
}
