<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model SlipGaji.
 * Merepresentasikan satu slip gaji yang sudah diproses untuk seorang karyawan
 * pada periode tertentu, lengkap dengan hasil kalkulasi penghasilan/potongan.
 */
class SlipGaji extends Model
{
    use HasFactory;

    protected $fillable = [
        'karyawan_id',
        'periode',
        'periode_awal',
        'periode_akhir',
        'gaji_pokok',
        'lembur',
        'pinjaman_karyawan',
        'total_penghasilan',
        'total_potongan',
        'gaji_bersih',
        'dikirim_email_at',
        'dikirim_whatsapp_at',
    ];

    /**
     * Aturan konversi tipe data otomatis: semua kolom nominal uang
     * disimpan/dibaca sebagai angka desimal 2 digit, periode_awal/periode_akhir
     * otomatis jadi object tanggal Carbon, dan kolom waktu
     * kirim (email/whatsapp) otomatis jadi object tanggal Carbon.
     */
    protected function casts(): array
    {
        return [
            'periode_awal' => 'date',
            'periode_akhir' => 'date',
            'gaji_pokok' => 'decimal:2',
            'lembur' => 'decimal:2',
            'pinjaman_karyawan' => 'decimal:2',
            'total_penghasilan' => 'decimal:2',
            'total_potongan' => 'decimal:2',
            'gaji_bersih' => 'decimal:2',
            'dikirim_email_at' => 'datetime',
            'dikirim_whatsapp_at' => 'datetime',
        ];
    }

    /**
     * Relasi: setiap slip gaji pasti milik satu karyawan.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    /**
     * Format salah satu kolom nominal uang menjadi teks rupiah yang rapi,
     * supaya tidak perlu menulis ulang logika format di banyak file Blade.
     * Contoh hasil: 5500000 -> "Rp 5.500.000".
     *
     * @param  string  $field  nama kolom yang mau diformat (contoh: 'gaji_bersih')
     * @return string  teks rupiah yang sudah rapi, siap ditampilkan di view
     */
    public function formatRupiah(string $field): string
    {
        return 'Rp ' . number_format((float) $this->{$field}, 0, ',', '.');
    }
}
