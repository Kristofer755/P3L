<?php

namespace App\Http\Controllers;

use App\Models\DiskusiProduk;
use App\Models\Pembeli;
use App\Models\Barang;
use Illuminate\Http\Request;

class DiskusiProdukController extends Controller
{
    public function index()
    {
        $allDiskusi = DiskusiProduk::all();
        return response()->json($allDiskusi);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_pembeli' => 'required|exists:pembeli,id_pembeli',
            'id_barang' => 'required|exists:barang,id_barang',
            'pesan' => 'required|string',
            'tgl_diskusi' => 'required|date_format:Y-m-d',
        ]);

        $diskusi = DiskusiProduk::create([
            'id_diskusi' => $request->id_diskusi,
            'id_pembeli' => $validatedData['id_pembeli'],
            'id_barang' => $validatedData['id_barang'],
            'pesan' => $validatedData['pesan'],
            'tgl_diskusi' => $validatedData['tgl_diskusi'],
        ]);

        return response()->json([
            'message' => 'Diskusi berhasil ditambahkan',
            'alamat' => $alamat,
        ], 201);
    }

}
