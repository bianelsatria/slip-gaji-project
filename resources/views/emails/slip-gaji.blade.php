<!--
    emails/slip-gaji.blade.php
    Isi (body) email yang dikirim ke karyawan saat tombol "Kirim ke Email" ditekan.
    Dipakai oleh App\Mail\SlipGajiMail sebagai ->view().
    UPDATE: rincian Penghasilan/Potongan/Gaji Bersih ditampilkan langsung sebagai
    tabel di sini. TIDAK ADA lampiran PDF (beda dengan versi sebelumnya).
-->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Gaji</title>
</head>
<body style="margin:0; padding:0; background-color:#FAFAFA; font-family: Arial, Helvetica, sans-serif; color:#111827; font-size:14px;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#FAFAFA; padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="480" cellpadding="0" cellspacing="0" style="background:#FFFFFF; border:1px solid #E5E7EB; border-radius:12px; overflow:hidden;">

                    {{-- Header --}}
                    <tr>
                        <td style="background:#1B2432; color:#FFFFFF; padding:16px 24px; font-weight:bold; font-size:15px;">
                            Slip Gaji Karyawan
                        </td>
                    </tr>

                    {{-- Isi --}}
                    <tr>
                        <td style="padding:24px;">
                            <p style="margin:0 0 8px;">Halo {{ $slipGaji->karyawan->nama }},</p>
                            <p style="margin:0 0 20px; color:#6B7280;">
                                Berikut rincian slip gaji Anda untuk periode
                                <strong style="color:#111827;">{{ $slipGaji->periode }}</strong>.
                            </p>

                            {{-- Info karyawan --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:18px; font-size:13px;">
                                <tr>
                                    <td style="padding:3px 0; color:#6B7280;">Nama</td>
                                    <td style="padding:3px 0; text-align:right; font-weight:bold;">{{ $slipGaji->karyawan->nama }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:3px 0; color:#6B7280;">NIK</td>
                                    <td style="padding:3px 0; text-align:right; font-weight:bold;">{{ $slipGaji->karyawan->nik }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:3px 0; color:#6B7280;">Jabatan</td>
                                    <td style="padding:3px 0; text-align:right; font-weight:bold;">{{ $slipGaji->karyawan->jabatan }}</td>
                                </tr>
                            </table>

                            {{-- Tabel Penjumlahan Slip Gaji --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #E5E7EB; border-radius:8px; font-size:13px; margin-bottom:16px;">
                                <tr>
                                    <td colspan="2" style="padding:10px 14px; background:#F9FAFB; font-weight:bold; border-bottom:1px solid #E5E7EB;">
                                        Penghasilan (Income)
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:8px 14px; color:#6B7280;">Gaji Pokok</td>
                                    <td style="padding:8px 14px; text-align:right;">{{ $slipGaji->formatRupiah('gaji_pokok') }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:8px 14px; color:#6B7280;">Lembur</td>
                                    <td style="padding:8px 14px; text-align:right;">{{ $slipGaji->formatRupiah('lembur') }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:8px 14px; font-weight:bold; border-top:1px solid #E5E7EB;">Total Penghasilan</td>
                                    <td style="padding:8px 14px; text-align:right; font-weight:bold; border-top:1px solid #E5E7EB;">{{ $slipGaji->formatRupiah('total_penghasilan') }}</td>
                                </tr>

                                <tr>
                                    <td colspan="2" style="padding:10px 14px; background:#F9FAFB; font-weight:bold; border-top:1px solid #E5E7EB; border-bottom:1px solid #E5E7EB;">
                                        Potongan (Deductions)
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:8px 14px; color:#6B7280;">Pinjaman Karyawan</td>
                                    <td style="padding:8px 14px; text-align:right;">{{ $slipGaji->formatRupiah('pinjaman_karyawan') }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:8px 14px; font-weight:bold; border-top:1px solid #E5E7EB;">Total Potongan</td>
                                    <td style="padding:8px 14px; text-align:right; font-weight:bold; border-top:1px solid #E5E7EB;">{{ $slipGaji->formatRupiah('total_potongan') }}</td>
                                </tr>
                            </table>

                            {{-- Ringkasan Gaji Bersih --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#EAF4FB; border:1px solid #BFE0F0; border-radius:8px;">
                                <tr>
                                    <td style="padding:14px 16px;">
                                        <div style="font-size:11px; font-weight:bold; letter-spacing:0.05em; text-transform:uppercase; color:#0B3B60;">
                                            Gaji Bersih Diterima
                                        </div>
                                        <div style="font-size:22px; font-weight:bold; color:#0B3B60;">
                                            {{ $slipGaji->formatRupiah('gaji_bersih') }}
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:20px 0 0; color:#6B7280; font-size:12px;">
                                Email ini dikirim otomatis oleh Sistem Slip Gaji. Terima kasih.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>