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
<<<<<<< HEAD
        // 'poin',
=======
        'poin',
>>>>>>> abcb99b34838138bd1d112b3b2aa725835eab98d
    ];

    public function alamat()
    {
        return $this->hasMany(Alamat::class, 'id_pembeli', 'id_pembeli');
    }

    public function diskusi()
    {   
        return $this->hasMany(Diskusi::class, 'id_pembeli', 'id_pembeli');
    }

}
