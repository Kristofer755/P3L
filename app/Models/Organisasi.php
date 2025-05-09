<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organisasi extends Model
{
    protected $table = 'organisasi'; 
    protected $primaryKey = 'id_organisasi';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_organisasi',
        'nama_organisasi',
        'no_telp_organisasi',
        'alamat_organisasi',
        'email',
        'password',
        'status_organisasi'
    ];

    public function requestDonasi()
    {
        return $this->hasMany(RequestDonasi::class, 'id_organisasi', 'id_organisasi');
    }
}
