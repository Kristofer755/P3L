<!DOCTYPE html>
<html>
<head>
    <title>Manajemen Data Penitip</title>
</head>
<body>
    <h1>Kelola Data Penitip</h1>

    {{-- Notifikasi --}}
    @if (session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    @if ($errors->any())
        <div>
            @foreach ($errors->all() as $error)
                <p style="color:red;">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- Form Pencarian --}}
    <form action="{{ route('cs.penitip.search') }}" method="GET">
        <input type="text" name="search" placeholder="Cari Nama Penitip" value="{{ request('search') }}">
        <button type="submit">Cari</button>
    </form>

    <hr>

    {{-- Form Tambah Penitip Baru --}}
    <h2>Tambah Penitip Baru</h2>
    <form action="{{ route('cs.penitip.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label>Nama Penitip:</label>
            <input type="text" name="nama_penitip" value="{{ old('nama_penitip') }}" required>
        </div>

        <div>
            <label>Email:</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div>
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>

        <div>
            <label>NIK (16 digit):</label>
            <input type="text" name="NIK" maxlength="16" value="{{ old('NIK') }}" required>
        </div>

        <div>
            <label>No. Telepon:</label>
            <input type="text" name="no_telp" value="{{ old('no_telp') }}" required>
        </div>

        <div>
            <label>Saldo:</label>
            <input type="number" name="saldo" value="{{ old('saldo') }}" required>
        </div>

        <div>
            <label>Foto KTP:</label>
            <input type="file" name="foto_ktp">
        </div>

        <button type="submit">Simpan</button>
    </form>

    <hr>

    {{-- Tabel Data Penitip --}}
    <h2>Daftar Penitip</h2>
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>ID Penitip</th>
                <th>Nama</th>
                <th>Email</th>
                <th>NIK</th>
                <th>No Telp</th>
                <th>Saldo</th>
                <th>Foto KTP</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penitip as $item)
                <tr>
                    <td>{{ $item->id_penitip }}</td>
                    <td>{{ $item->nama_penitip }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->NIK }}</td>
                    <td>{{ $item->no_telp }}</td>
                    <td>{{ $item->saldo }}</td>
                    <td>
                        @if($item->foto_ktp)
                            <img src="{{ asset('storage/' . $item->foto_ktp) }}" width="100">
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('cs.penitip.edit', $item->id_penitip) }}">Edit</a>
                        <form action="{{ route('cs.penitip.delete', $item->id_penitip) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Yakin hapus?')" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
