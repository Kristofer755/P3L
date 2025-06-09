<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Transaksi Pengiriman</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <div class="container mx-auto py-6">
        <h1 class="text-2xl font-bold mb-6 text-center">Daftar Transaksi Pengiriman</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse ($pengirimanList as $trx)
                <div class="bg-white p-4 rounded shadow">
                    <div class="flex gap-4 mb-2">
                    </div>
                    <div class="mb-2">
                        <div><b>No. Transaksi:</b> {{ $trx->id_transaksi_pembelian }}</div>
                        <div><b>Pembeli:</b> {{ $trx->nama_pembeli ?? '-' }}</div>
                        <div><b>Tanggal:</b> {{ $trx->tgl_transaksi }}</div>
                        <div><b>Metode Pengiriman:</b> <span class="font-bold">{{ ucfirst($trx->tipe_pengiriman) }}</span></div>
                        <div><b>Status Pengiriman:</b> <span class="font-bold">{{ ucfirst($trx->status_pengiriman) }}</span></div>
                    </div>
                    <div class="flex gap-2 mt-2">
                        <a href="{{ route('gudang.pengiriman.show', $trx->id_transaksi_pembelian) }}" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Detail</a>
                        <a href="{{ route('gudang.pengiriman.nota', $trx->id_transaksi_pembelian) }}" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">Cetak Nota PDF</a>
                    </div>

                    <!-- Form Assign Kurir -->
                    @if (strtolower($trx->tipe_pengiriman) == 'kurir')
                        <form action="{{ route('gudang.pengiriman.assign', $trx->id_transaksi_pembelian) }}" method="POST" class="mt-4">
                            @csrf
                            <div class="mb-2">
                                <label for="id_kurir" class="block font-semibold mb-1">Pilih Kurir:</label>
                                <select name="id_kurir" id="id_kurir" class="w-full border rounded px-3 py-2" required>
                                    <option value="">-- Pilih Kurir --</option>
                                    @foreach ($kurirList as $kurir)
                                        <option value="{{ $kurir->id_pegawai }}">{{ $kurir->nama_pegawai }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="bg-purple-600 text-white px-3 py-1 rounded hover:bg-purple-700 mt-2">Assign Kurir</button>
                        </form>
                    @endif

                    <!-- End Form -->

                </div>
            @empty
                <p class="text-gray-600">Tidak ada transaksi pengiriman.</p>
            @endforelse
        </div>
    </div>

</body>
</html>
