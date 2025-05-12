<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Request Donasi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="p-4">

    <h1 class="mb-4">Daftar Request Donasi</h1>

    {{-- Notifikasi sukses --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Form Pencarian --}}
    <form method="GET" action="{{ route('organisasi.request.search') }}" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan nama organisasi..." value="{{ request('search') }}">
            <button class="btn btn-primary">Cari</button>
        </div>
    </form>

    {{-- Form Tambah / Edit --}}
    <div class="card mb-4">
        <div class="card-header">{{ $editMode ? 'Edit Request Donasi' : 'Tambah Request Donasi' }}</div>
        <div class="card-body">
            <form method="POST" action="{{ $editMode ? route('organisasi.request.update', $requestDonasi->id_request_donasi) : route('organisasi.request.store') }}">
                @csrf
                @if($editMode)
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label for="id_organisasi" class="form-label">Organisasi</label>
                    <select name="id_organisasi" class="form-select" required>
                        @foreach($organisasiList as $org)
                            <option value="{{ $org->id_organisasi }}"
                                {{ ($editMode && $org->id_organisasi == $requestDonasi->id_organisasi) ? 'selected' : '' }}>
                                {{ $org->nama_organisasi }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="deskripsi_request" class="form-label">Deskripsi Request</label>
                    <input type="text" name="deskripsi_request" class="form-control" 
                           value="{{ $editMode ? $requestDonasi->deskripsi_request : old('deskripsi_request') }}" required>
                </div>

                <div class="mb-3">
                    <label for="status_request" class="form-label">Status</label>
                    <select name="status_request" class="form-select" required>
                        @php
                            $statuses = ['Diminta', 'Selesai', 'Diterima', 'Ditolak', 'Dikirim'];
                        @endphp
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" 
                                {{ $editMode && $requestDonasi->status_request === $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button class="btn btn-success" type="submit">{{ $editMode ? 'Update' : 'Tambah' }}</button>
                @if($editMode)
                    <a href="{{ route('organisasi.request.read') }}" class="btn btn-secondary ms-2">Batal</a>
                @endif
            </form>
        </div>
    </div>

    {{-- Tabel Daftar Request --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Request</th>
                <th>Organisasi</th>
                <th>Deskripsi</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dataRequest as $req)
                <tr>
                    <td>{{ $req->id_request_donasi }}</td>
                    <td>{{ $req->organisasi->nama_organisasi ?? '-' }}</td>
                    <td>{{ $req->deskripsi_request }}</td>
                    <td>{{ $req->tgl_request }}</td>
                    <td>{{ $req->status_request }}</td>
                    <td>
                        <a href="{{ route('organisasi.request.edit', $req->id_request_donasi) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('organisasi.request.delete', $req->id_request_donasi) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Yakin ingin menghapus request ini?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Data tidak ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
