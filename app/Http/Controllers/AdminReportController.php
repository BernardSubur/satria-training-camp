<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminReportController extends Controller
{
    public function laporanTransaksi(Request $request)
    {
        
        $query = Payment::with(['user', 'paket'])
            ->where('status', 'success');

        if ($request->tanggal_mulai && $request->tanggal_selesai) {
            $query->whereBetween('created_at', [
                $request->tanggal_mulai . ' 00:00:00',
                $request->tanggal_selesai . ' 23:59:59'
            ]);
        }

        $payments = $query->orderBy('created_at', 'desc')->get();

        $totalTransaksi = $payments->sum(fn($p) => $p->paket->harga ?? 0);

        $jumlahTransaksi = $payments->count();

        $perPaket = $payments->groupBy('paket_id')->map(function ($group) {
            $paket = $group->first()->paket;
            return [
                'nama_paket' => $paket->nama_paket ?? '-',
                'jumlah'     => $group->count(),
                'total'      => $group->sum(fn($p) => $p->paket->harga ?? 0),
            ];
        })->values();

        return view('admin.laporan-transaksi', compact(
            'payments',
            'totalTransaksi',
            'jumlahTransaksi',
            'perPaket'
        ));
    }

    public function exportPDF()
    {
        $payments = Payment::with(['user', 'paket'])
            ->where('status', 'success')
            ->get();

        $totalTransaksi = $payments->sum(fn($p) => $p->paket->harga ?? 0);

        $pdf = Pdf::loadView('admin.pdf-transaksi', compact(
            'payments',
            'totalTransaksi'
        ));

        return $pdf->download('laporan-transaksi.pdf');
    }
}
