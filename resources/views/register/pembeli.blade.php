<!DOCTYPE html>
<html>
<head>
    <title>Register Pembeli</title>
</head>
<body>
    <h1>Form Registrasi Pembeli</h1>

    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <!-- Tampilkan error validasi jika ada -->
    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Register -->
    <form method="POST" action="{{ route('register.pembeli.store') }}" >
        @csrf

        <label>Nama Pembeli:</label><br>
        <input type="text" name="nama_pembeli" value="{{ old('nama_pembeli') }}" required><br><br>

        <label>No Telepon:</label><br>
        <input type="text" name="no_telp" value="{{ old('no_telp') }}" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="{{ old('email') }}" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Daftar Pembeli</button>
    </form>
</body>
</html>
