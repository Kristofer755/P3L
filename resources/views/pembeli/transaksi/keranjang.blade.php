<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Keranjang Belanja</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 2rem;
      background-color: #f3f4f6;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background-color: white;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    th, td {
      padding: 12px;
      text-align: center;
      border: 1px solid #ddd;
    }
    img {
      width: 60px;
    }
    button {
      padding: 8px 16px;
      background-color: #10b981;
      border: none;
      color: white;
      border-radius: 6px;
      cursor: pointer;
    }
    .badge {
      display: inline-block;
      background: #ef4444;
      color: white;
      border-radius: 8px;
      padding: 3px 10px;
      font-size: 0.9em;
      margin-top: 6px;
    }
    .disabled {
      background: #d1d5db !important;
      color: #6b7280 !important;
      cursor: not-allowed !important;
    }
  </style>
</head>
<body>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
  <div class="alert alert-danger">{{ session('error') }}</div>
@endif

  <h1>Keranjang Belanja</h1>

  @if(count($detailBarang) > 0)
    <table>
      <thead>
        <tr>
          <th>Gambar</th>
          <th>Nama</th>
          <th>Harga</th>
          <th>Jumlah</th>
          <th>Subtotal</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @php 
            $total = 0; 
            $adaYangTidakTersedia = false;
        @endphp
        @foreach($detailBarang as $id => $item)
          @php
            $subtotal = $item['data']['harga'] * $item['data']['jumlah'];
            $total += $subtotal;
            if(!$item['status']) $adaYangTidakTersedia = true;
          @endphp
          <tr>
            <td><img src="{{ asset('images/' . $item['data']['gambar']) }}"></td>
            <td>{{ $item['data']['nama'] }}</td>
            <td>Rp {{ number_format($item['data']['harga'], 0, ',', '.') }}</td>
            <td>{{ $item['data']['jumlah'] }}</td>
            <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
            <td>
                @if($item['status'])
                  <span style="color: #10b981; font-weight:bold;">Tersedia</span>
                @else
                  <span class="badge">Tidak bisa dibeli</span>
                  @if($item['alasan'])
                    <div style="color: #ef4444; font-size: 0.9em;">{{ $item['alasan'] }}</div>
                  @endif
                @endif
            </td>
            <td>
                <form action="{{ route('keranjang.hapus', $id) }}" method="POST" onsubmit="return confirm('Hapus barang ini dari keranjang?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" style="background-color: #ef4444; color: white; padding: 4px 10px; border: none; border-radius: 4px;">
                    Hapus
                  </button>
                </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <p><strong>Total: Rp {{ number_format($total, 0, ',', '.') }}</strong></p>

    <form method="GET" action="{{ route('keranjang.checkout') }}">
      @csrf
      <button type="submit" 
        @if($adaYangTidakTersedia) 
            class="disabled" disabled 
            title="Ada barang yang tidak bisa dibeli"
        @endif
      >Lanjutkan Pembayaran</button>
    </form>

    @if($adaYangTidakTersedia)
      <p style="color: #ef4444; margin-top: 12px;">
        Silakan hapus dari keranjang sebelum checkout.
      </p>
    @endif

  @else
    <p>Keranjang Anda kosong.</p>
  @endif
</body>
</html>
