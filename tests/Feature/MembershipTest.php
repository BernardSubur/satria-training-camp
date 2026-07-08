<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Membership;
use App\Services\ReservationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\SessionEmptyMail;
use App\Mail\PackageExpiredMail;
use Carbon\Carbon;

class MembershipTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_sent_when_session_empty()
    {
        Mail::fake();

        $user = User::factory()->create();
        $membership = Membership::factory()->create([
            'user_id' => $user->id,
            'status' => 'aktif',
            'sesi_tersisa' => 0,
            'notif_sesi_habis' => false
        ]);

        $service = new ReservationService();
        $service->checkAndNotify($user, $membership);

        Mail::assertSent(SessionEmptyMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });

        $this->assertDatabaseHas('membership', [
            'id' => $membership->id,
            'notif_sesi_habis' => 1
        ]);
    }

    public function test_membership_expires_when_date_passed()
    {
        Mail::fake();

        $user = User::factory()->create();
        $membership = Membership::factory()->create([
            'user_id' => $user->id,
            'status' => 'aktif',
            'expired' => Carbon::yesterday(),
            'notif_expired' => false
        ]);

        $service = new ReservationService();
        $service->checkAndNotify($user, $membership);

        Mail::assertSent(PackageExpiredMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });

        $this->assertDatabaseHas('membership', [
            'id' => $membership->id,
            'status' => 'expired',
            'notif_expired' => 1
        ]);
    }
}
