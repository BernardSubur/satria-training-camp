<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi;
use App\Models\Membership;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\SessionEmptyMail;
use App\Mail\PackageExpiredMail;
use App\Services\ReservationService;

class ReservasiController extends Controller
{
    protected $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    public function index()
    {
        if (session()->has('demo_user')) {
            $reservasis = collect(session('demo_reservasi', []))->sortByDesc('tanggal')->values();
        } else {
            $reservasis = Reservasi::where('user_id', auth()->id())
                ->latest()
                ->get();
        }

        return view('member.reservasi', compact('reservasis'));
    }

    public function batal($id)
    {
        if (session()->has('demo_user')) {
            $reservasis = session('demo_reservasi', []);
            $newReservasis = [];
            foreach ($reservasis as $r) {
                if ($r->id == $id) {
                    $membership = session('demo_membership');
                    if ($membership) {
                        $membership->sesi_tersisa += 1;
                        session(['demo_membership' => $membership]);
                    }
                } else {
                    $newReservasis[] = $r;
                }
            }
            session(['demo_reservasi' => $newReservasis]);
            return back()->with('success', 'Reservasi berhasil dibatalkan');
        }

        $reservasi = Reservasi::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $membership = Membership::where('user_id', auth()->id())
            ->where('status', 'aktif')
            ->latest()
            ->first();

        if ($membership) {
            $membership->increment('sesi_tersisa');
        }

        $reservasi->delete();

        return back()->with('success', 'Reservasi berhasil dibatalkan');
    }

    public function getSlot(Request $request)
    {
        $tanggal = $request->tanggal;

        return response()->json([
            'slot1' => Reservasi::where('tanggal', $tanggal)->where('sesi', 'Sesi I')->count(),
            'slot2' => Reservasi::where('tanggal', $tanggal)->where('sesi', 'Sesi II')->count()
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if (session()->has('demo_user')) {
            $membership = session('demo_membership');
            if (!$membership || $membership->status == 'expired' || $membership->sesi_tersisa <= 0) {
                return back()->with('error', 'Sesi latihan kamu sudah habis atau expired.');
            }

            $tanggal = Carbon::parse($request->tanggal)->format('Y-m-d');
            $mapHari = [
                'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
                'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu',
            ];
            if ($request->hari != $mapHari[Carbon::parse($tanggal)->format('l')]) {
                return back()->with('error', 'Hari tidak sesuai.');
            }

            $fakeReservasi = new Reservasi([
                'id' => rand(10000, 99999),
                'user_id' => $user->id,
                'hari' => $request->hari,
                'tanggal' => $tanggal,
                'sesi' => $request->sesi ?? 'Private',
                'jam_mulai' => $request->jam_mulai ? $request->jam_mulai : null,
                'jam_selesai' => $request->jam_selesai ? $request->jam_selesai : null,
                'status' => 'booked'
            ]);

            $reservasis = session('demo_reservasi', []);
            $reservasis[] = $fakeReservasi;
            session(['demo_reservasi' => $reservasis]);

            $membership->sesi_tersisa -= 1;
            session(['demo_membership' => $membership]);

            return redirect()->route('dashboard')->with('success', 'Reservasi berhasil.');
        }

        $val = $this->reservationService->validateMembership($user);
        if (!$val['status']) {
            return back()->with('error', $val['message']);
        }
        $membership = $val['membership'];

        $tanggal = Carbon::parse($request->tanggal)->format('Y-m-d');

        $mapHari = [
            'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu',
        ];
        if ($request->hari != $mapHari[Carbon::parse($tanggal)->format('l')]) {
            return back()->with('error', 'Hari tidak sesuai.');
        }

        if ($user->role == 'member_private') {
            $res = $this->reservationService->processPrivateReservation($user, $request, $tanggal);
        } else {
            $res = $this->reservationService->processRegularReservation($user, $request, $tanggal);
        }

        if (!$res['status']) {
            return back()->with('error', $res['message']);
        }

        $this->reservationService->decrementAndNotify($user, $membership);

        return redirect()->route('dashboard')->with('success', 'Reservasi berhasil.');
    }
}
