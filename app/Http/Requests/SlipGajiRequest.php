<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request untuk validasi input slip gaji.
 * Memvalidasi nilai Gaji Pokok, Lembur, Pinjaman Karyawan (numerik, non-negatif)
 * dan mencocokkan jawaban captcha matematika terhadap nilai yang disimpan di session.
 */
class SlipGajiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // karyawan_id dikirim lewat hidden input di form slip gaji, dipakai
            // controller untuk tahu slip gaji ini mau disimpan untuk karyawan yang mana.
            'karyawan_id' => ['nullable', 'integer', 'exists:karyawans,id'],
            // UPDATE: periode sekarang dipilih manual lewat 2 input tanggal.
            // periode_akhir wajib sama atau setelah periode_awal (tidak boleh terbalik).
            'periode_awal' => ['required', 'date'],
            'periode_akhir' => ['required', 'date', 'after_or_equal:periode_awal'],
            'gaji_pokok' => ['required', 'numeric', 'min:0'],
            'lembur' => ['nullable', 'numeric', 'min:0'],
            'pinjaman_karyawan' => ['nullable', 'numeric', 'min:0'],
            'jawaban_captcha' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    $expected = (int) session('captcha_a') + (int) session('captcha_b');

                    if ((int) $value !== $expected) {
                        $fail('Verifikasi diperlukan.');
                    }
                },
            ],
        ];
    }

    /**
     * Pesan error kustom berbahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'gaji_pokok.required' => 'Gaji pokok wajib diisi.',
            'gaji_pokok.numeric' => 'Gaji pokok harus berupa angka.',
            'lembur.numeric' => 'Lembur harus berupa angka.',
            'pinjaman_karyawan.numeric' => 'Pinjaman karyawan harus berupa angka.',
            'periode_awal.required' => 'Tanggal mulai periode wajib diisi.',
            'periode_akhir.required' => 'Tanggal akhir periode wajib diisi.',
            'periode_akhir.after_or_equal' => 'Tanggal akhir periode tidak boleh sebelum tanggal mulai.',
            'jawaban_captcha.required' => 'Verifikasi diperlukan.',
            'jawaban_captcha.numeric' => 'Jawaban verifikasi harus berupa angka.',
        ];
    }
}
