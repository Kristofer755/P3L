<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        // 'jenis_role', // pembeli, penitip, owner, dll
        // 'id_role'
    ];
    protected $hidden   = ['password', 'remember_token'];
    protected $casts    = ['email_verified_at'=>'datetime'];

    // // Polymorphic profile
    // public function profile()
    // {
    //     return $this->morphTo(__FUNCTION__, 'jenis_role', 'id_role');
    // }

    // // Only for pegawai
    // public function roles()
    // {
    //     if ($this->type !== 'pegawai') return null;
    //     return $this->hasMany(RolePegawai::class, 'id_pegawai', 'id_role');
    // }

}
