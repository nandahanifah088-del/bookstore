<?php
session_start();
include 'koneksi.php';

// pastikan user login
if (!isset($_SESSION['id_user'])) {
    header("Location: login_user.php");
    exit;
}

// pastikan ada id pesanan
if (!isset($_GET['id_pesanan'])) {
    echo "<script>alert('Pesanan tidak ditemukan!'); window.location='riwayat_pesanan.php';</script>";
    exit;
}

$id_pesanan = $_GET['id_pesanan'];
$id_user = $_SESSION['id_user'];

// ambil data pesanan
$query = mysqli_query($conn, "SELECT * FROM pesanan WHERE id_pesanan='$id_pesanan' AND id_user='$id_user'");
$pesanan = mysqli_fetch_assoc($query);

if (!$pesanan) {
    echo "<script>alert('Pesanan tidak ditemukan!'); window.location='riwayat_pesanan.php';</script>";
    exit;
}

// proses upload
if (isset($_POST['upload'])) {
    $file_name = $_FILES['bukti']['name'];
    $tmp_name = $_FILES['bukti']['tmp_name'];
    $file_size = $_FILES['bukti']['size'];
    $allowed = ['jpg', 'jpeg', 'png'];
    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        echo "<script>alert('Hanya boleh upload file JPG, JPEG, atau PNG!');</script>";
    } elseif ($file_size > 2 * 1024 * 1024) {
        echo "<script>alert('Ukuran file maksimal 2MB!');</script>";
    } else {
        $new_name = 'bukti_' . time() . '.' . $ext;
        $upload_path = '../uploads/' . $new_name;

        if (move_uploaded_file($tmp_name, $upload_path)) {
            mysqli_query($conn, "UPDATE pesanan SET bukti_transfer='$new_name' WHERE id_pesanan='$id_pesanan'");
            echo "<script>alert('Bukti transfer berhasil diupload!'); window.location='riwayat_pesanan.php';</script>";
            exit;
        } else {
            echo "<script>alert('Gagal upload file!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Upload Bukti Transfer - BookSmart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="bg-light">

<div class="container my-5">
  <div class="card shadow-sm mx-auto" style="max-width: 500px;">
    <div class="card-header bg-dark text-white text-center">
      <h4>Upload Bukti Transfer</h4>
    </div>
    <div class="card-body">
      <p><strong>ID Pesanan:</strong> <?= htmlspecialchars($pesanan['id_pesanan']); ?></p>
      <p><strong>Total Bayar:</strong> Rp <?= number_format($pesanan['jumlah_bayar'], 0, ',', '.'); ?></p>
      <p><strong>Rekening Tujuan:</strong> <?= htmlspecialchars($pesanan['rekening_tujuan']); ?></p>

      <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label">Pilih Bukti Transfer (JPG, PNG)</label>
          <input type="file" name="bukti" class="form-control" accept=".jpg,.jpeg,.png" required>
        </div>
        <div class="d-flex justify-content-between">
          <a href="riwayat_pesanan.php" class="btn btn-secondary">← Kembali</a>
          <button type="submit" name="upload" class="btn btn-primary">Upload</button>
        </div>
      </form>
    </div>
  </div>
</div>

</body>
</html>