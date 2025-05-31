<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Beli Sekarang</title>
  <script src="https://unpkg.com/alpinejs@3.x.x" defer></script>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      background-color: #f9fafb;
    }

    .container {
      max-width: 600px;
      margin: 3rem auto;
      background-color: white;
      padding: 2rem;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    h1 {
      color: #111827;
      font-size: 1.5rem;
      margin-bottom: 1rem;
    }

    label {
      display: block;
      margin-top: 1rem;
      font-weight: 600;
      color: #374151;
    }

    input[type="number"], select {
      width: 100%;
      padding: 0.6rem;
      margin-top: 0.5rem;
      border: 1px solid #d1d5db;
      border-radius: 6px;
      box-sizing: border-box;
    }

    button {
      margin-top: 1.5rem;
      background-color: #10b981;
      color: white;
      border: none;
      padding: 0.75rem 1.5rem;
      border-radius: 6px;
      font-size: 1rem;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    button:hover {
      background-color: #059669;
    }

    .price {
      font-size: 1.2rem;
      margin-bottom: 1rem;
      color: #1f2937;
    }

    .shipping-options {
      margin-top: 1.5rem;
    }

    .alamat-box {
      background-color: #f3f4f6;
      padding: 1rem;
      border-radius: 6px;
      margin-top: 1rem;
      color: #1f2937;
    }

    .warning {
      color: red;
      font-size: 0.9rem;
    }

    .ongkir-box {
      margin-top: 1rem;
      padding: 0.75rem 1rem;
      background: #f1f5f9;
      border-radius: 8px;
      color: #333;
      font-size: 1.05rem;
    }
    .free-ongkir {
      color: #059669;
      font-weight: 600;
    }
    .paid-ongkir {
      color: #be123c;
      font-weight: 600;
    }
  </style>
</head>
<body>
  <div class="container"
       x-data="{
         metode: 'kurir',
         hargaBarang: {{ $barang->harga_barang }},
         jumlah: 1,
         poinMaks: {{ session('user')->poin }},
         tukarPoin: 0,
         get subtotal() { return this.hargaBarang * this.jumlah },
         get ongkir() {
           if(this.metode === 'kurir') {
             return this.subtotal >= 1500000 ? 0 : 100000;
           }
           return 0;
         },
         get maxDiskonPoin() { return Math.floor(this.poinMaks / 100) * 100; },
         get diskon() { return Math.floor(this.tukarPoin / 100) * 10000; },
         get totalBayar() {
           let total = this.subtotal + this.ongkir - this.diskon;
           return total > 0 ? total : 0;
         }
       }">
    <h1>Beli Sekarang: {{ $barang->nama_barang }}</h1>
    <p class="price">Harga: Rp {{ number_format($barang->harga_barang, 0, ',', '.') }}</p>

    

    <form action="{{ route('transaksi.proses') }}" method="POST" @submit="tukarPoin = Math.floor(tukarPoin/100)*100" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="id_barang" value="{{ $barang->id_barang }}">
      <input type="hidden" name="jumlah" value="1">
      <p><strong>Jumlah:</strong> 1</p>

      <div class="shipping-options">
        <label for="metode_pengiriman">Metode Pengiriman:</label>
        <select name="metode_pengiriman" id="metode_pengiriman" x-model="metode" required>
          <option value="kurir">Dikirim oleh Kurir</option>
          <option value="ambil">Ambil di Gudang</option>
        </select>
      </div>

      <!-- Ongkir Info -->
      <div class="ongkir-box" x-show="metode === 'kurir'">
        <template x-if="subtotal >= 1500000">
          <span class="free-ongkir">Ongkir Gratis karena pembelian &ge; Rp 1.500.000</span>
        </template>
        <template x-if="subtotal < 1500000">
          <span class="paid-ongkir">
            Ongkir: Rp <span x-text="ongkir.toLocaleString('id-ID')"></span>
            <small>(Gratis jika pembelian &ge; Rp 1.500.000)</small>
          </span>
        </template>
      </div>

      <template x-if="metode === 'kurir'">
        <div class="mt-4">
          <label>Pilih Alamat (Yogyakarta):</label>
          <select name="id_alamat" id="id_alamat" required>
            <option value="" disabled selected>-- Pilih Alamat --</option>
            @forelse ($alamatYogyaList as $alamat)
              <option value="{{ $alamat->id_alamat }}">
                {{ $alamat->nama_alamat }} — {{ $alamat->detail_alamat }}
              </option>
            @empty
              <option disabled>Belum ada alamat di Yogyakarta</option>
            @endforelse
          </select>
          @if($alamatYogyaList->isEmpty())
            <p class="warning">Kurir hanya tersedia untuk alamat di Yogyakarta.</p>
          @endif
        </div>
      </template>

      <template x-if="metode === 'ambil'">
        <p class="mt-3 text-gray-600">
          Kamu akan mengambil sendiri di gudang, jadi tidak perlu pilih alamat.
        </p>
      </template>

      <div style="margin-bottom:1rem;">
        <strong>Poin Anda:</strong> {{ session('user')->poin }} <br>
        <span style="font-size:0.95em;">(100 poin = Rp10.000 potongan)</span>
      </div>

      <!-- Penukaran poin -->
      <div class="mb-2" style="margin-top:1rem;">
        <label>Gunakan Poin (kelipatan 100):</label>
        <input type="number"
               min="0"
               step="100"
               :max="maxDiskonPoin"
               x-model.number="tukarPoin"
               name="tukar_poin"
               class="form-control"
               style="width: 180px; display: inline-block;">
        <span style="font-size:0.95em;">
          Maks: <span x-text="maxDiskonPoin"></span> poin
        </span>
        <div x-show="tukarPoin > poinMaks" style="color:red; font-size:0.9em;">
          Tidak boleh melebihi {{ session('user')->poin }} poin
        </div>
        <div x-show="tukarPoin % 100 !== 0 && tukarPoin !== 0" style="color:red; font-size:0.9em;">
          Penukaran hanya boleh kelipatan 100
        </div>
      </div>

      <!-- Diskon dari poin -->
      <div class="ongkir-box" x-show="diskon > 0">
        <span>Diskon dari poin: </span>
        <strong>Rp <span x-text="diskon.toLocaleString('id-ID')"></span></strong>
      </div>

      <div class="ongkir-box" style="margin-bottom:1rem; background:#e9fce9;">
        <span>Poin yang akan didapatkan: </span>
        <strong>
          <span x-text="
            (function(){
              let base = Math.floor(subtotal / 10000);
              if(subtotal > 500000) base += Math.floor(base * 0.2);
              return base;
            })()
          "></span> poin
        </strong>
      </div>


      <!-- Total bayar -->
      <div class="ongkir-box" style="margin-bottom:1rem;">
        <span>Total Bayar: </span>
        <strong>
          Rp <span x-text="totalBayar.toLocaleString('id-ID')"></span>
        </strong>
      </div>

      <button type="submit">Proses Pembelian</button>
    </form>
  </div>
</body>

</html>
