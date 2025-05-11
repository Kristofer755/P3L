<!DOCTYPE html>
<html>
<head>
    <title>Edit Penitip</title>
</head>
<body>
    <h1>Edit Penitip</h1>
    
    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('cs.penitip.update', $penitip->id_penitip) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div>
            <label>Nama Penitip:</label>
            <input type="text" name="nama_penitip" value="{{ $penitip->nama_penitip }}" required>
        </div>
        <div>
            <label>Email:</label>
            <input type="email" name="email" value="{{ $penitip->email }}" required>
        </div>
        <div>
            <label>NIK:</label>
            <input type="text" name="NIK" value="{{ $penitip->NIK }}" required>
        </div>
        <div>
            <label>No Telepon:</label>
            <input type="text" name="no_telp" value="{{ $penitip->no_telp }}" required>
        </div>
        <div>
            <label>Saldo:</label>
            <input type="number" name="saldo" value="{{ $penitip->saldo }}" required>
        </div>

        <div>
            <label>Foto KTP:</label>
            <input type="file" name="foto_ktp">
            @if($penitip->foto_ktp)
                <br>
                <img src="{{ asset('storage/' . $penitip->foto_ktp) }}" width="100">
            @endif
        </div>


        <button type="submit">Update Penitip</button>
    </form>
</body>
</html>
