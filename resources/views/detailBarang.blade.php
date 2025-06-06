<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Detail Barang - {{ $barang->nama_barang }}</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 2rem;
      background-color: #f3f4f6;
    }

    .container {
      max-width: 1000px;
      margin: auto;
      background: white;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .produk-wrapper {
      display: flex;
      flex-wrap: wrap;
      gap: 2rem;
    }

    .produk-img {
      flex: 1 1 300px;
      max-width: 300px;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .produk-info {
      flex: 2 1 500px;
    }

    .produk-info h1 {
      margin-top: 0;
      color: #2c3e50;
    }

    .produk-info p {
      margin: 0.5rem 0;
      font-size: 1rem;
      color: #444;
    }

    .produk-info strong {
      color: #333;
    }

    .diskusi {
      margin-top: 3rem;
    }

    .diskusi h2 {
      border-bottom: 2px solid #ccc;
      padding-bottom: 0.5rem;
      color: #2c3e50;
    }

    .diskusi-item {
      border-bottom: 1px solid #ddd;
      padding: 1rem 0;
    }

    .diskusi-item strong {
      color: #4b0082;
    }

    .diskusi-item small {
      color: #888;
      font-size: 0.9rem;
    }

    .form-diskusi {
      margin-top: 1.5rem;
    }

    .form-diskusi textarea {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid #ccc;
      border-radius: 6px;
      resize: vertical;
    }

    .form-diskusi button {
      background-color: #4b0082;
      color: white;
      padding: 0.6rem 1.2rem;
      border: none;
      border-radius: 6px;
      margin-top: 0.5rem;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .form-diskusi button:hover {
      background-color: #360061;
    }

    .login-reminder {
      margin-top: 1rem;
      font-style: italic;
      color: #555;
    }

    .pagination {
      margin-top: 1rem;
      text-align: center;
    }

    .pagination svg {
      height: 20px;
    }

    @media (max-width: 768px) {
      .produk-wrapper {
        flex-direction: column;
        align-items: center;
      }

      .produk-info {
        text-align: center;
      }
    }

    .btn-beli {
      padding: 0.6rem 1.2rem;
      background-color: #38a169;     
      color: #ffffff;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    .btn-beli:hover {
      background-color: #2f855a;   
    }

    .btn-keranjang {
      padding: 0.6rem 1.2rem;
      background-color: #5a67d8;     
      color: #ffffff;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    .btn-keranjang:hover {
      background-color: #434190;    
    }

  </style>
</head>
<body>
  <div style="position: absolute; top: 20px; right: 20px;">
    <a href="{{ route('keranjang.index') }}" style="position: relative; text-decoration: none;">
      🛒
      @if(session('keranjang') && count(session('keranjang')) > 0)
        <span style="position: absolute; top: -10px; right: -10px; background-color: red; color: white; font-size: 12px; padding: 2px 6px; border-radius: 50%;">
          {{ count(session('keranjang')) }}
        </span>
      @endif
    </a>
  </div>

  <div class="container">
    <div class="produk-wrapper">
      <img class="produk-img" src="{{ asset('images/' . $barang->gambar_barang) }}" alt="{{ $barang->nama_barang }}">
      <div class="produk-info">
        <h1>{{ $barang->nama_barang }}</h1>
        <p><strong>Harga:</strong> Rp {{ number_format($barang->harga_barang, 0, ',', '.') }}</p>
        <p><strong>Status Garansi:</strong> {{ $barang->status_garansi }}</p>
        <p><strong>Deskripsi:</strong><br>{{ $barang->deskripsi_barang }}</p>

  @php
    $user = session('user');
  @endphp

    <div style="margin-top: 1.5rem;">
    @if($user && isset($user->id_pembeli))
    <form action="{{ route('pembeli.beli', $barang->id_barang) }}" method="get" style="display:inline-block;">
      <button type="submit" class="btn-beli">Beli Sekarang</button>
    </form>

    <form action="{{ route('keranjang.tambah', $barang->id_barang) }}" method="POST" style="display:inline-block;">
      @csrf
      <button type="submit" class="btn-keranjang">Masukkan ke Keranjang</button>
    </form>
    @else
    <button
      type="button"
      onclick="alert('Silakan login terlebih dahulu untuk melakukan pembelian.');
        window.location.href='{{ route('login') }}';"
      class="btn-beli">Beli Sekarang
    </button>

    <button type="button"
      onclick="alert('Silakan login terlebih dahulu untuk menambah ke keranjang.')
        window.location.href='{{ route('login') }}';"
      class="btn-keranjang">Masukkan ke Keranjang
    </button>
    @endif
    </div>
  </div>

    
    <div class="diskusi">
      <h2>Diskusi Produk</h2>

      {{-- Notifikasi sukses / error --}}
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      {{-- Daftar Diskusi --}}
      @foreach ($diskusi as $item)
        <div class="diskusi-item {{ $item->tipe_sender == 'pembeli' ? 'diskusi-pembeli' : 'diskusi-admin' }}">
          <div class="d-flex justify-content-between">
            <strong>{{ $item->user->name ?? $item->nama_pengirim ?? 'Pengguna' }}</strong>
            <small>
              {{ \Carbon\Carbon::parse($item->tanggal_diskusi ?? $item->tgl_diskusi)
                    ->translatedFormat('d F Y, H:i') }}
            </small>
          </div>
          <p>{{ $item->pesan }}</p>
        </div>
      @endforeach

      {{-- Pagination --}}
      <div class="pagination">
        {{ $diskusi->links() }}
      </div>

      {{-- Form Tulis / Balas Diskusi --}}
      <div class="form-diskusi mt-4">
        <form method="POST" action="{{ route('diskusi.store', $barang->id_barang) }}">
          @csrf
          <input type="hidden" name="id_barang" value="{{ $barang->id_barang }}">
          <label for="pesan">Tulis Komentar:</label>
          <textarea
            name="pesan"
            id="pesan"
            rows="3"
            class="form-control mb-2"
            required
          ></textarea>

          @if(!$user || !isset($user->id_pembeli))
            <button
              type="button" class="btn btn-secondary"
              onclick="alert('Silakan login terlebih dahulu untuk mengirim komentar.');
                      window.location.href='{{ route('login') }}';"
            >Kirim
            </button>
          @else
            <button type="submit" class="btn btn-primary">Kirim</button>
          @endif
        </form>
    </div>
</div>
  </div>
</body>
</html>
