<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Karyawan.
 *
 * Model ini mewakili satu baris data karyawan di database (tabel `karyawans`).
 * Dipakai di 2 tempat utama:
 * 1. Halaman "List Karyawan" -> menampilkan semua data karyawan dalam tabel.
 * 2. Halaman "Slip Gaji" -> menampilkan Nama/NIK/Jabatan secara read-only,
 *    dan menjadi induk (parent) dari data SlipGaji yang dibuat untuk karyawan itu.
 */
class Karyawan extends Model
{
    use HasFactory;

    /**
     * Kolom apa saja yang boleh diisi lewat mass-assignment (Karyawan::create([...])).
     * Ditambahkan 'email' dan 'no_telepon' untuk fitur Tambah Karyawan yang baru.
     */
    protected $fillable = [
        'user_id',
        'nama',
        'nik',
        'jabatan',
        'email',
        'no_telepon',
        'gaji_pokok_default',
    ];

    /**
     * Aturan konversi tipe data otomatis. gaji_pokok_default disimpan sebagai
     * angka desimal 2 digit di belakang koma supaya perhitungan uang akurat.
     */
    protected function casts(): array
    {
        return [
            'gaji_pokok_default' => 'decimal:2',
        ];
    }

    /**
     * Relasi: satu karyawan bisa terhubung ke satu akun User (untuk login),
     * tapi ini sifatnya opsional sejak ada halaman List Karyawan -
     * karyawan bisa dibuat oleh admin tanpa harus punya akun login sendiri.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: satu karyawan bisa memiliki banyak riwayat SlipGaji
     * (satu baris SlipGaji untuk setiap periode/bulan yang sudah diproses).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function slipGajis()
    {
        return $this->hasMany(SlipGaji::class);
    }

    /**
     * Membuat inisial dari nama karyawan untuk ditampilkan di avatar bulat
     * pada header halaman (contoh: "Ahmad Fauzi" -> "AF").
     * Dipakai di beberapa file Blade supaya logikanya tidak diulang-ulang di view.
     *
     * @return string  inisial 1-2 huruf kapital
     */
    public function inisial(): string
    {
        $bagianNama = explode(' ', trim($this->nama));
        $depan = mb_substr($bagianNama[0] ?? '', 0, 1);
        $belakang = count($bagianNama) > 1 ? mb_substr(end($bagianNama), 0, 1) : '';

        return mb_strtoupper($depan . $belakang);
    }

    /**
     * Format gaji_pokok_default jadi teks rupiah yang rapi untuk ditampilkan
     * di tabel List Karyawan. Contoh hasil: 5500000 -> "Rp 5.500.000".
     *
     * @return string
     */
    public function formatGajiPokok(): string
    {
        return 'Rp ' . number_format((float) $this->gaji_pokok_default, 0, ',', '.');
    }
}
