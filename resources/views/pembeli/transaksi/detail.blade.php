<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Transaksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 py-8 px-4">

    <div class="max-w-4xl mx-auto">
        <a href="{{ route('pembeli.transaksi.index') }}" class="text-purple-600 underline mb-4 inline-block">← Kembali ke Riwayat</a>

        <h1 class="text-2xl font-bold mb-4 text-purple-700">Detail Transaksi</h1>

        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <p><strong>ID Transaksi:</strong> {{ $transaksi->id_transaksi_pembelian }}</p>
            <p><strong>Tanggal:</strong> {{ $transaksi->tgl_transaksi }}</p>
            <p><strong>Total Pembayaran:</strong> Rp {{ number_format($transaksi->total_pembayaran, 0, ',', '.') }}</p>
            <p><strong>Status Pembayaran:</strong> {{ ucfirst($transaksi->status_pembayaran) }}</p>
            @if ($transaksi->bukti_pembayaran)
                <p><strong>Bukti Pembayaran:</strong></p>
                <img src="{{ asset('storage/' . $transaksi->bukti_pembayaran) }}" alt="Bukti Pembayaran" class="w-48 mt-2">
            @endif
        </div>

        <h2 class="text-xl font-semibold mb-2">Daftar Barang:</h2>
        <div class="space-y-4">
            @foreach ($transaksi->detailTransaksi as $detail)
                <div class="bg-white p-4 rounded shadow-md">
                    <p><strong>Nama Barang:</strong> {{ $detail->barang->nama_barang ?? 'Barang tidak ditemukan' }}</p>
                    <p><strong>Jumlah:</strong> {{ $detail->jml_barang_pembelian }}</p>
                    <p><strong>Total Harga:</strong> Rp {{ number_format($detail->total_harga_pembelian, 0, ',', '.') }}</p>
                </div>
            @endforeach
        </div>
    </div>

</body>
</html>
