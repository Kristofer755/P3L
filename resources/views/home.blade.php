<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>ReuseMart - Solusi Barang Bekas Berkualitas</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <script src="https://kit.fontawesome.com/a2d9d5e0c3.js" crossorigin="anonymous"></script>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      background: #f8f9fa;
      color: #333;
    }

    header {
      background: linear-gradient(to right, #4b0082, #6a0dad);
      color: white;
      padding: 4rem 2rem;
      text-align: center;
      position: relative;
    }

    header h1 {
      font-size: 3rem;
      margin-bottom: 1rem;
    }

    header p {
      font-size: 1.2rem;
      max-width: 600px;
      margin: 0 auto 2rem;
    }

    header a {
      background: #fff;
      color: #4b0082;
      padding: 0.8rem 2rem;
      border-radius: 30px;
      font-weight: bold;
      text-decoration: none;
      transition: 0.3s;
    }

    header a:hover {
      background: #e0e0e0;
    }

    .features {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 2rem;
      padding: 4rem 2rem;
      background: #fff;
    }

    .feature-card {
      background: #f1f1f1;
      border-radius: 12px;
      padding: 2rem;
      text-align: center;
      width: 300px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
      transition: 0.3s;
    }

    .feature-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    }

    .feature-card i {
      font-size: 2.5rem;
      color: #6a0dad;
      margin-bottom: 1rem;
    }

    .feature-card h3 {
      margin-bottom: 1rem;
      font-size: 1.2rem;
    }

    .about {
      max-width: 900px;
      margin: 3rem auto;
      padding: 2rem;
      background: #ffffff;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    .about h2 {
      color: #4b0082;
      margin-bottom: 1rem;
    }

    .about p {
      line-height: 1.7;
      text-align: justify;
    }

    footer {
      background: #333;
      color: #ccc;
      text-align: center;
      padding: 1rem;
      margin-top: 2rem;
    }
  </style>
</head>
<body>

  <header>
    <h1>Selamat Datang di ReuseMart</h1>
    <p>Mari wujudkan lingkungan yang lebih bersih melalui pembelian barang bekas berkualitas.</p>
    <a href="{{ route('produk') }}">Jelajahi Produk</a>
  </header>

  <section class="features">
    <div class="feature-card">
      <i class="fas fa-recycle"></i>
      <h3>Ekonomi Sirkular</h3>
      <p>Mendaur ulang barang bekas yang masih layak guna demi lingkungan yang berkelanjutan.</p>
    </div>
    <div class="feature-card">
      <i class="fas fa-warehouse"></i>
      <h3>Layanan Penitipan</h3>
      <p>Tak sempat jual barang sendiri? Titipkan ke ReuseMart dan kami bantu menjualkannya!</p>
    </div>
    <div class="feature-card">
      <i class="fas fa-tags"></i>
      <h3>Harga Terjangkau</h3>
      <p>Temukan barang berkualitas dengan harga ramah kantong, langsung dari pengguna lain.</p>
    </div>
  </section>

  <section class="about">
    <h2>Tentang ReuseMart</h2>
    <p><strong>ReuseMart</strong> adalah perusahaan berbasis di Yogyakarta yang berfokus pada penjualan barang bekas berkualitas, baik elektronik maupun non-elektronik. Didirikan oleh Pak Raka Pratama, ReuseMart mengusung semangat mengurangi limbah dan menciptakan peluang baru lewat konsep ekonomi sirkular. Kami juga menyediakan layanan penitipan barang yang memudahkan pengguna untuk menjual tanpa ribet. Jika tidak terjual dalam waktu tertentu, barang akan disumbangkan ke lembaga sosial.</p>

    <p>Platform ini dikembangkan bersama <strong>GreenTech Solutions</strong> demi mewujudkan sistem digital yang ramah pengguna dan ramah lingkungan. Mari berkontribusi menciptakan masa depan yang lebih hijau dan bertanggung jawab!</p>
  </section>

  <footer>
    &copy; {{ date('Y') }} ReuseMart | Bersama Kita Kurangi Limbah, Ciptakan Manfaat
  </footer>

</body>
</html>
