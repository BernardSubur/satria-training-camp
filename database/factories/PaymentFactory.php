<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Paket;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'paket_id' => Paket::factory(),
            'metode_pembayaran' => 'transfer',
            'bukti_pembayaran' => 'payments/test.jpg',
            'status' => 'pending',
            'role_target' => 'member',
        ];
    }
}