<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verifikasi Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 10px;
            border: 1px solid #ddd;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #000;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background: #333;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Halo, {{ $userName }}!</h2>
        <p>Terima kasih telah mendaftar di Aplikasi Perpustakaan.</p>
        <p>Untuk mengaktifkan akun Anda, silakan klik tombol di bawah ini untuk verifikasi email:</p>
        
        <a href="{{ $verificationUrl }}" class="button">Verifikasi Email</a>
        
        <p>Atau salin dan tempel link berikut di browser Anda:</p>
        <p style="word-break: break-all; color: #007bff;">{{ $verificationUrl }}</p>
        
        <p style="margin-top: 30px; color: #666; font-size: 14px;">
            <strong>Catatan:</strong> Link ini akan kedaluwarsa dalam 24 jam. Jika Anda tidak mendaftar, abaikan email ini.
        </p>
        
        <div class="footer">
            <p>Jika tombol tidak berfungsi, salin link di atas dan buka di browser Anda.</p>
        </div>
    </div>
</body>
</html>
