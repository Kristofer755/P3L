<div>
    <strong>ReUse Mart</strong><br>
    Jl. Green Eco Park No. 456 Yogyakarta
</div>
<br>
<div style="text-decoration: underline; font-weight: bold;">
    LAPORAN TRANSAKSI PENITIP
</div>
ID Penitip: {{ $id_penitip }}<br>
Nama Penitip: {{ $nama_penitip }}<br>
Bulan: {{ $bulan }}<br>
Tahun: {{ $tahun }}<br>
Tanggal cetak: {{ date('d-m-Y') }}<br>

@php
    // Hitung total kolom
    $totalHarga      = collect($laporan)->sum('harga_jual_bersih');
    $totalBonus      = collect($laporan)->sum('bonus_penitip');
    $totalPendapatan = collect($laporan)->sum('pendapatan');
@endphp

<table border="1" cellspacing="0" cellpadding="5" width="100%">
    <thead>
        <tr>
            <th>Kode Produk</th>
            <th>Nama Produk</th>
            <th>Tanggal Masuk</th>
            <th>Tanggal Laku</th>
            <th>Harga Jual Bersih<br/>(sudah dipotong komisi)</th>
            <th>Bonus Terjual Cepat</th>
            <th>Pendapatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($laporan as $row)
        <tr>
            <td>{{ $row->kode_produk }}</td>
            <td>{{ $row->nama_barang }}</td>
            <td>{{ \Carbon\Carbon::parse($row->tgl_masuk)->format('j/n/Y') }}</td>
            <td>
                    {{ $row->tanggal_laku
                        ? \Carbon\Carbon::parse($row->tanggal_laku)->format('j/n/Y')
                        : '-' }}
                </td>
            <td style="text-align: right">
                {{ number_format($row->harga_jual_bersih, 0, ',', '.') }}
            </td>
            <td style="text-align: right">
                {{ number_format($row->bonus_penitip ?? 0, 0, ',', '.') }}
            </td>
            <td style="text-align: right">
                {{ number_format($row->pendapatan ?? 0, 0, ',', '.') }}
            </td>
        </tr>
        @endforeach

        {{-- Baris TOTAL --}}
        <tr>
            <th colspan="4" style="text-align: right;">TOTAL</th>
            <th style="text-align: right">
                {{ number_format($totalHarga, 0, ',', '.') }}
            </th>
            <th style="text-align: right">
                {{ number_format($totalBonus, 0, ',', '.') }}
            </th>
            <th style="text-align: right">
                {{ number_format($totalPendapatan, 0, ',', '.') }}
            </th>
        </tr>
    </tbody>
</table>
