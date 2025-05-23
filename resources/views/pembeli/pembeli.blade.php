<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Pembeli - ReuseMart</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/alpinejs@3.x.x" defer></script>
</head>
<body class="bg-gray-50 min-h-screen">

  <!-- Navbar -->
  <nav class="bg-white border-b shadow-md px-6 py-4 flex justify-between items-center">
    <div class="text-xl font-bold text-purple-700">ReuseMart</div>

    <div class="relative" x-data="{ open: false }" @click.away="open = false">
      <button @click="open = !open" class="flex items-center gap-2 focus:outline-none">
        <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-white font-bold">
          {{ strtoupper(substr(session('user')->nama_pembeli, 0, 1)) }}
        </div>
        <span>{{ session('user')->nama_pembeli ?? 'Profil' }}</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </button>

      <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg z-10">
        <a href="{{ route('pembeli.profil') }}" class="block px-4 py-2 hover:bg-purple-100">Profil</a>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="w-full text-left px-4 py-2 hover:bg-purple-100">Logout</button>
        </form>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="p-6">
    <h1 class="text-2xl font-semibold">Selamat Datang, {{ session('user')->nama_pembeli }}</h1>
    <p class="text-gray-600 mt-2">Ini adalah dashboard pembeli. Di sini Anda bisa melihat status pembelian, riwayat transaksi, dan lainnya.</p>

    <div class="mt-6 space-x-4">
      <a href="{{ route('pembeli.transaksi.index') }}" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
        Riwayat Transaksi
      </a>

      <form action="{{ route('pembeli.alamat') }}" method="get">
        <button type="submit">Kelola Data Alamat</button>
      </form>

      <form action="{{ route('diskusi.index') }}" method="get">
        <button type="submit">Diskusi Produk</button>
      </form>
      
    </div>
  </div>

  <!-- Products -->
  <div class="flex flex-wrap justify-center gap-6 px-4 pb-16">
    @foreach ($barangs as $barang)
      <a href="{{ route('barang.detail', $barang->id_barang) }}"
         class="bg-white w-52 rounded-xl shadow-md p-4 text-center transition hover:-translate-y-1 hover:shadow-lg text-gray-800 no-underline">
        <img src="{{ asset('images/' . $barang->gambar_barang) }}" alt="{{ $barang->nama_barang }}"
             class="w-full h-36 object-cover rounded-lg" />
        <p class="font-semibold mt-3">{{ $barang->nama_barang }}</p>
        <p class="text-sm text-gray-600">{{ $barang->deskripsi_barang }}</p>
        <p class="text-xs mt-1">
          Status Garansi:
          @if (strtolower($barang->status_garansi) === 'bergaransi')
            <span class="text-green-600 font-medium">Bergaransi</span>
          @else
            <span class="text-red-600 font-medium">Tidak Bergaransi</span>
          @endif
        </p>
        <strong class="text-orange-500 block mt-2">Rp {{ number_format($barang->harga_barang, 0, ',', '.') }}</strong>
      </a>
    @endforeach
  </div>

</body>
</html>
