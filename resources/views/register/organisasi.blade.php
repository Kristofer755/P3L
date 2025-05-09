<!DOCTYPE html>
<html>
<head>
    <title>Registrasi Organisasi</title>
</head>
<body>
    <h2>Form Registrasi Organisasi</h2>

    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    @if ($errors->any())
        <div>
            @foreach ($errors->all() as $error)
                <p style="color: red;">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('organisasi.store') }}">
        @csrf
        <div>
            <label>Nama Organisasi:</label>
            <input type="text" name="nama_organisasi" value="{{ old('nama_organisasi') }}" required>
        </div>
        <div>
            <label>No Telepon Organisasi:</label>
            <input type="text" name="no_telp_organisasi" value="{{ old('no_telp_organisasi') }}" required>
        </div>
        <div>
            <label>Alamat Organisasi:</label>
            <input type="text" name="alamat_organisasi" value="{{ old('alamat_organisasi') }}" required>
        </div>
        <div>
            <label>Email Organisasi:</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </div>
        <div>
            <label>Password Organisasi:</label>
            <input type="password" name="password" value="{{ old('password') }}" required>
        </div>
        <div>
            <label>Status Organisasi:</label>
            <select name="status_organisasi" required>
                <option value="aktif" {{ old('status_organisasi') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ old('status_organisasi') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
        <button type="submit">Daftar Organisasi</button>
    </form>
</body>
</html>
