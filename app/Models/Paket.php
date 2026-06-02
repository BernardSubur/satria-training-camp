<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    protected $table = 'paket';

    protected $fillable = [
        'nama_paket',
        'jumlah_sesi',
        'harga',
        'tipe',
        'durasi_bulan'
    ];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'paket_id');
    }

    public function membership()
    {
        return $this->hasMany(Membership::class, 'paket_id');
    }
}
