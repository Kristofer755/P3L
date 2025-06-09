<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\RequestDonasi;
use App\Models\Organisasi;
use App\Models\Penitip;

class LaporanController extends Controller
{
    public function requestDonasiPdf()
    {
        $requests = \App\Models\RequestDonasi::with('organisasi')
            ->where('status_request', 'Diminta') // Filter status_request Diminta
            ->get();

        $tanggalCetak = now()->translatedFormat('d F Y');
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.request_donasi', compact('requests', 'tanggalCetak'));
        return $pdf->download('laporan-request-donasi.pdf');
    }

    public function laporanDonasiPdf()
    {
        $laporan = DB::table('barang')
        ->join('donasi', 'barang.id_donasi', '=', 'donasi.id_donasi')
        ->join('request_donasi', 'donasi.id_request_donasi', '=', 'request_donasi.id_request_donasi')
        ->join('organisasi', 'request_donasi.id_organisasi', '=', 'organisasi.id_organisasi')
        ->join('detail_transaksi_penitipan', 'barang.id_barang', '=', 'detail_transaksi_penitipan.id_barang')
        ->join('transaksi_penitipan', 'detail_transaksi_penitipan.id_detail_transaksi_penitipan', '=', 'transaksi_penitipan.id_detail_transaksi_penitipan')
        ->join('penitip', 'transaksi_penitipan.id_penitip', '=', 'penitip.id_penitip')
        ->select(
            'barang.id_barang as kode_produk',
            'barang.nama_barang as nama_produk',
            'penitip.id_penitip',
            'penitip.nama_penitip',
            'donasi.tgl_donasi',
            'organisasi.nama_organisasi as organisasi',
            'donasi.nama_penerima'
        )
        ->distinct()
        ->whereNotNull('barang.id_donasi')
        ->orderBy('donasi.tgl_donasi', 'desc')
        ->get();

        $pdf = Pdf::loadView('laporan.donasi', compact('laporan'));

        return $pdf->download('laporan_donasi_'.date('Ymd_His').'.pdf');
    }

    public function formPilihPenitip()
    {
        $penitipList = \App\Models\Penitip::all();
        $bulan = date('m');
        $tahun = date('Y');
        return view('laporan.form_pilih_penitip', compact('penitipList', 'bulan', 'tahun'));
    }

    public function laporanPenitipPdf(Request $request)
    {
        $id_penitip   = $request->input('id_penitip');
        $bulan        = $request->input('bulan');
        $tahun        = $request->input('tahun');
        $nama_penitip = \App\Models\Penitip::where('id_penitip', $id_penitip)
                                        ->value('nama_penitip');

        $laporan = DB::table('transaksi_penitipan')
                ->join('detail_transaksi_penitipan',
                    'transaksi_penitipan.id_detail_transaksi_penitipan',
                    '=',
                    'detail_transaksi_penitipan.id_detail_transaksi_penitipan')
                ->join('barang',
                    'detail_transaksi_penitipan.id_barang',
                    '=',
                    'barang.id_barang')
                ->leftJoin('detail_transaksi_pembelian',
                        'detail_transaksi_pembelian.id_barang',
                        '=',
                        'barang.id_barang')
                ->leftJoin('transaksi_pembelian',
                        'transaksi_pembelian.id_transaksi_pembelian',
                        '=',
                        'detail_transaksi_pembelian.id_transaksi_pembelian')
                ->leftJoin('komisi',
                        'komisi.id_transaksi_pembelian',
                        '=',
                        'transaksi_pembelian.id_transaksi_pembelian')
                ->where('transaksi_penitipan.id_penitip', $id_penitip)
                ->whereMonth('transaksi_penitipan.tgl_masuk', $bulan)
                ->whereYear('transaksi_penitipan.tgl_masuk', $tahun)
                ->select([
                    'barang.id_barang as kode_produk',
                    'barang.nama_barang',
                    'transaksi_penitipan.tgl_masuk',
                    'transaksi_pembelian.tgl_transaksi as tanggal_laku',
                    'detail_transaksi_penitipan.total_harga_penitipan as harga_jual_bersih',
                    DB::raw("
                        CASE
                            WHEN transaksi_pembelian.tgl_transaksi IS NOT NULL
                            AND DATEDIFF(transaksi_pembelian.tgl_transaksi, transaksi_penitipan.tgl_masuk) <= 7
                            THEN detail_transaksi_penitipan.total_harga_penitipan * 0.2 * 0.1
                            ELSE 0
                        END
                        AS bonus_penitip
                        "),
                    DB::raw("
                        detail_transaksi_penitipan.total_harga_penitipan
                        +
                        CASE
                            WHEN transaksi_pembelian.tgl_transaksi IS NOT NULL
                            AND DATEDIFF(transaksi_pembelian.tgl_transaksi, transaksi_penitipan.tgl_masuk) <= 7
                            THEN detail_transaksi_penitipan.total_harga_penitipan * 0.2 * 0.1
                            ELSE 0
                        END
                        AS pendapatan
                        "),
                ])
                ->get();

        $row = $laporan->first();
        $laporan = $row ? collect([$row]) : collect();


        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.penitip', [
            'laporan'      => $laporan,
            'id_penitip'   => $id_penitip,
            'nama_penitip' => $nama_penitip,
            'bulan'        => $bulan,
            'tahun'        => $tahun,
        ]);

        return $pdf->download("laporan_penitip_{$id_penitip}_" . date('Ym') . ".pdf");
    }


}
