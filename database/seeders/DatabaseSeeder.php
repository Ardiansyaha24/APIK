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
        // 1 Akun Utama Super Administrator
        User::updateOrCreate(
            ['email' => 'admin@apik.local'],
            [
                'name' => 'Administrator APIK',
                'password' => Hash::make('password'),
            ]
        );
    }
}
