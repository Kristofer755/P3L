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
        'pesan',        
        'tgl_diskusi',
        'tipe_sender',  
    ];

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'id_pembeli');
    }
    
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }
    
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
    
    public function getNamaPengirimAttribute()
    {
        if ($this->tipe_sender == 'pembeli' && $this->pembeli) {
            return $this->pembeli->nama;
        } elseif ($this->tipe_sender == 'pegawai' && $this->pegawai) {
            return $this->pegawai->nama . ' (CS)';
        }
        
        return 'Unknown';
    }
       
}
