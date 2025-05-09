<!DOCTYPE html>
<html>
<head>
    <title>Register Alamat</title>
</head>
<body>
    <h2>Form Register Alamat</h2>

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

    <form method="POST" action="{{ url('/register-alamat') }}">
        @csrf
        <div>
            <label>Pilih Pembeli:</label>
            <select name="id_pembeli" required>
                <option value="">-- Pilih Pembeli --</option>
                @foreach ($pembeliList as $pembeli)
                    <option value="{{ $pembeli->id_pembeli }}">{{ $pembeli->nama_pembeli }} ({{ $pembeli->id_pembeli }})</option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Nama Alamat:</label>
            <input type="text" name="nama_alamat" required>
        </div>

        <div>
            <label>Detail Alamat:</label>
            <textarea name="detail_alamat" required></textarea>
        </div>

        <div>
            <label>Tipe Alamat:</label>
            <input type="text" name="tipe_alamat" required>
        </div>

        <div>
            <label>Status Default:</label>
            <select name="status_default" required>
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
            </select>
        </div>

        <button type="submit">Register Alamat</button>
    </form>
</body>
</html>
