<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\Barang;
use App\Models\Alamat;
use App\Models\TransaksiPembelian;
use App\Models\DetailTransaksiPembelian;
use App\Models\Pengiriman;
use App\Models\Pembeli;

class TransaksiController extends Controller
{
    /**
     * Tampilkan daftar transaksi milik pembeli yang sedang login
     */
    public function index()
    {
        $user = Session::get('user');

        // Ambil semua transaksi milik user, beserta detail barang
        $transaksis = TransaksiPembelian::with(['detailTransaksi.barang'])
            ->where('id_pembeli', $user->id_pembeli)
            ->orderBy('tgl_transaksi', 'desc')
            ->get();

        return view('pembeli.transaksi.index', compact('transaksis'));
    }

    /**
     * Tampilkan detail satu transaksi (header + line items)
     */
    public function detail($id)
    {
        $user = Session::get('user');

        // Pastikan hanya bisa akses transaksi milik sendiri
        $transaksi = TransaksiPembelian::with(['detailTransaksi.barang'])
            ->where('id_pembeli', $user->id_pembeli)
            ->where('id_transaksi_pembelian', $id)
            ->firstOrFail();

        return view('pembeli.transaksi.detail', compact('transaksi'));
    }

    public function beliSekarang($id_barang)
    {
        $user = Session::get('user');
        $barang = Barang::findOrFail($id_barang);
        $alamatList = Alamat::where('id_pembeli', $user->id_pembeli)->get();
        $alamatYogyaList    = Alamat::where('id_pembeli', $user->id_pembeli)
                                ->where('kota', 'Yogyakarta')
                                ->get();
        return view('pembeli.transaksi.beliSekarang', compact('barang', 'alamatList', 'alamatYogyaList'));
    }

