<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder untuk membuat 1 akun user + data karyawan contoh,
 * sesuai data pada rancangan tampilan (Ahmad Fauzi, Staff Keuangan).
 */
class KaryawanSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'bianelsatria@gmail.com'],
            [
                'name' => 'Bianel Satria',
                'password' => Hash::make('bian123'),
            ]
        );

        Karyawan::firstOrCreate(
            ['user_id' => $user->id],
            [
                'nama' => 'Bianel Satria',
                'nik' => '20090308',
                'jabatan' => 'CEO',
                'email' => 'bianelsatria@gmail.com',
                'no_telepon' => '0895619816477',
                'gaji_pokok_default' => 555000000,
            ]
        );
    }
}
