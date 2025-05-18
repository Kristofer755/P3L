<!DOCTYPE html>
<html>
<head>
    <title>Manajemen Alamat Pembeli</title>
</head>
<body>
    <h1>Kelola Data Alamat</h1>

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
    <form action="{{ route('pembeli.alamat.search') }}" method="GET">
        <input type="text" name="search" placeholder="Cari Nama Alamat" value="{{ request('search') }}">
        <button type="submit">Cari</button>
    </form>

    <hr>

    {{-- Form Tambah / Edit Alamat --}}
    @if(isset($editMode) && $editMode && isset($alamat))
        <h2>Edit Alamat</h2>
        <form action="{{ route('pembeli.alamat.update', $alamat->id_alamat) }}" method="POST">
            @method('PUT')
    @else
        <h2>Tambah Alamat Baru</h2>
        <form action="{{ route('pembeli.alamat.store') }}" method="POST">
    @endif

        @csrf

        <div>
            <label>Pembeli:</label>
            <input type="text" value="{{ $pembeliList->first()->nama_pembeli }}" readonly>
            <input type="hidden" name="id_pembeli" value="{{ $pembeliList->first()->id_pembeli }}">
        </div>

        <div>
            <label>Nama Alamat:</label>
            <input type="text" name="nama_alamat" value="{{ old('nama_alamat', $alamat->nama_alamat ?? '') }}" required>
        </div>

        <div>
            <label>Detail Alamat:</label>
            <textarea name="detail_alamat" required>{{ old('detail_alamat', $alamat->detail_alamat ?? '') }}</textarea>
        </div>

        <div>
            <label>Tipe Alamat:</label>
            <input type="text" name="tipe_alamat" value="{{ old('tipe_alamat', $alamat->tipe_alamat ?? '') }}" required>
        </div>

        <div>
            <label>Status Default:</label>
            <select name="status_default" required>
                <option value="aktif" {{ old('status_default', $alamat->status_default ?? '') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ old('status_default', $alamat->status_default ?? '') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>

        <button type="submit">{{ isset($editMode) && $editMode ? 'Update' : 'Simpan' }}</button>
    </form>

    <hr>

    {{-- Tabel Alamat --}}
    <h2>Daftar Alamat</h2>

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Alamat</th>
                <th>Detail</th>
                <th>Tipe</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dataAlamat as $item)
                <tr>
                    <td>{{ $item->id_alamat }}</td>
                    <td>{{ $item->nama_alamat }}</td>
                    <td>{{ $item->detail_alamat }}</td>
                    <td>{{ $item->tipe_alamat }}</td>
                    <td>{{ ucfirst($item->status_default) }}</td>
                    <td>
                        <a href="{{ route('pembeli.alamat.edit', $item->id_alamat) }}">Edit</a> |
                        <form action="{{ route('pembeli.alamat.delete', $item->id_alamat) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin hapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7">Tidak ada data alamat</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
