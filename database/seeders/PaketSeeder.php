<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaketSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('paket')->insert([

            [
                'nama_paket' => 'Student Package',
                'jumlah_sesi' => 16,
                'harga' => 200000,
                'kategori' => 'member'
            ],

            [
                'nama_paket' => 'Regular Class 1 Month',
                'jumlah_sesi' => 16,
                'harga' => 300000,
                'kategori' => 'member'
            ],

            [
                'nama_paket' => 'Regular Class 2 Month',
                'jumlah_sesi' => 32,
                'harga' => 550000,
                'kategori' => 'member'
            ],

            [
                'nama_paket' => 'Regular Class 3 Month',
                'jumlah_sesi' => 48,
                'harga' => 800000,
                'kategori' => 'member'
            ],

            [
                'nama_paket' => 'Private Class',
                'jumlah_sesi' => 8,
                'harga' => 550000,
                'kategori' => 'private'
            ]
        ]);
    }
}
