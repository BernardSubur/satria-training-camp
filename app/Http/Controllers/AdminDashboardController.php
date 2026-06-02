<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Reservasi;
use App\Models\Payment;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function dashboard()
    {
        $totalMember = User::whereIn('role', ['member', 'member_private'])->count();
        $totalReservasi = Reservasi::count();
        $sesiHariIni = Reservasi::whereDate('tanggal', Carbon::today())->count();

        $totalPendapatan = Payment::with('paket')
            ->where('status', 'success')
            ->get()
            ->sum(fn($p) => $p->paket->harga ?? 0);

        $totalPaketTerjual = Payment::where('status', 'success')->count();

        $totalPending = Payment::where('status', 'pending')->count();

        $jadwal = [];

        for ($i = 0; $i < 7; $i++) {

            $tanggal = Carbon::today()->addDays($i)->toDateString();

            $reservasis = Reservasi::with('user')
                ->whereDate('tanggal', $tanggal)
                ->get();

            $jadwal[$tanggal] = [
                'private' => [],
                'sesi1' => [],
                'sesi2' => []
            ];

            foreach ($reservasis as $r) {

                if ($r->jam_mulai) {
                    $jam = date('H:i', strtotime($r->jam_mulai)) . " - " .
                        date('H:i', strtotime($r->jam_selesai));

                    $jadwal[$tanggal]['private'][] = [
                        'jam' => $jam,
                        'nama' => $r->user->name
                    ];
                } elseif ($r->sesi == 'Sesi I') {
                    $jadwal[$tanggal]['sesi1'][] = $r->user->name;
                } elseif ($r->sesi == 'Sesi II') {
                    $jadwal[$tanggal]['sesi2'][] = $r->user->name;
                }
            }
        }

        return view('admin.dashboard', compact(
            'totalMember',
            'totalReservasi',
            'sesiHariIni',
            'totalPendapatan',
            'totalPaketTerjual',
            'totalPending',
            'jadwal'
        ));
    }
}
