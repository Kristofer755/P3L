<?php

namespace App\Http\Controllers;

use App\Models\pegawai;
use App\Models\TransaksiPembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\NotifikasiPenitip;

class PegawaiController extends Controller
{
    public function index()
    {
        $allPegawai = Pegawai::all();
        return response()->json($allPegawai);
    }

    public function resetPassword($id)
    {
        $pegawai = Pegawai::findOrFail($id);

        $allowedRoles = ['Admin', 'Owner', 'Customer Service', 'Pegawai Gudang'];
        if (!in_array($pegawai->jabatan, $allowedRoles)) {
            return redirect()->back()->with('error', 'Role tidak diizinkan untuk reset password.');
        }

        $tanggalLahir = date('Ymd', strtotime($pegawai->tgl_lahir));

        $pegawai->password = $tanggalLahir;
        $pegawai->save();

        return redirect()->back()->with('success', 'Password berhasil direset ke tanggal lahir.');
    }

    public function dashboardAdmin()
    {
        $pegawai = session('user'); 
        return view('dashboard.admin', compact('pegawai'));
    }

    public function dashboardCS()
    {
        $pegawai = session('user'); 
        return view('dashboard.cs', compact('pegawai'));
    }

    public function dashboardOwner()
    {
        $pegawai = session('user'); 
        return view('dashboard.owner', compact('pegawai'));
    }

    public function dashboardGudang()
    {
        $pegawai = session('user'); 
        return view('dashboard.gudang', compact('pegawai'));
    }


    public function kurirHunter()
    {
        $pegawai = Pegawai::whereIn('jabatan', ['kurir', 'hunter'])->get();
        return response()->json($pegawai);  
    }

    public function CSIndex()
    {
        $transaksis = TransaksiPembelian::with(['detailTransaksi.barang','pembeli','alamat'])
                    ->whereNotNull('bukti_pembayaran')
                    ->get();

        return view('cs.validasi', compact('transaksis'));
    }

    public function approve($id)
    {
        $t = TransaksiPembelian::with('detailTransaksi.barang')->findOrFail($id);
        $t->status_pembayaran = 'Disiapkan';
        $t->save();

        // Loop semua barang di transaksi ini
        foreach ($t->detailTransaksi as $detail) {
            $barang = $detail->barang;
            
            // Pastikan relasi barang ke detail transaksi penitipan ada penitip
            if ($barang && $barang->detailTransaksiPenitipan) {
                $penitip = $barang->detailTransaksiPenitipan->penitip;

                if ($penitip) {
                    NotifikasiPenitip::create([
                        'id_penitip' => $penitip->id_penitip,
                        'judul' => 'Barang laku terjual',
                        'pesan' => 'Selamat, barang Anda sudah laku terjual. Silakan cek statusnya.',
                    ]);
                }
            }
        }

        return back()->with('alert', 'Transaksi Disiapkan dan Notifikasi dikirim.');
    }


    public function reject($id)
    {
        $t = TransaksiPembelian::with('detailTransaksi')->findOrFail($id);
        // rollback poin
        $p = $t->pembeli;
        $p->poin += $t->tukar_poin;
        $p->save();
        // rollback stok
        foreach($t->detailTransaksi as $d) {
        $b = $d->barang;
        $b->status_barang = 'tersedia';
        $b->save();
        }
        // ubah status
        $t->status_pembayaran = 'Pembayaran Dibatalkan';
        $t->save();

        return back()->with('alert', 'Transaksi Dibatalkan.');
    }


}
