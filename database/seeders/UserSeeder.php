<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // Admin user
        User::create([
            'name' => 'Admin SMK',
            'email' => 'admin@smk.com',
            'password' => Hash::make('123456'),
            'role' => 'admin',
        ]);

        // Guru user
        User::create([
            'name' => 'harsono',
            'email' => 'guru@smk.com',
            'password' => Hash::make('123456'),
            'role' => 'guru',
        ]);

        // Siswa user
        User::create([
            'name' => 'dimas pratama',
            'email' => 'siswa@smk.com',
            'password' => Hash::make('123456'),
            'role' => 'siswa',
        ]);
    }
}
