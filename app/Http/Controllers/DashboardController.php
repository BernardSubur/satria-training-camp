<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Reservasi;
use App\Models\Membership;
use Carbon\Carbon;

use App\Services\ReservationService;

class DashboardController extends Controller
{
    protected $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    public function index()
    {
        Carbon::setLocale('id');

        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Demo Bypass
        if (session()->has('demo_user')) {
            $membership = session('demo_membership');
            $reservasis = collect(session('demo_reservasi', []));
        } else {
            $membership = Membership::where('user_id', $user->id)->latest()->first();

            if (!$membership) {
                return redirect()->route('paket')->with('error', 'Silakan membeli paket terlebih dahulu');
            }
        }

        $popup = null;

        if ($membership) {
            if (!session()->has('demo_user')) {
                $this->reservationService->checkAndNotify($user, $membership);
                $membership->refresh();
            }

            if ($membership->status === 'expired') {
                $popup = [
                    'type' => 'expired',
                    'message' => 'Masa aktif paket latihan kamu sudah berakhir.'
                ];
            }
        }

        if ($membership->status != 'aktif') {
            $popup = [
                'type' => 'expired',
                'message' => 'Membership kamu sudah tidak aktif.'
            ];
        }

        if ($membership->sesi_tersisa <= 0 && $membership->status == 'aktif') {
            $popup = [
                'type' => 'sesi_habis',
                'message' => 'Paket masih aktif tetapi sesi latihan kamu sudah habis.'
            ];
        }

        $now = Carbon::now();

        if (!session()->has('demo_user')) {
            $reservasis = Reservasi::where('user_id', $user->id)
                ->get()
                ->filter(function ($r) use ($now) {

                    $jamMulai = null;
                    $jamSelesai = null;

                    if ($r->jam_mulai) {
                        $tanggal = Carbon::parse($r->tanggal)->format('Y-m-d');

                        $jamMulai = Carbon::createFromFormat('Y-m-d H:i:s', $tanggal . ' ' . date('H:i:s', strtotime($r->jam_mulai)));
                        $jamSelesai = Carbon::createFromFormat('Y-m-d H:i:s', $tanggal . ' ' . date('H:i:s', strtotime($r->jam_selesai)));
                    }

                    elseif ($r->sesi) {
                        $tanggal = Carbon::parse($r->tanggal)->format('Y-m-d');

                        if ($r->hari == 'Selasa' || $r->hari == 'Kamis') {

                            $jamMulai = $r->sesi == 'Sesi I'
                                ? Carbon::parse($tanggal . ' 16:30')
                                : Carbon::parse($tanggal . ' 19:00');

                            $jamSelesai = $r->sesi == 'Sesi I'
                                ? Carbon::parse($tanggal . ' 18:00')
                                : Carbon::parse($tanggal . ' 20:30');
                        } else {

                            $jamMulai = $r->sesi == 'Sesi I'
                                ? Carbon::parse($tanggal . ' 15:30')
                                : Carbon::parse($tanggal . ' 17:00');

                            $jamSelesai = $r->sesi == 'Sesi I'
                                ? Carbon::parse($tanggal . ' 17:00')
                                : Carbon::parse($tanggal . ' 18:30');
                        }
                    }

                    if ($jamSelesai && $now->greaterThan($jamSelesai)) {

                        if ($r->status == 'booked') {
                            $r->update(['status' => 'selesai']);
                        }

                        return false;
                    }

                    return true;
                })
                ->sortByDesc('tanggal')
                ->values();
        }

        $latihanHariIni = 0;
        $latihanSelesai = 0;

        foreach ($reservasis as $r) {

            if (Carbon::parse($r->tanggal)->isToday()) {
                $latihanHariIni++;
            }
        }

        return view('member.dashboard', compact(
            'reservasis',
            'latihanHariIni',
            'latihanSelesai',
            'membership',
            'user',
            'popup'
        ))->with('sisaSesi', $membership->sesi_tersisa);
    }
}
