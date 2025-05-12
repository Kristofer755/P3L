<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // Jika kamu menggunakan Laravel default, biasanya ini tidak perlu diubah:
    protected $fillable = [
        'name', 'email', 'password', 'role',
    ];

    // Relasi ke Pembeli
    public function pembeli()
    {
        return $this->hasOne(Pembeli::class, 'id_user', 'id');
    }

    // Jika kamu juga punya relasi ke pegawai misalnya:
    public function pegawai()
    {
        return $this->hasOne(Pegawai::class, 'id_user', 'id');
    }
}
