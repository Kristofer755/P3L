<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiskusiProduk extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'diskusi_produk';
    protected $primaryKey = 'id_diskusi';

    protected $fillable = [
        'id_diskusi',
        'id_barang', 
        'id_pembeli', 
        'id_pegawai',
        'judul_diskusi',
        'tgl_diskusi',
    ];

    public function pesan()
    {
        return $this->hasMany(PesanDiskusi::class, 'id_diskusi');
    }
    
    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'id_pembeli');
    }
    
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }
       
}
