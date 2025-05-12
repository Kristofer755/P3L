<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
</head>
<body>
    <h1>Selamat Datang di Dashboard Admin</h1>

    <form action="{{ route('admin.organisasi.index') }}" method="get">
        <button type="submit">Kelola Data Organisasi</button>
    </form>

    <a href="{{ route('pegawai.resetPassword', $pegawai->id_pegawai) }}"
        onclick="return confirm('Yakin reset password ke tanggal lahir?')"
        class="btn btn-warning">Reset Password</a>



</body>
</html>
