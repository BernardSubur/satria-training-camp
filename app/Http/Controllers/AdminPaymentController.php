<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::with(['user', 'paket']);

        if ($request->filled('search')) {
            $payments->whereHas('user', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $payments = $payments->latest()->get();

        return view('admin.pembayaran', compact('payments'));
    }

    public function showBukti($id)
    {
        $payment = Payment::with(['user', 'paket'])->findOrFail($id);

        return response()->json([
            'nama_user' => $payment->user->name ?? '-',
            'paket' => $payment->paket->nama_paket ?? '-',
            'metode' => strtoupper($payment->metode_pembayaran),
            'status' => $payment->status,
            'bukti_url' => $payment->bukti_pembayaran ? '/storage/' . $payment->bukti_pembayaran : null
        ]);
    }

    public function accept($id)
    {
        $payment = Payment::with('paket')->findOrFail($id);
        
        $payment->update(['status' => 'success']);
        
        $user = User::find($payment->user_id);
        if ($user) {
            $user->update(['role' => $payment->role_target]);
            
            $durasiBulan = $payment->paket->durasi_bulan ?? 1;
            $expired = \Carbon\Carbon::now()->addMonths($durasiBulan);

            \App\Models\Membership::where('user_id', $user->id)
                ->where('status', 'aktif')
                ->update(['status' => 'expired']);

            \App\Models\Membership::create([
                'user_id' => $user->id,
                'paket_id' => $payment->paket_id,
                'sesi_tersisa' => $payment->paket->jumlah_sesi,
                'mulai' => \Carbon\Carbon::now(),
                'expired' => $expired,
                'status' => 'aktif'
            ]);
        }

        return redirect()->back()->with('success', 'Pembayaran diterima. Role user telah diupdate dan paket aktif.');
    }

    public function reject($id)
    {
        $payment = Payment::findOrFail($id);

        if ($payment->bukti_pembayaran) {
            Storage::disk('public')->delete($payment->bukti_pembayaran);
        }

        $payment->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Pembayaran ditolak dan bukti dihapus.');
    }

    public function destroy($id)
    {
        try {
            $payment = Payment::findOrFail($id);

            if ($payment->status !== 'rejected') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya data pembayaran yang ditolak yang dapat dihapus.'
                ], 403);
            }

            if ($payment->bukti_pembayaran) {
                Storage::disk('public')->delete($payment->bukti_pembayaran);
            }

            $payment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data pembayaran berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }
}
