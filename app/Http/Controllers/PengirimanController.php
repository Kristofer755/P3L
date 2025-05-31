<?php

namespace App\Http\Controllers;

use App\Models\Pengiriman;
use App\Models\TransaksiPembelian;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PengirimanController extends Controller
{
    /**
     * Tampilkan daftar semua pengiriman.
     */
    public function index()
    {
        // eager‐load relasi ke transaksi dan pegawai
        $pengirimans = Pengiriman::with(['transaksiPembelian', 'pegawai'])->get();

        return view('pengiriman.index', compact('pengirimans'));
    }

    /**
     * Tampilkan form pembuatan pengiriman baru.
     */
    public function create()
    {
        $transaksis = TransaksiPembelian::all();

        // daftar ID pegawai yang boleh dipakai untuk kurir
        $kurirIds  = ['PEG15','PEG2','PEG5'];
        // daftar ID pegawai gudang (tipe “ambil”)
        $gudangIds = ['PEG12','PEG16','PEG20','PEG3'];

        $kurirs  = Pegawai::whereIn('id_pegawai', $kurirIds)->get();
        $gudangs = Pegawai::whereIn('id_pegawai', $gudangIds)->get();

        return view('pengiriman.create', compact('transaksis','kurirs','gudangs'));
    }

    /**
     * Simpan pengiriman baru ke database.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_pegawai'              => ['required', Rule::exists('pegawais','id_pegawai')],
            'id_transaksi_pembelian'  => ['required', Rule::exists('transaksi_pembelian','id_transaksi_pembelian')],
            'tgl_pengiriman'          => 'required|date',
            'status_pengiriman'       => 'required|in:diproses,sedang dikirim,selesai',
            'tipe_pengiriman'         => 'required|in:kurir,ambil',
        ]);

        // 1) generate ID pengiriman di sini
        $lastNumber = Pengiriman::selectRaw("
                MAX(CAST(SUBSTRING(id_pengiriman, 4) AS UNSIGNED)) as max_id
            ")
            ->value('max_id');
        $newNumber   = $lastNumber ? $lastNumber + 1 : 1;
        $data['id_pengiriman'] = 'PGR' . $newNumber;

        Pengiriman::create($data);

        return redirect()
            ->route('pengiriman.index')
            ->with('success', 'Pengiriman berhasil dibuat.');
    }

    /**
     * Tampilkan detail satu pengiriman.
     */
    public function show($id)
    {
        $pengiriman = Pengiriman::with(['transaksiPembelian', 'pegawai'])
                                ->findOrFail($id);

        return view('pengiriman.show', compact('pengiriman'));
    }

    /**
     * Tampilkan form edit untuk satu pengiriman.
     */
    public function edit($id)
    {
        $pengiriman = Pengiriman::findOrFail($id);
        $transaksis = TransaksiPembelian::all();

        $kurirIds  = ['PEG15','PEG2','PEG5'];
        $gudangIds = ['PEG12','PEG16','PEG20','PEG3'];

        $kurirs  = Pegawai::whereIn('id_pegawai', $kurirIds)->get();
        $gudangs = Pegawai::whereIn('id_pegawai', $gudangIds)->get();

        return view('pengiriman.edit', compact('pengiriman','transaksis','kurirs','gudangs'));
    }

    /**
     * Proses update data pengiriman.
     */
    public function update(Request $request, $id)
    {
        $pengiriman = Pengiriman::findOrFail($id);

        $rules = [
            'tipe_pengiriman'        => 'required|in:kurir,ambil',
            'id_transaksi_pembelian' => ['required', Rule::exists('transaksi_pembelian','id_transaksi_pembelian')],
            'tgl_pengiriman'         => 'required|date',
            'status_pengiriman'      => 'required|in:diproses,sedang dikirim,selesai',
        ];

        $data = $request->validate($rules);

        $kurirIds  = ['PEG15','PEG2','PEG5'];
        $gudangIds = ['PEG12','PEG16','PEG20','PEG3'];
        $allowed   = $request->tipe_pengiriman === 'kurir' ? $kurirIds : $gudangIds;

        $request->validate([
            'id_pegawai' => ['required', Rule::in($allowed)],
        ]);

        $data['id_pegawai'] = $request->id_pegawai;

        $pengiriman->update($data);

        return redirect()
            ->route('pengiriman.index')
            ->with('success', 'Data pengiriman berhasil diperbarui.');
    }

    /**
     * Hapus satu pengiriman.
     */
    public function destroy($id)
    {
        Pengiriman::destroy($id);

        return redirect()
            ->route('pengiriman.index')
            ->with('success', 'Pengiriman berhasil dihapus.');
    }
}
