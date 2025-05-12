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

    {{-- Form Kirim Pesan --}}
    <div class="card mb-4">
        <div class="card-header">Kirim Pesan</div>
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
                    <label for="pesan">Pesan</label>
                    <textarea name="pesan" class="form-control" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-success mt-3">Kirim Pesan</button>
            </form>
        </div>
    </div>

    {{-- Daftar Diskusi Berdasarkan Barang --}}
    @foreach ($barangDenganDiskusi as $barang)
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            Diskusi Barang: {{ $barang->nama_barang }}
        </div>
        <div class="card-body">
            <div class="mb-3">
                <h5>Pesan-pesan:</h5>
                @foreach ($barang->diskusi as $diskusi)
                    <div class="border p-2 mb-2 {{ $diskusi->tipe_sender == 'pembeli' ? 'border-primary' : 'border-success' }}">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $diskusi->nama_pengirim }}</strong>
                            <small>{{ date('d/m/Y', strtotime($diskusi->tgl_diskusi)) }}</small>
                        </div>
                        <p class="mb-0 mt-2">{{ $diskusi->pesan }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Form Balas Pesan --}}
            <form action="{{ route('diskusi.kirim') }}" method="POST">
                @csrf
                <input type="hidden" name="id_diskusi" value="{{ $barang->diskusi->last()->id_diskusi }}">
                <div class="form-group">
                    <label for="pesan">Balas Pesan</label>
                    <textarea name="pesan" class="form-control" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Kirim Balasan</button>
            </form>
        </div>
    </div>
    @endforeach

    {{-- Pesan jika tidak ada diskusi --}}
    @if ($barangDenganDiskusi->isEmpty())
        <div class="alert alert-info">
            Belum ada diskusi. Silakan mulai diskusi dengan mengirim pesan pada formulir di atas.
        </div>
    @endif

</div>
</body>
</html>