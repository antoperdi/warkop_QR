<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat akun admin pertama
        User::updateOrCreate(
            ['email' => 'admin@warkop.com'],
            [
                'name' => 'Admin Warkop',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
            ]
        );
    }
}
