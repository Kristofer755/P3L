<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Proses Keranjang</title>
  <script src="https://unpkg.com/alpinejs@3.x.x" defer></script>
  <style>
    body { 
        font-family:'Segoe UI',sans-serif; 
        margin:0; 
        background:#f9fafb; 
        padding:2rem; 
    }

    .container { 
        max-width:800px; 
        margin:auto; 
        background:#fff; 
        padding:2rem; 
        border-radius:8px; 
        box-shadow:0 4px 12px rgba(0,0,0,0.1); 
    }

    h1 { 
        font-size:1.75rem; 
        color:#111827; 
        margin-bottom:1.5rem; 
    }

    table { width:100%; 
        border-collapse:collapse; 
        margin-bottom:1.5rem; }

    th,td { 
        padding:.75rem; 
        border:1px solid #d1d5db; 
        text-align:center; 
        color:#374151; 
    }

    th { 
        background:#f3f4f6; 
    }

    img { 
        width:60px; 
        border-radius:4px; 
    }
    .price { 
        text-align:right; 
        font-size:1.25rem; 
        color:#1f2937; 
        margin-bottom:1.5rem; 
    }
    
    label { 
        display:block; 
        font-weight:600; 
        margin-bottom:.5rem; 
        color:#374151; 
    }

    select { 
        width:100%; 
        padding:.6rem; 
        border:1px solid #d1d5db; 
        border-radius:6px; 
        margin-bottom:1rem; 
        box-sizing:border-box; 
    }

    button { 
        background:#10b981; 
        color:#fff; 
        border:none; 
        padding:.75rem 1.5rem; 
        border-radius:6px; 
        cursor:pointer; 
        transition:background .3s; }
    button:hover { 
        background:#059669; 
    }

    .warning {
      color: red;
      font-size: 0.9rem;
    }
  </style>
</head>
<body>
  <div class="container" x-data="{ metode: 'kurir' }">
    <h1>Beli Sekarang: {{ $barang->nama_barang }}</h1>
    <p class="price">Harga: Rp {{ number_format($barang->harga_barang, 0, ',', '.') }}</p>

    <form action="{{ route('transaksi.proses') }}" method="POST">
      @csrf
      {{-- Kirim ID barang dan jumlah (selalu 1) --}}
      <input type="hidden" name="id_barang" value="{{ $barang->id_barang }}">
      <input type="hidden" name="jumlah" value="1">

      {{-- Metode Pengiriman --}}
      <div class="shipping-options">
        <label for="metode_pengiriman">Metode Pengiriman:</label>
        <select name="metode_pengiriman" id="metode_pengiriman" x-model="metode" required>
          <option value="kurir">Dikirim oleh Kurir</option>
          <option value="ambil">Ambil di Gudang</option>
        </select>
      </div>

      {{-- Jika kurir, pilih alamat --}}
      <template x-if="metode === 'kurir'">
        <div class="alamat-box">
          <label for="alamat_pengiriman">Pilih Alamat Pengiriman:</label>
          <select name="alamat_pengiriman" id="alamat_pengiriman" required>
            @forelse ($alamatList as $alamat)
              <option value="{{ $alamat->detail_alamat }}">
                {{ $alamat->nama_alamat }} — {{ $alamat->detail_alamat }}
              </option>
            @empty
              <option disabled>Anda belum memiliki alamat</option>
            @endforelse
          </select>
          @if($alamatList->isEmpty())
            <p class="warning">Silakan tambahkan alamat di profil Anda terlebih dahulu.</p>
          @endif
        </div>
      </template>

      <button type="submit">Proses Pembelian</button>
    </form>
  </div>
</body>
</html>
