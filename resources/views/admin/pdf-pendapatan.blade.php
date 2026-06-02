<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #333; padding: 6px 8px; }
        th { background: #eee; }
        .total { margin-top: 15px; font-size: 14px; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>

<h2>Laporan Pendapatan - Satria Training Camp</h2>
<p class="text-center">Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>

<table>
    <tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>Member</th>
        <th>Paket</th>
        <th>Metode</th>
        <th class="text-right">Harga</th>
    </tr>

    @foreach ($payments as $key => $p)
    <tr>
        <td class="text-center">{{ $key + 1 }}</td>
        <td>{{ \Carbon\Carbon::parse($p->created_at)->format('d-m-Y') }}</td>
        <td>{{ $p->user->name ?? '-' }}</td>
        <td>{{ $p->paket->nama_paket ?? '-' }}</td>
        <td class="text-center">{{ strtoupper($p->metode_pembayaran) }}</td>
        <td class="text-right">Rp {{ number_format($p->paket->harga ?? 0, 0, ',', '.') }}</td>
    </tr>
    @endforeach

</table>

<p class="total">Total Pendapatan : Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>

</body>
</html>