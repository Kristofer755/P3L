<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Transaksi Pembelian</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 py-8 px-4">

    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6 text-purple-700">Riwayat Transaksi Pembelian</h1>

        @foreach ($transaksi as $trx)
            <div class="bg-white rounded-lg shadow-md p-4 mb-4">
                <div class="flex justify-between items-center">
                    <div>
                        <p><strong>ID Transaksi:</strong> {{ $trx->id_transaksi_pembelian }}</p>
                        <p><strong>Tanggal:</strong> {{ $trx->tgl_transaksi }}</p>
                        <p><strong>Total Pembayaran:</strong> Rp {{ number_format($trx->total_pembayaran, 0, ',', '.') }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($trx->status_pembayaran) }}</p>
                    </div>
                    <a href="{{ route('pembeli.transaksi.detail', $trx->id_transaksi_pembelian) }}" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                        Lihat Detail
                    </a>
                </div>
            </div>
        @endforeach

        @if ($transaksi->isEmpty())
            <p class="text-gray-600">Belum ada transaksi yang dilakukan.</p>
        @endif
    </div>

</body>
</html>
