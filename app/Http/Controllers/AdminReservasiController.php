<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi;

class AdminReservasiController extends Controller
{
    public function dataReservasi(Request $request)
    {
        $reservasis = Reservasi::with('user');

        if (
            $request->filled('tanggal_mulai') &&
            $request->filled('tanggal_selesai')
        ) {
            $reservasis->whereBetween('tanggal', [
                $request->tanggal_mulai,
                $request->tanggal_selesai,
            ]);
        }

        $reservasis = $reservasis
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('admin.data-reservasi', compact('reservasis'));
    }
}
