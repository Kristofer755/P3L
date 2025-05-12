<!DOCTYPE html>
<html>
<head>
    <title>Diskusi Produk - Customer Service</title>
</head>
<body>
    <h1>Diskusi Produk (Customer Service)</h1>

    <p>Silakan klik tombol di bawah untuk membuka halaman utama diskusi produk.</p>

    <form action="{{ route('diskusi.index') }}" method="get">
        <button type="submit">Lihat Semua Diskusi</button>
    </form>

    <br>
    <a href="{{ route('dashboard.cs') }}">← Kembali ke Dashboard CS</a>
</body>
</html>
