<!DOCTYPE html>
<html>
<head>
    <title>Edit Organisasi</title>
</head>
<body>
    <h1>Edit Data Organisasi</h1>

    <!-- Tampilkan error validasi -->
    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.organisasi.update', $organisasi->id_organisasi) }}" method="POST">
        @csrf
        @method('POST')

        <label>Nama Organisasi:</label><br>
        <input type="text" name="nama_organisasi" value="{{ old('nama_organisasi', $organisasi->nama_organisasi) }}"><br><br>

        <label>No Telepon:</label><br>
        <input type="text" name="no_telp_organisasi" value="{{ old('no_telp_organisasi', $organisasi->no_telp_organisasi) }}"><br><br>

        <label>Alamat:</label><br>
        <textarea name="alamat_organisasi">{{ old('alamat_organisasi', $organisasi->alamat_organisasi) }}</textarea><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="{{ old('email', $organisasi->email) }}"><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" placeholder="Masukkan password baru"><br><br>

        <label>Status:</label><br>
        <select name="status_organisasi">
            <option value="aktif" {{ $organisasi->status_organisasi == 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="nonaktif" {{ $organisasi->status_organisasi == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
        </select><br><br>

        <button type="submit">Update</button>
    </form>

    <br>
    <a href="{{ route('admin.organisasi') }}">Kembali ke Dashboard</a>
</body>
</html>
