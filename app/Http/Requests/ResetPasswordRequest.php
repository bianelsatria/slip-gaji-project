<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request untuk validasi input di halaman "Reset Kata Sandi" (langkah 2):
 * memastikan email valid, kode 6 digit diisi, dan kata sandi baru memenuhi
 * syarat minimal serta cocok dengan konfirmasinya.
 */
class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users,email'],
            'kode' => ['required', 'string', 'size:6'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email wajib diisi.',
            'email.exists' => 'Email ini tidak terdaftar di sistem.',
            'kode.required' => 'Kode verifikasi wajib diisi.',
            'kode.size' => 'Kode verifikasi harus 6 digit.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ];
    }
}
