<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Profil Pembeli</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
  <h1 class="text-2xl font-bold mb-4">Profil Pembeli</h1>

  <div class="bg-white p-4 rounded shadow">
    <p><strong>Nama:</strong> {{ $pembeli->nama_pembeli }}</p>
    <p><strong>Email:</strong> {{ $pembeli->email }}</p>
    <p><strong>No Telepon:</strong> {{ $pembeli->no_telp }}</p>
    <p><strong>Poin:</strong> {{ number_format($pembeli->poin, 0, ',', '.') }}</p>
  </div>

  <a href="{{ route('pembeli.pembeli') }}" class="mt-4 inline-block text-blue-500 hover:underline">← Kembali ke Dashboard</a>
</body>

</html>
