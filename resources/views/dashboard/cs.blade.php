<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Customer Service</title>
</head>
<body>
    <h1>Selamat Datang di Dashboard Customer Service</h1>

    <form action="{{ route('cs.penitip.index') }}" method="get">
        <button type="submit">Kelola Data Penitip</button>
    </form>

    <a href="{{ route('pegawai.resetPassword', $pegawai->id_pegawai) }}"
        onclick="return confirm('Yakin reset password ke tanggal lahir?')"
        class="btn btn-warning">Reset Password</a>

    <form action="{{ route('diskusi.index') }}" method="get">
        <button type="submit">Diskusi Produk</button>
    </form>

    <!-- Tombol Baru: Validasi Pembayaran -->
    <form action="{{ route('cs.validasi.index') }}" method="get" class="mt-4">
        <button type="submit" class="btn btn-primary">
            Validasi Pembayaran
        </button>
    </form>

</body>
</html>
