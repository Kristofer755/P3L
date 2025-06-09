<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Pengiriman;

class PengirimanController extends Controller
{
    public function index(Request $request)
    {
        $idPegawai = $request->query('id_pegawai');

        $query = Pengiriman::query()
            ->where('tipe_pengiriman', 'kurir');

        if ($idPegawai) {
            $query->where('id_pegawai', $idPegawai);
        }

        $data = $query->with(['transaksiPembelian', 'pegawai'])
                      ->orderByDesc('tgl_pengiriman')
                      ->get();

        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'status_pengiriman' => ['required', Rule::in(['diproses','sedang dikirim','selesai'])],
        ]);

        $pengiriman = Pengiriman::findOrFail($id);
        $pengiriman->update(['status_pengiriman' => $data['status_pengiriman']]);

        return response()->json([
            'message'   => 'Status berhasil diperbarui',
            'pengiriman'=> $pengiriman
        ]);
    }
}
