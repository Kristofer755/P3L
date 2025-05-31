<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Alamat Pembeli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5">
    <h1 class="mb-4">Kelola Data Alamat</h1>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Tambah / Edit Alamat --}}
    <div class="card mb-5">
        <div class="card-header bg-primary text-white">
            {{ (isset($editMode) && $editMode) ? 'Edit Alamat' : 'Tambah Alamat Baru' }}
        </div>
        <div class="card-body">
            @if(isset($editMode) && $editMode)
                <form action="{{ route('pembeli.alamat.update', $alamat->id_alamat) }}" method="POST">
                @method('PUT')
            @else
                <form action="{{ route('pembeli.alamat.store') }}" method="POST">
            @endif
                @csrf

                <input type="hidden" name="id_pembeli" value="{{ $pembeliList->first()->id_pembeli }}">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nama_alamat" class="form-label">Nama Alamat</label>
                        <input type="text"
                               id="nama_alamat"
                               name="nama_alamat"
                               class="form-control"
                               value="{{ old('nama_alamat', $alamat->nama_alamat ?? '') }}"
                               required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tipe_alamat" class="form-label">Tipe Alamat</label>
                        <input type="text"
                               id="tipe_alamat"
                               name="tipe_alamat"
                               class="form-control"
                               placeholder="Contoh: Rumah / Kantor"
                               value="{{ old('tipe_alamat', $alamat->tipe_alamat ?? '') }}"
                               required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="detail_alamat" class="form-label">Detail Alamat</label>
                    <textarea id="detail_alamat"
                              name="detail_alamat"
                              class="form-control"
                              rows="2"
                              required>{{ old('detail_alamat', $alamat->detail_alamat ?? '') }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="kota" class="form-label">Kota</label>
                        <input type="text"
                               id="kota"
                               name="kota"
                               class="form-control"
                               value="{{ old('kota', $alamat->kota ?? '') }}"
                               required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="provinsi" class="form-label">Provinsi</label>
                        <input type="text"
                               id="provinsi"
                               name="provinsi"
                               class="form-control"
                               value="{{ old('provinsi', $alamat->provinsi ?? '') }}"
                               required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="kode_pos" class="form-label">Kode Pos</label>
                        <input type="text"
                               id="kode_pos"
                               name="kode_pos"
                               class="form-control"
                               pattern="\d{5}"
                               title="Masukkan 5 digit angka"
                               value="{{ old('kode_pos', $alamat->kode_pos ?? '') }}"
                               required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="status_default" class="form-label">Status Default</label>
                    <select id="status_default"
                            name="status_default"
                            class="form-select"
                            required>
                        <option value="aktif" {{ old('status_default', $alamat->status_default ?? '') == 'aktif' ? 'selected' : '' }}>
                            Aktif
                        </option>
                        <option value="nonaktif" {{ old('status_default', $alamat->status_default ?? '') == 'nonaktif' ? 'selected' : '' }}>
                            Nonaktif
                        </option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">
                    {{ (isset($editMode) && $editMode) ? 'Update Alamat' : 'Simpan Alamat' }}
                </button>
            </form>
        </div>
    </div>

    {{-- Tabel Alamat --}}
    <div class="card">
        <div class="card-header bg-secondary text-white">Daftar Alamat</div>
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Detail</th>
                        <th>Kota</th>
                        <th>Provinsi</th>
                        <th>Kode Pos</th>
                        <th>Tipe</th>
                        <th>Status</th>
                        <th style="width:120px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dataAlamat as $item)
                        <tr>
                            <td>{{ $item->id_alamat }}</td>
                            <td>{{ $item->nama_alamat }}</td>
                            <td>{{ $item->detail_alamat }}</td>
                            <td>{{ $item->kota }}</td>
                            <td>{{ $item->provinsi }}</td>
                            <td>{{ $item->kode_pos }}</td>
                            <td>{{ $item->tipe_alamat }}</td>
                            <td>{{ ucfirst($item->status_default) }}</td>
                            <td>
                                <a href="{{ route('pembeli.alamat.edit', $item->id_alamat) }}"
                                   class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('pembeli.alamat.delete', $item->id_alamat) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Yakin hapus alamat ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-3">Tidak ada data alamat</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
