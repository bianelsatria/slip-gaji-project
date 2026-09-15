<?php

namespace App\Http\Controllers;

use App\Http\Requests\KaryawanRequest;
use App\Models\Karyawan;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Controller untuk fitur "List Karyawan": halaman utama setelah login.
 * Menyediakan operasi CRUD dasar untuk data karyawan:
 * - index()   -> lihat semua karyawan dalam bentuk tabel
 * - create()  -> tampilkan form tambah karyawan baru
 * - store()   -> simpan karyawan baru ke database
 * - destroy() -> hapus data karyawan
 *
 * Fitur "Edit" tidak punya method di sini karena sengaja diarahkan langsung
 * ke halaman Slip Gaji (lihat SlipGajiController@createForKaryawan), sesuai
 * kebutuhan: klik ikon Edit = buka form Slip Gaji untuk karyawan tersebut.
 */
class KaryawanController extends Controller
{
    /**
     * Tampilkan daftar semua karyawan dalam bentuk tabel
     * (Nama, NIK, Jabatan, No. Telepon, Aksi).
     * Ini adalah halaman pertama yang dilihat user setelah berhasil login.
     * Data dipaginasi 10 baris per halaman supaya tabel tetap rapi walau
     * data karyawan sudah banyak.
     *
     * @return View
     */
    public function index(): View
    {
        // Data karyawan diurutkan dari yang terbaru dibuat, supaya karyawan baru
        // langsung terlihat di bagian atas tabel.
        $karyawans = Karyawan::latest()->paginate(10);

        return view('karyawan.index', [
            'karyawans' => $karyawans,
        ]);
    }

    /**
     * Tampilkan form kosong untuk menambah data karyawan baru.
     *
     * @return View
     */
    public function create(): View
    {
        return view('karyawan.create');
    }

    /**
     * Simpan data karyawan baru ke database, lalu kembali ke halaman List Karyawan.
     *
     * @param  KaryawanRequest  $request  input form yang sudah tervalidasi
     *                                    (nama, nik, jabatan, email, no_telepon)
     * @return RedirectResponse
     */
    public function store(KaryawanRequest $request): RedirectResponse
    {
        Karyawan::create($request->validated());

        return redirect()
            ->route('karyawan.index')
            ->with('status', 'Data karyawan baru berhasil ditambahkan.');
    }

    /**
     * Hapus satu data karyawan dari database.
     * Dipanggil dari modal konfirmasi hapus di halaman List Karyawan.
     * Karena relasi slip_gajis di-set cascadeOnDelete pada migration,
     * seluruh riwayat slip gaji karyawan ini ikut terhapus otomatis.
     *
     * @param  Karyawan  $karyawan  karyawan yang mau dihapus (diambil otomatis dari ID di URL)
     * @return RedirectResponse
     */
    public function destroy(Karyawan $karyawan): RedirectResponse
    {
        $karyawan->delete();

        return redirect()
            ->route('karyawan.index')
            ->with('status', 'Data karyawan berhasil dihapus.');
    }
}
