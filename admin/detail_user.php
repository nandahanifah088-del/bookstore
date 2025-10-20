<?php
session_start();
include '../bookstore/koneksi.php';

// Pastikan hanya admin yang bisa akses
if (!isset($_SESSION['id_admin'])) {
    header("Location: login_admin.php");
    exit;
}

// Ambil id user dari URL
$id_user = $_GET['id'] ?? null;
if (!$id_user) {
    echo "<script>alert('User tidak ditemukan!'); window.location='list_user.php';</script>";
    exit;
}

// Ambil data user
$query = mysqli_query($conn, "SELECT * FROM user WHERE id_user='$id_user'");
$user = mysqli_fetch_assoc($query);
if (!$user) {
    echo "<script>alert('User tidak ditemukan!'); window.location='list_user.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail User - Admin BookSmart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .main-content {
            margin-left: 250px;
            padding: 20px;
            transition: margin-left 0.3s;
        }
        .main-content.sidebar-collapsed {
            margin-left: 0;
        }
        .profile-card {
            max-width: 600px;
            margin: 0 auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            background-color: #fff;
        }
        .profile-card h3 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="main-content">
    <div class="d-flex align-items-center justify-content-center position-relative mb-4">
        <button id="toggle-btn" class="btn btn-outline-secondary position-absolute start-0">
            <i class="bi bi-list"></i>
        </button>
        <h2 class="fw-bold mb-0">Detail User</h2>
    </div>

    <!-- Profil User -->
    <div class="profile-card text-start">
        <h3><?= htmlspecialchars($user['nama']); ?></h3>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
        <p><strong>Tanggal Daftar:</strong> <?= date('d M Y', strtotime($user['tanggal_daftar'])); ?></p>
        <?php if(!empty($user['alamat'])): ?>
            <p><strong>Alamat:</strong> <?= htmlspecialchars($user['alamat']); ?></p>
        <?php endif; ?>
        <?php if(!empty($user['no_hp'])): ?>
            <p><strong>No HP:</strong> <?= htmlspecialchars($user['no_hp']); ?></p>
        <?php endif; ?>
        <div class="mt-4">
            <a href="user.php" class="btn btn-secondary">← Kembali ke List User</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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