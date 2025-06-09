<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Penjualan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid black; padding: 5px; text-align: center; }
        .footer { margin-top: 20px; }
    </style>
</head>
<body>

<div class="header">
    <h3>ReUse Mart</h3>
    <p>Jl. Green Eco Park No. 456 Yogyakarta</p>
</div>

@php
    use Carbon\Carbon;

    // Format No Nota: Tahun.Bulan.NomorUrut
    $noNota = Carbon::now()->format('Y') . '.' . Carbon::now()->format('m') . '.' . $transaksi->id_transaksi_pembelian;

    // Perhitungan Poin
    $totalBelanja = $transaksi->total_pembayaran; // Sudah dipotong potongan poin
    $potonganPoin = $transaksi->tukar_poin ?? 0;

    $hargaSebelumPotong = $totalBelanja + $potonganPoin;
    $ongkosKirim = 0; // Default 0
    $hargaSetelahOngkir = $hargaSebelumPotong - $ongkosKirim;

    $poinBase = floor($hargaSetelahOngkir / 10000);
    $bonusPoin = 0;
    if ($hargaSetelahOngkir > 500000) {
        $bonusPoin = floor($poinBase * 0.2);
    }
    $totalPoinOrder = $poinBase + $bonusPoin;
    $totalPoinCustomer = ($pembeli->poin ?? 0) + $totalPoinOrder;

    // Format tanggal
    $tglPesan = Carbon::parse($transaksi->tgl_transaksi)->format('d/m/Y H:i');
    $tglLunas = Carbon::parse($transaksi->tgl_transaksi)->format('d/m/Y H:i');
    $tglKirim = Carbon::parse($transaksi->tgl_transaksi)->addDays(1)->format('d/m/Y');
@endphp

<p><strong>No Nota:</strong> {{ $noNota }}</p>
<p><strong>Tanggal Pesan:</strong> {{ $tglPesan }}</p>
<p><strong>Lunas Pada:</strong> {{ $tglLunas }}</p>
<p><strong>Tanggal Kirim:</strong> {{ $tglKirim }}</p>

<br>

<p><strong>Pembeli:</strong> {{ $pembeli->email }} / {{ $pembeli->nama_pembeli }} <br>
{{ $alamat->nama_alamat }}, {{ $alamat->detail_alamat}}, {{ $alamat->kota }}, {{ $alamat->provinsi }}</p>
<p><strong>Delivery:</strong> {{ $pengiriman->tipe_pengiriman == 'diantar' ? 'Kurir ReUseMart ('.$kurir->nama_pegawai.')' : 'Ambil Sendiri' }}</p>

<br>

<table class="table">
    <thead>
        <tr>
            <th>Nama Barang</th>
            <th>Jumlah</th>
            <th>Harga</th>
        </tr>
    </thead>
    <tbody>
        @foreach($barangList as $item)
        <tr>
            <td>{{ $item->nama_barang }}</td>
            <td>{{ $item->jml_barang_pembelian }}</td>
            <td>Rp{{ number_format($item->harga_satuan_pembelian * $item->jml_barang_pembelian, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<br>

<p><strong>Total:</strong> Rp{{ number_format($hargaSetelahOngkir, 0, ',', '.') }}</p>
<p><strong>Ongkos Kirim:</strong> Rp{{ number_format($ongkosKirim, 0, ',', '.') }}</p>
<p><strong>Total:</strong> Rp{{ number_format($hargaSetelahOngkir, 0, ',', '.') }}</p>
<p><strong>Potongan {{ $transaksi->tukar_poin }} Poin:</strong> - Rp{{ number_format($potonganPoin, 0, ',', '.') }}</p>
<p><strong>Total:</strong> Rp{{ number_format($totalBelanja, 0, ',', '.') }}</p>

<br>

<p><strong>Poin dari pesanan ini:</strong> {{ $totalPoinOrder }} poin</p>
<p><strong>Total Poin Customer:</strong> {{ $totalPoinCustomer }} poin</p>

<br><br>

<br><br>

<p>Diterima oleh:</p>
<br><br><br>
<p>______________________________</p>
<p>Tanggal: ____________________</p>

</body>
</html>
