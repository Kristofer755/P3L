<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penitip extends Model
{
    protected $table = 'penitip';
    protected $primaryKey = 'id_penitip';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_penitip',
        'nama_penitip',
        'email',
        'password',
        'NIK',
        'no_telp',
        'saldo',
        'foto_ktp',
        'poin',
    ];
}
