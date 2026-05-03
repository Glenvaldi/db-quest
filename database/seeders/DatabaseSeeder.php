<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\School;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. MEMBUAT DAFTAR SEKOLAH OTOMATIS
        // (Pakai firstOrCreate agar aman, kalau datanya sudah ada tidak akan didobel)
        $school1 = School::firstOrCreate(['name' => 'SMK Negeri 1 Malang']);
        School::firstOrCreate(['name' => 'SMA Negeri 1 Malang']);
        School::firstOrCreate(['name' => 'SMK Telkom Malang']);

        // 2. MEMBUAT AKUN ADMIN OTOMATIS
        User::firstOrCreate(
            ['email' => 'admin@dbquest.com'], // Email Admin
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password123'), // Password Admin
                'role' => 'admin',
                'school_id' => $school1->id,
            ]
        );
    }
}