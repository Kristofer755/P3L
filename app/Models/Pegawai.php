<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';
    protected $primaryKey = 'id_pegawai';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_pegawai',
        'id_role',
        'nama_pegawai',
        'email',
        'password',
        'no_telp',
        'jabatan',
        'status_pegawai',
        'tgl_lahir',
    ];

    public function diskusi()
    {
        return $this->hasMany(DiskusiProduk::class, 'id_pegawai', 'id_pegawai');
    }

    public function pengiriman()
    {
        return $this->hasMany(Pengiriman::class, 'id_pegawai', 'id_pegawai');
    }
}
