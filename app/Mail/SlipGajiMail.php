<?php

namespace App\Mail;

use App\Models\SlipGaji;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Mailable untuk mengirim slip gaji ke email karyawan.
 * UPDATE: rincian slip gaji (Penghasilan, Potongan, Gaji Bersih) sekarang
 * ditampilkan langsung sebagai tabel di ISI EMAIL, jadi TIDAK ada lagi
 * lampiran PDF di email (beda dengan tombol "Unduh PDF" yang masih terpisah
 * dan tetap tersedia di halaman sukses).
 */
class SlipGajiMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public SlipGaji $slipGaji)
    {
    }

    /**
     * Susun konten email: subject dan view body (berisi tabel rincian slip gaji).
     * Tidak ada attachment PDF.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Slip Gaji Periode ' . $this->slipGaji->periode)
            ->view('emails.slip-gaji');
    }
}
