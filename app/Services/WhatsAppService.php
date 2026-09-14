<?php

namespace App\Services;

use App\Models\SlipGaji;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service kecil untuk mengirim notifikasi slip gaji via WhatsApp.
 * Memanggil API pihak ketiga memakai Laravel HTTP Client.
 * Endpoint & token diambil dari config/services.php (lihat .env).
 * Jika endpoint belum dikonfigurasi, method ini akan mensimulasikan
 * pengiriman (dummy) agar alur tetap bisa didemokan tanpa API asli.
 */
class WhatsAppService
{
    /**
     * Kirim ringkasan slip gaji ke nomor WhatsApp karyawan.
     *
     * @param  SlipGaji  $slipGaji
     * @param  string  $nomorTujuan  nomor WhatsApp tujuan, format 62xxxxxxxxxx
     * @return bool  true jika terkirim (atau berhasil disimulasikan)
     */
    public function kirimSlipGaji(SlipGaji $slipGaji, string $nomorTujuan): bool
    {
        $endpoint = config('services.whatsapp.endpoint');
        $token = config('services.whatsapp.token');

        $pesan = sprintf(
            "Slip Gaji Periode %s\nNama: %s\nGaji Bersih: %s\nTerima kasih.",
            $slipGaji->periode,
            $slipGaji->karyawan->nama,
            $slipGaji->formatRupiah('gaji_bersih')
        );

        // Jika belum ada endpoint API pihak ketiga yang dikonfigurasi,
        // anggap pengiriman berhasil secara simulasi (dummy) agar demo tetap berjalan.
        if (empty($endpoint)) {
            Log::info('Simulasi kirim WhatsApp (dummy)', [
                'ke' => $nomorTujuan,
                'pesan' => $pesan,
            ]);

            return true;
        }

        $response = Http::withToken($token)->post($endpoint, [
            'to' => $nomorTujuan,
            'message' => $pesan,
        ]);

        return $response->successful();
    }
}
