<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Verifikasi Email - Aplikasi Perpustakaan</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <style>
    .verification-container {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      width: 100%;
      padding: 20px;
    }
    .verification-box {
      background: rgba(255, 255, 255, 0.6);
      padding: 40px;
      width: 100%;
      max-width: 500px;
      border-radius: 15px;
      text-align: center;
      backdrop-filter: blur(5px);
      box-shadow: 0 4px 20px rgba(0,0,0,0.25);
    }
    .verification-icon {
      font-size: 64px;
      margin-bottom: 20px;
    }
    .verification-title {
      font-size: 24px;
      font-weight: bold;
      color: #000;
      margin-bottom: 15px;
    }
    .verification-text {
      color: #555;
      font-size: 15px;
      line-height: 1.6;
      margin-bottom: 15px;
    }
    .verification-email {
      color: #000;
      font-weight: bold;
      font-size: 16px;
      margin: 15px 0;
      padding: 10px;
      background: rgba(0, 0, 0, 0.05);
      border-radius: 8px;
    }
    .verification-note {
      color: #666;
      font-size: 13px;
      line-height: 1.6;
      margin-top: 20px;
      padding: 15px;
      background: rgba(59, 130, 246, 0.1);
      border-radius: 8px;
      border-left: 4px solid #3b82f6;
    }
  </style>
</head>

<body class="bodymenu">
  <div class="verification-container">
    <div class="verification-box">
      <div class="verification-icon">📧</div>
      <h2 class="verification-title">Verifikasi Email</h2>
      
      @if (session('success'))
        <div style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; border: 1px solid #10b981; text-align: left;">
          <strong>✓</strong> {{ session('success') }}
        </div>
      @endif

      @if (session('error'))
        <div style="background: rgba(220, 38, 38, 0.1); color: #dc2626; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; border: 1px solid #dc2626; text-align: left;">
          <strong>⚠</strong> {{ session('error') }}
        </div>
      @endif

      @if (session('email'))
        <p class="verification-text">
          Kami telah mengirimkan <strong>link verifikasi</strong> ke email Anda:
        </p>
        <div class="verification-email">
          {{ session('email') }}
        </div>
      @endif

      <p class="verification-text">
        Silakan <strong>cek inbox email Anda</strong> dan klik link verifikasi yang telah kami kirimkan.
      </p>

      <div class="verification-note">
        <strong>💡 Tips:</strong> Jika tidak menemukan email, cek juga folder <strong>Spam</strong> atau <strong>Promosi</strong>. Link verifikasi berlaku selama 24 jam.
      </div>

      @if (session('email'))
        <form action="{{ route('resend.verification') }}" method="POST" style="margin-top: 25px;">
          @csrf
          <input type="hidden" name="email" value="{{ session('email') }}">
          <button type="submit" class="btn-login" style="background: #6b7280; margin-bottom: 15px;">
            🔄 Kirim Ulang Email Verifikasi
          </button>
        </form>
      @endif

      <div style="margin-top: 20px; font-size: 14px;">
        <a href="{{ route('login') }}" style="color: #007bff; text-decoration: none; font-weight: bold;">← Kembali ke Login</a>
      </div>
    </div>
  </div>
</body>
</html>