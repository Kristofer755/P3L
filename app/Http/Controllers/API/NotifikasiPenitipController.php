<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NotifikasiPenitip;
use App\Models\Pengiriman;

class NotifikasiPenitipController extends Controller
{
    public function getPenitipNotif($id_penitip)
    {
        // 1) Cari pengiriman selesai untuk penitip ini,
        //    lewat relasi detail transaksi → barang → detail_trans_penitipan → transaksi_penitipan
        $pengiriman = Pengiriman::with([
                'transaksiPembelian.detailTransaksi.barang.detailTransaksiPenitipan.transaksiPenitipan'
            ])
            ->where('status_pengiriman', 'selesai')
            ->whereHas('transaksiPembelian.detailTransaksi.barang.detailTransaksiPenitipan.transaksiPenitipan', function($q) use ($id_penitip) {
                $q->where('id_penitip', $id_penitip);
            })
            ->orderByDesc('tgl_pengiriman')
            ->first();

        // 2) Jika ada, buat record notifikasi jika belum pernah dibuat
        if ($pengiriman) {
            NotifikasiPenitip::firstOrCreate(
                [
                    'id_penitip' => $id_penitip,
                    'judul'      => 'Barang Telah Sampai',
                    'pesan'      => 'Barang Anda sudah diterima oleh pembeli.',
                ],
                [
                    'is_read'    => false,
                ]
            );
        }

        // 3) Ambil notifikasi terbaru yang belum dibaca
        $notif = NotifikasiPenitip::where('id_penitip', $id_penitip)
                    ->where('is_read', false)
                    ->orderBy('created_at', 'desc')
                    ->first();

        return response()->json($notif);
    }

    public function markAsRead($id)
    {
        $notif = NotifikasiPenitip::find($id);
        if ($notif) {
            $notif->is_read = true;
            $notif->save();
        }
        return response()->json(['message' => 'Marked as read']);
    }
}