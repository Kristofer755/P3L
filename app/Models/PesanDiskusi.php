<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesanDiskusi extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'pesan_diskusi';
    protected $primaryKey = 'id_pesan';

    protected $fillable = [
        'id_pesan',
        'id_diskusi', 
        'id_sender', 
        'tipe_sender', 
        'pesan'
    ];

    public function diskusi()
    {
        return $this->belongsTo(DiskusiProduk::class, 'id_diskusi');
    }
}
