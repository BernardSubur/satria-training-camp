<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Paket extends Model
{
    use HasFactory;
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
