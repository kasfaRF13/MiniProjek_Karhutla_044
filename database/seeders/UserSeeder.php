<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Pelapor
        User::create([
            'name' => 'Warga Pelapor',
            'email' => 'pelapor@karhutla.test',
            'password' => Hash::make('password123'),
            'role' => 'pelapor',
        ]);

        // Akun Relawan
        User::create([
            'name' => 'Tim Relawan Lapangan',
            'email' => 'relawan@karhutla.test',
            'password' => Hash::make('password123'),
            'role' => 'relawan',
        ]);

        // Akun Koordinator
        User::create([
            'name' => 'Koordinator Pusat',
            'email' => 'koordinator@karhutla.test',
            'password' => Hash::make('password123'),
            'role' => 'koordinator',
        ]);
    }
}

