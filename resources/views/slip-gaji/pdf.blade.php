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
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            font-weight: normal;
            color: #374151;
        }

        .header {
            padding: 0 0 14px 0;
            border-bottom: 2px solid #1677A8;
            margin-bottom: 18px;
        }

        .header .eyebrow {
            font-size: 10px;
            letter-spacing: 1px;
            color: #9CA3AF;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .header h1 {
            margin: 0;
            font-size: 17px;
            font-weight: normal;
            color: #111827;
        }

        .header .periode {
            font-size: 12px;
            color: #6B7280;
            margin-top: 2px;
        }

        .box {
            padding: 0 2px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        td {
            padding: 5px 0;
            font-size: 12px;
        }

        .label {
            color: #9CA3AF;
        }

        .value {
            font-weight: normal;
            color: #111827;
            text-align: right;
        }

        .section-title {
            font-weight: normal;
            color: #0B3B60;
            margin-top: 6px;
            margin-bottom: 6px;
            font-size: 11px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            border-bottom: 1px solid #E5E7EB;
            padding-bottom: 6px;
        }

        .total-row td {
            border-top: 1px solid #E5E7EB;
            padding-top: 8px;
            color: #111827;
        }

        .gaji-bersih {
            border-top: 1px solid #E5E7EB;
            padding-top: 16px;
            margin-top: 8px;
        }

        .gaji-bersih .label {
            font-size: 10px;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #9CA3AF;
        }

        .gaji-bersih .amount {
            font-size: 22px;
            font-weight: normal;
            color: #0B3B60;
            margin-top: 2px;
        }

        .footer-note {
            margin-top: 28px;
            font-size: 10px;
            color: #9CA3AF;
            border-top: 1px solid #F3F4F6;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="eyebrow">Slip Gaji Karyawan</div>
        <h1>Periode {{ $slipGaji->periode }}</h1>
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
            <div class="label">Gaji Bersih Diterima</div>
            <div class="amount">{{ $slipGaji->formatRupiah('gaji_bersih') }}</div>
        </div>

        <div class="footer-note">
            Dokumen ini dibuat secara otomatis oleh sistem Slip Gaji Karyawan.
        </div>
    </div>
</body>
</html>