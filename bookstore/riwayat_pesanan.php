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
// pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: login_user.php");
    exit;
}

$id_user = $_SESSION['id_user'];
$query = mysqli_query($conn, "SELECT * FROM pesanan WHERE id_user='$id_user' ORDER BY tanggal DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Riwayat Pesanan - BookSmart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    /* Pastikan navbar dan dropdown selalu di atas elemen lain */
.navbar {
  position: relative;
  z-index: 1030;
}

/* Dropdown menu muncul di atas semua elemen */
.profile-dropdown .dropdown-menu {
  position: absolute;
  z-index: 2000 !important;
}
</style>
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
<main class="flex-grow-1">
<div class="container my-5">
  <div class="card shadow-sm">
    <div class="card-header bg-dark text-white text-center">
      <h4>Riwayat Pesanan Anda</h4>
    </div>
    <div class="card-body">

      <?php if (mysqli_num_rows($query) == 0): ?>
        <p class="text-center text-muted">Belum ada pesanan yang dibuat.</p>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-bordered align-middle text-center">
            <thead class="table-dark">
              <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Metode Pembayaran</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              $no = 1;
              while ($row = mysqli_fetch_assoc($query)): 
                $status = $row['status'];
                $badge = match($status) {
                  'diproses' => 'warning',
                  'selesai' => 'success',
                  'dikirim' => 'info',
                  'dibatalkan' => 'danger',
                  default => 'secondary'
                };
              ?>
              <tr>
                <td><?= $no++; ?></td>
                <td><?= date('d M Y, H:i', strtotime($row['tanggal'])); ?></td>
                <td>Rp <?= number_format($row['jumlah_bayar'], 0, ',', '.'); ?></td>
                <td><?= htmlspecialchars($row['metode_pembayaran']); ?></td>
                <td><span class="badge bg-<?= $badge; ?> text-capitalize"><?= $status; ?></span></td>
                <td>
                  <a href="detail_pesanan.php?id_pesanan=<?= $row['id_pesanan']; ?>" class="btn btn-sm btn-outline-dark">Detail</a>

                  <?php if ($row['metode_pembayaran'] == 'Transfer Bank'): ?>
                    <?php if (!empty($row['bukti_transfer'])): ?>
                      <a href="../assets/bukti_transfer/<?= $row['bukti_transfer']; ?>" target="_blank" class="btn btn-sm btn-success">Lihat Bukti</a>
                    <?php else: ?>
                      <a href="detail_pesanan.php?id_pesanan=<?= $row['id_pesanan']; ?>" class="btn btn-sm btn-warning text-white">Upload Bukti</a>
                    <?php endif; ?>
                  <?php endif; ?>

                </td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>

      <div class="text-end mt-3">
        <a href="index.php" class="btn btn-secondary">← Kembali ke Beranda</a>
      </div>

    </div>
  </div>
</div>
</main>
<footer class="bg-dark text-white text-center py-3 mt-5">
  <div class="container-fluid">
    <p class="mb-0">&copy; 2025 BookSmart | Toko Buku Online</p>
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>