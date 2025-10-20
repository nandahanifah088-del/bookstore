<?php
session_start();
include '../bookstore/koneksi.php';

// pastikan admin sudah login
if (!isset($_SESSION['id_admin'])) {
    header("Location: login_admin.php");
    exit;
}

// ambil data dari database
$total_buku = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM buku"))['total'];
$total_user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM user"))['total'];
$total_pesanan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM pesanan"))['total'];
$total_pesan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM pesan"))['total'];

// hitung pesan belum dibaca
$unread_pesan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM pesan WHERE dibaca_admin = 0"))['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin - BookSmart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/style.css">
  <style>
    body {
      background-color: #f8f9fa;
      overflow-x: hidden;
    }

    .card {
      border: none;
      border-radius: 12px;
      cursor: pointer;
      transition: transform 0.2s;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    /* posisi ikon notifikasi */
    .notif-icon {
      position: absolute;
      top: 20px;
      right: 30px;
      font-size: 24px;
      color: #555;
      cursor: pointer;
    }

    .notif-badge {
      position: absolute;
      top: 12px;
      right: 24px;
      background-color: red;
      color: white;
      font-size: 12px;
      border-radius: 50%;
      padding: 3px 6px;
    }
  </style>
</head>
<body>
  <?php include 'sidebar.php'; ?>

  <!-- Main Content -->
  <div class="main-content position-relative me-3">
    <!-- 🔔 Notifikasi di pojok kanan atas -->
    <a href="pesan.php" class="notif-icon position-absolute">
      <i class="bi bi-bell"></i>
    </a>
    <?php if ($unread_pesan > 0): ?>
      <span class="notif-badge"><?= $unread_pesan; ?></span>
    <?php endif; ?>

    <div class="d-flex align-items-center gap-3 mb-4 mt-3">
      <button id="toggle-btn" class="btn btn-outline-secondary"><i class="bi bi-list"></i></button>
      <h2 class="fw-bold mb-0">Dashboard Admin</h2>
    </div>

    <div class="row g-4">
      <div class="col-md-4 col-lg-3">
        <div class="card shadow text-center py-4" onclick="window.location='kelola_buku.php'">
          <i class="bi bi-book" style="font-size:40px;color:#007bff;"></i>
          <h5>Total Buku</h5>
          <h3><?= $total_buku; ?></h3>
        </div>
      </div>

      <div class="col-md-4 col-lg-3">
        <div class="card shadow text-center py-4" onclick="window.location='user.php'">
          <i class="bi bi-people" style="font-size:40px;color:#198754;"></i>
          <h5>Total User</h5>
          <h3><?= $total_user; ?></h3>
        </div>
      </div>

      <div class="col-md-4 col-lg-3">
        <div class="card shadow text-center py-4" onclick="window.location='kelola_pesanan.php'">
          <i class="bi bi-bag-check" style="font-size:40px;color:#ffc107;"></i>
          <h5>Total Pesanan</h5>
          <h3><?= $total_pesanan; ?></h3>
        </div>
      </div>

      <div class="col-md-4 col-lg-3">
        <div class="card shadow text-center py-4" onclick="window.location='pesan.php'">
          <i class="bi bi-chat-dots" style="font-size:40px;color:#6f42c1;"></i>
          <h5>Pesan Masuk</h5>
          <h3><?= $total_pesan; ?></h3>
        </div>
      </div>

      <div class="col-md-4 col-lg-3">
        <div class="card shadow text-center py-4" onclick="window.location='pendapatan.php'">
          <i class="bi bi-cash-stack" style="font-size:40px;color:#fd7e14;"></i>
          <h5>Total Pendapatan</h5>
          <h3>?</h3>
        </div>
      </div>
    </div>
  </div>

  <script>
    const toggleBtn = document.getElementById('toggle-btn');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('.main-content');

    toggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('collapsed');
      mainContent.classList.toggle('collapsed');
    });
  </script>
</body>
</html>