<?php

namespace App\Http\Controllers;

use App\Http\Requests\SlipGajiRequest;
use App\Mail\SlipGajiMail;
use App\Models\Karyawan;
use App\Models\SlipGaji;
use App\Services\WhatsAppService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Controller utama untuk fitur Slip Gaji Karyawan:
 * menampilkan form, memproses kalkulasi & captcha, menyimpan slip gaji,
 * serta menyediakan aksi unduh PDF, kirim email, dan kirim WhatsApp.
 *
 * CATATAN UPDATE: sejak ada halaman "List Karyawan", form slip gaji ini bisa
 * dibuka dengan 2 cara:
 * 1. route('slip-gaji.create')            -> pakai data karyawan milik user yang login
 *    (cara lama, tetap dipertahankan supaya tidak merusak alur yang sudah jalan)
 * 2. route('slip-gaji.create-for', $id)   -> pakai data karyawan yang dipilih dari
 *    tabel List Karyawan (klik ikon Edit), ini alur BARU.
 * Kedua cara ini sama-sama memanggil method renderForm() di bawah supaya
 * tidak ada kode yang ditulis dua kali (duplikasi).
 */
class SlipGajiController extends Controller
{
    /**
     * (Cara lama) Tampilkan form slip gaji untuk karyawan milik user yang login.
     * Dipakai kalau user membuka menu Slip Gaji tanpa memilih karyawan dulu.
     *
     * @return View
     */
    public function create(): View
    {
        $karyawan = Auth::user()->karyawan;

        return $this->renderForm($karyawan);
    }

    /**
     * (Cara baru) Tampilkan form slip gaji untuk karyawan TERTENTU, yaitu
     * karyawan yang barisnya diklik ikon Edit di halaman List Karyawan.
     * Nilai $karyawan otomatis diisi Laravel dari ID pada URL (route model binding).
     *
     * @param  Karyawan  $karyawan  karyawan yang datanya mau dibuatkan slip gaji
     * @return View
     */
    public function createForKaryawan(Karyawan $karyawan): View
    {
        return $this->renderForm($karyawan);
    }

    /**
     * Fungsi bantu (dipakai bersama oleh create() dan createForKaryawan()) untuk
     * menyiapkan tampilan form slip gaji: membuat soal captcha matematika baru,
     * menyimpannya di session, lalu merender view dengan data karyawan yang dipilih.
     *
     * @param  Karyawan  $karyawan  data karyawan yang Nama/NIK/Jabatan-nya
     *                              akan ditampilkan read-only di form
     * @return View
     */
    private function renderForm(Karyawan $karyawan): View
    {
        $angkaA = random_int(1, 9);
        $angkaB = random_int(1, 9);

        // Jawaban captcha yang benar disimpan di session (bukan dikirim ke browser),
        // supaya tidak bisa dicurangi lewat inspect element / edit HTML.
        session([
            'captcha_a' => $angkaA,
            'captcha_b' => $angkaB,
        ]);

        return view('slip-gaji.index', [
            'karyawan' => $karyawan,
            // Default periode: tanggal 1 sampai tanggal terakhir bulan berjalan.
            // User tetap bebas mengubah kedua tanggal ini lewat input di form.
            'periodeAwalDefault' => now()->startOfMonth()->format('Y-m-d'),
            'periodeAkhirDefault' => now()->endOfMonth()->format('Y-m-d'),
            'captchaA' => $angkaA,
            'captchaB' => $angkaB,
        ]);
    }

    /**
     * Proses submit form slip gaji: validasi ulang nilai numerik & captcha di server,
     * hitung Total Penghasilan, Total Potongan, dan Gaji Bersih, lalu simpan ke database.
     *
     * @param  SlipGajiRequest  $request  input yang sudah tervalidasi (termasuk captcha
     *                                    dan karyawan_id dari field tersembunyi di form)
     * @return RedirectResponse
     */
    public function store(SlipGajiRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // karyawan_id dikirim lewat hidden input di form (lihat slip-gaji/index.blade.php).
        // Kalau karena suatu hal field itu kosong, sistem tetap jatuh (fallback) ke
        // karyawan milik user yang login, supaya alur lama tetap berfungsi.
        $karyawan = ! empty($data['karyawan_id'])
            ? Karyawan::findOrFail($data['karyawan_id'])
            : Auth::user()->karyawan;

        $gajiPokok = (float) $data['gaji_pokok'];
        $lembur = (float) ($data['lembur'] ?? 0);
        $pinjaman = (float) ($data['pinjaman_karyawan'] ?? 0);

        // Kalkulasi ulang di server, tidak mempercayai hasil hitung JS di sisi klien.
        $totalPenghasilan = $gajiPokok + $lembur;
        $totalPotongan = $pinjaman;
        $gajiBersih = $totalPenghasilan - $totalPotongan;

        // Ubah 2 tanggal yang dipilih user jadi 1 label teks yang gampang dibaca,
        // contoh: "01 September 2024 - 30 September 2024". Label ini yang dipakai
        // di tabel, PDF, dan email (kolom `periode`), sementara tanggal aslinya
        // tetap disimpan terpisah di periode_awal & periode_akhir.
        $periodeAwal = \Carbon\Carbon::parse($data['periode_awal']);
        $periodeAkhir = \Carbon\Carbon::parse($data['periode_akhir']);
        $labelPeriode = $periodeAwal->translatedFormat('d F Y') . ' - ' . $periodeAkhir->translatedFormat('d F Y');

        $slipGaji = SlipGaji::create([
            'karyawan_id' => $karyawan->id,
            'periode' => $labelPeriode,
            'periode_awal' => $periodeAwal,
            'periode_akhir' => $periodeAkhir,
            'gaji_pokok' => $gajiPokok,
            'lembur' => $lembur,
            'pinjaman_karyawan' => $pinjaman,
            'total_penghasilan' => $totalPenghasilan,
            'total_potongan' => $totalPotongan,
            'gaji_bersih' => $gajiBersih,
        ]);

        // Captcha hanya berlaku sekali pakai.
        session()->forget(['captcha_a', 'captcha_b']);

        return redirect()->route('slip-gaji.success', $slipGaji);
    }

