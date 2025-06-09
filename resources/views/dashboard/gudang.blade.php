<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Pegawai Gudang</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 py-6 px-4">

    <div class="max-w-5xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-4">Selamat Datang di Dashboard Pegawai Gudang</h1>

        <!-- Tombol Reset Password -->
        <div class="mb-6">
            <a href="{{ route('pegawai.resetPassword', $pegawai->id_pegawai) }}"
                onclick="return confirm('Yakin reset password ke tanggal lahir?')"
                class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                Reset Password
            </a>
        </div>

        <!-- Daftar Barang Dikonfirmasi -->
        <h2 class="text-xl font-semibold mb-4">Daftar Barang Dikonfirmasi untuk Diambil</h2>

        @php
            $data = \Illuminate\Support\Facades\DB::table('transaksi_penitipan')
                ->join('detail_transaksi_penitipan', 'transaksi_penitipan.id_detail_transaksi_penitipan', '=', 'detail_transaksi_penitipan.id_detail_transaksi_penitipan')
                ->join('barang', 'detail_transaksi_penitipan.id_barang', '=', 'barang.id_barang')
                ->where('transaksi_penitipan.status_pengambilan', 'dikonfirmasi')
                ->select('transaksi_penitipan.*', 'barang.nama_barang', 'barang.gambar_barang')
                ->get();

            $penitipList = \Illuminate\Support\Facades\DB::table('penitip')->get();
            $kategoriList = \Illuminate\Support\Facades\DB::table('kategori')->get();
        @endphp

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @forelse ($data as $item)
            <div class="border rounded p-4 mb-4 shadow-sm bg-white flex items-center gap-4">
                <img src="{{ asset('storage/' . $item->gambar_barang) }}" width="100">
                <div>
                    <h3 class="text-lg font-semibold">{{ $item->nama_barang }}</h3>
                    <p class="text-gray-700">Tgl Akhir: {{ $item->tgl_akhir }}</p>
                    <p class="text-gray-700">Status Penitipan: {{ $item->status_penitipan }}</p>
                    <p class="text-gray-700">Status Pengambilan: {{ $item->status_pengambilan }}</p>
                    <form action="{{ url('/gudang/pengambilan/selesai/' . $item->id_transaksi_penitipan) }}" method="POST"
                          onsubmit="return confirm('Konfirmasi bahwa barang ini sudah diambil kembali?')">
                        @csrf
                        <button type="submit" class="mt-2 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            Barang Telah Diambil
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-gray-600">Tidak ada barang yang perlu dikonfirmasi pengambilannya.</p>
        @endforelse

        <!-- Form Tambah Barang -->
        <h2 class="text-xl font-semibold mb-4 mt-10">Form Tambah Barang Titipan</h2>

        <form action="{{ url('/gudang/tambah-barang') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block mb-1">Pilih Penitip</label>
                <select name="id_penitip" class="w-full border border-gray-300 rounded px-3 py-2" required>
                    @foreach($penitipList as $penitip)
                        <option value="{{ $penitip->id_penitip }}">{{ $penitip->nama_penitip }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-1">Pilih Kategori</label>
                <select name="id_kategori" class="w-full border border-gray-300 rounded px-3 py-2" required>
                    @foreach($kategoriList as $kategori)
                        <option value="{{ $kategori->id_kategori }}">{{ $kategori->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-1">Nama Barang</label>
                <input type="text" name="nama_barang" class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="block mb-1">Deskripsi Barang</label>
                <textarea name="deskripsi_barang" class="w-full border border-gray-300 rounded px-3 py-2" required></textarea>
            </div>

            <div class="mb-4">
                <label class="block mb-1">Harga Barang</label>
                <input type="number" name="harga_barang" class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="block mb-1">Status Garansi</label>
                <select name="status_garansi" class="w-full border border-gray-300 rounded px-3 py-2" required>
                    <option value="Ada">Ada</option>
                    <option value="Tidak Ada" selected>Tidak Ada</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-1">Gambar Barang 1</label>
                <input type="file" name="gambar_barang" class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="block mb-1">Gambar Barang 2</label>
                <input type="file" name="gambar_barang_2" class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Tambah Barang</button>
        </form>
    </div>

    <div class="mb-6">
        <a href="{{ route('gudang.pengiriman.index') }}"
        class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
            Lihat Daftar Transaksi Pengiriman
        </a>
    </div>


</body>
</html>

@php
$pengirimanList = DB::table('pengiriman')
    ->join('transaksi_pembelian', 'pengiriman.id_transaksi_pembelian', '=', 'transaksi_pembelian.id_transaksi_pembelian')
    ->join('pembeli', 'transaksi_pembelian.id_pembeli', '=', 'pembeli.id_pembeli')
    ->where('transaksi_pembelian.status_pembayaran', 'sudah dibayar')
    ->whereIn('pengiriman.status_pengiriman', ['diproses', 'siap dikirim'])
    ->select(
        'transaksi_pembelian.id_transaksi_pembelian',
        'transaksi_pembelian.tgl_transaksi',
        'pembeli.nama_pembeli',
        'pengiriman.status_pengiriman',
        'pengiriman.tipe_pengiriman'
    )
    ->orderByDesc('transaksi_pembelian.tgl_transaksi')
    ->get();
@endphp

<h2 class="text-xl font-semibold mb-4 mt-10">Daftar Transaksi Pengiriman</h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
  @forelse ($pengirimanList as $trx)
    @php
        // Ambil 1 gambar barang untuk transaksi ini
        $barang = DB::table('detail_transaksi_pembelian')
            ->join('barang', 'detail_transaksi_pembelian.id_barang', '=', 'barang.id_barang')
            ->where('detail_transaksi_pembelian.id_transaksi_pembelian', $trx->id_transaksi_pembelian)
            ->select('barang.nama_barang', 'barang.gambar_barang')
            ->first();
    @endphp
    <div class="bg-white p-4 rounded shadow">
        <div class="flex gap-4 mb-2">
          @if($barang)
            <img src="{{ asset('storage/' . $barang->gambar_barang) }}" class="w-24 h-24 rounded object-cover" alt="{{ $barang->nama_barang }}">
          @endif
        </div>
        <div class="mb-2">
          <div><b>Nama Pembeli:</b> {{ $trx->nama_pembeli ?? '-' }}</div>
          <div><b>Nama Barang:</b> {{ $barang->nama_barang ?? '-' }}</div>
          <div><b>Metode Pengiriman:</b> 
              <span class="font-bold">{{ ucfirst($trx->tipe_pengiriman) }}</span>
          </div>
          <div><b>Status Pengiriman:</b> <span class="font-bold">{{ ucfirst($trx->status_pengiriman) }}</span></div>
        </div>
        <div class="flex gap-2 mt-2">
          <a href="{{ url('/gudang/pengiriman/'.$trx->id_transaksi_pembelian) }}"
             class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Detail</a>
          <a href="{{ url('/gudang/pengiriman/'.$trx->id_transaksi_pembelian.'/nota') }}"
             class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">Cetak Nota PDF</a>
        </div>
    </div>
  @empty
    <p class="text-gray-600">Tidak ada transaksi pengiriman.</p>
  @endforelse
</div>

