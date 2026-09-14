<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration tabel slip_gajis.
 * Menyimpan riwayat slip gaji yang sudah diproses per periode untuk setiap karyawan.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slip_gajis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained()->cascadeOnDelete();
            $table->string('periode'); // contoh: "September 2024"
            $table->decimal('gaji_pokok', 15, 2);
            $table->decimal('lembur', 15, 2)->default(0);
            $table->decimal('pinjaman_karyawan', 15, 2)->default(0);
            $table->decimal('total_penghasilan', 15, 2);
            $table->decimal('total_potongan', 15, 2);
            $table->decimal('gaji_bersih', 15, 2);
            $table->timestamp('dikirim_email_at')->nullable();
            $table->timestamp('dikirim_whatsapp_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slip_gajis');
    }
};
