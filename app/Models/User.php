<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role',
    ];

    public function pembeli()
    {
        return $this->hasOne(Pembeli::class, 'id_user', 'id');
    }

    public function pegawai()
    {
        return $this->hasOne(Pegawai::class, 'id_user', 'id');
    }
}
