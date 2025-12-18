<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\Skill;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Ambil semua ID Skill
        $allSkillIds = Skill::pluck('id')->toArray();

        DB::transaction(function () use ($faker, $allSkillIds) {

            // Buat 2 Client Dummy
            for ($i = 1; $i <= 2; $i++) {
                $name  = $faker->company(); // Pakai nama perusahaan biar relevan
                $email = "client{$i}@gmail.com";

                // 1. Buat User
                $user = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name'     => $name,
                        'phone'    => '628' . $faker->numerify('##########'),
                        'slug'     => Str::slug($name) . '-' . Str::lower(Str::random(6)),
                        'password' => Hash::make('password'),
                        'gender'   => $faker->randomElement(['L', 'P']),
                        'address'  => $faker->address,
                        'roles'    => ['user', 'client'], // Pastikan setup roles/permission sesuai
                    ]
                );

                // Ensure Roles logic (jika pakai Spatie atau array manual)
                $roles = (array) ($user->roles ?? []);
                if (!in_array('client', $roles)) {
                    $roles[] = 'client';
                    $user->update(['roles' => array_unique($roles)]);
                }

                // 2. Pilih Skill Random untuk Client ini
                // Client membeli akses untuk 3-5 skill acak
                $selectedSkills = $this->pickRandomIds($allSkillIds, rand(3, 5));

                // 3. Buat Client Profile
                // (HAPUS kolom expertise_id)
                $client = Client::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'section_hero'       => $faker->text(20),
                        'banner_title'       => $faker->sentence(3),
                        'banner_desc'        => $faker->paragraph(2),
                        'author_name'        => $faker->name, // HR Manager name maybe?
                        'slug_page'          => Str::slug($name),
                        // Hapus expertise_id di sini
                    ]
                );

                // 4. ISI PIVOT TABLE (client_skill)
                $client->skills()->sync($selectedSkills);
            }
        });
    }

    /**
     * Helper untuk ambil N ID unik acak
     */
    private function pickRandomIds(array $source, int $n): array
    {
        if (empty($source)) return [];
        $n = min($n, count($source));
        if ($n === 0) return [];

        $keys = array_rand($source, $n);

        // array_rand mengembalikan int jika n=1, array jika n>1
        if (!is_array($keys)) {
            return [$source[$keys]];
        }

        return array_map(fn($k) => $source[$k], $keys);
    }
}
