<?php

namespace App\Mail;

use App\Models\SlipGaji;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Mailable untuk mengirim slip gaji ke email karyawan,
 * dengan file PDF slip gaji terlampir sebagai attachment.
 */
class SlipGajiMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public SlipGaji $slipGaji)
    {
    }

    /**
     * Susun konten email: subject, view body, dan lampiran PDF.
     *
     * @return $this
     */
    public function build()
    {
        $pdf = Pdf::loadView('slip-gaji.pdf', ['slipGaji' => $this->slipGaji]);

        return $this
            ->subject('Slip Gaji Periode ' . $this->slipGaji->periode)
            ->view('emails.slip-gaji')
            ->attachData(
                $pdf->output(),
                'slip-gaji-' . $this->slipGaji->karyawan->nik . '-' . $this->slipGaji->periode . '.pdf',
                ['mime' => 'application/pdf']
            );
    }
}
