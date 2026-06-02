<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paket;
use App\Models\Transaksi;
use App\Models\Membership;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PaketController extends Controller
{

    public function index()
    {
        $pakets = Paket::all();

        $membership = Membership::where('user_id', Auth::id())
            ->latest()
            ->first();

        return view('member.paket', compact('pakets', 'membership'));
    }

    public function beli($id)
    {
        $paket = Paket::findOrFail($id);

        $user = Auth::user();

        $membershipLama = Membership::where('user_id', $user->id)
            ->where('status', 'aktif')
            ->latest()
            ->first();

        if ($membershipLama) {
            $membershipLama->update([
                'status' => 'expired'
            ]);
        }

        $order_id = 'STC-' . Str::upper(Str::random(10));

        $transaksi = Transaksi::create([

            'user_id' => $user->id,
            'paket_id' => $paket->id,
            'order_id' => $order_id,
            'harga' => $paket->harga,
            'status' => 'pending'

        ]);

        return redirect()->route('transaksi.show', $transaksi->id);
    }

    public function batal()
    {

        auth()->logout();

        return redirect()->route('welcome');
    }

    public function show($id)
    {
        $transaksi = Transaksi::with('paket')->findOrFail($id);

        if ($transaksi->user_id != Auth::id()) {
            abort(403, 'Akses ditolak');
        }

        return view('member.pembayaran', compact('transaksi'));
    }
}
