<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'email' => 'kastaraparama.idn@gmail.com',
                'slug'  => 'kastara-parama-nusantara',
                'name'  => 'Kastara Parama Nusantara',
            ],
            [
                'email' => 'bu4893229@gmail.com',
                'slug'  => 'kastara-parama',
                'name'  => 'Kastara Parama',
            ],
        ];

        foreach ($users as $data) {
            $existingUser = User::where('email', $data['email'])->first();

            if (!$existingUser) {
                $adminExists = User::whereJsonContains('roles', 'administrator')->exists();

                $roles = ['user'];
                if (!$adminExists && $data['email'] === 'kastaraparama.idn@gmail.com') {
                    $roles[] = 'administrator';
                }

                User::create([
                    'name'     => $data['name'],
                    'email'    => $data['email'],
                    'phone'    => '6282299668860',
                    'slug'     => $data['slug'],
                    'password' => 'KASTARAAPPS', // diasumsikan password di-cast agar auto hash
                    'gender'   => 'L',
                    'address'  => 'Jl. Paus Dalam No.37 Rawamangun, Pulo Gadung, Jakarta Timur 13220',
                    'roles'    => $roles,
                ]);
            }
        }
    }
}
