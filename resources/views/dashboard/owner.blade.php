<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Owner</title>
</head>
<body>
    <h1>Selamat Datang di Dashboard Owner</h1>

    <a href="{{ route('pegawai.resetPassword', $pegawai->id_pegawai) }}"
        onclick="return confirm('Yakin reset password ke tanggal lahir?')"
        class="btn btn-warning">Reset Password</a>
</body>
</html>