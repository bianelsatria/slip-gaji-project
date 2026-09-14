<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request untuk validasi input form "Tambah Karyawan".
 * Memastikan Nama, NIK, dan Jabatan wajib diisi, NIK tidak boleh duplikat,
 * serta format Email dan Nomor Telepon valid sebelum data disimpan ke database.
 */
class KaryawanRequest extends FormRequest
{
    /**
     * Semua user yang sudah login (lolos middleware auth) boleh membuat
     * data karyawan baru. Tidak ada pembatasan role tambahan di aplikasi ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi tiap field pada form tambah karyawan.
     *
     * @return array<string, array>
     */
    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'max:50', 'unique:karyawans,nik'],
            'jabatan' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'no_telepon' => ['nullable', 'string', 'max:20'],
        ];
    }

    /**
     * Pesan error kustom berbahasa Indonesia yang ditampilkan lewat @error di Blade.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama.required' => 'Nama karyawan wajib diisi.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.unique' => 'NIK ini sudah terdaftar, gunakan NIK lain.',
            'jabatan.required' => 'Jabatan wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ];
    }
}
