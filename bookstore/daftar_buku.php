<?php
include 'koneksi.php';
session_start();

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
// --- Pagination ---
$limit = 5; // jumlah buku per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;
// --- Ambil daftar kategori ---
$kategori_query = mysqli_query($conn, "SELECT * FROM kategori");
$kategori_list = [];
while ($row = mysqli_fetch_assoc($kategori_query)) {
    $kategori_list[] = $row;
}
// --- Pencarian & Filter Kategori ---
$where = [];
$search = "";
$selected_kategori = "";

if (!empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $where[] = "(buku.judul LIKE '%$search%' OR buku.penulis LIKE '%$search%' OR buku.penerbit LIKE '%$search%')";
}

if (!empty($_GET['kategori'])) {
    $selected_kategori = (int) $_GET['kategori'];
    $where[] = "buku.id_kategori = $selected_kategori";
}

$where_sql = "";
if (count($where) > 0) {
    $where_sql = "WHERE " . implode(" AND ", $where);
}

// --- Hitung total buku ---
$result_count = mysqli_query($conn, "SELECT COUNT(*) AS total FROM buku $where_sql");
$total_buku = mysqli_fetch_assoc($result_count)['total'];
$total_pages = ceil($total_buku / $limit);

// --- Ambil data buku (dengan nama kategori) ---
$query = mysqli_query($conn, "
    SELECT buku.*, kategori.nama_kategori 
    FROM buku 
    INNER JOIN kategori ON buku.id_kategori = kategori.id_kategori
    $where_sql
    LIMIT $start, $limit
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Buku - BookSmart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    body {
      background-color: #f8f9fa;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    .navbar {
      position: relative; z-index: 1030; }
    /* Dropdown menu muncul di atas semua elemen */
    .profile-dropdown .dropdown-menu { position: absolute; z-index: 2000 !important; }
    main {
      flex: 1;
    }
    .card { border-radius: 10px; overflow: hidden; transition: transform 0.2s ease; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    .card:hover { transform: scale(1.03);   }
    .card-img-top { width: 100%; height: 297px; object-fit: cover; }
    .card-body { padding: 12px; text-align: center; display: flex; flex-direction: column; justify-content: space-between; }
    .card-title { font-size: 14px; font-weight: 600; margin-bottom: 6px; }
    .card-text { font-size: 13px; color: #555; margin-bottom: 6px; 
    }
    .text-success { margin-bottom: 15px; }
    .card-body .btn { font-size: 13px; border-radius: 6px; padding: 7px 10px; width: 100%; }
    .btn-wrapper { display: flex; flex-direction: column; gap: 10px; margin-top: auto; }
    .col-custom { width: 16.66%; }
    @media (max-width: 1200px) {
      .col-custom { width: 20%; }
    }
    @media (max-width: 992px) {
      .col-custom { width: 25%; }
    }
    @media (max-width: 768px) {
      .col-custom { width: 33.33%; }
    }
    @media (max-width: 576px) {
      .col-custom { width: 50%; }
    }
    .row-flex { display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; }
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
        <li class="nav-item"><a class="nav-link active" href="daftar_buku.php">Daftar Buku</a></li>
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
<main class="container mt-5">
  <h1 class="text-center mb-4">Daftar Buku</h1>

  <!-- Search Bar -->
  <form class="d-flex justify-content-center mb-4" method="GET" action="">
    <select name="kategori" class="form-select w-auto me-2">
      <option value="">Semua Kategori</option>
      <?php foreach ($kategori_list as $kat): ?>
        <option value="<?php echo $kat['id_kategori']; ?>" 
          <?php echo ($selected_kategori == $kat['id_kategori']) ? 'selected' : ''; ?>>
          <?php echo htmlspecialchars($kat['nama_kategori']); ?>
        </option>
      <?php endforeach; ?>
    </select>

    <input type="text" name="search" class="form-control w-50" 
      placeholder="Cari judul, penulis, atau penerbit..." 
      value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit" class="btn btn-primary ms-2">Cari</button>
  </form>

  <!-- Daftar Buku -->
  <div class="row-flex">
    <?php if (mysqli_num_rows($query) > 0): ?>
      <?php while ($buku = mysqli_fetch_assoc($query)): ?>
        <div class="col-custom">
          <div class="card h-100">
            <img src="../assets/img/<?php echo $buku['gambar']; ?>" class="card-img-top" alt="<?php echo $buku['judul']; ?>">
            <div class="card-body">
              <h5 class="card-title"><?php echo $buku['judul']; ?></h5>
              <p class="card-text"><strong>Penulis:</strong> <?php echo $buku['penulis']; ?></p>
              <p class="card-text"><strong>Penerbit:</strong> <?php echo $buku['penerbit']; ?></p>
              <p class="card-text"><strong>Kategori:</strong> <?php echo $buku['nama_kategori'] ?></p>
              <p class="text-success fw-bold mb-2">Rp <?php echo number_format($buku['harga'], 0, ',', '.'); ?></p>

              <div class="btn-wrapper">
                <a href="detail_buku.php?id=<?php echo $buku['id_buku']; ?>" class="btn btn-outline-primary">Detail</a>
                <?php if (isset($_SESSION['id_user'])): ?>
                  <a href="tambah_keranjang.php?id=<?php echo $buku['id_buku']; ?>" class="btn btn-primary">Tambah ke Keranjang</a>
                <?php else: ?>
                  <a href="login_user.php" class="btn btn-primary">Tambah ke Keranjang</a>
                <?php endif; ?>
                </div>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="text-center">Tidak ada buku yang ditemukan.</p>
    <?php endif; ?>
  </div>

  <!-- Pagination -->
  <nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center mt-4">
      <?php if ($page > 1): ?>
        <li class="page-item">
          <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&kategori=<?php echo $selected_kategori; ?>">Sebelumnya</a>
        </li>
      <?php endif; ?>

      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
          <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&kategori=<?php echo $selected_kategori; ?>"><?php echo $i; ?></a>
        </li>
      <?php endfor; ?>

      <?php if ($page < $total_pages): ?>
        <li class="page-item">
          <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&kategori=<?php echo $selected_kategori; ?>">Selanjutnya</a>
        </li>
      <?php endif; ?>
    </ul>
  </nav>
</main>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-3 mt-5">
  <p class="mb-0">&copy; 2025 BookSmart | Toko Buku Online</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>