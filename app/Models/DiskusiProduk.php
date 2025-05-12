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

    // Relasi ke pembeli
    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'id_pembeli');
    }
    
    // Relasi ke pegawai
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }
    
    // Relasi ke barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
    
    // Fungsi untuk mendapatkan nama pengirim pesan berdasarkan tipe_sender
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
