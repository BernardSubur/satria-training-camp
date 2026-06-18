<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Membership extends Model
{
    use HasFactory;
    protected $table = 'membership';

    protected $fillable = [
        'user_id',
        'paket_id',
        'sesi_tersisa',
        'mulai',
        'expired',
        'status',
        'notif_sesi_habis',
        'notif_expired'
    ];

    protected $casts = [
        'mulai' => 'datetime',
        'expired' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class, 'paket_id');
    }

    public function isExpired()
    {
        return $this->expired && Carbon::now()->greaterThan($this->expired);
    }

    public function sisaHari()
    {
        return Carbon::now()->diffInDays($this->expired, false);
    }
}
