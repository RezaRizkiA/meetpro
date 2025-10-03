<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\Expertise;
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

        // Ambil hanya LEAF (level 3): parent_id ≠ null
        $leafIds = Expertise::whereNotNull('parent_id')->pluck('id')->all();

        DB::transaction(function () use ($faker, $leafIds) {

            for ($i = 1; $i < 3; $i++) { // hasilkan 2 client (1 & 2)
                $name  = $faker->name();
                $email = "client{$i}@gmail.com";

                // --- USER (idempotent by email) ---
                $user = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name'     => $name,
                        'phone'    => '628' . $faker->numerify('##########'),
                        'slug'     => Str::slug($name) . '-' . Str::lower(Str::random(6)),
                        'password' => Hash::make('password'),
                        'gender'   => $faker->randomElement(['L', 'P']),
                        'address'  => $faker->address,
                        'roles'    => ['user', 'client'],
                    ]
                );

                // Jika user sudah ada, pastikan roles mengandung 'user' & 'client'
                if ($user->wasRecentlyCreated === false) {
                    $roles = (array) ($user->roles ?? []);
                    $roles[] = 'user';
                    $roles[] = 'client';
                    $roles = array_values(array_unique($roles));
                    $user->update(['roles' => $roles]);
                }

                // Pilih 3–4 leaf expertise acak (string ids)
                $pickCount = min(4, max(3, 3));
                $selected  = $this->pickLeafIds($leafIds, $pickCount);

                // --- CLIENT (idempotent by user_id) ---
                Client::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'section_hero'       => $faker->text(15),
                        'banner_title'       => $faker->sentence(3),
                        'banner_desc'        => $faker->paragraph(5),
                        'author_name'        => $user->name,
                        'author_photo'       => null,
                        'banner_background'  => null,
                        'banner_footer'      => $faker->sentence(2),
                        'banner_footer_desc' => $faker->sentence(4),
                        'color'              => null,
                        'logo'               => null,
                        'slug_page'          => Str::slug($user->name) . '-' . substr($user->id, 0, 6),
                        // simpan sebagai array string sesuai pola kamu
                        'expertise_id'       => array_map('strval', $selected),
                    ]
                );
            }
        });
    }

    /**
     * Pilih N id leaf unik secara acak dari array sumber,
     * aman untuk kasus jumlah sumber < N.
     */
    private function pickLeafIds(array $source, int $n): array
    {
        $source = array_values(array_unique($source));
        if (empty($source)) return [];

        $n = min($n, count($source));
        if ($n <= 1) return [$source[array_rand($source)]];

        $keys = array_rand($source, $n);
        if (!is_array($keys)) $keys = [$keys];

        return array_map(fn($k) => $source[$k], $keys);
    }
}
