<?php
session_start();
include 'koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Silakan login terlebih dahulu untuk melihat keranjang!');</script>";
    echo "<script>window.location='login_user.php';</script>";
    exit;
}

$id_user = $_SESSION['id_user'];
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
// Hapus item dari keranjang
if (isset($_GET['hapus'])) {
    $id_keranjang = (int)$_GET['hapus'];
    mysqli_query($conn, "DELETE FROM keranjang WHERE id_keranjang='$id_keranjang' AND id_user='$id_user'");
    echo "<script>alert('Item dihapus dari keranjang!'); window.location='cart.php';</script>";
    exit;
}

// Ambil data keranjang user beserta data buku
$query = mysqli_query($conn, "
    SELECT k.id_keranjang, k.jumlah, b.id_buku, b.judul, b.harga, b.gambar, b.stok
    FROM keranjang k
    JOIN buku b ON k.id_buku = b.id_buku
    WHERE k.id_user = '$id_user'
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Keranjang - BookSmart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    body {
      background-color: #f8f9fa;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    main { flex: 1; }
    .cart-img {
      width: 70px;
      height: 100px;
      object-fit: cover;
      border-radius: 6px;
    }
    table {
      background: white;
      border-radius: 10px;
      overflow: hidden;
    }
    th {
      background-color: #343a40;
      color: white;
      text-align: center;
    }
    td { vertical-align: middle; text-align: center; }
    .btn-hapus {
      background-color: #dc3545;
      color: white;
    }
    .btn-hapus:hover {
      background-color: #c82333;
      color: white;
    }
    input[type="number"] {
      width: 60px;
      text-align: center;
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
        <li class="nav-item"><a class="nav-link active" href="cart.php">Keranjang</a></li>
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
<!-- Konten -->
<main class="container mt-5">
  <h2 class="text-center mb-4">Keranjang Belanja Anda</h2>

  <?php if (mysqli_num_rows($query) > 0): ?>
    <div class="table-responsive">
      <table class="table align-middle text-center" id="cartTable">
        <thead>
          <tr>
            <th><input type="checkbox" id="selectAll"></th>
            <th>No</th>
            <th>Gambar</th>
            <th>Judul Buku</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $no = 1;
          while ($row = mysqli_fetch_assoc($query)): 
              $subtotal = $row['harga'] * $row['jumlah'];
          ?>
          <tr data-id="<?php echo $row['id_keranjang']; ?>" data-price="<?php echo $row['harga'];?>" data-stok="<?php echo $row['stok']; ?>">
            <td><input type="checkbox" class="item-checkbox"></td>
            <td><?php echo $no++; ?></td>
            <td><img src="../assets/img/<?php echo $row['gambar']; ?>" class="cart-img"></td>
            <td><?php echo $row['judul']; ?></td>
            <td class="harga">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
            <td>
              <input type="number" min="1" value="<?php echo $row['jumlah']; ?>" class="form-control form-control-sm jumlah" style="display:inline-block;width:70px;">
            </td>
            <td class="subtotal">Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
            <td>
              <a href="cart.php?hapus=<?php echo $row['id_keranjang']; ?>" class="btn btn-hapus btn-sm" onclick="return confirm('Yakin ingin menghapus buku ini dari keranjang?')">Hapus</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <div class="text-end mt-3">
      <h5>Total: <span id="totalHarga" class="text-success fw-bold">Rp 0</span></h5>
      <form id="checkoutForm" action="checkout.php" method="POST">
        <input type="hidden" id="selectedItems" name="selectedItems">
        
        <button type="submit" id="checkoutBtn" class="btn btn-primary mt-3" disabled>
            Lanjut ke Checkout
        </button>
      </form>
    </div>
  <?php else: ?>
    <div class="alert alert-info text-center">Keranjang Anda masih kosong.</div>
  <?php endif; ?>
</main>

<footer class="bg-dark text-white text-center py-3 mt-5">
  <p class="mb-0">&copy; 2025 BookSmart | Toko Buku Online</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
  const checkboxes = document.querySelectorAll(".item-checkbox");
  const selectAll = document.getElementById("selectAll");
  const totalEl = document.getElementById("totalHarga");
  const checkoutBtn = document.getElementById("checkoutBtn");
  const selectedInput = document.getElementById("selectedItems");

  // format rupiah
  const formatRupiah = angka => "Rp " + angka.toLocaleString("id-ID");

  // hitung total
  function hitungTotal() {
    let total = 0;
    checkboxes.forEach(cb => {
      if (cb.checked) {
        const tr = cb.closest("tr");
        const subtotalText = tr.querySelector(".subtotal").innerText.replace(/[^\d]/g, "");
        total += parseInt(subtotalText) || 0;
      }
    });
    totalEl.textContent = formatRupiah(total);
    checkoutBtn.disabled = total === 0;
  }

  // update subtotal & validasi jumlah
  document.querySelectorAll(".jumlah").forEach(input => {
    input.addEventListener("input", function() {
      const tr = this.closest("tr");
      const harga = parseInt(tr.dataset.price);
      const stok = parseInt(tr.dataset.stok);
      let jumlah = parseInt(this.value) || 1;

      if (jumlah > stok) {
        jumlah = stok;
        this.value = stok;
        alert(`Jumlah melebihi stok (${stok})`);
      } else if (jumlah < 1) {
        jumlah = 1;
        this.value = 1;
      }

      const subtotal = harga * jumlah;
      tr.querySelector(".subtotal").innerText = formatRupiah(subtotal);
      hitungTotal();

      const idKeranjang = tr.dataset.id;
      fetch("update_jumlah.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "id_keranjang=" + idKeranjang + "&jumlah=" + jumlah
      });
    });
  });

  // checkbox individual
  checkboxes.forEach(cb => {
    cb.addEventListener("change", () => {
      selectAll.checked = Array.from(checkboxes).every(c => c.checked);
      hitungTotal();
    });
  });

  // checkbox "pilih semua"
  selectAll.addEventListener("change", () => {
    checkboxes.forEach(cb => cb.checked = selectAll.checked);
    hitungTotal();
  });

  // tombol checkout
  checkoutBtn.addEventListener("click", function(event) {
    event.preventDefault();

    const selectedIds = Array.from(checkboxes)
      .filter(cb => cb.checked)
      .map(cb => cb.closest("tr").dataset.id);

    if (selectedIds.length === 0) {
      alert("Silakan pilih minimal satu buku sebelum checkout!");
      return;
    }

    // masukkan ID yang dipilih ke input hidden
    selectedInput.value = selectedIds.join(",");

    // ubah action form
    const form = document.getElementById("checkoutForm");
    form.action = "checkout.php?keranjang=" + encodeURIComponent(selectedIds.join(","));
    form.submit();
  });
});
</script>
</body>
</html>