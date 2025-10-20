<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login_user.php");
    exit;
}

$id_user = $_SESSION['id_user'];

// Ambil data user
$user_query = mysqli_query($conn, "SELECT * FROM user WHERE id_user='$id_user'");
$user = mysqli_fetch_assoc($user_query);

// Ambil daftar id_keranjang yang dikirim dari cart.php
$selected_items = [];

if (isset($_POST['keranjang'])) {
    $selected_items = explode(',', $_POST['keranjang']);
} elseif (isset($_GET['keranjang'])) {
    $selected_items = explode(',', $_GET['keranjang']);
}

// Jika tidak ada barang yang dipilih, kembali ke keranjang
if (empty($selected_items)) {
    echo "<script>alert('Silakan pilih minimal satu buku terlebih dahulu!'); window.location='cart.php';</script>";
    exit;
}

// Siapkan format untuk query IN (...)
$id_list = implode(',', array_map('intval', $selected_items));

// Ambil data keranjang hanya untuk item yang dipilih
$query = mysqli_query($conn, "
    SELECT k.*, b.judul, b.harga, b.gambar, b.stok, b.id_buku
    FROM keranjang k 
    JOIN buku b ON k.id_buku = b.id_buku 
    WHERE k.id_user = '$id_user' AND k.id_keranjang IN ($id_list)
");

$total = 0;
$items = [];
while ($row = mysqli_fetch_assoc($query)) {
    $row['subtotal'] = $row['jumlah'] * $row['harga'];
    $total += $row['subtotal'];
    $items[] = $row;
}

if (isset($_POST['checkout'])) {
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $metode = mysqli_real_escape_string($conn, $_POST['metode']);
    $tanggal = date('Y-m-d H:i:s');
    $rekening_tujuan = ($metode == 'Transfer Bank') ? '1234567890 (Bank BRI a.n BookSmart)' : '-';

    $insert_pesanan = mysqli_query($conn, "INSERT INTO pesanan (id_user, jumlah_bayar, metode_pembayaran, alamat, no_hp, rekening_tujuan, tanggal, status)
                                           VALUES ('$id_user', '$total', '$metode', '$alamat', '$no_hp', '$rekening_tujuan', '$tanggal', 'diproses')");

    if ($insert_pesanan) {
        $id_pesanan = mysqli_insert_id($conn);

        // Masukkan detail pesanan dan kurangi stok buku
        foreach ($items as $item) {
            $id_buku = $item['id_buku'];
            $jumlah = $item['jumlah'];

            // Tambah ke tabel detail_pesanan
            mysqli_query($conn, "INSERT INTO detail_pesanan (id_pesanan, id_buku, jumlah) 
                                 VALUES ('$id_pesanan', '$id_buku', '$jumlah')");

            // Kurangi stok buku
            mysqli_query($conn, "UPDATE buku SET stok = stok - $jumlah WHERE id_buku = '$id_buku'");
        }

        // Hapus item yang dipilih dari keranjang
        mysqli_query($conn, "DELETE FROM keranjang WHERE id_user='$id_user' AND id_keranjang IN ($id_list)");

        echo "<script>
                alert('Pesanan berhasil dibuat!');
                window.location='riwayat_pesanan.php';
              </script>";
        exit;
    } else {
        echo "<script>alert('Gagal membuat pesanan!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - BookSmart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-light">
<div class="container my-5 d-flex justify-content-center">
  <div class="card shadow-sm" style="width: 100%; max-width: 600px;">
        <div class="card-header bg-dark text-white text-center">
            <h4>Konfirmasi Pesanan</h4>
        </div>
        <div class="card-body">
            <p><strong>Nama Pemesan:</strong> <?= htmlspecialchars($user['nama']); ?></p>
            <hr>

            <h5 class="mb-3">Daftar Buku yang Dipesan:</h5>
            <ul>
              <?php foreach ($items as $item): ?>
                <li><?= htmlspecialchars($item['judul']); ?> (<?= $item['jumlah']; ?>x) - Rp <?= number_format($item['subtotal'], 0, ',', '.'); ?></li>
              <?php endforeach; ?>
            </ul>
            <hr>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Alamat Pengiriman</label>
                    <textarea name="alamat" class="form-control" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nomor HP</label>
                    <input type="text" name="no_hp" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Metode Pembayaran</label>
                    <select name="metode" id="metode" class="form-select" required>
                        <option value="">-- Pilih Metode --</option>
                        <option value="Transfer Bank">Transfer Bank</option>
                        <option value="COD (Bayar di Tempat)">COD (Bayar di Tempat)</option>
                    </select>
                </div>
                <!-- muncul otomatis jika pilih Transfer -->
                <div class="mb-3" id="rekeningBox" style="display:none;">
                    <label class="form-label">Rekening Tujuan</label>
                    <input type="text" class="form-control" value="1234567890 (Bank BRI a.n BookSmart)" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Total Bayar:</label>
                    <h4 class="text-success">Rp <?= number_format($total, 0, ',', '.'); ?></h4>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="cart.php" class="btn btn-secondary">← Kembali ke Keranjang</a>
                    <button type="submit" name="checkout" class="btn btn-primary">Konfirmasi Pesanan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
  const metode = document.getElementById('metode');
  const rekeningBox = document.getElementById('rekeningBox');

  metode.addEventListener('change', function() {
    if (metode.value === 'Transfer Bank') {
      rekeningBox.style.display = 'block';
    } else {
      rekeningBox.style.display = 'none';
    }
  });
});
</script>
</body>
</html>