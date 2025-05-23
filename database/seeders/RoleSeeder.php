<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            'Administrator',
            'User',
            'Client',
            'Expert',
        ];

        foreach ($roles as $roleName) {
            $slug = Str::slug($roleName);
            // Cek apakah slug sudah ada
            $exists = DB::table('roles')->where('slug', $slug)->exists();
            if (!$exists) {
                DB::table('roles')->insert([
                    'name' => $roleName,
                    'slug' => $slug,
                ]);
            }
        }
    }
}
