<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PaketFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama_paket' => 'Paket Reguler',
            'kategori' => 'regular',
            'harga' => 150000,
            'jumlah_sesi' => 8,
            'durasi_bulan' => 1,
        ];
    }
}