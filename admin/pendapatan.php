<?php
session_start();
include '../bookstore/koneksi.php';

// pastikan hanya admin yang login
if (!isset($_SESSION['id_admin'])) {
    header("Location: login_admin.php");
    exit;
}

// --- Query Pendapatan ---
// total keseluruhan
$q_total = mysqli_query($conn, "
    SELECT SUM(jumlah_bayar) AS total 
    FROM pesanan 
    WHERE status IN ('Dibayar', 'Selesai')
");
$total = mysqli_fetch_assoc($q_total)['total'] ?? 0;

// harian
$q_harian = mysqli_query($conn, "
    SELECT SUM(jumlah_bayar) AS total 
    FROM pesanan 
    WHERE DATE(tanggal) = CURDATE()
      AND status IN ('Dibayar', 'Selesai')
");
$harian = mysqli_fetch_assoc($q_harian)['total'] ?? 0;

// mingguan
$q_mingguan = mysqli_query($conn, "
    SELECT SUM(jumlah_bayar) AS total 
    FROM pesanan 
    WHERE YEARWEEK(tanggal, 1) = YEARWEEK(CURDATE(), 1)
      AND status IN ('Dibayar', 'Selesai')
");
$mingguan = mysqli_fetch_assoc($q_mingguan)['total'] ?? 0;

// bulanan
$q_bulanan = mysqli_query($conn, "
    SELECT SUM(jumlah_bayar) AS total 
    FROM pesanan 
    WHERE MONTH(tanggal) = MONTH(CURDATE())
      AND YEAR(tanggal) = YEAR(CURDATE())
      AND status IN ('Dibayar', 'Selesai')
");
$bulanan = mysqli_fetch_assoc($q_bulanan)['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Pendapatan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f5f5f5;
    }
    .card {
      border: none;
      border-radius: 15px;
      transition: all 0.2s;
    }
    .card:hover {
      transform: translateY(-3px);
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .main-content {
      padding: 30px;
    }
  </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="main-content">
  <div class="container-fluid">
    <div class="d-flex align-items-center justify-content-center position-relative mb-4">
      <button id="toggle-btn" class="btn btn-outline-secondary position-absolute start-0">
        <i class="bi bi-list"></i>
      </button>
      <h2 class="fw-bold mb-0">Laporan Pendapatan</h2>
    </div>

    <div class="row text-center">
      <div class="col-md-3 mb-3">
        <div class="card shadow-sm">
          <div class="card-body">
            <h6>Pendapatan Hari Ini</h6>
            <h4 class="fw-bold text-success">Rp <?= number_format($harian, 0, ',', '.') ?></h4>
          </div>
        </div>
      </div>

      <div class="col-md-3 mb-3">
        <div class="card shadow-sm">
          <div class="card-body">
            <h6>Pendapatan Minggu Ini</h6>
            <h4 class="fw-bold text-primary">Rp <?= number_format($mingguan, 0, ',', '.') ?></h4>
          </div>
        </div>
      </div>

      <div class="col-md-3 mb-3">
        <div class="card shadow-sm">
          <div class="card-body">
            <h6>Pendapatan Bulan Ini</h6>
            <h4 class="fw-bold text-warning">Rp <?= number_format($bulanan, 0, ',', '.') ?></h4>
          </div>
        </div>
      </div>

      <div class="col-md-3 mb-3">
        <div class="card shadow-sm">
          <div class="card-body">
            <h6>Total Pendapatan</h6>
            <h4 class="fw-bold text-dark">Rp <?= number_format($total, 0, ',', '.') ?></h4>
          </div>
        </div>
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