<?php

namespace App\Services;

use App\Models\SlipGaji;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service untuk mengirim notifikasi slip gaji via WhatsApp memakai API Fonnte
 * (https://fonnte.com), dipanggil dengan Laravel HTTP Client.
 * Token diambil dari config/services.php (nilainya datang dari .env).
 *
 * CATATAN: kalau WHATSAPP_API_TOKEN di .env masih kosong, method ini akan
 * mensimulasikan pengiriman (dummy, cuma dicatat di log) supaya alur tetap
 * bisa didemokan sebelum Anda daftar akun Fonnte asli.
 */
class WhatsAppService
{
    /**
     * Alamat API Fonnte untuk mengirim pesan WhatsApp.
     */
    private const FONNTE_ENDPOINT = 'https://api.fonnte.com/send';

    /**
     * Kirim ringkasan slip gaji ke nomor WhatsApp karyawan lewat Fonnte.
     *
     * @param  SlipGaji  $slipGaji  slip gaji yang ringkasannya mau dikirim
     * @param  string  $nomorTujuan  nomor WhatsApp tujuan, boleh format 08xxx atau 62xxx
     * @return bool  true kalau Fonnte melaporkan pesan berhasil diproses/dikirim
     */
    public function kirimSlipGaji(SlipGaji $slipGaji, string $nomorTujuan): bool
    {
        $token = config('services.whatsapp.token');
        $nomorFormatFonnte = $this->normalisasiNomor($nomorTujuan);

        $pesan = sprintf(
            "*Slip Gaji Periode %s*\nNama: %s\nNIK: %s\nGaji Bersih: %s\n\nTerima kasih.",
            $slipGaji->periode,
            $slipGaji->karyawan->nama,
            $slipGaji->karyawan->nik,
            $slipGaji->formatRupiah('gaji_bersih')
        );

        // Kalau token Fonnte belum diisi di .env, anggap pengiriman berhasil
        // secara simulasi (dummy) supaya fitur ini tetap bisa didemokan.
        if (empty($token)) {
            Log::info('Simulasi kirim WhatsApp (dummy, token Fonnte belum diisi)', [
                'ke' => $nomorFormatFonnte,
                'pesan' => $pesan,
            ]);

            return true;
        }

        // Dokumentasi Fonnte: https://docs.fonnte.com/
        // Header "Authorization" diisi token device, body berupa form data biasa.
        $response = Http::asForm()
            ->withHeaders(['Authorization' => $token])
            ->post(self::FONNTE_ENDPOINT, [
                'target' => $nomorFormatFonnte,
                'message' => $pesan,
            ]);

        if (! $response->successful()) {
            Log::warning('Gagal mengirim WhatsApp via Fonnte (HTTP error)', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return false;
        }

        // Fonnte selalu membalas JSON dengan field "status": true/false.
        $status = (bool) ($response->json('status') ?? false);

        if (! $status) {
            Log::warning('Fonnte menolak pesan WhatsApp', ['response' => $response->json()]);
        }

        return $status;
    }

    /**
     * Ubah format nomor telepon ke format yang diminta Fonnte (diawali 62,
     * tanpa tanda "+", tanpa spasi/strip). Contoh: "08123456789" -> "628123456789".
     *
     * @param  string  $nomor  nomor telepon mentah dari data karyawan
     * @return string  nomor yang sudah dirapikan
     */
    private function normalisasiNomor(string $nomor): string
    {
        // Buang semua karakter selain angka (spasi, strip, tanda kurung, dll).
        $angkaSaja = preg_replace('/\D/', '', $nomor);

        // Nomor lokal biasanya diawali 0 (contoh: 0812xxxx), Fonnte butuh awalan 62.
        if (str_starts_with($angkaSaja, '0')) {
            $angkaSaja = '62' . substr($angkaSaja, 1);
        }

        return $angkaSaja;
    }
}
