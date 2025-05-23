<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ExpertiseSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'Teknik & Teknologi' => [
                'Teknik Sipil',
                'Teknik Mesin',
                'Teknik Elektro',
                'Teknik Informatika / Software Engineering',
                'Cybersecurity',
                'Data Science / Data Analysis',
                'Cloud Computing',
                'Artificial Intelligence / Machine Learning',
                'UI/UX Design',
                'DevOps',
                'Internet of Things (IoT)',
            ],
            'Bisnis & Manajemen' => [
                'Manajemen Strategis',
                'Manajemen Proyek',
                'Manajemen SDM (HR)',
                'Manajemen Operasional',
                'Business Development',
                'Pemasaran Digital (Digital Marketing)',
                'Branding & Komunikasi',
                'Sales / Penjualan',
                'Keuangan Perusahaan',
                'Akuntansi & Perpajakan',
                'Supply Chain Management',
                'Customer Relationship Management (CRM)',
            ],
            'Pendidikan & Pelatihan' => [
                'Kurikulum dan Pembelajaran',
                'Desain Instruksional',
                'E-learning & LMS',
                'Pendidikan Anak Usia Dini (PAUD)',
                'Pendidikan Inklusif',
                'Pendidikan Karakter',
                'Pelatihan Soft Skill',
                'Fasilitasi dan Coaching',
            ],
            'Hukum & Regulasi' => [
                'Hukum Perdata',
                'Hukum Pidana',
                'Hukum Bisnis',
                'Hukum Ketenagakerjaan',
                'Hukum Perpajakan',
                'Kepatuhan & Audit Hukum (Compliance)',
            ],
            'Kesehatan & Psikologi' => [
                'Kedokteran Umum',
                'Keperawatan',
                'Farmasi',
                'Gizi & Dietetik',
                'Psikologi Klinis',
                'Psikologi Industri & Organisasi',
                'Kesehatan Mental & Konseling',
                'Hipnoterapi / NLP',
            ],
            'Human Capital & Pengembangan SDM' => [
                'Rekrutmen dan Seleksi',
                'Employee Engagement',
                'Talent Management',
                'Career Coaching',
                'Leadership Development',
                'Organizational Development',
                'Performance Management',
                'HR Analytics',
            ],
            'Kreatif & Seni' => [
                'Desain Grafis',
                'Desain Produk',
                'Animasi & Multimedia',
                'Videografi & Editing',
                'Fotografi',
                'Penulisan Kreatif',
                'Content Creation',
                'Ilustrasi Digital',
            ],
            'Pemerintahan & Sosial' => [
                'Administrasi Publik',
                'Pelayanan Masyarakat',
                'Pemberdayaan Komunitas',
                'Studi Kebijakan',
                'Hubungan Internasional',
                'Sosiologi Terapan',
            ],
            'Lingkungan & Pertanian' => [
                'Teknik Lingkungan',
                'Manajemen Limbah',
                'Kehutanan',
                'Agribisnis',
                'Perikanan & Kelautan',
                'Peternakan',
            ],
            'Digital & Industri Kreatif' => [
                'Social Media Marketing',
                'SEO / SEM',
                'E-commerce Strategy',
                'Influencer Management',
                'Copywriting',
                'UI/UX Writing',
            ],
        ];

        foreach ($data as $category => $expertises) {
            $categoryId = DB::table('expertise_categories')->where('slug', Str::slug($category))->value('id');

            if ($categoryId) {
                foreach ($expertises as $expertise) {
                    $slug = Str::slug($expertise);

                    if (!DB::table('expertises')->where('slug', $slug)->exists()) {
                        DB::table('expertises')->insert([
                            'category_id' => $categoryId,
                            'name' => $expertise,
                            'slug' => $slug,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
    }
}
