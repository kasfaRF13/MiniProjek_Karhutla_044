<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Pelapor (Masyarakat)
       User::firstOrCreate(
            ['email' => 'pelapor@karhutla.test'],
            [
                'name' => 'Warga Pelapor',
                'password' => Hash::make('password123'),
                'role' => 'pelapor',
            ]
        );
        
        // 2. Akun Relawan Pemantau
        User::firstOrCreate(
            ['email' => 'relawan@karhutla.test'],
            [
                'name' => 'Budi Relawan',
                'password' => Hash::make('password123'),
                'role' => 'relawan',
            ]
        );

        // 3. Akun Koordinator Relawan
        User::firstOrCreate(
            ['email' => 'koordinator@karhutla.test'],
            [
                'name' => 'Andi Koordinator',
                'password' => Hash::make('password123'),
                'role' => 'koordinator',
            ]
        );
    }
}