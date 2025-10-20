<?php
session_start();
include '../bookstore/koneksi.php';

if (!isset($_SESSION['id_admin'])) {
  header("Location: login_admin.php");
  exit;
}

$editMode = false;
$buku = ['id_buku' => '', 'id_kategori' => '', 'judul' => '', 'penulis' => '', 'penerbit' => '', 'harga' => '', 'stok' => '', 'gambar' => '', 'deskripsi' => ''];

//proses hapus
if(isset($_GET['hapus'])){
    $id = mysqli_real_escape_string($conn, $_GET['hapus']);
    $hapus = mysqli_query($conn, "DELETE FROM buku WHERE id_buku='$id'");
    if ($hapus) {
        echo "<script>alert('Buku berhasil dihapus.'); window.location='kelola_buku.php'; </script>";
        exit();
    } else {
        echo "<script>alert('Gagal menghapus buku.'); window.location='kelola_buku.php'; </script>";
        exit();
    }
}

//proses edit
if (isset($_GET['edit'])){
    $editMode = true;
    $id = $_GET['edit'];
    $query = mysqli_query($conn, "SELECT * FROM buku WHERE id_buku=$id");
    $buku = mysqli_fetch_assoc($query);
}

// --- Proses tambah & edit buku ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_buku = $_POST['id_buku'] ?? ''; // kosong kalau tambah
    $id_kategori = $_POST['id_kategori'];
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $penerbit = $_POST['penerbit'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $deskripsi = $_POST['deskripsi'];
    $gambarBaru = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];

    // Kalau ada upload gambar baru
    if (!empty($gambarBaru)) {
        $folder = "../assets/img/" . $gambarBaru;
        move_uploaded_file($tmp, $folder);
    }

    // Kalau id_buku ada → edit, kalau tidak → tambah baru
    if (!empty($id_buku)) {
        // ambil gambar lama (kalau gak upload baru)
        if (empty($gambarBaru)) {
            $queryGambar = mysqli_query($conn, "SELECT gambar FROM buku WHERE id_buku='$id_buku'");
            $dataGambar = mysqli_fetch_assoc($queryGambar);
            $gambarBaru = $dataGambar['gambar'];
        }

        $query = "UPDATE buku SET 
                    id_kategori='$id_kategori',
                    judul='$judul',
                    penulis='$penulis',
                    penerbit='$penerbit',
                    harga='$harga',
                    stok='$stok',
                    gambar='$gambarBaru',
                    deskripsi='$deskripsi'
                  WHERE id_buku='$id_buku'";
        mysqli_query($conn, $query);
        echo "<script>alert('Data buku berhasil diperbarui!'); window.location='kelola_buku.php';</script>";
    } else {
        // Tambah baru
        if (!empty($gambarBaru)) {
            $query = "INSERT INTO buku (id_kategori, judul, penulis, penerbit, harga, stok, gambar, deskripsi)
                      VALUES ('$id_kategori', '$judul', '$penulis', '$penerbit', '$harga', '$stok', '$gambarBaru', '$deskripsi')";
            mysqli_query($conn, $query);
            echo "<script>alert('Buku berhasil ditambahkan!'); window.location='kelola_buku.php';</script>";
        } else {
            echo "<script>alert('Gambar belum dipilih!');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Buku - Admin BookSmart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      overflow-x: hidden;
    }
    .sidebar {
      width: 250px;
      background-color: #2c2c2c;
      color: #fff;
      height: 100vh;
      position: fixed;
      transition: all 0.3s ease;
      z-index: 1000;
    }
    .sidebar.collapsed {
      width: 80px;
    }
    .main-content {
      margin-left: 250px;
      padding: 30px;
      transition: all 0.3s ease;
    }
    .main-content.expanded {
      margin-left: 80px;
    }
  </style>
</head>
<body>
  <?php include 'sidebar.php'; ?>
  <div class="main-content">
    <div class="d-flex align-items-center mb-4">
      <button id="toggle-btn" class="btn btn-outline-secondary"><i class="bi bi-list"></i></button>
      <h3 class="fw-bold mb-0"><?= $editMode ? 'Edit Buku' : 'Tambah Buku' ?></h3>
    </div>

    <div class="card shadow p-4">
      <form method="POST" enctype="multipart/form-data">
        <?php if ($editMode): ?>
            <input type="hidden" name="id_buku" value="<?= $buku['id_buku']; ?>">
            <input type="hidden" name="editMode" value="1">
        <?php endif; ?>
        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Kategori</label>
            <select name="id_kategori" class="form-select" required>
              <option value="">-- Pilih Kategori --</option>
              <?php
              $kategori = mysqli_query($conn, "SELECT * FROM kategori");
              while ($row = mysqli_fetch_assoc($kategori)) {
                $selected = ($buku['id_kategori'] == $row['id_kategori']) ? 'selected' : '';
                echo "<option value='{$row['id_kategori']}' $selected>{$row['nama_kategori']}</option>";
              }
              ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Judul Buku</label>
            <input type="text" name="judul" class="form-control" value="<?= $buku['judul']; ?>" required>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Penulis</label>
            <input type="text" name="penulis" class="form-control" value="<?= $buku['penulis']; ?>" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Penerbit</label>
            <input type="text" name="penerbit" class="form-control" value="<?= $buku['penerbit']; ?>" required>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-4">
            <label class="form-label">Harga</label>
            <input type="number" name="harga" class="form-control" value="<?= $buku['harga']; ?>" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Stok</label>
            <input type="number" name="stok" class="form-control" value="<?= $buku['stok']; ?>" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Gambar</label>
            <input type="file" name="gambar" class="form-control" accept="image/*" <?= $editMode ? '' : 'required' ?>>
            <?php if ($editMode && !empty($buku['gambar'])): ?>
                <small class="text-muted">Gambar saat ini: <?= htmlspecialchars($buku['gambar']) ?></small>
            <?php endif; ?>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Deskripsi</label>
          <textarea name="deskripsi" rows="4" class="form-control" required><?= htmlspecialchars($buku['deskripsi']); ?></textarea>
        </div>

        <div class="text-end">
            <?php if ($editMode): ?>
            <button type="submit" name="update" class="btn btn-warning"><i class="bi bi-pencil-square"></i> Update Buku</button>
          <?php else: ?>
            <button type="submit" name="tambah" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah Buku</button>
          <?php endif; ?>
          <a href="kelola_buku.php" class="btn btn-secondary">Batal</a>
        </div>
      </form>
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