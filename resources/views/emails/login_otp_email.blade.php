<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Login</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; background:#f5f7fb; margin:0; padding:24px;">
    <div style="max-width:560px; margin:0 auto; background:#ffffff; border:1px solid #e5e7eb; border-radius:12px; padding:24px;">
        <h2 style="margin:0 0 12px; color:#0f172a;">Login OTP - Perpustakaan Digital</h2>
        <p style="margin:0 0 16px; color:#334155;">Halo {{ $name ?? 'Pengguna' }},</p>
        <p style="margin:0 0 20px; color:#334155;">
            Gunakan kode OTP berikut untuk login:
        </p>
        <div style="font-size:28px; font-weight:700; letter-spacing:8px; color:#1d4ed8; background:#eff6ff; border:1px dashed #93c5fd; border-radius:10px; padding:14px 16px; text-align:center;">
            {{ $otp }}
        </div>
        <p style="margin:20px 0 0; color:#64748b; font-size:13px;">
            Kode berlaku 10 menit. Jangan bagikan kode ini kepada siapa pun.
        </p>
    </div>
</body>
</html>

