<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Validasi Pembayaran - CS</title>
  <link href="https://cdn.tailwindcss.com" rel="stylesheet">
  <style>
    .bukti-img {
      width: 70px;
      height: 70px;
      object-fit: cover;
      border-radius: 0.75rem;
      border: 2px solid #e9d5ff;
      background: #f8fafc;
      box-shadow: 0 2px 12px rgba(124,58,237,0.08);
      transition: transform 0.18s;
    }
    .bukti-img:hover {
      transform: scale(1.08) rotate(-2deg);
      box-shadow: 0 6px 20px rgba(124,58,237,0.13);
    }
    th, td {
      border: 1px solid #c4b5fd !important; /* Tailwind purple-300 */
    }
    thead th {
      position: sticky;
      top: 0;
      z-index: 10;
      background: linear-gradient(to right, #8b5cf6, #6366f1);
      color: white;
    }
  </style>
</head>
<body class="bg-gray-50 min-h-screen font-sans py-8 px-2">
  <div class="max-w-6xl mx-auto">
    <h1 class="text-3xl font-bold text-purple-700 mb-8">Validasi Pembayaran</h1>
    <div class="overflow-x-auto bg-white rounded-xl shadow-lg border border-purple-100 p-3">
      <table class="min-w-full text-sm text-left border-collapse">
        <thead>
          <tr>
            <th class="px-4 py-3">Barang</th>
            <th class="px-4 py-3">Pembeli</th>
            <th class="px-4 py-3">Alamat</th>
            <th class="px-4 py-3">ID Transaksi</th>
            <th class="px-4 py-3">Tanggal</th>
            <th class="px-4 py-3">Total Bayar</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3">Tukar Poin</th>
            <th class="px-4 py-3 text-center">Bukti</th>
            <th class="px-4 py-3 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($transaksis as $transaksi)
          <tr class="hover:bg-purple-50 transition">
            <td class="px-4 py-3 font-semibold">{{ $transaksi->detailTransaksi->first()->barang->nama_barang }}</td>
            <td class="px-4 py-3">{{ $transaksi->pembeli->nama_pembeli }}</td>
            <td class="px-4 py-3">
              {{ $transaksi->alamat && $transaksi->alamat->nama_alamat ? $transaksi->alamat->nama_alamat : 'Gudang' }}
            </td>
            <td class="px-4 py-3 font-mono text-purple-800">{{ $transaksi->id_transaksi_pembelian }}</td>
            <td class="px-4 py-3">{{ $transaksi->tgl_transaksi->format('d-m-Y') }}</td>
            <td class="px-4 py-3 text-green-700 font-bold">Rp {{ number_format($transaksi->total_pembayaran, 0, ',', '.') }}</td>
            <td class="px-4 py-3">
              <span class="inline-block px-3 py-1 rounded-full text-xs font-bold
                  @if($transaksi->status_pembayaran == 'valid') bg-green-200 text-green-900
                  @elseif($transaksi->status_pembayaran == 'pending') bg-yellow-100 text-yellow-800
                  @else bg-red-100 text-red-800
                  @endif">
                {{ ucfirst($transaksi->status_pembayaran) }}
              </span>
            </td>
            <td class="px-4 py-3 text-center">{{ $transaksi->tukar_poin }}</td>
            <td class="px-4 py-3 text-center">
              <a href="{{ asset('storage/' . $transaksi->bukti_pembayaran) }}" target="_blank">
                <img src="{{ asset('storage/' . $transaksi->bukti_pembayaran) }}" alt="Bukti"
                  class="bukti-img" />
              </a>
            </td>
            <td class="px-4 py-3 flex flex-col gap-2 items-center">
              <form action="{{ route('cs.validasi.approve', $transaksi->id_transaksi_pembelian) }}" method="POST">
                @csrf
                <button type="submit" class="w-24 px-3 py-1.5 bg-green-500 text-white rounded-lg hover:bg-green-600 font-semibold shadow">Valid</button>
              </form>
              <form action="{{ route('cs.validasi.reject', $transaksi->id_transaksi_pembelian) }}" method="POST">
                @csrf
                <button type="submit" class="w-24 px-3 py-1.5 bg-red-500 text-white rounded-lg hover:bg-red-600 font-semibold shadow">Tidak Valid</button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="mt-8 text-xs text-gray-400 text-center">ReuseMart &copy; {{ date('Y') }}</div>
  </div>
</body>
</html>
