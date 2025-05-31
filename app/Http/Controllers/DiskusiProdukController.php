<?php

namespace App\Http\Controllers;

use App\Models\DiskusiProduk;
use App\Models\Barang;
use App\Models\Pembeli;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class DiskusiProdukController extends Controller
{
    // Tampilkan daftar diskusi, dikelompokkan berdasarkan barang
    public function index()
    {
        // Ambil semua barang yang memiliki diskusi
        $barangDenganDiskusi = Barang::whereHas('diskusi')->with([
            'diskusi' => function($query) {
                $query->with(['pembeli', 'pegawai'])->orderBy('tgl_diskusi', 'asc')->orderBy('id_diskusi', 'asc');
            }
        ])->get();
        
        // Ambil semua barang untuk dropdown
        $semuaBarang = Barang::all();
        
        // Add this line to get all discussions for the view
        $diskusi = DiskusiProduk::with(['pembeli', 'pegawai'])->orderBy('tgl_diskusi', 'desc')->get();
        
        return view('diskusi.diskusi', [
            'barangDenganDiskusi' => $barangDenganDiskusi,
            'barang' => $semuaBarang,
            'diskusi' => $diskusi // Add this variable to pass to the view
        ]);
    }

    // Simpan pesan diskusi baru
    public function store(Request $request)
    {
        $request->validate([
            'id_barang' => 'required',
            'pesan' => 'required|string',
        ]);

        // Mendapatkan user dari session
        $user = Session::get('user');
        
        if (!$user) {
            return back()->with('error', 'Anda harus login terlebih dahulu.');
        }
        
        // Menentukan id_pembeli atau id_pegawai dan role
        $id_pembeli = null;
        $id_pegawai = null;
        $tipe_sender = '';

        // Cek tipe user
        if (isset($user->id_pembeli)) {
            $id_pembeli = $user->id_pembeli;
            $tipe_sender = 'pembeli';
        } elseif (isset($user->id_pegawai) && strtolower($user->jabatan) === 'customer service') {
            $id_pegawai = $user->id_pegawai;
            $tipe_sender = 'pegawai';
        } else {
            return back()->with('error', 'Hanya pembeli atau customer service yang bisa membuat diskusi.');
        }

        // Membuat ID diskusi baru
        $lastNumber = DiskusiProduk::selectRaw('MAX(CAST(SUBSTRING(id_diskusi, 4) AS UNSIGNED)) as max_id')->value('max_id');
        $newNumber = $lastNumber ? $lastNumber + 1 : 1;
        $newId = 'DIS' . $newNumber;

        // Membuat entri diskusi baru
        $diskusi = DiskusiProduk::create([
            'id_diskusi'   => $newId,
            'id_barang'    => $request->id_barang,
            'id_pembeli'   => $id_pembeli,
            'id_pegawai'   => $id_pegawai,
            'pesan'        => $request->pesan,
            'tgl_diskusi'  => now()->toDateString(),
            'tipe_sender'  => $tipe_sender
        ]);

        // return redirect()->route('diskusi.index')->with('success', 'Pesan berhasil dikirim!');
        return back()->with('success','Pesan berhasil dikirim!');

    }

    public function show($id)
    {
        $selectedDiskusi = DiskusiProduk::with(['pembeli', 'pegawai', 'barang'])->findOrFail($id);
        
        // Get all messages related to this discussion
        $pesan = DiskusiProduk::where('id_barang', $selectedDiskusi->id_barang)
                            ->orderBy('tgl_diskusi', 'asc')
                            ->get();
        
        $selectedDiskusi->pesan = $pesan;
        
        // Get all products for the dropdown
        $semuaBarang = Barang::all();
        $diskusi = DiskusiProduk::with(['pembeli', 'pegawai'])->orderBy('tgl_diskusi', 'desc')->get();
        
        return view('diskusi.diskusi', [
            'barangDenganDiskusi' => Barang::whereHas('diskusi')->with('diskusi')->get(),
            'barang' => $semuaBarang,
            'diskusi' => $diskusi,
            'selectedDiskusi' => $selectedDiskusi
        ]);
    }

    public function kirimPesan(Request $request)
    {
        $request->validate([
            'id_diskusi' => 'required', // Changed from id_barang to id_diskusi
            'pesan' => 'required|string',
        ]);

        $user = Session::get('user');

        if (!$user) {
            return back()->with('error', 'Anda harus login untuk mengirim pesan.');
        }

        // Get the selected discussion to get the id_barang
        $diskusi = DiskusiProduk::findOrFail($request->id_diskusi);
        $id_barang = $diskusi->id_barang;

        // Menentukan id_pembeli atau id_pegawai dan role
        $id_pembeli = null;
        $id_pegawai = null;
        $tipe_sender = '';

        if (isset($user->id_pembeli)) {
            $id_pembeli = $user->id_pembeli;
            $tipe_sender = 'pembeli';
        } elseif (isset($user->id_pegawai) && strtolower($user->jabatan) === 'customer service') {
            $id_pegawai = $user->id_pegawai;
            $tipe_sender = 'pegawai';
        } else {
            return back()->with('error', 'Role tidak dikenali.');
        }

        // Membuat ID diskusi baru
        $lastNumber = DiskusiProduk::selectRaw('MAX(CAST(SUBSTRING(id_diskusi, 4) AS UNSIGNED)) as max_id')->value('max_id');
        $newNumber = $lastNumber ? $lastNumber + 1 : 1;
        $newId = 'DIS' . $newNumber;

        // Membuat entri diskusi baru
        DiskusiProduk::create([
            'id_diskusi'   => $newId,
            'id_barang'    => $id_barang, // Use the id_barang from the selected discussion
            'id_pembeli'   => $id_pembeli,
            'id_pegawai'   => $id_pegawai,
            'pesan'        => $request->pesan,
            'tgl_diskusi'  => now()->toDateString(),
            'tipe_sender'  => $tipe_sender
        ]);

        return redirect()->route('diskusi.show', $request->id_diskusi)->with('success', 'Pesan dikirim!');
    }

    public function diskusiPembeli()
    {
        $user = Session::get('user');

        if (!$user || !isset($user->id_pembeli)) {
            return redirect()->route('login')->with('error', 'Anda harus login sebagai pembeli terlebih dahulu.');
        }

        $diskusi = DiskusiProduk::where('id_pembeli', $user->id_pembeli)
                            ->with(['barang'])
                            ->orderBy('tgl_diskusi', 'desc')
                            ->get();
        
        return view('pembeli.diskusi', [
            'diskusi' => $diskusi
        ]);
    }   
}