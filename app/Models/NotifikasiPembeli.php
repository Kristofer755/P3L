<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotifikasiPembeli extends Model
{
    use HasFactory;
    protected $table = 'notifikasi_pembeli';
    protected $fillable = ['id_pembeli', 'judul', 'pesan', 'is_read'];
}
