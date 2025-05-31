<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    protected $table = 'pengiriman';
    protected $primaryKey = 'id_pengiriman';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_pengiriman',
        'id_pegawai',
        'id_transaksi_pembelian',
        'tgl_pengiriman',
        'status_pengiriman',
        'tipe_pengiriman',
    ];

    public function transaksiPembelian()
    {
        return $this->belongsTo(TransaksiPembelian::class, 'id_transaksi_pembelian');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }
}
