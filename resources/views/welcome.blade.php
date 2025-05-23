<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Welcome - TokoOnline</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

  <!-- Navbar -->
  <div class="fixed top-0 w-full bg-purple-900 text-white shadow z-50">
    <div class="max-w-6xl mx-auto flex justify-between items-center px-6 py-4">
      <div class="text-xl font-bold">🛒 ReuseMart</div>
      <div class="space-x-6">
        <a href="{{ route('login') }}" class="hover:underline">Login</a>
        <a href="{{ route('register') }}" class="hover:underline">Register</a>
      </div>
    </div>
  </div>

  <!-- Search Bar -->
  <div class="fixed top-16 w-full bg-white shadow z-40 text-center py-4">
    <input
      type="text"
      placeholder="🔍 Cari produk favoritmu di sini..."
      class="w-3/5 px-5 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-4 focus:ring-purple-200 transition"
    />
  </div>

  <!-- Banner -->
  <div class="mt-40 flex justify-center px-4">
    <img src="{{ asset('images/promo1.png') }}" alt="Banner Promo" class="w-full max-w-5xl rounded-xl shadow-lg" />
  </div>

  <!-- Categories -->
  <div class="flex flex-wrap justify-center gap-5 my-12 px-4">
    <div class="flex flex-col items-center justify-center text-center w-24 h-24 rounded-full bg-white shadow hover:scale-110 transition cursor-pointer">
      💻<p class="text-sm mt-1">Elektronik & Gadget</p>
    </div>
    <div class="flex flex-col items-center justify-center text-center w-24 h-24 rounded-full bg-white shadow hover:scale-110 transition cursor-pointer">
      👗<p class="text-sm mt-1">Pakaian & Aksesoris</p>
    </div>
    <div class="flex flex-col items-center justify-center text-center w-24 h-24 rounded-full bg-white shadow hover:scale-110 transition cursor-pointer">
      🛋️<p class="text-sm mt-1">Perabotan Rumah Tangga</p>
    </div>
    <div class="flex flex-col items-center justify-center text-center w-24 h-24 rounded-full bg-white shadow hover:scale-110 transition cursor-pointer">
      📚<p class="text-sm mt-1">Buku & Sekolah</p>
    </div>
    <div class="flex flex-col items-center justify-center text-center w-24 h-24 rounded-full bg-white shadow hover:scale-110 transition cursor-pointer">
      🧸<p class="text-sm mt-1">Hobi & Koleksi</p>
    </div>
    <div class="flex flex-col items-center justify-center text-center w-24 h-24 rounded-full bg-white shadow hover:scale-110 transition cursor-pointer">
      👶<p class="text-sm mt-1">Perlengkapan Bayi</p>
    </div>
    <div class="flex flex-col items-center justify-center text-center w-24 h-24 rounded-full bg-white shadow hover:scale-110 transition cursor-pointer">
      💄<p class="text-sm mt-1">Kosmetik</p>
    </div>
    <div class="flex flex-col items-center justify-center text-center w-24 h-24 rounded-full bg-white shadow hover:scale-110 transition cursor-pointer">
      🚗<p class="text-sm mt-1">Otomotif</p>
    </div>
    <div class="flex flex-col items-center justify-center text-center w-24 h-24 rounded-full bg-white shadow hover:scale-110 transition cursor-pointer">
      🌿<p class="text-sm mt-1">Outdoor</p>
    </div>
    <div class="flex flex-col items-center justify-center text-center w-24 h-24 rounded-full bg-white shadow hover:scale-110 transition cursor-pointer">
      🖨️<p class="text-sm mt-1">Peralatan Kantor</p>
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
