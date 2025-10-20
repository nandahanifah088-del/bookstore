<?php
session_start();
include 'koneksi.php';

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
// Cek apakah user sudah login
$id_user = $_SESSION['id_user'] ?? null;
// Cek apakah user sudah pernah kirim pesan
if ($id_user) {
    $cekPesan = mysqli_query($conn, "SELECT id_pesan FROM pesan WHERE id_user='$id_user' LIMIT 1");
    if (mysqli_num_rows($cekPesan) > 0) {
      // Kalau sudah pernah kirim → langsung arahkan ke halaman chat user
        header("Location: pesan_user.php");
        exit;
    }
}
// Proses kirim pesan pertama kali
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $subjek = mysqli_real_escape_string($conn, $_POST['subjek']);
    $isi = mysqli_real_escape_string($conn, $_POST['isi']);

    $query = "INSERT INTO pesan (id_user, nama_pengirim, email, subjek, isi, status, dibaca_user)
              VALUES ('$id_user', '$nama', '$email', '$subjek', '$isi', 'Belum Dibalas', 'Sudah')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Pesan berhasil dikirim! Kami akan segera membalas.'); window.location='contact.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan, coba lagi.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kontak Kami | BookSmart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="d-flex flex-column min-vh-100">

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
        <li class="nav-item"><a class="nav-link active" href="contact.php">Kontak</a></li>
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
<!-- Konten -->
<section class="py-5 bg-light flex-grow-1">
  <div class="container">
    <div class="row align-items-center">
      <!-- Gambar -->
      <div class="col-md-6 mb-4 mb-md-0">
        <img src="../assets/img/contact.webp" alt="Kontak Kami" class="img-fluid rounded shadow-sm">
      </div>

      <!-- Form -->
      <div class="col-md-6">
        <h2 class="fw-bold mb-3 text-dark">Hubungi <span class="text-primary">Kami</span></h2>
        <p class="text-muted mb-4">
          Ada pertanyaan, saran, atau kendala? Kirimkan pesanmu di bawah ini. Kami akan segera merespons!
        </p>

        <form method="POST" action="">
          <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control" name="nama" id="nama" required>
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" id="email" required>
          </div>

          <div class="mb-3">
            <label for="subjek" class="form-label">Subjek</label>
            <input type="text" class="form-control" name="subjek" id="subjek" required>
          </div>

          <div class="mb-3">
            <label for="isi" class="form-label">Pesan</label>
            <textarea class="form-control" name="isi" id="isi" rows="4" required></textarea>
          </div>

          <button type="submit" class="btn btn-primary w-100">Kirim Pesan</button>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-3 mt-5">
  <div class="container-fluid">
    <p class="mb-0">&copy; 2025 BookSmart | Toko Buku Online</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>