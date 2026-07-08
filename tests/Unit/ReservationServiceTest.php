<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Membership;
use App\Models\Reservasi;
use App\Services\ReservationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class ReservationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $reservationService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->reservationService = new ReservationService();
    }

    public function test_validate_membership_returns_true_for_active_membership()
    {
        $user = User::factory()->create();
        $membership = Membership::factory()->create([
            'user_id' => $user->id,
            'status' => 'aktif',
            'sesi_tersisa' => 5
        ]);

        $result = $this->reservationService->validateMembership($user);

        $this->assertTrue($result['status']);
        $this->assertEquals($membership->id, $result['membership']->id);
    }

    public function test_validate_membership_fails_if_no_membership()
    {
        $user = User::factory()->create();

        $result = $this->reservationService->validateMembership($user);

        $this->assertFalse($result['status']);
        $this->assertEquals('Silakan membeli paket terlebih dahulu.', $result['message']);
    }

    public function test_validate_membership_fails_if_expired()
    {
        $user = User::factory()->create();
        Membership::factory()->create([
            'user_id' => $user->id,
            'status' => 'expired',
            'sesi_tersisa' => 5
        ]);

        $result = $this->reservationService->validateMembership($user);

        $this->assertFalse($result['status']);
        $this->assertEquals('Paket kamu sudah habis, silakan beli paket.', $result['message']);
    }

    public function test_validate_membership_fails_if_session_empty()
    {
        $user = User::factory()->create();
        Membership::factory()->create([
            'user_id' => $user->id,
            'status' => 'aktif',
            'sesi_tersisa' => 0
        ]);

        $result = $this->reservationService->validateMembership($user);

        $this->assertFalse($result['status']);
        $this->assertEquals('Sesi latihan kamu sudah habis.', $result['message']);
    }

    public function test_regular_reservation_fails_if_slot_full()
    {
        $user = User::factory()->create();
        $tanggal = Carbon::tomorrow()->format('Y-m-d');
        $sesi = 'Sesi I';

        // Penuhi slot (17)
        for ($i = 0; $i < 17; $i++) {
            $u = User::factory()->create();
            Reservasi::create([
                'user_id' => $u->id,
                'hari' => 'Senin',
                'tanggal' => $tanggal,
                'sesi' => $sesi,
                'status' => 'booked'
            ]);
        }

        $request = new \Illuminate\Http\Request([
            'hari' => 'Senin',
            'tanggal' => $tanggal,
            'sesi' => $sesi
        ]);

        $result = $this->reservationService->processRegularReservation($user, $request, $tanggal);

        $this->assertFalse($result['status']);
        $this->assertEquals('Slot penuh.', $result['message']);
    }
}
