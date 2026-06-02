<!DOCTYPE html>
<html>
<head>
    <title>Notifikasi Pembayaran Baru</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="background-color: #ffffff; padding: 30px; border-radius: 8px; max-width: 600px; margin: 0 auto; box-shadow: 0 4px 8px rgba(0,0,0,0.05);">
        <h2 style="color: #333;">Halo Admin,</h2>
        <p style="color: #555; font-size: 16px;">Terdapat pembayaran baru yang menunggu konfirmasi Anda.</p>
        
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold; width: 40%;">Nama Member</td>
                <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $user->name }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold;">Paket Latihan</td>
                <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $paket->nama_paket }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold;">Harga</td>
                <td style="padding: 10px; border-bottom: 1px solid #eee;">Rp {{ number_format($paket->harga, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold;">Metode Pembayaran</td>
                <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ strtoupper($payment->metode_pembayaran) }}</td>
            </tr>
        </table>

        <div style="margin-top: 30px; text-align: center;">
            <a href="{{ url('/admin/pembayaran') }}" style="background-color: #4CAF50; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">Cek Pembayaran Sekarang</a>
        </div>

        <p style="margin-top: 30px; color: #888; font-size: 12px; text-align: center;">
            &copy; {{ date('Y') }} Satria Training Camp.
        </p>
    </div>
</body>
</html>
