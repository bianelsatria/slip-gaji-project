<!--
    emails/password-reset-code.blade.php
    Isi (body) email berisi kode 6 digit untuk reset kata sandi.
    Dipakai oleh App\Mail\PasswordResetCodeMail sebagai ->view().
    Variabel $kode dan $masaBerlakuMenit dikirim dari Mailable.
-->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kode Reset Kata Sandi</title>
</head>
<body style="margin:0; padding:0; background-color:#FAFAFA; font-family: Arial, Helvetica, sans-serif; color:#111827; font-size:14px;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#FAFAFA; padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="420" cellpadding="0" cellspacing="0" style="background:#FFFFFF; border:1px solid #E5E7EB; border-radius:12px; overflow:hidden;">
                    <tr>
                        <td style="background:#1B2432; color:#FFFFFF; padding:16px 24px; font-weight:bold; font-size:15px;">
                            Sistem Slip Gaji
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px 24px; text-align:center;">
                            <p style="margin:0 0 6px; color:#111827;">Ada permintaan reset kata sandi untuk akun ini.</p>
                            <p style="margin:0 0 22px; color:#6B7280; font-size:13px;">
                                Masukkan kode berikut di halaman Reset Kata Sandi:
                            </p>

                            <div style="display:inline-block; background:#EAF4FB; border:1px solid #BFE0F0; border-radius:10px; padding:16px 28px; margin-bottom:20px;">
                                <span style="font-size:32px; font-weight:bold; letter-spacing:8px; color:#0B3B60;">{{ $kode }}</span>
                            </div>

                            <p style="margin:0; color:#6B7280; font-size:12px;">
                                Kode ini berlaku selama {{ $masaBerlakuMenit }} menit.
                                Jika Anda tidak merasa meminta ini, abaikan saja email ini.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>