<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Expert;
use App\Models\Skill; // <--- Ganti Expertise jadi Skill
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ExpertSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Data Dummy
        $positions      = ['Software Engineer', 'Backend Developer', 'Frontend Developer', 'System Analyst', 'DevOps Engineer', 'Data Analyst', 'IT Consultant', 'Project Manager'];
        $companies      = ['Tokopedia', 'Gojek', 'Bukalapak', 'Traveloka', 'Shopee', 'OVO', 'Ruangguru', 'Zenius', 'Telkom Indonesia', 'Bank Mandiri', 'BRI'];
        $certifications = ['AWS Certified Developer', 'Google Cloud Engineer', 'Laravel Professional', 'Scrum Master', 'Microsoft Azure Associate', 'PMP', 'ITIL 4 Foundation'];
        $types          = ['Counselor', 'Psychologist', 'Coach', 'Trainer', 'Consultant'];

        // Ambil semua Skill (Level 3)
        $allSkills = Skill::all();

        DB::transaction(function () use ($allSkills, $faker, $positions, $companies, $certifications, $types) {

            foreach ($allSkills as $skill) {
                // Target: Minimal 2 Expert per Skill
                $targetPerSkill = 2;

                // Cek jumlah expert yang sudah punya skill ini (via Pivot Table)
                $existingCount = Expert::whereHas('skills', function ($q) use ($skill) {
                    $q->where('id', $skill->id);
                })->count();

                $need = max(0, $targetPerSkill - $existingCount);
                if ($need === 0) continue;

                for ($i = 0; $i < $need; $i++) {
                    // Email unik
                    $email = Str::slug($skill->name) . "-{$skill->id}-" . Str::random(5) . '@seed.local';

                    // 1. Buat User
                    $name = $faker->name();
                    $user = User::firstOrCreate(
                        ['email' => $email],
                        [
                            'name'     => $name,
                            'phone'    => '628' . $faker->numerify('##########'),
                            'slug'     => Str::slug($name) . '-' . Str::lower(Str::random(6)),
                            'password' => Hash::make('password'),
                            'gender'   => $faker->randomElement(['L', 'P']),
                            'address'  => $faker->address,
                            'roles'    => ['user', 'expert'],
                        ]
                    );

                    // 2. Buat Expert Profile
                    // (HAPUS kolom expertise_id dan expertise string di sini)
                    $expert = Expert::updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'biography'   => '<p>' . $faker->paragraph(5) . '</p>',
                            'experiences' => [
                                [
                                    'position'    => $faker->randomElement($positions),
                                    'company'     => $faker->randomElement($companies),
                                    'years'       => (string) $faker->numberBetween(2015, 2024),
                                    'description' => $faker->paragraph(3),
                                ],
                            ],
                            'licenses'    => [
                                [
                                    'certification' => $faker->randomElement($certifications),
                                    'attachment'    => null,
                                ],
                            ],
                            'price'       => $faker->numberBetween(5, 20) * 10000,
                            'type'        => $faker->randomElements($types, $faker->numberBetween(1, 3)),
                        ]
                    );

                    // 3. ISI PIVOT TABLE (expert_skill)
                    // Expert ini PASTI punya skill loop saat ini
                    // Opsional: Tambahkan 1-2 skill acak lain biar terlihat realistis
                    $randomExtraSkills = Skill::inRandomOrder()->limit(rand(0, 2))->pluck('id')->toArray();

                    // Gabungkan skill utama + skill random
                    $skillIdsToAttach = array_unique(array_merge([$skill->id], $randomExtraSkills));

                    $expert->skills()->syncWithoutDetaching($skillIdsToAttach);
                }
            }
        });
    }
}
