<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Mail\PasswordResetCodeMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

/**
 * Controller untuk fitur "Lupa Kata Sandi" dengan KODE VERIFIKASI via email
 * (bukan link reset). Alurnya 2 langkah:
 * 1. User isi email di halaman Forgot Password -> sistem generate kode 6 digit,
 *    simpan (dalam bentuk ter-hash) di tabel password_reset_tokens, lalu
 *    kirim kode itu ke email user.
 * 2. User isi email + kode + kata sandi baru di halaman Reset Password ->
 *    sistem cocokkan kode & cek belum kedaluwarsa, lalu update password user.
 */
class PasswordResetController extends Controller
{
    /**
     * Berapa lama (dalam menit) kode verifikasi berlaku sejak dikirim.
     */
    private const MASA_BERLAKU_KODE_MENIT = 60;

    /**
     * Tampilkan halaman "Lupa Kata Sandi" (form input email).
     *
     * @return View
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Proses permintaan lupa kata sandi: generate kode 6 digit acak, simpan
     * versi ter-hash-nya ke tabel password_reset_tokens, lalu kirim kode
     * tersebut (versi asli, tidak di-hash) ke email user lewat Laravel Mail.
     *
     * @param  ForgotPasswordRequest  $request  berisi email yang sudah tervalidasi & terdaftar
     * @return RedirectResponse
     */
    public function store(ForgotPasswordRequest $request): RedirectResponse
    {
        $email = $request->validated()['email'];

        // Kode 6 digit acak, contoh: "048213". str_pad supaya angka kecil
        // (misal 42) tetap tampil 6 digit ("000042").
        $kode = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // updateOrInsert: kalau user ini sebelumnya pernah minta kode juga,
        // kode lama otomatis ditimpa (bukan menumpuk banyak baris).
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            ['token' => Hash::make($kode), 'created_at' => now()]
        );

        Mail::to($email)->send(new PasswordResetCodeMail($kode, self::MASA_BERLAKU_KODE_MENIT));

        return redirect()
            ->route('password.reset.form', ['email' => $email])
            ->with('status', 'Kode verifikasi sudah dikirim ke email Anda. Cek inbox atau folder spam.');
    }

    /**
     * Tampilkan halaman "Reset Kata Sandi" (form input kode + password baru).
     * Email diambil dari query string (?email=...) supaya sudah otomatis
     * terisi, tidak perlu diketik ulang oleh user.
     *
     * @param  Request  $request  dipakai untuk membaca query string "email"
     * @return View
     */
    public function showResetForm(Request $request): View
    {
        return view('auth.reset-password', [
            'email' => $request->query('email', ''),
        ]);
    }

    /**
     * Proses reset kata sandi: cocokkan kode yang diinput user dengan kode
     * ter-hash yang tersimpan, pastikan belum kedaluwarsa, lalu update
     * password user dan hapus kode tersebut (kode hanya berlaku sekali pakai).
     *
     * @param  ResetPasswordRequest  $request  berisi email, kode, dan password baru yang sudah tervalidasi
     * @return RedirectResponse
     */
    public function reset(ResetPasswordRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $record = DB::table('password_reset_tokens')->where('email', $data['email'])->first();

        if (! $record) {
            return back()->withErrors(['kode' => 'Belum ada kode yang diminta untuk email ini.'])->withInput();
        }

        if (! Hash::check($data['kode'], $record->token)) {
            return back()->withErrors(['kode' => 'Kode verifikasi salah.'])->withInput();
        }

        $kedaluwarsa = Carbon::parse($record->created_at)->addMinutes(self::MASA_BERLAKU_KODE_MENIT);

        if (now()->greaterThan($kedaluwarsa)) {
            return back()->withErrors(['kode' => 'Kode verifikasi sudah kedaluwarsa. Silakan minta kode baru.'])->withInput();
        }

        User::where('email', $data['email'])->update([
            'password' => Hash::make($data['password']),
        ]);

        // Kode hanya berlaku sekali pakai, langsung dihapus setelah dipakai.
        DB::table('password_reset_tokens')->where('email', $data['email'])->delete();

        return redirect()
            ->route('login')
            ->with('status', 'Kata sandi berhasil diubah. Silakan masuk dengan kata sandi baru Anda.');
    }
}
