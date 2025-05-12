<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id_barang';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_barang',
        'id_donasi',
        'nama_barang',
        'deskripsi_barang',
        'harga_barang',
        'status_barang',
        'status_garansi',
        'kategori_barang',
    ];

    public function diskusi()
    {
        return $this->hasMany(DiskusiProduk::class, 'id_barang', 'id_barang');
    }

}
