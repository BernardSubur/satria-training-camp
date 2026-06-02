<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi;

class AdminReservasiController extends Controller
{
    public function dataReservasi()
    {
        $reservasis = Reservasi::with('user')
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('admin.data-reservasi', compact('reservasis'));
    }
}
