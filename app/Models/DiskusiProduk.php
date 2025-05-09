<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiskusiProduk extends Model
{
    protected $table = 'diskusi_produk';
    protected $primaryKey = 'id_diskusi';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_diskusi',
        'id_barang',
        'id_pembeli',
        'pesan',
        'tgl_diskusi',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'id_pembeli', 'id_pembeli');
    }
}
