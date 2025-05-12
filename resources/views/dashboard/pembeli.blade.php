<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Pembeli</title>
</head>
<body>
    <h1>Selamat Datang di Dashboard Pembeli</h1>
    <form action="{{ route('pembeli.alamat') }}" method="get">
        <button type="submit">Kelola Data Alamat</button>
    </form>

    <form action="{{ route('pembeli.diskusiPembeli') }}" method="get">
        <button type="submit">Diskusi Produk</button>
    </form>
</body>
</html>