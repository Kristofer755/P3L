<div>
    <strong>ReUse Mart</strong><br>
    Jl. Green Eco Park No. 456 Yogyakarta
</div>
<br>
<div style="text-decoration: underline; font-weight: bold;">
    LAPORAN DONASI
</div>
<div>Tahun : {{ date('Y') }}</div>
<div>Tanggal cetak: {{ date('d F Y') }}</div>
<br>
<table border="1" cellpadding="6" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Kode Produk</th>
            <th>Nama Produk</th>
            <th>Id Penitip</th>
            <th>Nama Penitip</th>
            <th>Tanggal Donasi</th>
            <th>Organisasi</th>
            <th>Nama Penerima</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($laporan as $row)
        <tr>
            <td>{{ $row->kode_produk }}</td>
            <td>{{ $row->nama_produk }}</td>
            <td>{{ $row->id_penitip }}</td>
            <td>{{ $row->nama_penitip }}</td>
            <td>{{ \Carbon\Carbon::parse($row->tgl_donasi)->format('d/m/Y') }}</td>
            <td>{{ $row->organisasi }}</td>
            <td>{{ $row->nama_penerima }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
