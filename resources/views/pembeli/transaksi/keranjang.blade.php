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

  @if(session('keranjang') && count(session('keranjang')) > 0)
    <table>
      <thead>
        <tr>
          <th>Gambar</th>
          <th>Nama</th>
          <th>Harga</th>
          <th>Jumlah</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        @php $total = 0; @endphp
        @foreach(session('keranjang') as $id => $item)
            @php $subtotal = $item['harga'] * $item['jumlah']; $total += $subtotal; @endphp
            <tr>
            <td><img src="{{ asset('images/' . $item['gambar']) }}"></td>
            <td>{{ $item['nama'] }}</td>
            <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
            <td>{{ $item['jumlah'] }}</td>
            <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
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

      <form method="GET" action="{{ route('keranjang.checkout')}}">
        @csrf
        <button type="submit">Lanjutkan Pembayaran</button>
      </form>
  @else
    <p>Keranjang Anda kosong.</p>
  @endif
</body>
</html>
