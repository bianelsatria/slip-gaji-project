<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\SlipGajiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Aplikasi Slip Gaji Karyawan
|--------------------------------------------------------------------------
| Daftar semua alamat (URL) yang bisa diakses di aplikasi ini, dan
| controller + method mana yang menangani masing-masing alamat tersebut.
*/

// Halaman login (Halaman 1). "/" dan "/login" sengaja menuju method yang sama
// supaya user yang belum login otomatis melihat form login di halaman awal.
Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login.show');

// Proses submit form login (cek email & password lewat Auth::attempt()).
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');

// Proses logout (hapus session, kembali ke halaman login).
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Semua route di bawah ini hanya bisa diakses kalau user SUDAH login
// (middleware 'auth' akan otomatis redirect ke halaman login kalau belum).
Route::middleware('auth')->group(function () {

    // ================= LIST KARYAWAN (halaman utama setelah login) =================

    // Halaman daftar semua karyawan (tabel Nama, NIK, Jabatan, Aksi).
    Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');

    // Form untuk menambah data karyawan baru.
    Route::get('/karyawan/tambah', [KaryawanController::class, 'create'])->name('karyawan.create');

    // Proses simpan karyawan baru dari form "Tambah Karyawan".
    Route::post('/karyawan', [KaryawanController::class, 'store'])->name('karyawan.store');

    // Proses hapus satu data karyawan (dipanggil dari modal konfirmasi hapus).
    Route::delete('/karyawan/{karyawan}', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');

    // ================= SLIP GAJI =================

    // (Cara lama) Halaman form Slip Gaji untuk karyawan milik user yang login sendiri.
    Route::get('/slip-gaji', [SlipGajiController::class, 'create'])->name('slip-gaji.create');

    // (Cara baru) Halaman form Slip Gaji untuk karyawan TERTENTU, dituju lewat
    // ikon Edit di tabel List Karyawan. {karyawan} adalah ID karyawan di URL.
    Route::get('/slip-gaji/karyawan/{karyawan}', [SlipGajiController::class, 'createForKaryawan'])->name('slip-gaji.create-for');

    // Proses submit form Slip Gaji: validasi, hitung ulang di server, simpan ke database.
    Route::post('/slip-gaji', [SlipGajiController::class, 'store'])->name('slip-gaji.store');

    // Halaman konfirmasi sukses (Halaman 3) setelah slip gaji berhasil disimpan.
    Route::get('/slip-gaji/{slipGaji}/success', [SlipGajiController::class, 'success'])->name('slip-gaji.success');

    // Unduh slip gaji dalam bentuk file PDF.
    Route::get('/slip-gaji/{slipGaji}/pdf', [SlipGajiController::class, 'downloadPdf'])->name('slip-gaji.pdf');

    // Kirim slip gaji ke email user yang login (PDF terlampir).
    Route::post('/slip-gaji/{slipGaji}/email', [SlipGajiController::class, 'sendEmail'])->name('slip-gaji.email');

    // Kirim ringkasan slip gaji lewat WhatsApp (via API pihak ketiga / simulasi).
    Route::post('/slip-gaji/{slipGaji}/whatsapp', [SlipGajiController::class, 'sendWhatsapp'])->name('slip-gaji.whatsapp');
});
