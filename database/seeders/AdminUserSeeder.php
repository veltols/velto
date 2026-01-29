<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@velto.com'],
            [
                'name' => 'Velto Admin',
                'password' => Hash::make('password'), // Default password
                'is_admin' => true,
                'email_verified_at' => now(),
                'phone' => '03001234567',
                'address' => 'Velto HQ',
                'city' => 'Lahore',
            ]
        );
    }
}
