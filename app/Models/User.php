<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model User.
 *
 * Model bawaan Laravel yang dipakai untuk login (Auth::attempt di
 * AuthenticatedSessionController). Di aplikasi ini, User berperan sebagai
 * akun admin/HR yang setelah login akan melihat halaman List Karyawan
 * dan bisa mengelola slip gaji semua karyawan yang ada.
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang boleh diisi lewat mass-assignment saat membuat/mengubah user.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Kolom yang disembunyikan saat model ini diubah jadi array/JSON
     * (misalnya password tidak boleh ikut terkirim ke response).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Aturan konversi tipe data otomatis untuk beberapa kolom,
     * termasuk otomatis meng-hash password saat disimpan.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi satu-ke-satu (opsional) ke data karyawan (Nama, NIK, Jabatan).
     * Dulu dipakai supaya "user yang login = karyawan itu sendiri" (self-service).
     * Sejak ada halaman List Karyawan, relasi ini tetap dipertahankan sebagai
     * fallback: kalau slip gaji dibuka tanpa memilih karyawan tertentu, sistem
     * akan memakai data karyawan milik user yang login (jika ada).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function karyawan()
    {
        return $this->hasOne(Karyawan::class);
    }
}
