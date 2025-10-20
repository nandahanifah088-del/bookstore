<?php
session_start();
include 'koneksi.php';
// notif
if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
    $result = mysqli_query($conn, "
        SELECT COUNT(*) as total 
        FROM pesan 
        WHERE id_user = '$id_user' AND dibaca_user = '0'
    ");
    $row = mysqli_fetch_assoc($result);
    $unread_count = $row['total'];
} else {
    $unread_count = 0;
}
?>
<!DOCTYPE html>
<html lang="id">      
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BookSmart - Toko Buku Online</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    .hero-section {
      display: flex;
      align-items: center;
      justify-content: center;
      flex-wrap: wrap;
      margin-top: 60px;
      gap: 40px;
    }
    .hero-section img {
      max-width: 420px;
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }
    .hero-text {
      max-width: 550px;
    }
    .hero-text h1 {
      font-weight: 700;
      color: #343a40;
    }
    .hero-text p {
      color: #555;
      font-size: 1.05rem;
      text-align: justify;
    }
    .book-section {
      margin-top: 80px;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark position-relative">
  <div class="container">
    <a class="navbar-brand" href="index.php">BookSmart</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active" href="index.php">Beranda</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">Tentang Kami</a></li>
        <li class="nav-item"><a class="nav-link" href="daftar_buku.php">Daftar Buku</a></li>
        <li class="nav-item"><a class="nav-link" href="cart.php">Keranjang</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Kontak</a></li>
      </ul>

      <?php if (isset($_SESSION['nama'])): ?>
        <div class="dropdown profile-dropdown position-relative">
  <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
    <div class="position-relative">
      <img src="profil.png" class="profile-img" alt="Profil">
      <?php if ($unread_count > 0): ?>
        <span class="badge bg-danger position-absolute top-0 start-100 translate-middle" style="font-size: 0.65em;"><?php echo $unread_count; ?></span>
      <?php endif; ?>
    </div>
    <span class="text-white ms-2"><?php echo $_SESSION['nama']; ?></span>
  </a>
  <ul class="dropdown-menu dropdown-menu-end">
    <li><a class="dropdown-item" href="profil_user.php">Profil Saya</a></li>
    <li><a class="dropdown-item" href="riwayat_pesanan.php">Riwayat Pesanan</a></li>
    <li><a class="dropdown-item" href="pesan_user.php">
      Pesan Saya
      <?php if ($unread_count > 0): ?>
        <span class="badge bg-danger ms-1"><?php echo $unread_count; ?></span>
      <?php endif; ?>
    </a></li>
    <li><hr class="dropdown-divider"></li>
    <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
  </ul>
</div>
      <?php else: ?>
        <a href="login_user.php" class="btn btn-outline-light position-absolute end-0 me-3">Login</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<!-- Hero Section -->
<div class="container hero-section">
  <img src="../assets/img/about.jpg" alt="Tentang BookSmart" data-aos="fade-right" data-aos-duration="1000">
  <div class="hero-text" data-aos="fade-left" data-aos-duration="1000">
    <h1><?php if (isset($_SESSION['nama'])): ?>
        Selamat Datang, <span class="text-primary"><?php echo $_SESSION['nama']; ?></span> di BookSmart!
      <?php else: ?>
        Selamat Datang di BookSmart
      <?php endif; ?></h1>
    <p>
      BookSmart adalah toko buku online modern yang menyediakan berbagai koleksi buku dari berbagai genre — mulai dari fiksi, non-fiksi, hingga buku akademik. 
      Kami berkomitmen memberikan pengalaman berbelanja buku yang mudah, cepat, dan nyaman bagi seluruh pecinta literasi di Indonesia.
    </p>
    <p>
      Kami percaya bahwa setiap buku memiliki kekuatan untuk menginspirasi, mendidik, dan mengubah kehidupan pembacanya.
      Yuk, mulai jelajahi koleksi kami dan temukan buku favoritmu!
    </p>
    <a href="daftar_buku.php" class="btn btn-primary mt-3">Lihat Koleksi Buku</a>
  </div>
</div>

<!-- Daftar Buku -->
<div class="container book-section" data-aos="fade-up" data-aos-duration="1000">
  <h2 class="text-center mb-4 fw-bold">Buku Pilihan</h2>
  <div class="row justify-content-center">

    <?php
    // Ambil data buku dari database
    $query = mysqli_query($conn, "SELECT * FROM buku LIMIT 3"); // LIMIT 3 artinya hanya tampilkan 3 buku di halaman depan
    // Periksa apakah ada data
    if (mysqli_num_rows($query) > 0) {
      $delay = 100; // buat efek AOS delay berurutan
      while ($row = mysqli_fetch_assoc($query)) {
        // Data buku dari database
        $id_buku   = $row['id_buku'];
        $judul     = $row['judul'];
        $deskripsi = $row['deskripsi'];
        $gambar    = $row['gambar']; // misal simpan nama file gambar di DB
    ?>
        <div class="col-md-3 mb-4" data-aos="zoom-in" data-aos-delay="<?php echo $delay; ?>">
          <a href="detail_buku.php?id=<?php echo $id_buku; ?>" style="text-decoration: none; color: inherit;">
            <div class="card h-100 shadow-sm">
              <img src="../assets/img/<?php echo $gambar; ?>" class="card-img-top" alt="<?php echo $judul; ?>">
              <div class="card-body d-flex flex-column">
                <h5 class="card-title"><?php echo $judul; ?></h5>
                <p class="card-text">
                  <?php 
                  // Potong deskripsi biar nggak kepanjangan
                  echo substr($deskripsi, 0, 150) . (strlen($deskripsi) > 150 ? '...' : '');
                  ?>
                </p>
                <a href="tambah_keranjang.php?id=<?php echo $id_buku; ?>" class="btn btn-primary mt-auto">
                  Tambah ke Keranjang
                </a>
              </div>
            </div>
          </a>
        </div>
    <?php
        $delay += 100; // delay naik tiap kartu biar animasinya muncul bergiliran
      }
    } else {
      echo "<p class='text-center'>Belum ada buku yang tersedia.</p>";
    }
    ?>
  </div>
</div>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-3 mt-5">
  <div class="container-fluid">
    <p class="mb-0">&copy; 2025 BookSmart | Toko Buku Online</p>
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init();
</script>
</body>
</html>