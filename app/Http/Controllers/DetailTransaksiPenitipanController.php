<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksiPenitipan;
use Illuminate\Http\Request;

class DetailTransaksiPenitipanController extends Controller
{
    // Lihat semua detail transaksi penitipan
    public function index()
    {
        $details = DetailTransaksiPenitipan::with('barang')->get();
        return response()->json($details);
    }

    // Lihat detail transaksi penitipan tertentu
    public function show($id)
    {
        $detail = DetailTransaksiPenitipan::with('barang')->findOrFail($id);
        return response()->json($detail);
    }
}
