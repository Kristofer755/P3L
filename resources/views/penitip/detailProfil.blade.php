<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Profil Penitip</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
  <h1 class="text-2xl font-bold mb-4">Profil Penitip</h1>

  <div class="bg-white p-4 rounded shadow">
    <p><strong>Nama:</strong> {{ $penitip->nama_penitip }}</p>
    <p><strong>Email:</strong> {{ $penitip->email }}</p>
    <p><strong>No Telepon:</strong> {{ $penitip->no_telp }}</p>
    <p><strong>Saldo:</strong> Rp{{ number_format($penitip->saldo, 0, ',', '.') }}</p>
    <p><strong>Poin:</strong> {{ number_format($penitip->poin, 0, ',', '.') }}</p>
  </div>

  <a href="{{ route('dashboard.penitip') }}" class="mt-4 inline-block text-blue-500 hover:underline">← Kembali ke Dashboard</a>
</body>
</html>
