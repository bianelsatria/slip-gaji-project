<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Controller untuk proses autentikasi (login & logout) memakai Laravel Auth bawaan.
 */
class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman form login (Halaman 1: input Email & Kata Sandi).
     *
     * @return View
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Proses percobaan login memakai Auth::attempt(). Kalau email/password cocok,
     * user diarahkan ke halaman List Karyawan (halaman utama aplikasi).
     *
     * @param  LoginRequest  $request  data email & password yang sudah tervalidasi
     * @return RedirectResponse
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Email atau kata sandi salah.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        // UPDATE: dulu diarahkan langsung ke route('slip-gaji.create'), sekarang
        // diarahkan ke route('karyawan.index') karena halaman List Karyawan
        // sudah menjadi halaman utama setelah login.
        return redirect()->intended(route('karyawan.index'));
    }

    /**
     * Logout user: hapus status login & hancurkan session yang sedang aktif,
     * lalu kembalikan user ke halaman login.
     *
     * @param  Request  $request  request saat ini, dipakai untuk akses session
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
