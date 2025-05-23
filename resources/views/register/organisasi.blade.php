<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Organisasi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light p-5">

    <div class="container">
        <h2 class="text-center mb-4">Form Registrasi Organisasi</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('organisasi.store') }}">
            @csrf
            <div class="mb-3">
                <label for="nama_organisasi" class="form-label">Nama Organisasi</label>
                <input type="text" name="nama_organisasi" id="nama_organisasi" class="form-control" value="{{ old('nama_organisasi') }}" required>
            </div>

            <div class="mb-3">
                <label for="no_telp_organisasi" class="form-label">No Telepon Organisasi</label>
                <input type="text" name="no_telp_organisasi" id="no_telp_organisasi" class="form-control" value="{{ old('no_telp_organisasi') }}" required>
            </div>

            <div class="mb-3">
                <label for="alamat_organisasi" class="form-label">Alamat Organisasi</label>
                <input type="text" name="alamat_organisasi" id="alamat_organisasi" class="form-control" value="{{ old('alamat_organisasi') }}" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Organisasi</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password Organisasi</label>
                <input type="password" name="password" id="password" class="form-control" value="{{ old('password') }}" required>
            </div>

            <div class="mb-3">
                <label for="status_organisasi" class="form-label">Status Organisasi</label>
                <select name="status_organisasi" id="status_organisasi" class="form-select" required>
                    <option value="aktif" {{ old('status_organisasi') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status_organisasi') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div class="mb-3 text-center">
                <button type="submit" class="btn btn-primary">Daftar Organisasi</button>
            </div>
        </form>
    </div>

</body>
</html>