    public function prosesPembelian(Request $request)
    {
        $user = Session::get('user');
        $pembeli = Pembeli::findOrFail($user->id_pembeli);

        // validasi, termasuk id_alamat
        $request->validate([
            'id_barang'         => 'required|exists:barang,id_barang',
            'jumlah'            => 'required|integer|min:1',
            'metode_pengiriman' => 'required|in:kurir,ambil',
            'id_alamat'         => [
                'required_if:metode_pengiriman,kurir',
                Rule::exists('alamat','id_alamat')
                    ->where('id_pembeli', $user->id_pembeli)
                    ->where('kota', 'Yogyakarta'),
            ],
            'tukar_poin' => [
                'nullable',
                'integer',
                'min:0',
                function ($attribute, $value, $fail) use ($pembeli) {
                    if ($value > $pembeli->poin) {
                        $fail('Poin yang ditukar melebihi saldo poin Anda.');
                    }
                    if ($value % 100 !== 0) {
                        $fail('Penukaran poin harus kelipatan 100.');
                    }
                }
            ],
            'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000',
        ]);

        $barang   = Barang::findOrFail($request->id_barang);
        $idAlamat = $request->metode_pengiriman === 'kurir'
                ? $request->id_alamat
                : null;
        $qty      = $request->jumlah;
        $unitHarga = $barang->harga_barang;
        $subtotal = $qty * $unitHarga;
        $tukarPoin = $request->tukar_poin ?? 0;
        $diskon = intval($tukarPoin / 100) * 10000;

        if ($request->metode_pengiriman === 'kurir') {
            $ongkir = $subtotal < 1500000 ? 100000 : 0;
        } else {
            $ongkir = 0;
        }
        
        $totalBayar = max(0, $subtotal + $ongkir - $diskon);

        // generate ID transaksi
        $lastNumber  = TransaksiPembelian::selectRaw(
            'MAX(CAST(SUBSTRING(id_transaksi_pembelian,5) AS UNSIGNED)) as max_id'
        )->value('max_id');
        $newNumber   = $lastNumber ? $lastNumber + 1 : 1;
        $idTransaksi = 'TPEM'.$newNumber;

        $transaksi = TransaksiPembelian::create([
            'id_transaksi_pembelian' => $idTransaksi,
            'id_pembeli'             => $user->id_pembeli,
            'id_alamat'              => $idAlamat,             
            'tgl_transaksi'          => now(),
            'total_pembayaran'       => $totalBayar,
            'status_pembayaran'      => 'belum dibayar',
            'tukar_poin'             => $tukarPoin,
        ]);

        // Generate no_transaksi
        $tahun = date('y', strtotime($transaksi->tgl_transaksi)); // dua digit tahun
        $bulan = date('m', strtotime($transaksi->tgl_transaksi)); // dua digit bulan

        // Ambil angka urut dari id_transaksi_pembelian (misal: TPEM20 → 20)
        preg_match('/(\d+)$/', $transaksi->id_transaksi_pembelian, $match);
        $angkaUrut = $match[1] ?? '0';



        $lastNumber = DB::table('detail_transaksi_pembelian')
            ->selectRaw("MAX(CAST(SUBSTRING(id_detail_transaksi_pembelian,5) AS UNSIGNED)) as max_id")
            ->value('max_id');
        $newNumber = $lastNumber ? $lastNumber + 1 : 1;
        $idDetail  = 'DPEM' . $newNumber;

        $detail = DetailTransaksiPembelian::create([
            'id_detail_transaksi_pembelian' => $idDetail,
            'id_transaksi_pembelian'        => $idTransaksi,
            'id_barang'                     => $request->id_barang,
            'jml_barang_pembelian'          => $request->jumlah,
            'harga_satuan_pembelian'        => $barang->harga_barang,
            'total_harga_pembelian'         => $totalBayar,
        ]);

        $barang = Barang::find($detail->id_barang);
        if ($barang) {
            $barang->status_barang = 'sold out';
            $barang->save();
        }

        $lastNumber = DB::table('pengiriman')
            ->selectRaw("MAX(CAST(SUBSTRING(id_pengiriman,4) AS UNSIGNED)) as max_id")
            ->value('max_id');
        $newNumber   = $lastNumber ? $lastNumber + 1 : 1;
        $idPengiriman = 'PGR' . $newNumber;

        $kurirIds  = ['PEG15','PEG2','PEG5'];
        $gudangIds = ['PEG12','PEG16','PEG20','PEG3'];

        if ($request->metode_pengiriman === 'kurir') {
            $allowed = $kurirIds;
            $tipe    = 'kurir';
        } else {
            $allowed = $gudangIds;
            $tipe    = 'ambil';
        }

        $idPegawai = $allowed[array_rand($allowed)];

        Pengiriman::create([
            'id_pengiriman'          => $idPengiriman,
            'id_transaksi_pembelian' => $idTransaksi,
            'id_pegawai'             => $idPegawai,
            'tgl_pengiriman'         => now(),
            'status_pengiriman'      => 'diproses',
            'tipe_pengiriman'        => $tipe,
        ]);

        $pembeli->poin -= $tukarPoin;
        $pembeli->save();
        Session::put('user', $pembeli);

        $transaksi->no_transaksi = "$tahun.$bulan.$angkaUrut";
        $transaksi->bukti_pembayaran = null;
        $transaksi->status_pembayaran = 'Belum Dibayar';
        $transaksi->save();

        return redirect()
            ->route('transaksi.bukti', ['id' => $idTransaksi])
            ->with('alert', 'Silakan upload bukti pembayaran!');
    }

    public function cancelBukti($id)
    {
        $transaksi = TransaksiPembelian::with('detailTransaksi','pembeli')->findOrFail($id);

        // hanya batalkan jika belum upload bukti
        if ($transaksi->status_pembayaran !== 'Belum Dibayar') {
            return redirect()->route('dashboard.pembeli')
                            ->with('alert', 'Transaksi sudah diproses.');
        }

        // --- rollback poin ---
        $pembeli = $transaksi->pembeli;
        $poinDipakai = $transaksi->tukar_poin ?? 0;      // pastikan kolom tukar_poin ada
        $pembeli->poin += $poinDipakai;
        $pembeli->save();
        Session::put('user', $pembeli);

        // --- rollback status barang ---
        foreach ($transaksi->detailTransaksi as $det) {
            $barang = $det->barang;
            $barang->status_barang = 'tersedia';        // atau nilai default-mu
            $barang->save();
        }

        // --- set transaksi jadi batal ---
        $transaksi->status_pembayaran = 'Pembayaran Dibatalkan';
        $transaksi->save();

        return redirect()->route('dashboard.pembeli')
                        ->with('alert', 'Waktu habis—Transaksi dibatalkan dan poin dikembalikan.');
    }


