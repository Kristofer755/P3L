<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class TransaksiPengirimanController extends Controller
{
    public function index()
    {
        $pengirimanList = DB::table('pengiriman')
            ->join('transaksi_pembelian', 'pengiriman.id_transaksi_pembelian', '=', 'transaksi_pembelian.id_transaksi_pembelian')
            ->join('pembeli', 'transaksi_pembelian.id_pembeli', '=', 'pembeli.id_pembeli')
            // ->where('transaksi_pembelian.status_pembayaran', 'sudah dibayar')
            // ->whereIn('pengiriman.status_pengiriman', ['diproses'])
            ->select(
                'transaksi_pembelian.id_transaksi_pembelian',
                'transaksi_pembelian.tgl_transaksi',
                'pembeli.nama_pembeli',
                'pengiriman.status_pengiriman',
                'pengiriman.tipe_pengiriman'
            )
            ->orderByDesc('transaksi_pembelian.tgl_transaksi')
            ->get();

        $kurirList = DB::table('pegawai')->where('jabatan', 'Kurir')->get();

        return view('gudang.pengiriman.index', compact('pengirimanList', 'kurirList'));
    }

    public function show($id)
    {
        $transaksi = DB::table('transaksi_pembelian')->where('id_transaksi_pembelian', $id)->first();
        $pembeli = DB::table('pembeli')->where('id_pembeli', $transaksi->id_pembeli)->first();
        $pengiriman = DB::table('pengiriman')->where('id_transaksi_pembelian', $id)->first();

        $barangList = DB::table('detail_transaksi_pembelian')
            ->join('barang', 'detail_transaksi_pembelian.id_barang', '=', 'barang.id_barang')
            ->leftJoin('gambar_barang', 'barang.id_barang', '=', 'gambar_barang.id_barang')
            ->whereRaw('BINARY detail_transaksi_pembelian.id_transaksi_pembelian = BINARY ?', [$id])
            ->select('barang.*', 'gambar_barang.path_gambar', 'detail_transaksi_pembelian.jml_barang_pembelian', 'detail_transaksi_pembelian.harga_satuan_pembelian')
            ->get();

        $kurirList = DB::table('pegawai')->where('jabatan', 'Kurir')->get();

        return view('gudang.pengiriman.show', compact('transaksi', 'pembeli', 'pengiriman', 'barangList', 'kurirList'));
    }

    // public function jadwalkan(Request $request, $id)
    // {
    //     $pengiriman = DB::table('pengiriman')->where('id_transaksi_pembelian', $id)->first();

    //     // Dapatkan id_pembeli dari transaksi
    //     $transaksi = DB::table('transaksi_pembelian')->where('id_transaksi_pembelian', $id)->first();

    //     $tglNotif = '';
    //     if ($pengiriman->tipe_pengiriman == 'kurir') {
    //         $now = Carbon::now();
    //         $tglPengiriman = ($now->hour < 16) ? $now->toDateString() : $now->addDay()->toDateString();

    //         DB::table('pengiriman')
    //             ->where('id_transaksi_pembelian', $id)
    //             ->update([
    //                 'id_pegawai' => $request->id_kurir,
    //                 'tgl_pengiriman' => $tglPengiriman
    //             ]);
    //         $tglNotif = $tglPengiriman;
    //     } else {
    //         DB::table('pengiriman')
    //             ->where('id_transaksi_pembelian', $id)
    //             ->update([
    //                 'tgl_pengiriman' => $request->jadwal
    //             ]);
    //         $tglNotif = $request->jadwal;
    //     }

    //     // --- Tambahkan notifikasi pembeli ---
    //     if ($transaksi) {
    //         DB::table('notifikasi_pembeli')->insert([
    //             'id_pembeli' => $transaksi->id_pembeli,
    //             'judul'      => 'Jadwal Pengiriman/Pengambilan Ditetapkan',
    //             'pesan'      => 'Jadwal pengiriman/pengambilan barang Anda telah ditetapkan pada ' . $tglNotif,
    //             'is_read'    => false,
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ]);
    //     }
    //     // --- end notifikasi ---

    //     return redirect()->back()->with('success', 'Jadwal berhasil disimpan.');
    // }

    // public function jadwalkan(Request $request, $id)
    // {
    //     \Log::info("==[DEBUG]== Fungsi jadwalkan dipanggil untuk transaksi_pembelian ID: $id");

    //     // Ambil data pengiriman
    //     $pengiriman = DB::table('pengiriman')->where('id_transaksi_pembelian', $id)->first();
    //     if (!$pengiriman) {
    //         \Log::error("==[ERROR]== Data pengiriman TIDAK DITEMUKAN untuk id_transaksi_pembelian: $id");
    //         return redirect()->back()->with('error', 'Pengiriman tidak ditemukan.');
    //     }
    //     \Log::info("==[DEBUG]== Data pengiriman ditemukan. Tipe: {$pengiriman->tipe_pengiriman}");

    //     // Ambil data transaksi
    //     $transaksi = DB::table('transaksi_pembelian')->where('id_transaksi_pembelian', $id)->first();
    //     if (!$transaksi) {
    //         \Log::error("==[ERROR]== Data transaksi_pembelian TIDAK DITEMUKAN untuk id: $id");
    //         return redirect()->back()->with('error', 'Transaksi pembelian tidak ditemukan.');
    //     }
    //     \Log::info("==[DEBUG]== Data transaksi_pembelian ditemukan. ID Pembeli: {$transaksi->id_pembeli}");

    //     $tglNotif = '';
    //     if ($pengiriman->tipe_pengiriman == 'kurir') {
    //         $now = \Carbon\Carbon::now();
    //         $tglPengiriman = ($now->hour < 16) ? $now->toDateString() : $now->addDay()->toDateString();

    //         $affected = DB::table('pengiriman')
    //             ->where('id_transaksi_pembelian', $id)
    //             ->update([
    //                 'id_pegawai' => $request->id_kurir,
    //                 'tgl_pengiriman' => $tglPengiriman
    //             ]);
    //         \Log::info("==[DEBUG]== Pengiriman diupdate untuk tipe 'kurir'. Baris terpengaruh: $affected. Tanggal pengiriman: $tglPengiriman");
    //         $tglNotif = $tglPengiriman;
    //     } else {
    //         $affected = DB::table('pengiriman')
    //             ->where('id_transaksi_pembelian', $id)
    //             ->update([
    //                 'tgl_pengiriman' => $request->jadwal
    //             ]);
    //         \Log::info("==[DEBUG]== Pengiriman diupdate untuk tipe 'ambil'. Baris terpengaruh: $affected. Tanggal pengiriman: {$request->jadwal}");
    //         $tglNotif = $request->jadwal;
    //     }

    //     // Tambahkan notifikasi pembeli
    //     if ($transaksi) {
    //         try {
    //             $notifId = DB::table('notifikasi_pembeli')->insertGetId([
    //                 'id_pembeli' => $transaksi->id_pembeli,
    //                 'judul'      => 'Jadwal Pengiriman/Pengambilan Ditetapkan',
    //                 'pesan'      => 'Jadwal pengiriman/pengambilan barang Anda telah ditetapkan pada ' . $tglNotif,
    //                 'is_read'    => false,
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ]);
    //             \Log::info("==[DEBUG]== Notifikasi pembeli berhasil dibuat. ID Notif: $notifId, ID Pembeli: {$transaksi->id_pembeli}");
    //         } catch (\Exception $e) {
    //             \Log::error("==[ERROR]== Gagal membuat notifikasi pembeli: " . $e->getMessage());
    //         }
    //     } else {
    //         \Log::error("==[ERROR]== Tidak ada data transaksi untuk create notifikasi.");
    //     }

    //     return redirect()->back()->with('success', 'Jadwal berhasil disimpan. Lihat log untuk debug.');
    // }

    public function jadwalkan(Request $request, $id)
{
    \Log::info("==[DEBUG]== Fungsi jadwalkan dipanggil untuk transaksi_pembelian ID: $id");

    // Ambil data pengiriman
    $pengiriman = DB::table('pengiriman')->where('id_transaksi_pembelian', $id)->first();
    if (!$pengiriman) {
        \Log::error("==[ERROR]== Data pengiriman TIDAK DITEMUKAN untuk id_transaksi_pembelian: $id");
        return redirect()->back()->with('error', 'Pengiriman tidak ditemukan.');
    }
    \Log::info("==[DEBUG]== Data pengiriman ditemukan. Tipe: {$pengiriman->tipe_pengiriman}");

    // Ambil data transaksi
    $transaksi = DB::table('transaksi_pembelian')->where('id_transaksi_pembelian', $id)->first();
    if (!$transaksi) {
        \Log::error("==[ERROR]== Data transaksi_pembelian TIDAK DITEMUKAN untuk id: $id");
        return redirect()->back()->with('error', 'Transaksi pembelian tidak ditemukan.');
    }
    \Log::info("==[DEBUG]== Data transaksi_pembelian ditemukan. ID Pembeli: {$transaksi->id_pembeli}");

    $tglNotif = '';
    if ($pengiriman->tipe_pengiriman == 'kurir') {
        $now = \Carbon\Carbon::now();
        $tglPengiriman = ($now->hour < 16) ? $now->toDateString() : $now->addDay()->toDateString();

        $affected = DB::table('pengiriman')
            ->where('id_transaksi_pembelian', $id)
            ->update([
                'id_pegawai' => $request->id_kurir,
                'tgl_pengiriman' => $tglPengiriman
            ]);
        \Log::info("==[DEBUG]== Pengiriman diupdate untuk tipe 'kurir'. Baris terpengaruh: $affected. Tanggal pengiriman: $tglPengiriman");
        $tglNotif = $tglPengiriman;
    } else {
        $affected = DB::table('pengiriman')
            ->where('id_transaksi_pembelian', $id)
            ->update([
                'tgl_pengiriman' => $request->jadwal
            ]);
        \Log::info("==[DEBUG]== Pengiriman diupdate untuk tipe 'ambil'. Baris terpengaruh: $affected. Tanggal pengiriman: {$request->jadwal}");
        $tglNotif = $request->jadwal;
    }

    // Tambahkan notifikasi pembeli
    if ($transaksi) {
        try {
            $notifId = DB::table('notifikasi_pembeli')->insertGetId([
                'id_pembeli' => $transaksi->id_pembeli,
                'judul'      => 'Jadwal Pengiriman/Pengambilan Ditetapkan',
                'pesan'      => 'Jadwal pengiriman/pengambilan barang Anda telah ditetapkan pada ' . $tglNotif,
                'is_read'    => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            \Log::info("==[DEBUG]== Notifikasi pembeli berhasil dibuat. ID Notif: $notifId, ID Pembeli: {$transaksi->id_pembeli}");
        } catch (\Exception $e) {
            \Log::error("==[ERROR]== Gagal membuat notifikasi pembeli: " . $e->getMessage());
        }
    } else {
        \Log::error("==[ERROR]== Tidak ada data transaksi untuk create notifikasi.");
    }

    // === Tambahkan notifikasi untuk semua penitip barang di transaksi ini ===
    try {
        $id_penitips = DB::table('transaksi_penitipan as tp')
            ->join('detail_transaksi_penitipan as dtpen', 'tp.id_detail_transaksi_penitipan', '=', 'dtpen.id_detail_transaksi_penitipan')
            ->join('detail_transaksi_pembelian as dtbeli', 'dtpen.id_barang', '=', 'dtbeli.id_barang')
            ->where('dtbeli.id_transaksi_pembelian', $id)
            ->groupBy('tp.id_penitip')
            ->pluck('tp.id_penitip');

        foreach ($id_penitips as $id_penitip) {
            DB::table('notifikasi_penitip')->insert([
                'id_penitip' => $id_penitip,
                'judul'      => 'Jadwal Pengiriman/Pengambilan Ditetapkan',
                'pesan'      => 'Jadwal pengiriman/pengambilan barang titipan Anda telah ditetapkan pada ' . $tglNotif,
                'is_read'    => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            \Log::info("==[DEBUG]== Notifikasi penitip berhasil dibuat untuk penitip $id_penitip pada transaksi $id");
        }
    } catch (\Exception $e) {
        \Log::error("==[ERROR]== Gagal membuat notifikasi penitip: " . $e->getMessage());
    }

    return redirect()->back()->with('success', 'Jadwal berhasil disimpan. Lihat log untuk debug.');
}


    public function cetakNota($id)
    {
        $transaksi = DB::table('transaksi_pembelian')->where('id_transaksi_pembelian', $id)->first();
        $pembeli = DB::table('pembeli')->where('id_pembeli', $transaksi->id_pembeli)->first();
        $alamat = DB::table('alamat')->where('id_alamat', $transaksi->id_alamat)->first();
        $pengiriman = DB::table('pengiriman')->where('id_transaksi_pembelian', $id)->first();

        $kurir = null;
        if ($pengiriman->tipe_pengiriman == 'diantar' && $pengiriman->id_pegawai) {
            $kurir = DB::table('pegawai')->where('id_pegawai', $pengiriman->id_pegawai)->first();
        }

        $barangList = DB::table('detail_transaksi_pembelian')
            ->join('barang', 'detail_transaksi_pembelian.id_barang', '=', 'barang.id_barang')
            ->where('detail_transaksi_pembelian.id_transaksi_pembelian', $id)
            ->select('barang.nama_barang', 'detail_transaksi_pembelian.jml_barang_pembelian', 'detail_transaksi_pembelian.harga_satuan_pembelian')
            ->get();

        $qc = DB::table('pegawai')->where('jabatan', 'QC')->inRandomOrder()->first();

        $pdf = Pdf::loadView('gudang.pengiriman.nota', compact('transaksi', 'pembeli', 'alamat', 'pengiriman', 'kurir', 'barangList', 'qc'));

        return $pdf->download('nota_penjualan_' . $id . '.pdf');
    }


    public function assignKurir(Request $request, $id)
    {
        $request->validate([
            'id_kurir' => 'required|exists:pegawai,id_pegawai',
        ]);

        DB::table('pengiriman')
            ->where('id_transaksi_pembelian', $id)
            ->update(['id_pegawai' => $request->id_kurir]);

        return redirect()->back()->with('success', 'Kurir berhasil ditugaskan.');
    }
}
