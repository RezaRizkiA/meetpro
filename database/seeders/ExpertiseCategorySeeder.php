<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ExpertiseCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Teknik & Teknologi' => 'Keahlian teknis di bidang rekayasa dan teknologi modern.',
            'Bisnis & Manajemen' => 'Pengelolaan bisnis, strategi, dan operasional perusahaan.',
            'Pendidikan & Pelatihan' => 'Pengembangan kurikulum, pembelajaran, dan pelatihan.',
            'Hukum & Regulasi' => 'Bidang hukum, kepatuhan, dan peraturan perundang-undangan.',
            'Kesehatan & Psikologi' => 'Layanan medis, psikologis, dan kesejahteraan mental.',
            'Human Capital & Pengembangan SDM' => 'Pengelolaan dan pengembangan sumber daya manusia.',
            'Kreatif & Seni' => 'Ekspresi artistik melalui desain, media, dan seni digital.',
            'Pemerintahan & Sosial' => 'Layanan publik, kebijakan, dan pembangunan sosial.',
            'Lingkungan & Pertanian' => 'Keberlanjutan alam, agrikultur, dan konservasi lingkungan.',
            'Digital & Industri Kreatif' => 'Teknologi digital, konten kreatif, dan pemasaran daring.',
        ];

        foreach ($categories as $name => $desc) {
            $slug = Str::slug($name);

            if (!DB::table('expertise_categories')->where('slug', $slug)->exists()) {
                DB::table('expertise_categories')->insert([
                    'name' => $name,
                    'slug' => $slug,
                    'desc' => $desc,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
