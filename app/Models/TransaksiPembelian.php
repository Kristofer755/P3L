<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiPembelian extends Model
{
    protected $table = 'transaksi_pembelian';
    protected $primaryKey = 'id_transaksi_pembelian';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_transaksi_pembelian',
        'id_pembeli',
        'id_detail_transaksi_pembelian',
        'tgl_transaksi',
        'total_pembayaran',
        'status_pembayaran',
        'bukti_pembayaran',
    ];

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksiPembelian::class, 'id_detail_transaksi_pembelian', 'id_detail_transaksi_pembelian');
    }

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'id_pembeli');
    }
}
