<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{

    public function index()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan']);
        }

        $otp = rand(100000, 999999);

        $user->otp_code = (string)$otp;
        $user->otp_expired_at = now()->addMinutes(5);
        $user->save();

        Mail::raw("Kode OTP reset password anda adalah: $otp", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('OTP Reset Password');
        });

        session([
            'reset_email' => $user->email,
            'otp_verified' => false
        ]);

        return redirect()->route('password.otp.form')
            ->with('success', 'OTP berhasil dikirim ke email');
    }

    public function formOtp()
    {
        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required'
        ]);

        $user = User::where('email', session('reset_email'))->first();

        if (!$user) {
            return redirect()->route('login');
        }
        
        $otpInput = trim($request->otp);

        if ((string)$user->otp_code !== (string)$otpInput) {
            return back()->withErrors(['otp' => 'OTP salah']);
        }

        if ($user->otp_expired_at && now()->greaterThan($user->otp_expired_at)) {
            return back()->withErrors(['otp' => 'OTP sudah kadaluarsa']);
        }

        session(['otp_verified' => true]);

        return redirect()->route('password.reset.form');
    }

    public function formReset()
    {
        if (!session('otp_verified')) {
            return redirect()->route('login');
        }

        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::where('email', session('reset_email'))->first();

        if (!$user) {
            return redirect()->route('login');
        }

        $user->password = Hash::make($request->password);
        $user->otp_code = null;
        $user->otp_expired_at = null;
        $user->save();

        session()->forget(['reset_email', 'otp_verified']);

        return redirect()->route('login')
            ->with('status', 'Password berhasil diubah');
    }
}
