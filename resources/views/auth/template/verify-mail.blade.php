<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aktivasi Akun</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 1px solid #eee;
        }
        .content {
            padding: 30px 0;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #007bff;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #0056b3;
            color: #f8f8f8 !important;
        }
        .footer {
            text-align: center;
            padding: 20px 0;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
        </div>
        
        <div class="content">
            <h2>Halo {{ $user->name }}!</h2>
            
            <p>Terima kasih telah mendaftar di {{ config('app.name') }}. Untuk mengaktifkan akun Anda, silakan klik tombol di bawah ini:</p>
            
            <div style="text-align: center;">
                <a href="{{ $activationUrl }}" class="button">Aktivasi Akun</a>
            </div>
            
            <p>Atau Anda dapat menyalin dan menempelkan link berikut ke browser Anda:</p>
            <p style="word-break: break-all; background-color: #f8f9fa; padding: 10px; border-radius: 4px;">
                {{ $activationUrl }}
            </p>
            
            <div class="warning">
                <strong>Penting:</strong> Jika Anda tidak mendaftar di {{ config('app.name') }}, abaikan email ini.
            </div>
        </div>
        
        <div class="footer">
            <p>Email ini dikirim secara otomatis, mohon jangan membalas email ini.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>