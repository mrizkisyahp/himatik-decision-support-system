<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="{{ asset('images/Logo_HIMATIK-DIC1vDRy.png') }}" type="image/png">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email HIMATIK PNJ</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
            color: #333333;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }
        .header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 600;
            letter-spacing: 1px;
        }
        .content {
            padding: 40px 30px;
            text-align: center;
        }
        .content p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
            color: #555555;
        }
        .otp-container {
            background-color: #f8fbff;
            border: 2px dashed #2a5298;
            border-radius: 8px;
            padding: 20px;
            margin: 30px auto;
            max-width: 300px;
        }
        .otp-code {
            font-size: 36px;
            font-weight: 700;
            letter-spacing: 8px;
            color: #1e3c72;
            margin: 0;
        }
        .footer {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #eeeeee;
        }
        .footer p {
            font-size: 13px;
            color: #999999;
            margin: 0;
        }
        .warning {
            font-size: 14px !important;
            color: #e74c3c !important;
            margin-top: 30px;
        }
        .logo-text {
            font-size: 28px;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }
        .accent {
            color: #4facfe;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo-text">HIMATIK<span class="accent">PNJ</span></div>
        </div>
        <div class="content">
            <h2 style="color: #1e3c72; margin-top: 0;">Verifikasi Email Anda</h2>
            <p>Halo Calon Anggota HIMATIK PNJ,</p>
            <p>Terima kasih telah mendaftar. Untuk melanjutkan proses pendaftaran, silakan gunakan kode OTP berikut untuk memverifikasi alamat email Anda:</p>
            
            <div class="otp-container">
                <p class="otp-code">{{ $code }}</p>
            </div>
            
            <p>Kode ini berlaku selama <strong>{{ $expiresInMinutes }} menit</strong>. Jangan berikan kode ini kepada siapapun.</p>
            
            <p class="warning">Jika Anda tidak merasa melakukan pendaftaran, Anda dapat mengabaikan email ini dengan aman.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Himpunan Mahasiswa Teknik Informatika dan Komputer Politeknik Negeri Jakarta.<br>Hak Cipta Dilindungi.</p>
        </div>
    </div>
</body>
</html>
