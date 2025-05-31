<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Checkout Keranjang</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Pastikan skrip AlpineJS -->
  <script src="https://unpkg.com/alpinejs@3.x.x" defer></script>
  <script>
    window.keranjangItems = [
    @foreach($keranjang as $id => $item)
      { 
        nama: @json($barangList[$id]->nama_barang),
        harga: {{ $barangList[$id]->harga_barang }},
        jumlah: {{ $item['jumlah'] }}
      },
    @endforeach
    ];
  </script>
</head>
<body class="bg-light">
<div class="container py-5"
  x-data="{
    metode: 'kurir',
    poinMaks: {{ session('user')->poin }},
    tukarPoin: 0,
    items: window.keranjangItems,
    get subtotal() { return this.items.reduce((tot, x) => tot + (x.harga * x.jumlah), 0); },
    get ongkir() { if (this.metode === 'kurir') { return this.subtotal >= 1500000 ? 0 : 100000; } return 0; },
    get maxDiskonPoin() { return Math.floor(this.poinMaks / 100) * 100; },
    get diskon() { return Math.floor(this.tukarPoin / 100) * 10000; },
    get totalBayar() { let total = this.subtotal + this.ongkir - this.diskon; return total > 0 ? total : 0; },
    get reward() { let base = Math.floor(this.subtotal / 10000); if (this.subtotal > 500000) base += Math.floor(base * 0.2); return base; }
  }"
>
  <h1 class="mb-4">Checkout Keranjang</h1>

  {{-- Tabel item --}}
  <table class="table table-striped shadow mb-4 rounded" style="overflow: hidden;">
    <thead>
      <tr>
        <th>Produk</th>
        <th>Harga</th>
        <th>Jumlah</th>
        <th>Subtotal</th>
      </tr>
    </thead>
    <tbody>
      <template x-for="item in items" :key="item.nama">
        <tr>
          <td x-text="item.nama"></td>
          <td x-text="'Rp ' + (item.harga).toLocaleString('id-ID')"></td>
          <td x-text="item.jumlah"></td>
          <td x-text="'Rp ' + (item.harga * item.jumlah).toLocaleString('id-ID')"></td>
        </tr>
      </template>
      <tr>
        <th colspan="3" class="text-end">Total</th>
        <th x-text="'Rp ' + subtotal.toLocaleString('id-ID')"></th>
      </tr>
    </tbody>
  </table>

  <form action="{{ route('transaksi.prosesKeranjang') }}" method="POST" class="form-section" @submit="tukarPoin = Math.floor(tukarPoin/100)*100">
    @csrf

    <div class="mb-3">
      <label class="form-label">Metode Pengiriman</label>
      <select name="metode_pengiriman" x-model="metode" class="form-select" required>
        <option value="kurir">Dikirim oleh Kurir</option>
        <option value="ambil">Ambil di Gudang</option>
      </select>
    </div>

    <div x-show="metode === 'kurir'">
      <label class="form-label">Pilih Alamat (Yogyakarta)</label>
      <select name="alamat_pengiriman" class="form-select" required>
        <option value="" disabled selected>-- Pilih Alamat --</option>
        @forelse($alamatYogyaList as $alamat)
          <option value="{{ $alamat->id_alamat }}">
            {{ $alamat->nama_alamat }} — {{ $alamat->detail_alamat }}
          </option>
        @empty
          <option disabled>Belum ada alamat di Yogyakarta</option>
        @endforelse
      </select>
    </div>

    <div x-show="metode === 'ambil'">
      <p class="text-muted">Kamu akan mengambil sendiri di gudang.</p>
    </div>
    <hr class="my-3" />

    {{-- Info Ongkir --}}
    <div x-show="metode === 'kurir'">
      <template x-if="subtotal >= 1500000">
        <div class="alert alert-success py-2">
          Ongkir Gratis karena pembelian &ge; Rp 1.500.000
        </div>
      </template>
      <template x-if="subtotal < 1500000">
        <div class="alert alert-warning py-2">
          Ongkir: Rp <span x-text="ongkir.toLocaleString('id-ID')"></span>
          <small>(Gratis jika pembelian &ge; Rp 1.500.000)</small>
        </div>
      </template>
    </div>

    {{-- Penukaran poin --}}
    <div class="mb-2 mt-2">
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

    <div class="alert alert-info mb-2" x-show="diskon > 0">
      Diskon dari poin: <strong>Rp <span x-text="diskon.toLocaleString('id-ID')"></span></strong>
    </div>

    <div class="alert alert-success mb-2" style="background:#e9fce9;">
      Poin yang akan didapatkan:
      <strong>
        <span x-text="reward"></span> poin
      </strong>
    </div>

    <div class="alert alert-primary mb-3">
      Total Bayar:
      <strong>Rp <span x-text="totalBayar.toLocaleString('id-ID')"></span></strong>
    </div>

    <button type="submit" class="btn btn-success btn-lg w-100">
      Proses Pembayaran
    </button>
  </form>
</div>
</body>
</html>
