<?php
namespace Database\Seeders;

use App\Models\Client;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        for ($i = 1; $i < 3; $i++) {
            $name  = $faker->name();
            $email = "client{$i}@example.com";

            $user = User::create([
                'name'     => $name,
                'email'    => $email,
                'phone'    => '628' . rand(1000000000, 9999999999),
                'slug'     => Str::slug($name),
                'password' => Hash::make('password'),
                'gender'   => 'L',
                'address'  => $faker->address,
                'roles'    => ['user', 'client'],
            ]);

            Client::create([
                'user_id'            => $user->id,
                'section_hero'       => $faker->text(15),
                'banner_title'       => $faker->sentence(3),
                'banner_desc'        => $faker->paragraph(5),
                'author_name'        => $name,
                'author_photo'       => null,
                'banner_background'  => null,
                'banner_footer'      => $faker->sentence(2),
                'banner_footer_desc' => $faker->sentence(4),
                'color'              => null,
                'logo'               => null,
                'slug_page'          => Str::slug($name),
                'expertise_id'       => array_map('strval', array_rand(array_flip(range(1, 59)), 4)),
            ]);

        }
    }
}