    public function formBukti($id)
    {
        $transaksi = TransaksiPembelian::findOrFail($id);
        return view('pembeli.transaksi.bukti', compact('transaksi'));
    }

    public function uploadBukti(Request $request, $id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);

        $transaksi = TransaksiPembelian::findOrFail($id);
        $pembeli    = Pembeli::findOrFail($transaksi->id_pembeli);

        $file = $request->file('bukti_pembayaran');
        $fileName = 'bukti_' . $id . '.' . $file->getClientOriginalExtension();
        $buktiPembayaranPath = $file->storeAs('bukti_pembayaran', $fileName, 'public');

        $transaksi->bukti_pembayaran = $buktiPembayaranPath;
        $transaksi->status_pembayaran = 'Sudah Dibayar';
        $transaksi->save();

        $subtotal = $transaksi->detailTransaksi->sum('total_harga_pembelian');

        $reward = floor($subtotal / 10000);
        if ($subtotal > 500000) {
            $reward += floor($reward * 0.2);
        }

        $pembeli->poin += $reward;
        $pembeli->save();
        Session::put('user', $pembeli);

        return redirect()
            ->route('dashboard.pembeli')
            ->with('alert', "Bukti berhasil diupload! Anda mendapat $reward poin.");
        }

    public function checkoutKeranjang()
    {
        $user = Session::get('user');
        $keranjang = Session::get('keranjang', []);
        // load data barang untuk menampilkan nama & harga
        $barangList = Barang::whereIn('id_barang', array_keys($keranjang))->get()
                          ->keyBy('id_barang');
        // alamat Yogyakarta untuk opsi kurir
        $alamatYogyaList = Alamat::where('id_pembeli', $user->id_pembeli)
                                 ->where('kota','Yogyakarta')
                                 ->get();

        return view('pembeli.transaksi.checkout', compact(
          'keranjang','barangList','alamatYogyaList'
        ));
    }

    public function prosesKeranjang(Request $request)
    {
        $user      = Session::get('user');
        $pembeli   = Pembeli::findOrFail($user->id_pembeli);
        $keranjang = Session::get('keranjang', []);

        if (empty($keranjang)) {
            return redirect()->route('keranjang.index')
                            ->with('error', 'Keranjang kosong.');
        }

        // Validasi input (metode pengiriman & alamat, dan tukar poin)
        $request->validate([
            'metode_pengiriman' => 'required|in:kurir,ambil',
            'alamat_pengiriman' => [
                'required_if:metode_pengiriman,kurir',
                Rule::exists('alamat','id_alamat')
                    ->where('id_pembeli', $user->id_pembeli)
                    ->where('kota','Yogyakarta'),
            ],
            'tukar_poin' => [
                'nullable',
                'integer',
                'min:0',
                function ($attribute, $value, $fail) use ($pembeli) {
                    if ($value > $pembeli->poin) {
                        $fail('Poin yang ditukar melebihi saldo poin Anda.');
                    }
                    if ($value % 100 !== 0) {
                        $fail('Penukaran poin harus kelipatan 100.');
                    }
                }
            ]
        ]);

        $idAlamat = $request->metode_pengiriman === 'kurir'
                ? $request->alamat_pengiriman
                : null;
        $tukarPoin = $request->tukar_poin ?? 0;
        $diskon = intval($tukarPoin / 100) * 10000;

        // 1. Hitung subtotal semua barang
        $subtotal = 0;
        foreach ($keranjang as $barangId => $item) {
            $subtotal += $item['harga'] * $item['jumlah'];
        }

        // 2. Hitung ongkir (berlaku untuk subtotal semua barang)
        $ongkir = ($request->metode_pengiriman === 'kurir' && $subtotal < 1500000) ? 100000 : 0;

        // 3. Hitung total bayar setelah diskon
        $totalBayar = max(0, $subtotal + $ongkir - $diskon);

        // 4. Generate ID transaksi
        $lastNumber  = TransaksiPembelian::selectRaw(
            'MAX(CAST(SUBSTRING(id_transaksi_pembelian,5) AS UNSIGNED)) as max_id'
        )->value('max_id');
        $newNumber   = $lastNumber ? $lastNumber + 1 : 1;
        $idTransaksi = 'TPEM'.$newNumber;

        // 5. Simpan transaksi (header)
        $transaksi = TransaksiPembelian::create([
            'id_transaksi_pembelian' => $idTransaksi,
            'id_pembeli'             => $user->id_pembeli,
            'id_alamat'              => $idAlamat,
            'tgl_transaksi'          => now(),
            'total_pembayaran'       => $totalBayar,
            'status_pembayaran'      => 'belum dibayar',
            'tukar_poin'             => $tukarPoin,
            // 'no_transaksi' akan diisi setelah dapat id
        ]);

        // 6. Generate no_transaksi
        $tahun = date('y', strtotime($transaksi->tgl_transaksi)); // dua digit tahun
        $bulan = date('m', strtotime($transaksi->tgl_transaksi)); // dua digit bulan
        preg_match('/(\d+)$/', $transaksi->id_transaksi_pembelian, $match);
        $angkaUrut = $match[1] ?? '0';
        $transaksi->no_transaksi = "$tahun.$bulan.$angkaUrut";
        $transaksi->save();

        // 7. Simpan detail transaksi untuk tiap item di keranjang
        $lastDetail = DB::table('detail_transaksi_pembelian')
            ->selectRaw("MAX(CAST(SUBSTRING(id_detail_transaksi_pembelian,5) AS UNSIGNED)) as max_id")
            ->value('max_id');
        $counter = $lastDetail ?: 0;

        foreach ($keranjang as $barangId => $item) {
            $counter++;
            $idDetail = 'DPEM' . $counter;

            DetailTransaksiPembelian::create([
                'id_detail_transaksi_pembelian' => $idDetail,
                'id_transaksi_pembelian'        => $idTransaksi,
                'id_barang'                     => $barangId,
                'jml_barang_pembelian'          => $item['jumlah'],
                'harga_satuan_pembelian'        => $item['harga'],
                'total_harga_pembelian'         => $item['jumlah'] * $item['harga'],
            ]);

            // Update status barang
            $barang = Barang::find($barangId);
            if ($barang) {
                $barang->status_barang = 'sold out';
                $barang->save();
            }
        }

        // 8. Generate ID Pengiriman
        $lastShip = Pengiriman::selectRaw(
            'MAX(CAST(SUBSTRING(id_pengiriman,4) AS UNSIGNED)) as max_id'
        )->value('max_id');
        $nextShip    = $lastShip ? $lastShip + 1 : 1;
        $idPengiriman = 'PGR' . $nextShip;

        $kurirIds  = ['PEG15','PEG2','PEG5'];
        $gudangIds = ['PEG12','PEG16','PEG20','PEG3'];

        $pool       = $request->metode_pengiriman === 'kurir'
                    ? $kurirIds
                    : $gudangIds;
        $idPegawai  = $pool[array_rand($pool)];

        Pengiriman::create([
            'id_pengiriman'          => $idPengiriman,
            'id_transaksi_pembelian' => $idTransaksi,
            'id_pegawai'             => $idPegawai,
            'tgl_pengiriman'         => now(),
            'status_pengiriman'      => 'diproses',
            'tipe_pengiriman'        => $request->metode_pengiriman,
        ]);

        // 9. Kurangi poin pembeli jika ada penukaran poin
        $pembeli->poin -= $tukarPoin;
        $pembeli->save();
        Session::put('user', $pembeli);

        // 10. Bersihkan keranjang
        Session::forget('keranjang');

        // 11. Redirect ke form upload bukti pembayaran
        return redirect()
            ->route('transaksi.bukti', ['id' => $idTransaksi])
            ->with('alert', 'Silakan upload bukti pembayaran!');
    }


}
