<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembeli extends Model
{
    protected $table = 'pembeli';
    protected $primaryKey = 'id_pembeli';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_pembeli',
        'nama_pembeli',
        'no_telp',
        'email',
        'password',
        'poin', 
    ];

    public function alamat()
    {
        return $this->hasMany(Alamat::class, 'id_pembeli', 'id_pembeli');
    }

    public function diskusi()
    {   
        return $this->hasMany(Diskusi::class, 'id_pembeli', 'id_pembeli');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
    
    public function profil()
    {
        return view('pembeli.profil');
    }
}