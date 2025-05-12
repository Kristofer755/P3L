<!DOCTYPE html>
<html>
<head>
    <title>Diskusi Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">

    {{-- Notifikasi sukses --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Notifikasi error --}}
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Form Buat Diskusi --}}
    <div class="card mb-4">
        <div class="card-header">Buat Diskusi Baru</div>
        <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

            <form action="{{ route('diskusi.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="id_barang">Pilih Barang</label>
                    <select name="id_barang" class="form-control" required>
                        <option value="" disabled selected>-- Pilih Barang --</option>
                        @foreach ($barang as $b)
                            <option value="{{ $b->id_barang }}">{{ $b->nama_barang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mt-2">
                    <label for="judul_diskusi">Judul Diskusi</label>
                    <input type="text" name="judul_diskusi" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success mt-3">Buat Diskusi</button>
            </form>
        </div>
    </div>

    {{-- Daftar Diskusi --}}
    <div class="card mb-4">
        <div class="card-header">Daftar Diskusi</div>
        <div class="card-body">
        @foreach ($diskusi as $d)
            <div class="border p-2 mb-2">
                <strong>{{ $d->judul_diskusi }}</strong><br>
                <small>Dibuat oleh:
                    @if ($d->pembeli)
                        {{ $d->pembeli->nama }}
                    @elseif ($d->pegawai)
                        {{ $d->pegawai->nama }} (CS)
                    @endif
                </small>
                <a href="{{ route('diskusi.show', $d->id_diskusi) }}" class="btn btn-sm btn-primary float-end">Lihat</a>
            </div>
        @endforeach
        </div>
    </div>

    {{-- Jika sedang melihat detail diskusi --}}
    @isset($selectedDiskusi)
        <div class="card">
            <div class="card-header">Diskusi: {{ $selectedDiskusi->judul_diskusi }}</div>
            <div class="card-body">
                <div class="mb-4">
                    <h5>Pesan</h5>
                    @foreach ($selectedDiskusi->pesan as $pesan)
                        <div class="border p-2 my-2">
                            <strong>{{ ucfirst($pesan->tipe_sender) }} #{{ $pesan->id_sender }}:</strong>
                            <p>{{ $pesan->pesan }}</p>
                        </div>
                    @endforeach
                </div>

                <form action="{{ route('diskusi.kirim') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_diskusi" value="{{ $selectedDiskusi->id_diskusi }}">
                    <div class="form-group">
                        <label for="pesan">Tulis Pesan</label>
                        <textarea name="pesan" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Kirim Pesan</button>
                </form>
            </div>
        </div>
    @endisset

</div>
</body>
</html>
