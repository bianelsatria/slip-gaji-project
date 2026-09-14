<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder untuk membuat 1 akun user + data karyawan contoh,
 * sesuai data pada rancangan tampilan figma (Bianel Satria, CEO).
 */
class KaryawanSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'bian@gmail.com'],
            [
                'name' => 'Bianel Satria',
                'password' => Hash::make('bian123'),
            ]
        );

        Karyawan::firstOrCreate(
            ['user_id' => $user->id],
            [
                'nama' => 'Bianel Satria',
                'nik' => '20260303',
                'jabatan' => 'CEO',
                'email' => 'bian@gmail.com',
                'no_telepon' => '0867676767',
                'gaji_pokok_default' => 300000000,
            ]
        );
    }
}
