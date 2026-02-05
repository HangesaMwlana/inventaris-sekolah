<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Penting untuk hashing password
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Data Login Utama (Admin & Petugas)
        User::create([
            'name' => 'Admin Sistem',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Petugas Inventaris',
            'email' => 'petugas@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'petugas',
        ]);

        // 2. Data Siswa atau Test User
        User::create([
            'name' => 'Siswa Peminjam',
            'email' => 'siswa@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'siswa',
        ]);
    }
}