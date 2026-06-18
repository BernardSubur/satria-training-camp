<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Paket;
use Illuminate\Database\Eloquent\Factories\Factory;

class MembershipFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'paket_id' => Paket::factory(),
            'sesi_tersisa' => 8,
            'mulai' => now(),
            'expired' => now()->addMonth(),
            'status' => 'aktif',
            'notif_sesi_habis' => false,
            'notif_expired' => false,
        ];
    }
}