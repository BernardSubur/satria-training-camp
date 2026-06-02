<!DOCTYPE html>
<html>
<head>
    <title>Sesi Latihan Habis</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="background-color: #ffffff; padding: 30px; border-radius: 8px; max-width: 600px; margin: 0 auto; box-shadow: 0 4px 8px rgba(0,0,0,0.05);">
        <h2 style="color: #d9534f;">Sesi Latihan Anda Telah Habis</h2>
        <p style="color: #555; font-size: 16px;">Halo <strong>{{ $user->name }}</strong>,</p>
        <p style="color: #555; font-size: 16px;">Sesi latihan pada paket aktif Anda saat ini telah habis (0 sesi tersisa).</p>
        
        <p style="color: #555; font-size: 16px;">Agar Anda tetap dapat melakukan reservasi dan melanjutkan latihan, silakan lakukan pembelian paket baru di aplikasi web Satria Training Camp.</p>

        <div style="margin-top: 30px; text-align: center;">
            <a href="{{ url('/paket') }}" style="background-color: #0275d8; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">Beli Paket Sekarang</a>
        </div>

        <p style="margin-top: 30px; color: #888; font-size: 12px; text-align: center;">
            &copy; {{ date('Y') }} Satria Training Camp.
        </p>
    </div>
</body>
</html>
