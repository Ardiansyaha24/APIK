<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin Utama
        User::updateOrCreate(
            ['email' => 'lp2m@iain-manado.ac.id'],
            [
                'name' => 'Admin LP2M IAIN Manado',
                'password' => Hash::make('qwerty12345'),
            ]
        );

        // 2. Akun Admin Rafiud
        User::updateOrCreate(
            ['email' => 'rafiud1243@gmail.com'],
            [
                'name' => 'Admin Rafiud',
                'password' => Hash::make('qwerty12345'),
            ]
        );
    }
}
