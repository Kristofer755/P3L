<?php

namespace App\Http\Controllers;

use App\Models\DiskusiProduk;
use App\Models\PesanDiskusi;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DiskusiProdukController extends Controller
{
    // Tampilkan daftar diskusi
    public function index()
    {
        $diskusi = DiskusiProduk::with('pesan')->get();
        $barang = Barang::all(); // ambil semua barang
        return view('diskusi.diskusi', compact('diskusi', 'barang'));
    }

    // Tampilkan form buat diskusi baru
    public function create()
    {
        return view('diskusi.create');
    }

    // Simpan diskusi baru
    public function store(Request $request)
    {
        $request->validate([
            'id_barang' => 'required',
            'judul_diskusi' => 'required|string',
        ]);

        // Mendapatkan user dari session
        $user = Session::get('user');
        
        if (!$user) {
            return back()->with('error', 'Anda harus login terlebih dahulu.');
        }
        
        // Memastikan user dan menentukan role
        $id_pembeli = null;
        $id_pegawai = null;

        // Cek apakah user memiliki property id_pembeli (berarti pembeli)
        if (isset($user->id_pembeli)) {
            $id_pembeli = $user->id_pembeli;
        }
        // Cek apakah user memiliki property id_pegawai dan jabatan CS
        elseif (isset($user->id_pegawai) && strtolower($user->jabatan) === 'customer service') {
            $id_pegawai = $user->id_pegawai;
        } 
        else {
            return back()->with('error', 'Hanya pembeli atau customer service yang bisa membuat diskusi.');
        }

        // Membuat ID diskusi baru
        $lastNumber = DiskusiProduk::selectRaw('MAX(CAST(SUBSTRING(id_diskusi, 4) AS UNSIGNED)) as max_id')->value('max_id');
        $newNumber = $lastNumber ? $lastNumber + 1 : 1;
        $newId = 'DIS' . $newNumber;

        // Membuat diskusi baru
        $diskusi = DiskusiProduk::create([
            'id_diskusi'   => $newId,
            'id_barang'    => $request->id_barang,
            'id_pembeli'   => $id_pembeli,  
            'id_pegawai'   => $id_pegawai,  
            'judul_diskusi'=> $request->judul_diskusi,
            'tgl_diskusi'  => now()->toDateString(),
        ]);

        return redirect()->route('diskusi.show', $diskusi->id_diskusi);
    }


    public function show($id_diskusi)
    {
        $diskusi = DiskusiProduk::with('pesan')->get();
        $selectedDiskusi = DiskusiProduk::with('pesan')->findOrFail($id_diskusi);
        $barang = Barang::all(); // ambil semua barang
        return view('diskusi.diskusi', compact('diskusi', 'selectedDiskusi', 'barang'));
    }

    // Simpan pesan baru
    public function kirimPesan(Request $request)
    {
        $request->validate([
            'id_diskusi' => 'required',
            'pesan' => 'required|string',
        ]);

        $user = Session::get('user');

        if (!$user) {
            return back()->with('error', 'Anda harus login untuk mengirim pesan.');
        }

        // Gunakan isset() alih-alih property_exists() untuk data dari session
        if (isset($user->id_pembeli)) {
            $tipe_sender = 'pembeli';
            $id_sender = $user->id_pembeli;
        } elseif (isset($user->id_pegawai)) {
            $tipe_sender = 'pegawai';
            $id_sender = $user->id_pegawai;
        } else {
            return back()->with('error', 'Role tidak dikenali.');
        }

        PesanDiskusi::create([
            'id_diskusi' => $request->id_diskusi,
            'tipe_sender' => $tipe_sender,
            'id_sender' => $id_sender,
            'pesan' => $request->pesan,
        ]);

        return back()->with('success', 'Pesan dikirim!');
    }
}
