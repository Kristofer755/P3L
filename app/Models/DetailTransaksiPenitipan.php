<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksiPenitipan extends Model
{
    protected $table = 'detail_transaksi_penitipan';
    protected $primaryKey = 'id_detail_transaksi_penitipan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_detail_transaksi_penitipan',
        'id_barang',
        'jml_barang_penitipan',
        'harga_satuan_penitipan',
        'total_harga_penitipan',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    public function transaksiPenitipan()
    {
        return $this->hasOne(TransaksiPenitipan::class, 'id_detail_transaksi_penitipan', 'id_detail_transaksi_penitipan');
    }

}
