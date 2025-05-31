<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Pembeli - ReuseMart</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/alpinejs@3.x.x" defer></script>
</head>
<body class="bg-gray-50 min-h-screen font-sans">
  @if(session('alert'))
  <script>
    alert("{{ session('alert') }}");
  </script>
@endif


  <!-- Navbar -->
  <nav class="bg-gradient-to-r from-purple-700 to-purple-500 text-white px-6 py-4 shadow-md flex justify-between items-center">
    <div class="text-2xl font-bold tracking-wide">ReuseMart</div>

    <div class="flex items-center gap-6">
      <!-- Icon Keranjang -->
      <a href="{{ route('keranjang.index') }}" class="relative text-2xl hover:scale-110 transition">
        🛒
        @if(session('keranjang') && count(session('keranjang')) > 0)
          <span class="absolute -top-2 -right-3 bg-red-600 text-white text-xs px-2 py-0.5 rounded-full animate-pulse">
            {{ count(session('keranjang')) }}
          </span>
        @endif
      </a>

      <!-- Dropdown Profil -->
      <div class="relative" x-data="{ open: false }" @click.away="open = false">
        <button @click="open = !open" class="flex items-center gap-2 focus:outline-none hover:scale-105 transition">
          <div class="w-8 h-8 rounded-full bg-white text-purple-700 flex items-center justify-center font-bold shadow">
            {{ strtoupper(substr(session('user')->nama_pembeli, 0, 1)) }}
          </div>
          <span>{{ session('user')->nama_pembeli ?? 'Profil' }}</span>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>

        <div x-show="open" x-transition class="absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg z-10">
          <a href="{{ route('pembeli.profil') }}" class="block px-4 py-2 hover:bg-purple-100">Profil</a>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left px-4 py-2 hover:bg-purple-100">Logout</button>
          </form>
        </div>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="max-w-6xl mx-auto py-10 px-6">
    <h1 class="text-3xl font-semibold text-gray-800">Halo, {{ session('user')->nama_pembeli }}</h1>
    <p class="text-gray-500 mt-1">Selamat datang di dashboard pembeli. Silakan pilih fitur yang ingin Anda akses.</p>

    <!-- Action Buttons -->
    <div class="mt-6 flex flex-wrap gap-4">
      <a href="{{ route('pembeli.transaksi.index') }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg shadow hover:bg-purple-700 transition">Riwayat Transaksi</a>

      <a href="{{ route('pembeli.alamat') }}" class="bg-white text-purple-700 border border-purple-500 px-4 py-2 rounded-lg shadow hover:bg-purple-100 transition">Kelola Data Alamat</a>
    </div>

    <!-- Product Grid -->
    <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    @foreach ($barangs as $barang)
      @if (strtolower($barang->status_barang) === 'sold out')
        <div
          class="bg-white rounded-xl shadow p-4 opacity-50 cursor-not-allowed hover:shadow-none transition duration-300"
          onclick="alert('Barang Sudah Sold Out')"
        >
          <img src="{{ asset('images/' . $barang->gambar_barang) }}"
              alt="{{ $barang->nama_barang }}"
              class="w-full h-36 object-cover rounded-lg mb-3" />
          <h3 class="font-semibold text-lg text-gray-800 truncate">{{ $barang->nama_barang }}</h3>
          <p class="text-sm text-gray-500 h-12 overflow-hidden">{{ $barang->deskripsi_barang }}</p>
          <p class="text-xs mt-1">
            Status Garansi:
            @if (strtolower($barang->status_garansi) === 'bergaransi')
              <span class="text-green-600 font-medium">Bergaransi</span>
            @else
              <span class="text-red-600 font-medium">Tidak Bergaransi</span>
            @endif
          </p>
          <p class="text-orange-500 font-bold mt-2">Rp {{ number_format($barang->harga_barang, 0, ',', '.') }}</p>
          <span class="mt-2 inline-block text-red-600 font-semibold">Sold Out</span>
        </div>
      @else
        <a href="{{ route('barang.detail', $barang->id_barang) }}"
          class="bg-white rounded-xl shadow hover:shadow-lg transform hover:-translate-y-1 transition duration-300 p-4">
          <img src="{{ asset('images/' . $barang->gambar_barang) }}"
              alt="{{ $barang->nama_barang }}"
              class="w-full h-36 object-cover rounded-lg mb-3" />
          <h3 class="font-semibold text-lg text-gray-800 truncate">{{ $barang->nama_barang }}</h3>
          <p class="text-sm text-gray-500 h-12 overflow-hidden">{{ $barang->deskripsi_barang }}</p>
          <p class="text-xs mt-1">
            Status Garansi:
            @if (strtolower($barang->status_garansi) === 'bergaransi')
              <span class="text-green-600 font-medium">Bergaransi</span>
            @else
              <span class="text-red-600 font-medium">Tidak Bergaransi</span>
            @endif
          </p>
          <p class="text-orange-500 font-bold mt-2">Rp {{ number_format($barang->harga_barang, 0, ',', '.') }}</p>
        </a>
      @endif
    @endforeach
    </div>

  </div>
</body>
</html>
