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
// Pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: login_user.php");
    exit;
}

$id_user = $_SESSION['id_user'];
$id_pesanan = $_GET['id_pesanan'] ?? null;

if (!$id_pesanan) {
    echo "<script>alert('Pesanan tidak ditemukan!'); window.location='riwayat_pesanan.php';</script>";
    exit;
}

// Ambil data pesanan
$pesanan_query = mysqli_query($conn, "
    SELECT * FROM pesanan 
    WHERE id_pesanan = '$id_pesanan' AND id_user = '$id_user'
");
$pesanan = mysqli_fetch_assoc($pesanan_query);

if (!$pesanan) {
    echo "<script>alert('Pesanan tidak valid!'); window.location='riwayat_pesanan.php';</script>";
    exit;
}

// Upload bukti transfer
if (isset($_POST['upload_bukti'])) {
    $target_dir = "../assets/bukti_transfer/";
    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

    $file_name = basename($_FILES["bukti"]["name"]);
    $target_file = $target_dir . time() . "_" . $file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (in_array($imageFileType, ['jpg', 'jpeg', 'png'])) {
        if (move_uploaded_file($_FILES["bukti"]["tmp_name"], $target_file)) {
            $nama_simpan = basename($target_file);
            mysqli_query($conn, "UPDATE pesanan SET bukti_transfer='$nama_simpan', status='diproses' WHERE id_pesanan='$id_pesanan'");
            echo "<script>alert('Bukti transfer berhasil diunggah!'); window.location='detail_pesanan.php?id_pesanan=$id_pesanan';</script>";
            exit;
        } else {
            echo "<script>alert('Gagal mengunggah bukti!');</script>";
        }
    } else {
        echo "<script>alert('Format file tidak valid! (hanya JPG/PNG)');</script>";
    }
}

// Ambil detail buku
$detail_query = mysqli_query($conn, "
    SELECT d.*, b.judul, b.harga, b.gambar 
    FROM detail_pesanan d
    JOIN buku b ON d.id_buku = b.id_buku
    WHERE d.id_pesanan = '$id_pesanan'
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Pesanan - BookSmart</title>
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
<div class="container my-5">
  <div class="card shadow-sm">
    <div class="card-header bg-dark text-white text-center">
      <h4>Detail Pesanan</h4>
    </div>
    <div class="card-body">

      <!-- Info Pesanan -->
      <div class="row mb-4">
        <div class="col-md-6">
          <p><strong>Tanggal Pesanan:</strong><br><?= date('d M Y, H:i', strtotime($pesanan['tanggal'])); ?></p>
          <p><strong>Status:</strong><br>
            <?php 
              $status = $pesanan['status'];
              $badge = match($status) {
                'diproses' => 'warning',
                'selesai' => 'success',
                'dikirim' => 'info',
                'dibatalkan' => 'danger',
                default => 'secondary'
              };
            ?>
            <span class="badge bg-<?= $badge; ?> text-capitalize"><?= $status; ?></span>
          </p>
        </div>
        <div class="col-md-6">
          <p><strong>Metode Pembayaran:</strong><br><?= $pesanan['metode_pembayaran']; ?></p>
          <?php if ($pesanan['metode_pembayaran'] == 'Transfer Bank'): ?>
            <p><strong>Rekening Tujuan:</strong><br><?= htmlspecialchars($pesanan['rekening_tujuan']); ?></p>
          <?php endif; ?>
        </div>
      </div>

      <hr>

      <!-- Daftar Buku -->
      <h5 class="mb-3">Daftar Buku</h5>
      <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
          <thead class="table-dark">
            <tr>
              <th>No</th>
              <th>Gambar</th>
              <th>Judul Buku</th>
              <th>Harga</th>
              <th>Jumlah</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $no = 1;
            $total = 0;
            while ($detail = mysqli_fetch_assoc($detail_query)): 
              $subtotal = $detail['harga'] * $detail['jumlah'];
              $total += $subtotal;
            ?>
            <tr>
              <td><?= $no++; ?></td>
              <td><img src="../assets/img/<?= $detail['gambar']; ?>" width="60" class="rounded"></td>
              <td><?= htmlspecialchars($detail['judul']); ?></td>
              <td>Rp <?= number_format($detail['harga'], 0, ',', '.'); ?></td>
              <td><?= $detail['jumlah']; ?></td>
              <td>Rp <?= number_format($subtotal, 0, ',', '.'); ?></td>
            </tr>
            <?php endwhile; ?>
            <tr class="table-light">
              <td colspan="5" class="text-end fw-bold">Total Bayar:</td>
              <td class="fw-bold text-success">Rp <?= number_format($total, 0, ',', '.'); ?></td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Upload Bukti Transfer -->
      <?php if ($pesanan['metode_pembayaran'] == 'Transfer Bank'): ?>
        <hr>
        <h5 class="mb-3">Bukti Transfer</h5>
        <?php if (!empty($pesanan['bukti_transfer'])): ?>
          <p><strong>Sudah diunggah:</strong></p>
          <img src="../assets/bukti_transfer/<?= $pesanan['bukti_transfer']; ?>" alt="Bukti Transfer" class="img-fluid rounded mb-3" style="max-width:200px;">
        <?php else: ?>
          <form method="post" enctype="multipart/form-data" class="mt-3">
            <div class="mb-3">
              <label class="form-label">Unggah Bukti (JPG/PNG)</label>
              <input type="file" name="bukti" class="form-control" accept=".jpg,.jpeg,.png" required>
            </div>
            <button type="submit" name="upload_bukti" class="btn btn-dark">Upload Bukti</button>
          </form>
        <?php endif; ?>
      <?php endif; ?>

      <div class="text-end mt-4">
        <a href="riwayat_pesanan.php" class="btn btn-secondary">← Kembali</a>
      </div>

    </div>
  </div>
</div>

<!-- Footer -->
  <footer class="bg-dark text-white text-center py-3 mt-5">
  <div class="container-fluid">
    <p class="mb-0">&copy; 2025 BookSmart | Toko Buku Online</p>
  </div>
  </footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>