    /**
     * Tampilkan halaman konfirmasi setelah slip gaji berhasil dikirim/diproses.
     *
     * @param  SlipGaji  $slipGaji  slip gaji yang baru saja dibuat (dari ID di URL)
     * @return View
     */
    public function success(SlipGaji $slipGaji): View
    {
        return view('slip-gaji.success', ['slipGaji' => $slipGaji]);
    }

    /**
     * Unduh slip gaji dalam format PDF memakai package barryvdh/laravel-dompdf.
     *
     * @param  SlipGaji  $slipGaji  slip gaji yang mau diunduh PDF-nya
     * @return \Symfony\Component\HttpFoundation\Response  file PDF untuk didownload browser
     */
    public function downloadPdf(SlipGaji $slipGaji)
    {
        $pdf = Pdf::loadView('slip-gaji.pdf', ['slipGaji' => $slipGaji]);

        return $pdf->download('slip-gaji-' . $slipGaji->karyawan->nik . '-' . $slipGaji->periode . '.pdf');
    }

    /**
     * Kirim slip gaji ke ALAMAT EMAIL KARYAWAN (bukan email admin yang login).
     * Memakai Laravel Mail (bukan simulasi) — email ini benar-benar terkirim
     * asalkan konfigurasi SMTP di file .env sudah benar.
     * UPDATE: rincian Penghasilan/Potongan/Gaji Bersih ditampilkan sebagai tabel
     * langsung di isi email (lihat resources/views/emails/slip-gaji.blade.php),
     * TIDAK ada lagi lampiran PDF di email ini.
     *
     * @param  SlipGaji  $slipGaji  slip gaji yang mau dikirim
     * @return RedirectResponse
     */
    public function sendEmail(SlipGaji $slipGaji): RedirectResponse
    {
        $emailTujuan = $slipGaji->karyawan->email;

        // Kalau karyawan belum punya email terdaftar, jangan kirim (akan error kalau dipaksa).
        if (empty($emailTujuan)) {
            return back()->withErrors([
                'email' => 'Karyawan ini belum memiliki alamat email. Tambahkan email lewat data karyawan terlebih dahulu.',
            ]);
        }

        Mail::to($emailTujuan)->send(new SlipGajiMail($slipGaji));

        $slipGaji->update(['dikirim_email_at' => now()]);

        return back()->with('status', 'Slip gaji berhasil dikirim ke email ' . $emailTujuan . '.');
    }

    /**
     * Kirim ringkasan slip gaji ke NOMOR WHATSAPP KARYAWAN (diambil dari data
     * karyawan, bukan input manual) memakai API Fonnte lewat WhatsAppService.
     *
     * @param  SlipGaji  $slipGaji  slip gaji yang ringkasannya mau dikirim
     * @param  WhatsAppService  $whatsAppService  service pembungkus pemanggilan API Fonnte
     * @return RedirectResponse
     */
    public function sendWhatsapp(SlipGaji $slipGaji, WhatsAppService $whatsAppService): RedirectResponse
    {
        $nomorTujuan = $slipGaji->karyawan->no_telepon;

        // Kalau karyawan belum punya nomor telepon terdaftar, jangan kirim.
        if (empty($nomorTujuan)) {
            return back()->withErrors([
                'whatsapp' => 'Karyawan ini belum memiliki nomor telepon. Tambahkan nomor lewat data karyawan terlebih dahulu.',
            ]);
        }

        $berhasil = $whatsAppService->kirimSlipGaji($slipGaji, $nomorTujuan);

        if ($berhasil) {
            $slipGaji->update(['dikirim_whatsapp_at' => now()]);

            return back()->with('status', 'Slip gaji berhasil dikirim via WhatsApp ke ' . $nomorTujuan . '.');
        }

        return back()->withErrors(['whatsapp' => 'Gagal mengirim slip gaji via WhatsApp. Cek token Fonnte di .env atau lihat log Laravel.']);
    }
}
