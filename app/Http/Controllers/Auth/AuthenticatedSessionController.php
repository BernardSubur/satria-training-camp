<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{

    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'member' || $user->role === 'member_private') {
            return redirect()->route('dashboard');
        }

        if ($user->role === 'user') {
            $latestPayment = $user->payments()->latest()->first();

            if ($latestPayment) {
                if ($latestPayment->status === 'success') {
                    return redirect()->route('dashboard');
                } elseif ($latestPayment->status === 'pending') {
                    return redirect()->route('paket')->with('info', 'Pembayaran Anda sedang diproses oleh admin.');
                } elseif ($latestPayment->status === 'rejected') {
                    return redirect()->route('paket')->with('error', 'Pembayaran Anda ditolak. Silakan lakukan pembelian ulang.');
                }
            }

            return redirect()->route('paket')->with('info', 'Silakan pilih paket untuk melanjutkan.');
        }

        return redirect()->route('welcome');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
