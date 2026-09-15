<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration tambahan: menambah kolom periode_awal & periode_akhir (tanggal)
 * ke tabel slip_gajis. UPDATE FITUR: periode slip gaji sekarang dipilih
 * sendiri oleh user lewat 2 input tanggal ("Dari Tanggal" - "Sampai Tanggal"),
 * tidak lagi otomatis dari bulan berjalan.
 *
 * Kolom `periode` (teks) yang sudah ada TETAP dipakai untuk ditampilkan di
 * tabel/PDF/email (contoh: "01 September 2024 - 30 September 2024"), tapi
 * sekarang isinya dihitung dari periode_awal & periode_akhir, bukan dari
 * tanggal hari ini. Dibuat nullable supaya data lama (kalau ada) tidak error.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('slip_gajis', function (Blueprint $table) {
            $table->date('periode_awal')->nullable()->after('karyawan_id');
            $table->date('periode_akhir')->nullable()->after('periode_awal');
        });
    }

    public function down(): void
    {
        Schema::table('slip_gajis', function (Blueprint $table) {
            $table->dropColumn(['periode_awal', 'periode_akhir']);
        });
    }
};
