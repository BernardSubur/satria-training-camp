<?php

namespace App\Services;

use App\Models\Reservasi;
use App\Models\Membership;
use Illuminate\Support\Facades\Mail;
use App\Mail\SessionEmptyMail;
use App\Mail\PackageExpiredMail;
use Carbon\Carbon;

class ReservationService
{
    public function validateMembership($user)
    {
        $membership = Membership::where('user_id', $user->id)
            ->where('status', 'aktif')
            ->latest()
            ->first();

        if (!$membership) {
            return ['status' => false, 'message' => 'Silakan membeli paket terlebih dahulu.'];
        }

        if ($membership->status == 'expired') {
            return ['status' => false, 'message' => 'Paket kamu sudah habis, silakan beli paket.'];
        }

        if ($membership->sesi_tersisa <= 0) {
            return ['status' => false, 'message' => 'Sesi latihan kamu sudah habis.'];
        }

        return ['status' => true, 'membership' => $membership];
    }

    public function processPrivateReservation($user, $request, $tanggal)
    {
        $request->validate([
            'hari' => 'required',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required'
        ]);

        $mulai = Carbon::createFromFormat('H:i', $request->jam_mulai);
        $selesai = Carbon::createFromFormat('H:i', $request->jam_selesai);

        if ($mulai->diffInMinutes($selesai) != 60) {
            return ['status' => false, 'message' => 'Durasi harus 1 jam.'];
        }

        $cek = Reservasi::where('user_id', $user->id)
            ->where('tanggal', $tanggal)
            ->exists();

        if ($cek) {
            return ['status' => false, 'message' => 'Sudah reservasi pada tanggal ini.'];
        }

        $jadwalDipakai = Reservasi::where('tanggal', $tanggal)
            ->where('sesi', 'Private')
            ->where(function ($query) use ($request) {
                $query->whereBetween('jam_mulai', [
                    $request->jam_mulai,
                    $request->jam_selesai
                ])

                    ->orWhereBetween('jam_selesai', [
                        $request->jam_mulai,
                        $request->jam_selesai
                    ])

                    ->orWhere(function ($q) use ($request) {
                        $q->where('jam_mulai', '<=', $request->jam_mulai)
                            ->where('jam_selesai', '>=', $request->jam_selesai);
                    });
            })
            ->exists();
        if ($jadwalDipakai) {
            return ['status' => false, 'message' => 'Jadwal sudah digunakan.'];
        }

        Reservasi::create([
            'user_id' => $user->id,
            'hari' => $request->hari,
            'tanggal' => $tanggal,
            'jam_mulai' => $mulai,
            'jam_selesai' => $selesai,
            'sesi' => 'Private',
            'status' => 'booked'
        ]);

        return ['status' => true];
    }

    public function processRegularReservation($user, $request, $tanggal)
    {
        $request->validate([
            'hari' => 'required',
            'tanggal' => 'required',
            'sesi' => 'required'
        ]);

        $cek = Reservasi::where('user_id', $user->id)
            ->where('tanggal', $tanggal)
            ->where('sesi', $request->sesi)
            ->exists();

        if ($cek) {
            return ['status' => false, 'message' => 'Sudah reservasi untuk sesi ini.'];
        }

        $slot = Reservasi::where('tanggal', $tanggal)
            ->where('sesi', $request->sesi)
            ->count();

        if ($slot >= 17) {
            return ['status' => false, 'message' => 'Slot penuh.'];
        }

        Reservasi::create([
            'user_id' => $user->id,
            'hari' => $request->hari,
            'tanggal' => $tanggal,
            'sesi' => $request->sesi,
            'status' => 'booked'
        ]);

        return ['status' => true];
    }

    public function decrementAndNotify($user, $membership)
    {
        $membership->decrement('sesi_tersisa');

        // Refresh data
        $membership->refresh();

        $this->checkAndNotify($user, $membership);
    }

    public function checkAndNotify($user, $membership)
    {
        if (!$user || !$user->email) return;

        // 1. Notifikasi Sesi Habis (Jika sisa sesi 0 dan belum pernah dikirim notifikasinya)
        if ($membership->sesi_tersisa <= 0 && $membership->status == 'aktif' && !$membership->notif_sesi_habis) {
            try {
                Mail::to($user->email)->send(new SessionEmptyMail($user));
                $membership->update(['notif_sesi_habis' => true]);
                \Log::info("Email Sesi Habis terkirim ke: " . $user->email);
            } catch (\Exception $e) {
                \Log::error("Gagal kirim email Sesi Habis: " . $e->getMessage());
            }
        }

        // 2. Notifikasi Paket Berakhir (Jatuh Tempo)
        // Tetap dikirim jika sudah jatuh tempo meskipun sisa sesi belum 0
        $expiredDate = Carbon::parse($membership->expired)->startOfDay();
        $today = Carbon::today();

        if ($today->greaterThanOrEqualTo($expiredDate) && $membership->status == 'aktif' && !$membership->notif_expired) {
            try {
                $membership->update([
                    'status' => 'expired',
                    'notif_expired' => true
                ]);
                Mail::to($user->email)->send(new PackageExpiredMail($user));
                \Log::info("Email Paket Berakhir terkirim ke: " . $user->email);
            } catch (\Exception $e) {
                \Log::error("Gagal kirim email Paket Berakhir: " . $e->getMessage());
            }
        }
    }
}
