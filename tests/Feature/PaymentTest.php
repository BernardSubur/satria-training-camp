<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_can_be_created()
    {
        $payment = Payment::factory()->create();

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'pending'
        ]);
    }

    public function test_payment_has_user_relation()
    {
        $payment = Payment::factory()->create();

        $this->assertNotNull($payment->user);
    }

    public function test_payment_has_paket_relation()
    {
        $payment = Payment::factory()->create();

        $this->assertNotNull($payment->paket);
    }
}