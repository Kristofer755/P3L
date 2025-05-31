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

    protected $casts = [
        'tgl_transaksi' => 'datetime',  
    ];

    protected $fillable = [
        'id_transaksi_pembelian',
        'id_pembeli',
        'id_alamat',
        'no_transaksi',
        'tgl_transaksi',
        'total_pembayaran',
        'status_pembayaran',
        'tukar_poin',
        'bukti_pembayaran',
    ];

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksiPembelian::class, 'id_transaksi_pembelian', 'id_transaksi_pembelian');
    }

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'id_pembeli');
    }

    public function alamat()
    {
        return $this->belongsTo(Alamat::class, 'id_alamat');
    }

    public function pengiriman()
    {
        return $this->hasMany(Pengiriman::class, 'id_transaksi_pembelian', 'id_transaksi_pembelian');
    }
}
