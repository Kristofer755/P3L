<!DOCTYPE html>
<html>
<head>
    <title>Diskusi Produk - Pembeli</title>
</head>
<body>
    <h1>Diskusi Produk (Pembeli)</h1>

    <p>Silakan klik tombol di bawah untuk membuka halaman utama diskusi produk.</p>

    <form action="{{ route('diskusi.index') }}" method="get">
        <button type="submit">Lihat Semua Diskusi</button>
    </form>

    <br>
    <a href="{{ route('dashboard.pembeli') }}">← Kembali ke Dashboard Pembeli</a>
</body>
</html>
