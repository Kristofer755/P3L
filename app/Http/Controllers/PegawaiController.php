<?php

namespace App\Http\Controllers;

use App\Models\Penitip;
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

    // public function dashboardOwner()
    // {
    //     $pegawai = session('user'); 
    //     return view('dashboard.owner', compact('pegawai'));
    // }

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
        \Log::info("==[DEBUG]== Fungsi approve dipanggil untuk ID: $id");

        // Ambil transaksi pembelian beserta detail & barang
        $t = TransaksiPembelian::with('detailTransaksi.barang')->findOrFail($id);

        \Log::info("==[DEBUG]== Status pembayaran awal: {$t->status_pembayaran}");
        $t->status_pembayaran = 'Disiapkan';
        $t->save();
        \Log::info("==[DEBUG]== Status pembayaran diupdate ke: {$t->status_pembayaran}");

        foreach ($t->detailTransaksi as $detail) {
            \Log::info("==[DEBUG]== Loop detailTransaksi: {$detail->id_detail_transaksi_penitipan}");

            $barang = $detail->barang;
            if (!$barang) {
                \Log::info("==[DEBUG]== Barang TIDAK ADA untuk detail: {$detail->id_detail_transaksi_penitipan}");
                continue;
            }

            \Log::info("==[DEBUG]== Barang ditemukan: {$barang->id_barang}");

            // Cek relasi ke detailTransaksiPenitipan di Barang
            if (!$barang->detailTransaksiPenitipan) {
                \Log::info("==[DEBUG]== detailTransaksiPenitipan TIDAK ADA di barang: {$barang->id_barang}");
                continue;
            }

            $dtp = $barang->detailTransaksiPenitipan;
            \Log::info("==[DEBUG]== detailTransaksiPenitipan ditemukan: {$dtp->id_detail_transaksi_penitipan}");

            // Cek relasi ke penitip
            if (!$dtp->penitip) {
                \Log::info("==[DEBUG]== Penitip TIDAK ADA pada detailTransaksiPenitipan: {$dtp->id_detail_transaksi_penitipan}");
                continue;
            }

            $penitip = $dtp->penitip;
            \Log::info("==[DEBUG]== Penitip ditemukan: {$penitip->id_penitip}");

            // Coba create notifikasi
            try {
                $notif = NotifikasiPenitip::create([
                    'id_penitip' => $penitip->id_penitip,
                    'judul'      => 'Barang laku terjual',
                    'pesan'      => 'Selamat, barang Anda sudah laku terjual. Silakan cek statusnya.',
                ]);
                \Log::info("==[DEBUG]== Notifikasi berhasil dibuat. ID Notif: {$notif->id} untuk Penitip: {$penitip->id_penitip}");
            } catch (\Exception $e) {
                \Log::error("==[ERROR]== Gagal create notifikasi: " . $e->getMessage());
            }
        }

        return back()->with('alert', 'Transaksi Disiapkan dan Proses Notifikasi Sudah Dijalankan. Cek log debug untuk detail.');
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

    public function dashboardOwner()
    {
        $pegawai     = session('user');
        $penitipList = \App\Models\Penitip::all();
        $bulan       = date('m');
        $tahun       = date('Y');
        return view('dashboard.owner', compact(
            'pegawai',
            'penitipList',
            'bulan',
            'tahun'
        ));
    }
}
