<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Jalankan seeder utama aplikasi.
     * Memanggil KaryawanSeeder untuk membuat akun contoh (Bianel Satria)
     * agar bisa langsung dipakai untuk login & uji coba slip gaji.
     */
    public function run(): void
    {
        $this->call([
            KaryawanSeeder::class,
        ]);
    }
}
