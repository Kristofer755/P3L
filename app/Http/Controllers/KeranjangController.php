<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\Session;
use App\Models\DetailTransaksiPembelian;
use App\Models\TransaksiPembelian;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class KeranjangController extends Controller
{
    public function index()
    {
        return view('pembeli.transaksi.keranjang');
    }

    public function tambah(Request $request, $id_barang)
    {
        $user = Session::get('user');

        $barang = Barang::findOrFail($id_barang);
        $keranjang = session()->get('keranjang', []);

        if (isset($keranjang[$id_barang])) {
            return response()->make(
                "<script>
                    alert('Barang ini sudah ada di keranjang');
                    window.location.href = '" . url()->previous() . "';
                </script>",
                200,
                ['Content-Type' => 'text/html']
            );
        }

        $keranjang[$id_barang] = [
            'nama' => $barang->nama_barang,
            'harga' => $barang->harga_barang,
            'jumlah' => 1,
            'gambar' => $barang->gambar_barang
        ];

        session(['keranjang' => $keranjang]);

        return response()->make(
            '<script>
                alert("Barang ditambahkan ke keranjang.");
                window.location.href = "'.route('keranjang.index').'";
            </script>',
            200,
            ['Content-Type' => 'text/html']
        );

    }            

    public function hapus($id_barang)
    {
        $keranjang = session()->get('keranjang', []);

        if (isset($keranjang[$id_barang])) {
            unset($keranjang[$id_barang]);
            session(['keranjang' => $keranjang]);
        }

        return redirect()->route('keranjang.index')->with('success', 'Barang dihapus dari keranjang.');
    }

}
