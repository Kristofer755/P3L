<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
</head>
<body>
    <h1>Selamat Datang di Dashboard Admin</h1>

    <form action="{{ route('admin.organisasi') }}" method="get">
        <button type="submit">Kelola Data Organisasi</button>
    </form>

</body>
</html>
