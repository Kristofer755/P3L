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
</body>
</html>