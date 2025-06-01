<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksiPembelian;
use App\Models\Barang;
use App\Models\TransaksiPembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DetailTransaksiPembelianController extends Controller
{
    public function index()
    {
        $details = DetailTransaksiPembelian::with(['barang','transaksi'])->get();
        return view('detail_transaksi_pembelian.index', compact('details'));
    }

    public function show($id)
    {
        $detail = DetailTransaksiPembelian::with(['barang','transaksi'])->findOrFail($id);
        return view('detail_transaksi_pembelian.show', compact('detail'));
    }

    public function create()
    {
        $barangs    = Barang::all();
        $transaksis = TransaksiPembelian::all();
        return view('detail_transaksi_pembelian.create', compact('barangs','transaksis'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_transaksi_pembelian'    => 'required|exists:transaksi_pembelian,id_transaksi_pembelian',
            'id_barang'                 => 'required|exists:barangs,id_barang',
            'jml_barang_pembelian'      => 'required|integer|min:1',
            'harga_satuan_pembelian'    => 'required|numeric',
        ]);

        // 3) Hitung total harga
        $data['total_harga_pembelian'] = 
            $data['harga_satuan_pembelian'] * $data['jml_barang_pembelian'];

        // 4) Simpan
        DetailTransaksiPembelian::create($data);

        return redirect()
            ->route('detail_transaksi_pembelian.index')
            ->with('success', 'Detail transaksi berhasil ditambahkan.');
    }
}