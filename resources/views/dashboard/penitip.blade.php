<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Penitip - ReuseMart</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/alpinejs@3.x.x" defer></script>
  <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-gray-50 min-h-screen">

  <!-- Navbar -->
    <nav class="bg-white border-b shadow-md px-6 py-4 flex justify-between items-center">
      <div class="text-xl font-bold text-purple-700">ReuseMart</div>

      <div class="relative" x-data="{ open: false }" @click.away="open = false">
        <button @click="open = !open" class="flex items-center gap-2 focus:outline-none">
          <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-white font-bold">
            {{ strtoupper(substr(session('user')->nama_penitip, 0, 1)) }}
          </div>
          <span>{{ session('user')->nama_penitip ?? 'Profil' }}</span>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>

        <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg z-10">
          <a href="{{ route('penitip.profil') }}" class="block px-4 py-2 hover:bg-purple-100">Profil</a>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left px-4 py-2 hover:bg-purple-100">Logout</button>
          </form>
        </div>
      </div>
    </nav>

    <div class="p-6">
      <h1 class="text-2xl font-semibold">Selamat Datang, {{ session('user')->nama_penitip }}</h1>
      <p class="text-gray-600 mt-2">Ini adalah dashboard penitip. Di sini Anda bisa melihat status titipan, riwayat penjualan, dan lainnya.</p>
    </div>


  </body>
</html>
