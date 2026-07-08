<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Paket;
use App\Models\Membership;
use App\Models\Reservasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_reservation_page()
    {
        $user = User::factory()->create(['role' => 'member']);
        $response = $this->actingAs($user)->get('/reservasi');
        $response->assertStatus(200);
    }

    public function test_user_can_make_regular_reservation()
    {
        $user = User::factory()->create(['role' => 'member']);
        $paket = Paket::factory()->create();
        Membership::factory()->create([
            'user_id' => $user->id,
            'paket_id' => $paket->id,
            'status' => 'aktif',
            'sesi_tersisa' => 8,
        ]);

        $tanggal = Carbon::tomorrow();
        $mapHari = [
            'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu',
        ];
        $hari = $mapHari[$tanggal->format('l')];

        $response = $this->actingAs($user)->post('/reservasi', [
            'hari' => $hari,
            'tanggal' => $tanggal->format('Y-m-d'),
            'sesi' => 'Sesi I'
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('reservasi', [
            'user_id' => $user->id,
            'sesi' => 'Sesi I'
        ]);

        // Pastikan sesi berkurang
        $this->assertDatabaseHas('membership', [
            'user_id' => $user->id,
            'sesi_tersisa' => 7
        ]);
    }

    public function test_user_can_cancel_reservation_and_restore_session()
    {
        $user = User::factory()->create(['role' => 'member']);
        $paket = Paket::factory()->create();
        Membership::factory()->create([
            'user_id' => $user->id,
            'paket_id' => $paket->id,
            'status' => 'aktif',
            'sesi_tersisa' => 7, // Sudah kurang 1 karena reservasi
        ]);

        $reservasi = Reservasi::create([
            'user_id' => $user->id,
            'hari' => 'Senin',
            'tanggal' => Carbon::tomorrow()->format('Y-m-d'),
            'sesi' => 'Sesi I',
            'status' => 'booked'
        ]);

        $response = $this->actingAs($user)->delete("/reservasi/{$reservasi->id}/batal");

        $response->assertStatus(302); // back()
        $this->assertDatabaseMissing('reservasi', [
            'id' => $reservasi->id
        ]);

        // Sesi kembali jadi 8
        $this->assertDatabaseHas('membership', [
            'user_id' => $user->id,
            'sesi_tersisa' => 8
        ]);
    }
}