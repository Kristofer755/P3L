<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    

    public function diskusi()
    {
        return $this->hasMany(Diskusi::class, 'id_barang', 'id_barang');
    }

}
