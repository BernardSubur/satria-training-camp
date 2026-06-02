<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Reservasi;
use App\Models\Transaksi;
use App\Models\Membership;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'user';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'google_id',
        'sisa_sesi',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'pekerjaan',
        'no_telp',
        'agama',
        'golongan_darah',
        'tinggi_badan',
        'berat_badan',
        'pernah_beladiri',
        'pernah_sakit',
        'is_profile_complete'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'tanggal_lahir' => 'date',
        'password' => 'hashed',
        'is_profile_complete' => 'boolean'
    ];


    public function reservasi()
    {
        return $this->hasMany(Reservasi::class, 'user_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'user_id');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'user_id');
    }

    public function membership()
    {
        return $this->hasMany(Membership::class, 'user_id');
    }

    public function membershipAktif()
    {
        return $this->hasOne(Membership::class, 'user_id')
            ->where('status', 'aktif')
            ->latest();
    }

    public function latestMembership()
    {
        return $this->hasOne(Membership::class, 'user_id')->latestOfMany();
    }

    public function getNoHpFormatAttribute()
    {
        $noHp = preg_replace('/[^0-9]/', '', $this->no_telp);

        if (substr($noHp, 0, 1) === '0') {
            $noHp = '62' . substr($noHp, 1);
        }

        return $noHp;
    }
}