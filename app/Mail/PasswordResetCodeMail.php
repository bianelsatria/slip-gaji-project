<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Mailable untuk mengirim KODE VERIFIKASI 6 digit ke email user yang lupa
 * kata sandi. Kodenya ditampilkan langsung di isi email (bukan link),
 * lalu user mengetik ulang kode itu di halaman Reset Kata Sandi.
 */
class PasswordResetCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  string  $kode  kode 6 digit yang berlaku sementara (lihat masaBerlakuMenit)
     * @param  int  $masaBerlakuMenit  lama kode ini valid, dalam menit, ditampilkan di email
     */
    public function __construct(
        public string $kode,
        public int $masaBerlakuMenit = 60
    ) {
    }

    /**
     * Susun konten email: subject dan view body (berisi kode besar & jelas).
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Kode Reset Kata Sandi - Sistem Slip Gaji')
            ->view('emails.password-reset-code');
    }
}
