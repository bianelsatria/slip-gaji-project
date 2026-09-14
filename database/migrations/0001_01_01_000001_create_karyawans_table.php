<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration tabel karyawans.
 * Menyimpan data identitas karyawan (Nama, NIK, Jabatan) yang direlasikan
 * satu-ke-satu dengan tabel users (akun login).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('nama');
            $table->string('nik')->unique();
            $table->string('jabatan');
            // Gaji pokok default, dipakai sebagai nilai awal saat mengisi slip gaji baru
            $table->decimal('gaji_pokok_default', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};
