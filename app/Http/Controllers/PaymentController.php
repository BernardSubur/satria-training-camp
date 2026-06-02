<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentSubmittedMail;

class PaymentController extends Controller
{
    public function show($paket_id)
    {
        $paket = Paket::findOrFail($paket_id);
        return view('pembayaran', compact('paket'));
    }

    public function store(Request $request, $paket_id)
    {
        $request->validate([
            'metode_pembayaran' => 'required|in:qris,transfer',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $paket = Paket::findOrFail($paket_id);
        
        $role_target = 'member';
        if (str_contains(strtolower($paket->nama_paket), 'private')) {
            $role_target = 'member_private';
        }

        $imagePath = $request->file('bukti_pembayaran')->store('payments', 'public');

        $payment = Payment::create([
            'user_id' => Auth::id(),
            'paket_id' => $paket->id,
            'metode_pembayaran' => $request->metode_pembayaran,
            'bukti_pembayaran' => $imagePath,
            'status' => 'pending',
            'role_target' => $role_target,
        ]);

        $admin = User::where('role', 'admin')->first();
        if ($admin && $admin->email) {
            try {
                Mail::to($admin->email)->send(new PaymentSubmittedMail(Auth::user(), $paket, $payment));
            } catch (\Exception $e) {
                \Log::error('Gagal mengirim email pembayaran: ' . $e->getMessage());
            }
        }

        return redirect()->route('paket')->with('success', 'Bukti pembayaran berhasil diupload. Silakan tunggu konfirmasi admin.');
    }
}
