<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckMembershipExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-membership-expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengecek paket latihan yang sudah berakhir dan mengirimkan notifikasi email ke member';
    /**
     * Execute the console command.
     */
    public function handle(\App\Services\ReservationService $service)
    {
        $activeMemberships = \App\Models\Membership::where('status', 'aktif')
            ->whereNotNull('expired')
            ->get();

        $count = 0;
        foreach ($activeMemberships as $membership) {
            $user = $membership->user;
            
            $oldStatus = $membership->status;
            $service->checkAndNotify($user, $membership);
            
            if ($membership->fresh()->status === 'expired' && $oldStatus === 'aktif') {
                $count++;
            }
        }

        $this->info("Berhasil memproses pengecekan membership. {$count} paket baru saja ditandai berakhir.");
    }
}
