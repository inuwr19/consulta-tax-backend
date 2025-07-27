<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@amztax.com',
            'password' => Hash::make('admin123'),
            'phone_number' => '081100000000',
            'role' => 'admin',
            'address' => 'Kantor AMZ Tax Consultant, Jakarta',
        ]);

        // User 1
        User::create([
            'name' => 'Yusuf Aminu',
            'email' => 'aminuyusuf119@gmail.com',
            'password' => Hash::make('user123'),
            'phone_number' => '081388367927',
            'role' => 'user',
            'address' => 'Villa Tomang Baru A1 47, Gelam Jaya, Pasar Kemis, Tangerang',
        ]);

        // // User 2
        User::create([
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => Hash::make('user123'),
            'phone_number' => '081288800000',
            'role' => 'user',
            'address' => 'Jl. Mawar No. 12, Jakarta Selatan',
        ]);
    }

}
