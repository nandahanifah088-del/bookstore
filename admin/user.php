<?php 
session_start();
include '../bookstore/koneksi.php';

//cek login biar hanya admin yang bisa akses
if(isset($SESSION['id_admin'])){
    header("Location: login.admin.php");
    exit;
}

//proses hapus
if(isset($_GET['hapus'])){
    $id = mysqli_real_escape_string($conn, $_GET['hapus']);
    $hapus = mysqli_query($conn, "DELETE FROM user WHERE id_user='$id'");
    if ($hapus) {
        echo "<script>alert('Id User berhasil dihapus.'); window.location='user.php'; </script>";
        exit();
    } else {
        echo "<script>alert('Gagal menghapus id User.'); window.location='user.php'; </script>";
        exit();
    }
}

//ambil data user dari database
$query = mysqli_query($conn, "SELECT * FROM user ORDER BY id_user ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List User - Admin BookSmart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="main-content">
        <div class="container-fluid mt-4">
            <div class="d-flex align-items-center justify-content-center position-relative mb-4">
                <button id="toggle-btn" class="btn btn-outline-secondary position-absolute start-0"><i class="bi bi-list"></i></button>
            <h2 class="fw-bold mb-0"><i class="bi bi-people" style="font-size:35px;color:#2c2c2c;"></i> Daftar User</h2>
            </div>
            <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Tanggal Daftar</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php 
                $no = 1;
                while ($user = mysqli_fetch_assoc($query)):
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($user['nama']); ?></td>
                    <td><?= htmlspecialchars($user['email']); ?></td>
                    <td><?= date('d M Y', strtotime($user['tanggal_daftar'])); ?></td>
                    <td>
                    <a href="detail_user.php?id=<?= $user['id_user']; ?>" class="btn btn-sm btn-info">Detail</a>
                    <a href="hapus_user.php?id=<?= $user['id_user']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus user ini?');">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php if(mysqli_num_rows($query) == 0): ?>
                <tr>
                    <td colspan="5">Belum ada user terdaftar.</td>
                </tr>
                <?php endif; ?>
                </tbody>
            </table>
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
</html>