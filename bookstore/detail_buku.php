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
// Pastikan id_buku dikirim lewat URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
  echo "<div class='alert alert-danger text-center'>Buku tidak ditemukan!</div>";
  exit;
}

$id_buku = $_GET['id'];

// Ambil data buku dari database + kategori
$query = mysqli_query($conn, "
  SELECT buku.*, kategori.nama_kategori 
  FROM buku 
  JOIN kategori ON buku.id_kategori = kategori.id_kategori
  WHERE buku.id_buku = '$id_buku'
");
$buku = mysqli_fetch_assoc($query);


if (!$buku) {
  echo "<div class='alert alert-danger text-center'>Data buku tidak tersedia!</div>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $buku['judul']; ?> - BookSmart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
  html, body {
    height: 100%;
    margin: 0;
  }
  body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    background-color: #f8f9fa;
  }
  main {
    flex: 1;
  }
  footer {
    margin-top: auto;
  }

  /* Gaya kotak detail buku */
  .detail-card {
    background-color: #fff;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    border-radius: 12px;
    padding: 40px;
    max-width: 1000px;
    margin: 40px auto;
  }
  .book-info {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-start;
    gap: 40px;
  }

  .book-info img {
    width: 280px;
    height: 400px;
    object-fit: cover;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
  }

  .book-detail {
    flex: 1;
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
  <!-- Konten Detail Buku -->
  <main>
    <div class="detail-card">
      <div class="book-info">
        <img src="../assets/img/<?php echo $buku['gambar']; ?>" alt="<?php echo $buku['judul']; ?>">
        <div class="book-detail">
          <h2><?php echo $buku['judul']; ?></h2>
          <p class="text-muted mb-1">Kategori: <?php echo $buku['nama_kategori']; ?></p>
          <p class="text-muted mb-1">Penulis: <?php echo $buku['penulis']; ?></p>
          <p class="text-success fw-bold fs-4">Rp <?php echo number_format($buku['harga'], 0, ',', '.'); ?></p>
          <p><strong>Stok:</strong> <?php echo $buku['stok']; ?></p>
          <p class="mt-4"><?php echo nl2br($buku['deskripsi']); ?></p>
            <!--button -->
            <?php if (isset($_SESSION['id_user'])): ?>
              <a href="tambah_keranjang.php?id=<?php echo $buku['id_buku']; ?>" class="btn btn-primary">Tambah ke Keranjang</a>
            <?php else: ?>
              <a href="login_user.php" class="btn btn-primary">Tambah ke Keranjang</a>
            <?php endif; ?>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-dark text-white text-center py-3 mt-5">
    <p class="mb-0">&copy; 2025 BookSmart | Toko Buku Online</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>