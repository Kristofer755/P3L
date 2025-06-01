<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiPenitipan extends Model
{
    protected $table = 'transaksi_penitipan';
    protected $primaryKey = 'id_transaksi_penitipan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_transaksi_penitipan',
        'id_penitip',
        'id_pegawai',
        'id_detail_transaksi_penitipan',
        'tgl_masuk',
        'tgl_akhir',
        'perpanjangan',
        'status_penitipan',
    ];

    public function detailTransaksiPenitipan()
    {
        return $this->belongsTo(DetailTransaksiPenitipan::class, 'id_detail_transaksi_penitipan', 'id_detail_transaksi_penitipan');
    }

    public function penitip()
    {
        return $this->belongsTo(Penitip::class, 'id_penitip', 'id_penitip');
    }
}
