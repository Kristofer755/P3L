<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NotifikasiPembeli;
use App\Models\Pengiriman;

class NotifikasiPembeliController extends Controller
{
    public function getPembeliNotif($id_pembeli)
    {
        // 1) Cari pengiriman selesai untuk pembeli ini
        $pengiriman = Pengiriman::with('transaksiPembelian')
            ->where('status_pengiriman', 'selesai')
            ->whereHas('transaksiPembelian', function($q) use ($id_pembeli) {
                $q->where('id_pembeli', $id_pembeli);
            })
            ->orderByDesc('tgl_pengiriman')
            ->first();

        // 2) Jika ada, buat notifikasi sekali saja (judul + pesan sama)
        if ($pengiriman) {
            NotifikasiPembeli::firstOrCreate(
                [
                    'id_pembeli' => $id_pembeli,
                    'judul'      => 'Barang Telah Diterima',
                ],
                [
                    'pesan'   => 'Barang telah diterima',
                    'is_read' => false,
                ]
            );
        }

        // 3) Ambil notifikasi terakhir yang belum dibaca
        $notif = NotifikasiPembeli::where('id_pembeli', $id_pembeli)
                    ->where('is_read', false)
                    ->orderBy('created_at', 'desc')
                    ->first();

        return response()->json($notif);
    }

    public function markAsRead($id)
    {
        $notif = NotifikasiPembeli::find($id);
        if ($notif) {
            $notif->is_read = true;
            $notif->save();
        }

        return response()->json(['message' => 'Marked as read']);
    }
}