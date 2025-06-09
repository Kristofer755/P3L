<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>LAPORAN REQUEST DONASI</title>
    <style>
        body { font-size: 12px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #222; padding: 6px 8px; font-size: 12px; }
        th { background: #f4f4f4; }
        h4 { margin-bottom: 5px; }
        .kop { margin-bottom: 0.7em; }
    </style>
</head>
<body>
    <div class="kop">
        <strong>ReUse Mart</strong><br>
        Jl. Green Eco Park No. 456 Yogyakarta
    </div>
    <h4 style="margin-bottom: 2px; margin-top:10px;"><u>LAPORAN REQUEST DONASI</u></h4>
    <div style="margin-bottom: 10px;">Tanggal cetak: {{ $tanggalCetak }}</div>

    <table>
        <thead>
            <tr>
                <th>ID Organisasi</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Request</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requests as $req)
                <tr>
                    <td>{{ $req->organisasi->id_organisasi ?? '-' }}</td>
                    <td>{{ $req->organisasi->nama_organisasi ?? '-' }}</td>
                    <td>{{ $req->organisasi->alamat_organisasi ?? '-' }}</td>
                    <td>{{ $req->deskripsi_request }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
