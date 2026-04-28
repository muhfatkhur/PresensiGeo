<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');


        $mapel = ['Matematika', 'B. Inggris', 'Pemrograman Web', 'Basis Data', 'PBO', 'PKn', 'Agama', 'B. Indonesia', 'Fisika', 'Kimia'];
        
        for ($i = 0; $i < 10; $i++) {
            $guru = Guru::create([
                'nip' => $faker->unique()->numerify('198#########00#'),
                'nama' => $faker->name,
                'mata_pelajaran' => $mapel[$i]
            ]);

            User::create([
                'name' => $guru->nama,
                'email' => 'guru' . ($i + 1) . '@smk.com',
                'password' => Hash::make('password123'),
                'role' => 'guru',
                'guru_id' => $guru->id
            ]);
        }

        $kelasLvl = ['X RPL 1', 'X RPL 2', 'XI RPL 1', 'XII RPL 1'];
        
        for ($i = 0; $i < 10; $i++) {
            $siswa = Siswa::create([
                'nama' => $faker->name,
                'kelas' => $faker->randomElement($kelasLvl),
                'no_wa_wali' => '628' . $faker->numerify('#########')
            ]);

            User::create([
                'name' => $siswa->nama,
                'email' => 'siswa' . ($i + 1) . '@smk.com',
                'password' => Hash::make('password123'),
                'role' => 'siswa',
                'siswa_id' => $siswa->id
            ]);
        }
    }
}