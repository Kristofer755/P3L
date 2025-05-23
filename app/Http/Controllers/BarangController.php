<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DiskusiProduk;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function show($id)
    {
        $barang = Barang::findOrFail($id);
        $diskusi = DiskusiProduk::where('id_barang', $id)
            ->orderBy('tgl_diskusi', 'desc')
            ->paginate(10);

        return view('detailBarang', compact('barang', 'diskusi'));
    }
}
