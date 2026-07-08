<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Paket;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_submit_payment_proof()
    {
        Storage::fake('public');
        
        $user = User::factory()->create();
        $paket = Paket::factory()->create();

        $file = UploadedFile::fake()->image('bukti.jpg');

        $response = $this->actingAs($user)->post("/pembayaran/{$paket->id}", [
            'metode_pembayaran' => 'transfer',
            'bukti_pembayaran' => $file
        ]);

        $response->assertRedirect('/paket');
        $this->assertDatabaseHas('payments', [
            'user_id' => $user->id,
            'paket_id' => $paket->id,
            'status' => 'pending'
        ]);
    }

    public function test_admin_can_accept_payment_and_assign_membership()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']); // belum member
        $paket = Paket::factory()->create(['durasi_bulan' => 1, 'jumlah_sesi' => 8, 'nama_paket' => 'Reguler']);

        $payment = Payment::create([
            'user_id' => $user->id,
            'paket_id' => $paket->id,
            'metode_pembayaran' => 'transfer',
            'status' => 'pending',
            'role_target' => 'member'
        ]);

        $response = $this->actingAs($admin)->post("/admin/pembayaran/{$payment->id}/accept");

        $response->assertStatus(302); // Redirect back
        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'success'
        ]);

        $user->refresh();
        $this->assertEquals('member', $user->role);

        $membershipCount = \App\Models\Membership::where('user_id', $user->id)
            ->where('paket_id', $paket->id)
            ->where('status', 'aktif')
            ->count();
        $this->assertEquals(1, $membershipCount);
    }

    public function test_admin_can_reject_payment()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);
        $paket = Paket::factory()->create();

        $payment = Payment::create([
            'user_id' => $user->id,
            'paket_id' => $paket->id,
            'metode_pembayaran' => 'transfer',
            'status' => 'pending',
            'role_target' => 'member'
        ]);

        $response = $this->actingAs($admin)->post("/admin/pembayaran/{$payment->id}/reject");

        $response->assertStatus(302);
        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'rejected'
        ]);
    }
}