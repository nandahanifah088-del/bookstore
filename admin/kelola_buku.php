<?php
include '../bookstore/koneksi.php';

// --- Ambil semua kategori buat dropdown ---
$kategoriQuery = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");

// --- Fitur Search dan Filter ---
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$filterKategori = isset($_GET['kategori']) ? mysqli_real_escape_string($conn, $_GET['kategori']) : '';

$where = [];
if (!empty($search)) {
  $where[] = "(judul LIKE '%$search%' OR penulis LIKE '%$search%' OR penerbit LIKE '%$search%')";
}
if (!empty($filterKategori) && $filterKategori != '0') {
  $where[] = "id_kategori = '$filterKategori'";
}
$whereSQL = count($where) ? "WHERE " . implode(" AND ", $where) : "";

$query = mysqli_query($conn, "SELECT * FROM buku $whereSQL ORDER BY id_buku DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Buku - Admin BookSmart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      overflow-x: hidden;
    }
    .card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .card-header {
      background-color: #2c2c2c;
      color: white;
      font-weight: 600;
      border-top-left-radius: 12px;
      border-top-right-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 15px 20px;
      position: relative;
      height: 70px;
      z-index: 100;
    }

    /* Tombol garis tiga */
    #btn-toggle {
      font-size: 1.6rem;
      color: white;
      background: none;
      border: none;
      margin-right: 10px;
      transition: 0.3s;
      position: relative;
      z-index: 1100;
    }

    /* Search di tengah */
    .search-container {
      position: absolute;
      left: 50%;
      top: 50%;
      transform: translate(-50%, -50%);
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .search-container select,
    .search-container input,
    .search-container button {
      height: 40px;
    }

    .btn {
      color: white;
      font-weight: 500;
    }
    .btn:hover {
      color: #fff;
    }

    th {
      background-color: #2c2c2c;
      color: #fff;
      text-align: center;
    }

    td img {
      width: 60px;
      border-radius: 8px;
    }

    .action-btn a {
      display: inline-block;
      padding: 6px 10px;
      border-radius: 6px;
      font-size: 13px;
      font-weight: 600;
      text-decoration: none;
      margin: 2px;
    }

    .edit-btn {
      background-color: #ffc107;
      color: #000;
    }

    .delete-btn {
      background-color: #dc3545;
      color: #fff;
    }

    .edit-btn:hover {
      background-color: #e0a800;
    }

    .delete-btn:hover {
      background-color: #c82333;
    }
  </style>
</head>
<body>
<?php include 'sidebar.php'; ?>
  <div class="main-content">
    <div class="card">
      <div class="card-header">
        <!-- Kiri: tombol garis tiga + judul -->
        <div class="d-flex align-items-center gap-2">
          <button id="btn-toggle"><i class="bi bi-list"></i></button>
          <h5 class="mb-0 text-white"><i class="bi bi-book"></i> Kelola Buku</h5>
        </div>
        <!-- Tengah: form search -->
        <div class="search-container">
          <form method="GET" class="d-flex align-items-center">
            <select name="kategori" class="form-select w-auto me-2">
              <option value="0">Semua Kategori</option>
              <?php
              $kategoriQuery2 = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
              while ($kat = mysqli_fetch_assoc($kategoriQuery2)) { 
                $selected = ($filterKategori == $kat['id_kategori']) ? 'selected' : '';
                echo "<option value='{$kat['id_kategori']}' $selected>{$kat['nama_kategori']}</option>";
              }
              ?>
            </select>

            <input type="text" name="search" class="form-control me-2" placeholder="Cari buku..." value="<?= htmlspecialchars($search) ?>" style="max-width: 300px;">
            <button class="btn btn-primary d-flex align-items-center"><i class="bi bi-search"></i></button>
          </form>
        </div>

        <!-- Kanan: tombol tambah buku -->
        <a href="tambah_buku.php" class="btn btn-primary d-flex align-items-center">
          <i class="bi bi-plus-circle me-1"></i> Tambah Buku
        </a>

      </div>

      <div class="card-body table-responsive">
        <table class="table table-bordered table-striped align-middle text-center">
          <thead>
            <tr>
              <th>No</th>
              <th>Gambar</th>
              <th>Judul</th>
              <th>Penulis</th>
              <th>Penerbit</th>
              <th>Harga</th>
              <th>Stok</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            if (mysqli_num_rows($query) > 0) {
              while ($row = mysqli_fetch_assoc($query)) {
            ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><img src="../assets/img/<?= $row['gambar'] ?>" alt="<?= $row['judul'] ?>"></td>
              <td><?= $row['judul'] ?></td>
              <td><?= $row['penulis'] ?></td>
              <td><?= $row['penerbit'] ?></td>
              <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
              <td><?= $row['stok'] ?></td>
              <td class="action-btn">
                <a href="tambah_buku.php?edit=<?= $row['id_buku'] ?>" class="edit-btn"><i class="bi bi-pencil-square"></i></a>
                <a href="kelola_buku.php?hapus=<?= $row['id_buku'] ?>" class="delete-btn" onclick="return confirm('Yakin ingin menghapus buku ini?')"><i class="bi bi-trash"></i></a>
              </td>
            </tr>
            <?php
              }
            } else {
              echo "<tr><td colspan='8'>Tidak ada buku yang ditemukan.</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    const toggleBtn = document.getElementById('btn-toggle');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('.main-content');

    toggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('collapsed');
      mainContent.classList.toggle('collapsed');
    });
  </script> 
</body>
</html>