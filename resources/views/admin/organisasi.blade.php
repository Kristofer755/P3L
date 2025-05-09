<!DOCTYPE html>
<html>
<head>
    <title>Kelola Data Organisasi</title>
</head>
<body>
    <h1>Selamat Datang di Dashboard Admin</h1>

    <h2>Kelola Data Organisasi</h2>

    <!-- Form Search -->
    <form method="GET" action="{{ route('admin.organisasi.search') }}">

    <form method="GET" action="{{ route('dashboard.admin.search') }}">
        <input type="text" name="search" placeholder="Cari nama organisasi">
        <button type="submit">Cari</button>
    </form>

    <!-- Show message if no data -->
    @if(isset($organisasi) && count($organisasi) > 0)
        <!-- Tabel Read -->
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>No Telp</th>
                    <th>Alamat</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($organisasi as $org)
                <tr>
                    <td>{{ $org->id_organisasi }}</td>
                    <td>{{ $org->nama_organisasi }}</td>
                    <td>{{ $org->no_telp_organisasi }}</td>
                    <td>{{ $org->alamat_organisasi }}</td>
                    <td>{{ $org->email }}</td>
                    <td>{{ $org->password }}</td>
                    <td>{{ $org->status_organisasi }}</td>
                    <td>
                        <!-- Tombol Update -->
                        <a href="{{ route('admin.organisasi.edit', $org->id_organisasi) }}">Edit</a>

                        
                        <!-- Tombol Delete -->
                        <form action="{{ route('admin.organisasi.delete', $org->id_organisasi) }}" method="POST" style="display:inline;">

                        <a href="{{ route('dashboard.admin.edit', $org->id_organisasi) }}">Edit</a>
                        
                        <!-- Tombol Delete -->
                        <form action="{{ route('dashboard.admin.delete', $org->id_organisasi) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin hapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Tidak ada data organisasi yang tersedia.</p>
    @endif

</body>

</html>

</html>
