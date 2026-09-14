<!--
    slip-gaji/pdf.blade.php
    Template KHUSUS untuk dicetak jadi file PDF oleh package barryvdh/laravel-dompdf
    (dipanggil dari SlipGajiController@downloadPdf dan App\Mail\SlipGajiMail).
    CSS di sini sengaja dibuat sederhana & inline karena dompdf tidak mendukung
    semua fitur CSS3 seperti browser biasa.
-->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Gaji - {{ $slipGaji->karyawan->nama }}</title>
    <style>
        /* dompdf tidak mendukung seluruh CSS3, style dibuat sederhana & inline-friendly */
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111827; }
        .header { background: #1B2432; color: #fff; padding: 14px 18px; border-radius: 8px 8px 0 0; }
        .header h1 { margin: 0; font-size: 15px; }
        .box { border: 1px solid #E5E7EB; border-top: none; padding: 18px; border-radius: 0 0 8px 8px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        td { padding: 4px 0; font-size: 12px; }
        .label { color: #6B7280; }
        .value { font-weight: bold; text-align: right; }
        .section-title { font-weight: bold; margin-top: 10px; margin-bottom: 6px; font-size: 13px; }
        .total-row td { border-top: 1px solid #E5E7EB; padding-top: 8px; font-weight: bold; }
        .gaji-bersih { background: #FDF2F1; padding: 14px 18px; border-radius: 8px; margin-top: 12px; }
        .gaji-bersih .amount { font-size: 20px; font-weight: bold; color: #A8583C; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Slip Gaji Karyawan - Periode {{ $slipGaji->periode }}</h1>
    </div>
    <div class="box">
        <table>
            <tr>
                <td class="label">Nama Karyawan</td>
                <td class="value">{{ $slipGaji->karyawan->nama }}</td>
            </tr>
            <tr>
                <td class="label">NIK</td>
                <td class="value">{{ $slipGaji->karyawan->nik }}</td>
            </tr>
            <tr>
                <td class="label">Jabatan</td>
                <td class="value">{{ $slipGaji->karyawan->jabatan }}</td>
            </tr>
        </table>

        <div class="section-title">Penghasilan (Income)</div>
        <table>
            <tr>
                <td class="label">Gaji Pokok</td>
                <td class="value">{{ $slipGaji->formatRupiah('gaji_pokok') }}</td>
            </tr>
            <tr>
                <td class="label">Lembur</td>
                <td class="value">{{ $slipGaji->formatRupiah('lembur') }}</td>
            </tr>
            <tr class="total-row">
                <td>Total Penghasilan</td>
                <td class="value">{{ $slipGaji->formatRupiah('total_penghasilan') }}</td>
            </tr>
        </table>

        <div class="section-title">Potongan (Deductions)</div>
        <table>
            <tr>
                <td class="label">Pinjaman Karyawan</td>
                <td class="value">{{ $slipGaji->formatRupiah('pinjaman_karyawan') }}</td>
            </tr>
            <tr class="total-row">
                <td>Total Potongan</td>
                <td class="value">{{ $slipGaji->formatRupiah('total_potongan') }}</td>
            </tr>
        </table>

        <div class="gaji-bersih">
            <div class="label">GAJI BERSIH DITERIMA</div>
            <div class="amount">{{ $slipGaji->formatRupiah('gaji_bersih') }}</div>
        </div>
    </div>
</body>
</html>
