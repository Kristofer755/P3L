<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Organisasi</title>
</head>
<body>
    <h1>Selamat Datang di Dashboard Organisasi</h1>

    <button onclick="window.location.href='{{ url('register/organisasi') }}'">
        Register Organisasi
    </button>

    <form action="{{ route('organisasi.request.read') }}" method="get">
        <button type="submit">Request Donasi</button>
    </form>

</body>
</html>
