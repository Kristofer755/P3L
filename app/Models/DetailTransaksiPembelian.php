<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksiPembelian extends Model
{
    protected $table = 'detail_transaksi_pembelian';
    protected $primaryKey = 'id_detail_transaksi_pembelian';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_detail_transaksi_pembelian',
        'id_barang',
        'jml_barang_pembelian',
        'harga_satuan_pembelian',
        'total_harga_pembelian',
    ];

    public function transaksi()
    {
        return $this->belongsTo(TransaksiPembelian::class, 'id_detail_transaksi_pembelian', 'id_detail_transaksi_pembelian');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}
