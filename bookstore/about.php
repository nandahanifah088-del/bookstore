<?php
include 'koneksi.php'; 
session_start();
//notif
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
  <title>Tentang Kami | BookSmart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    .navbar {
      position: relative;
      z-index: 1030;
    }

    /* Dropdown menu muncul di atas semua elemen */
    .profile-dropdown .dropdown-menu {
      position: absolute;
      z-index: 2000 !important;
    }
    /* Efek halus tanpa animasi berlebihan */
    .fade-section {
      opacity: 0;
      transform: translateY(20px);
      transition: all 0.7s ease-in-out;
    }
    .fade-section.visible {
      opacity: 1;
      transform: translateY(0);
    }

    img {
      transition: transform 0.5s ease;
    }
    img:hover {
      transform: scale(1.03);
    }

    .quote-box {
      background: #ffffffff;
      border-left: 5px solid #0d6efd;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 8px 15px rgba(0,0,0,0.05);
      transition: transform 0.4s ease;
    }
    .quote-box:hover {
      transform: translateY(-5px);
    }
    .quote-text {
      font-style: italic;
      color: #333;
      font-size: 1.2rem;
    }
    .quote-author {
      font-weight: 600;
      color: #0d6efd;
      margin-top: 10px;
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
        <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
        <li class="nav-item"><a class="nav-link active" href="about.php">Tentang Kami</a></li>
        <li class="nav-item"><a class="nav-link" href="daftar_buku.php">Daftar Buku</a></li>
        <li class="nav-item"><a class="nav-link" href="cart.php">Keranjang</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Kontak</a></li>
      </ul>

      <?php if (isset($_SESSION['nama'])): ?>
        <div class="dropdown profile-dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
            <img src="profil.png" class="profile-img" alt="Profil">
            <span class="text-white ms-2"><?php echo $_SESSION['nama']; ?></span>
            <?php if ($unread_count > 0): ?>
            <span class="badge bg-danger ms-2" style="font-size: 0.7em;"><?php echo $unread_count; ?></span>
          <?php endif; ?>
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
<section class="py-5 bg-light border-bottom fade-section">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-6 mb-4 mb-md-0">
        <img src="../assets/img/tentang.jpg" alt="Tentang BookSmart" class="img-fluid rounded shadow-sm">
      </div>
      <div class="col-md-6">
        <h1 class="fw-bold mb-3 text-dark">Tentang <span class="text-primary">BookSmart</span></h1>
        <p class="lead text-muted">
          <strong>BookSmart</strong> adalah platform toko buku online modern yang dirancang untuk mempermudah Anda menemukan, membeli, dan membaca buku favorit di mana pun dan kapan pun.
        </p>
        <p class="text-muted">
          Kami percaya bahwa setiap buku memiliki kekuatan untuk menginspirasi, mengedukasi, dan mengubah hidup. Dengan tampilan website yang sederhana, responsif, dan elegan, BookSmart berkomitmen untuk menghadirkan pengalaman berbelanja buku yang cepat, nyaman, dan menyenangkan.
        </p>
        <a href="daftar_buku.php" class="btn btn-primary mt-2">Lihat Koleksi Buku</a>
      </div>
    </div>
  </div>
</section>

<!-- Visi & Misi Section -->
<section class="py-5 bg-white fade-section">
  <div class="container text-center">
    <h2 class="fw-bold mb-4">Visi & Misi Kami</h2>
    <div class="row justify-content-center">
      <div class="col-md-8">
        <p class="text-muted mb-3">
          <strong>Visi:</strong> Menjadi toko buku online terbaik di Indonesia yang mendukung budaya literasi dan mempermudah akses terhadap ilmu pengetahuan bagi semua kalangan.
        </p>
        <p class="text-muted">
          <strong>Misi:</strong>
          <ul class="text-muted text-start d-inline-block">
            <li>Menyediakan berbagai macam buku berkualitas dari penulis lokal maupun internasional.</li>
            <li>Menghadirkan pengalaman belanja yang cepat, aman, dan nyaman.</li>
            <li>Mendukung penulis dan penerbit lokal untuk berkembang melalui platform digital.</li>
          </ul>
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Quote Section -->
<section class="py-5 bg-light border-top fade-section">
  <div class="container">
    <div class="quote-box mx-auto text-center">
      <p class="quote-text">“Buku adalah jendela dunia — dan setiap halaman yang dibuka membawa kita lebih dekat pada impian.”</p>
      <p class="quote-author">– BookSmart</p>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-3 mt-auto">
  <div class="container-fluid">
    <p class="mb-0">&copy; 2025 BookSmart | Toko Buku Online</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Animasi halus saat scroll (tanpa library)
  const fadeSections = document.querySelectorAll('.fade-section');
  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) entry.target.classList.add('visible');
    });
  }, { threshold: 0.2 });

  fadeSections.forEach(section => observer.observe(section));
</script>

</body>
</html>