<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin AoraGrand',
            'email' => 'admin@aoragrand.com',
            'password' => Hash::make('password123'),
            'phone' => '+62812345678',
            'address' => 'Jl. Hotel AoraGrand No. 1, Jakarta',
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create demo user
        User::create([
            'name' => 'John Doe',
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
            'phone' => '+62812345679',
            'address' => 'Jl. Contoh No. 123, Jakarta',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'identity_number' => '1234567890123456',
            'role' => 'user',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }
}
