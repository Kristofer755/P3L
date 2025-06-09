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

    public function penitip()
    {
        return $this->hasOneThrough(
            Penitip::class,                // model tujuan
            TransaksiPenitipan::class,     // model perantara
            'id_detail_transaksi_penitipan', // FK di transaksi_penitipan
            'id_penitip',                  // FK di penitip
            'id_detail_transaksi_penitipan', // localKey di detail_transaksi_penitipan
            'id_penitip'                   // localKey di transaksi_penitipan
        );
    }
}
