<!DOCTYPE html>
<html>
<head>
    <title>Manajemen Alamat Pembeli</title>
</head>
<body>
    <h1>Kelola Alamat Pembeli</h1>

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
    @if(isset($editMode) && $editMode)
        <h2>Edit Alamat</h2>
        <form action="{{ route('pembeli.alamat.update', $alamat->id) }}" method="POST">
            @method('PUT')
    @else
        <h2>Tambah Alamat Baru</h2>
        <form action="{{ route('pembeli.alamat.store') }}" method="POST">
    @endif

        @csrf
        <div>
            <label>Pilih Pembeli:</label>
            <select name="id_pembeli" required>
                <option value="">-- Pilih Pembeli --</option>
                @foreach ($pembeliList as $pembeli)
                    <option value="{{ $pembeli->id_pembeli }}"
                        {{ old('id_pembeli', $alamat->id_pembeli ?? '') == $pembeli->id_pembeli ? 'selected' : '' }}>
                        {{ $pembeli->nama_pembeli }} ({{ $pembeli->id_pembeli }})
                    </option>
                @endforeach
            </select>
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

    {{-- Tabel Data Alamat --}}
    <h2>Daftar Alamat</h2>

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Nama Alamat</th>
                <th>Detail</th>
                <th>Tipe</th>
                <th>Status Default</th>
                <th>Pembeli</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dataAlamat as $item)
                <tr>
                    <td>{{ $item->nama_alamat }}</td>
                    <td>{{ $item->detail_alamat }}</td>
                    <td>{{ $item->tipe_alamat }}</td>
                    <td>{{ $item->status_default }}</td>
                    <td>{{ $item->pembeli->nama_pembeli ?? '-' }}</td>
                    <td>
                        <a href="{{ route('pembeli.alamat.edit', $item->id) }}">Edit</a>
                        <form action="{{ route('pembeli.alamat.destroy', $item->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Yakin hapus?')" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
