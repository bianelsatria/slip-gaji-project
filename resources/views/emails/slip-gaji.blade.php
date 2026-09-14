<!--
    emails/slip-gaji.blade.php
    Isi (body) email yang dikirim ke karyawan saat tombol "Kirim ke Email" ditekan.
    Dipakai oleh App\Mail\SlipGajiMail sebagai ->view(). File PDF slip gaji
    dilampirkan terpisah oleh SlipGajiMail, bukan ditampilkan di sini.
-->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Gaji</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111827; font-size: 14px;">
    <p>Halo {{ $slipGaji->karyawan->nama }},</p>

    <p>
        Slip gaji Anda untuk periode <strong>{{ $slipGaji->periode }}</strong> sudah tersedia.
        Rincian lengkap dapat dilihat pada lampiran PDF di email ini.
    </p>

    <table style="margin: 16px 0; border-collapse: collapse;">
        <tr>
            <td style="padding: 4px 12px 4px 0; color: #6B7280;">NIK</td>
            <td style="padding: 4px 0; font-weight: bold;">{{ $slipGaji->karyawan->nik }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 12px 4px 0; color: #6B7280;">Gaji Bersih</td>
            <td style="padding: 4px 0; font-weight: bold; color: #C16C4C;">{{ $slipGaji->formatRupiah('gaji_bersih') }}</td>
        </tr>
    </table>

    <p>Terima kasih.</p>
</body>
</html>
