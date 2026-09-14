<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration tambahan: menambah kolom email dan no_telepon ke tabel karyawans.
 * Dibuat sebagai migration BARU (bukan mengubah migration lama) supaya riwayat
 * migration tetap rapi dan aman dijalankan di database yang sudah pernah di-migrate.
 */
return new class extends Migration
{
    /**
     * Jalankan migration: tambah kolom email & no_telepon setelah kolom jabatan.
     * Kedua kolom dibuat nullable supaya data karyawan lama (yang sudah ada
     * sebelum fitur ini dibuat) tidak error karena kolom kosong.
     */
    public function up(): void
    {
        Schema::table('karyawans', function (Blueprint $table) {
            $table->string('email')->nullable()->after('jabatan');
            $table->string('no_telepon')->nullable()->after('email');
        });
    }

    /**
     * Batalkan migration: hapus kembali kolom email & no_telepon.
     */
    public function down(): void
    {
        Schema::table('karyawans', function (Blueprint $table) {
            $table->dropColumn(['email', 'no_telepon']);
        });
    }
};
