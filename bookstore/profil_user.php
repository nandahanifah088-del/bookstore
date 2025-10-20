<?php
session_start();
include 'koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: login_user.php");
    exit;
}

$id_user = $_SESSION['id_user'];

// --- Proses update data ---
if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $telepon = mysqli_real_escape_string($conn, $_POST['telepon']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

    // cek apakah user upload foto baru
    if (!empty($_FILES['foto']['name'])) {
        $foto_name = time() . '_' . basename($_FILES['foto']['name']);
        $target = "../assets/img/" . $foto_name;

        // pindahkan file upload ke folder img
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
            $update = mysqli_query($conn, "
                UPDATE user 
                SET nama='$nama', email='$email', telepon='$telepon', alamat='$alamat', foto='$foto_name' 
                WHERE id_user='$id_user'
            ");
        }
    } else {
        $update = mysqli_query($conn, "
            UPDATE user 
            SET nama='$nama', email='$email', telepon='$telepon', alamat='$alamat' 
            WHERE id_user='$id_user'
        ");
    }

    if ($update) {
        echo "<script>alert('Profil berhasil diperbarui!'); window.location='profil_user.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal memperbarui profil!');</script>";
    }
}

// --- Ambil data user ---
$query = mysqli_query($conn, "SELECT * FROM user WHERE id_user='$id_user'");
$user = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Profil Saya | BookSmart</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  body {
    background-color: #f8f9fa;
  }
  .profile-card {
    max-width: 700px;
    margin: 60px auto;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border: none;
  }
  .profile-img {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid #0d6efd;
  }
</style>
</head>
<body>
<div class="container">
  <div class="card profile-card">
    <div class="card-body text-center p-5">
      <img src="<?php echo $user['foto'] ? '../assets/img/'.$user['foto'] : '../assets/img/default_user.png'; ?>" 
           alt="Foto Profil" class="profile-img mb-3">
      <h4 class="fw-bold mb-1"><?php echo htmlspecialchars($user['nama']); ?></h4>
      <p class="text-muted mb-4"><?php echo htmlspecialchars($user['email']); ?></p>

      <form method="POST" enctype="multipart/form-data" class="text-start">
        <div class="mb-3">
          <label class="form-label">Nama Lengkap</label>
          <input type="text" name="nama" value="<?php echo htmlspecialchars($user['nama']); ?>" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Nomor Telepon</label>
          <input type="text" name="telepon" value="<?php echo htmlspecialchars($user['telepon']); ?>" class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label">Alamat</label>
          <textarea name="alamat" class="form-control" rows="3"><?php echo htmlspecialchars($user['alamat']); ?></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Foto Profil</label>
          <input type="file" name="foto" class="form-control">
        </div>
        <div class="d-flex justify-content-between">
          <button type="submit" name="update" class="btn btn-primary px-4">Simpan Perubahan</button>
          <a href="index.php" class="btn btn-outline-secondary px-4">Kembali</a>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>