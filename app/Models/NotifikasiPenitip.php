<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotifikasiPenitip extends Model
{
    use HasFactory;
    protected $table = 'notifikasi_penitip';
    protected $fillable = ['id_penitip', 'judul', 'pesan', 'is_read'];
}
