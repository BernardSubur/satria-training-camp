<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Paket;
use App\Models\Membership;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_has_active_membership()
    {
        $user = User::factory()->create([
            'role' => 'member'
        ]);

        $paket = Paket::factory()->create();

        Membership::factory()->create([
            'user_id' => $user->id,
            'paket_id' => $paket->id,
            'status' => 'aktif',
            'sesi_tersisa' => 8,
        ]);

        $this->assertDatabaseHas('membership', [
            'user_id' => $user->id,
            'status' => 'aktif'
        ]);
    }

    public function test_member_without_membership()
    {
        $user = User::factory()->create([
            'role' => 'member'
        ]);

        $this->assertDatabaseMissing('membership', [
            'user_id' => $user->id
        ]);
    }
}