<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Transaksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen py-6 px-4">

    <div class="max-w-5xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-6">Detail Transaksi</h1>

        <p><strong>No Transaksi:</strong> {{ $transaksi->id_transaksi_pembelian }}</p>
        <p><strong>Nama Pembeli:</strong> {{ $pembeli->nama_pembeli }}</p>
        <p><strong>Tanggal Transaksi:</strong> {{ \Carbon\Carbon::parse($transaksi->tgl_transaksi)->format('d M Y H:i') }}</p>
        <p><strong>Metode Pengiriman:</strong> {{ ucfirst($pengiriman->tipe_pengiriman) }}</p>
        <p><strong>Status Pengiriman:</strong> {{ ucfirst($pengiriman->status_pengiriman) }}</p>

        <h2 class="text-xl font-semibold mt-6 mb-4">Daftar Barang</h2>
        <div class="grid grid-cols-2 gap-4">
            @foreach ($barangList as $barang)
            <div class="bg-gray-50 p-4 rounded shadow">
                <img src="{{ asset('storage/' . ($barang->path_gambar ?? $barang->gambar_barang)) }}" alt="{{ $barang->nama_barang }}" class="w-full h-40 object-cover rounded mb-2">
                <h3 class="text-lg font-bold">{{ $barang->nama_barang }}</h3>
                <p>Jumlah: {{ $barang->jml_barang_pembelian }}</p>
                <p>Harga: Rp{{ number_format($barang->harga_satuan_pembelian, 0, ',', '.') }}</p>
            </div>
            @endforeach
        </div>

        <h2 class="text-xl font-semibold mt-6 mb-4">Penjadwalan</h2>

        <form action="{{ url('/gudang/pengiriman/' . $transaksi->id_transaksi_pembelian . '/jadwalkan') }}" method="POST">
            @csrf

            @if ($pengiriman->tipe_pengiriman == 'diantar')
                <!-- Jika pengiriman kurir -->
                <div class="mb-4">
                    <label class="block mb-2">Pilih Kurir:</label>
                    <select name="id_kurir" class="w-full border rounded px-3 py-2" required>
                        <option value="">-- Pilih Kurir --</option>
                        @foreach($kurirList as $kurir)
                            <option value="{{ $kurir->id_pegawai }}">{{ $kurir->nama_pegawai }}</option>
                        @endforeach
                    </select>
                </div>
            @else
                <!-- Jika ambil sendiri -->
                <div class="mb-4">
                    <label class="block mb-2">Jadwal Pengambilan:</label>
                    <input type="datetime-local" name="jadwal" class="w-full border rounded px-3 py-2" required>
                </div>
            @endif

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan Jadwal</button>
        </form>

        <div class="mt-6">
            <a href="{{ url('/gudang/pengiriman/' . $transaksi->id_transaksi_pembelian . '/nota') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Cetak Nota PDF
            </a>
        </div>

    </div>

</body>
</html>
