<?php
session_start();
include '../bookstore/koneksi.php';

// Cek admin login
if (!isset($_SESSION['id_admin'])) {
    header("Location: login_admin.php");
    exit;
}

// Ambil ID Pesanan
$id_pesanan = $_GET['id_pesanan'] ?? null;
if(!$id_pesanan){
    echo "<script>alert('Pesanan tidak ditemukan'); window.location='kelola_pesanan.php';</script>";
    exit;
}

// Ambil data pesanan + user
$pesanan_query = mysqli_query($conn, "
    SELECT p.*, u.nama, u.email 
    FROM pesanan p
    JOIN user u ON p.id_user = u.id_user
    WHERE p.id_pesanan='$id_pesanan'
");
$pesanan = mysqli_fetch_assoc($pesanan_query);
if(!$pesanan){
    echo "<script>alert('Pesanan tidak valid'); window.location='kelola_pesanan.php';</script>";
    exit;
}

// Ambil detail buku
$detail_query = mysqli_query($conn, "
    SELECT d.*, b.judul, b.harga, b.gambar 
    FROM detail_pesanan d
    JOIN buku b ON d.id_buku = b.id_buku
    WHERE d.id_pesanan='$id_pesanan'
");

// Ubah status
if(isset($_POST['ubah_status'])){
    $status_baru = $_POST['status'];
    mysqli_query($conn, "UPDATE pesanan SET status='$status_baru' WHERE id_pesanan='$id_pesanan'");
    echo "<script>alert('Status berhasil diperbarui'); window.location='detail_pesanan_admin.php?id_pesanan=$id_pesanan';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Pesanan - Admin BookSmart</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/style.css">
<style>
    body { background-color: #f8f9fa; }
    .breadcrumb a { text-decoration: none; }
    .page-header {
        background: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .card-header { font-weight: 600; }
</style>
</head>
<body>

<div class="container my-4">

    <!-- Header & Breadcrumb -->
    <div class="page-header">
        <h2 class="fw-bold mb-2"><i class="bi bi-box-seam"></i> Detail Pesanan</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="kelola_pesanan.php">Kelola Pesanan</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Pesanan</li>
            </ol>
        </nav>
    </div>

    <!-- Informasi Pesanan -->
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">Informasi Pesanan</div>
        <div class="card-body row">
            <div class="col-md-6">
                <p><strong>User:</strong> <?= htmlspecialchars($pesanan['nama']); ?> (<?= htmlspecialchars($pesanan['email']); ?>)</p>
                <p><strong>Tanggal:</strong> <?= date('d M Y, H:i', strtotime($pesanan['tanggal'])); ?></p>
                <p><strong>Metode Pembayaran:</strong> <?= $pesanan['metode_pembayaran']; ?></p>
            </div>
            <div class="col-md-6">
                <?php if($pesanan['metode_pembayaran']=='Transfer Bank' && $pesanan['bukti_transfer']): ?>
                    <p><strong>Bukti Transfer:</strong></p>
                    <img src="../assets/bukti_transfer/<?= $pesanan['bukti_transfer']; ?>" class="img-fluid mb-2" style="max-width:200px; border-radius:5px;">
                <?php endif; ?>
                <?php 
                $status = $pesanan['status'];
                [$badgeClass, $icon] = match($status){
                    'diproses'   => ['bg-warning text-dark', '⏳'],
                    'dikirim'    => ['bg-info text-dark', '🚚'],
                    'selesai'    => ['bg-success', '✅'],
                    'dibatalkan' => ['bg-danger', '❌'],
                    default      => ['bg-secondary', 'ℹ️']
                };
                ?>
                <p><strong>Status:</strong>
                    <span class="badge rounded-pill <?= $badgeClass; ?> text-capitalize p-2">
                        <?= $icon . ' ' . htmlspecialchars($status); ?>
                    </span>
                </p>
            </div>
        </div>
    </div>

    <!-- Daftar Buku -->
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">Daftar Buku</div>
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Judul Buku</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no=1; $total=0;
                    while($d=mysqli_fetch_assoc($detail_query)):
                        $subtotal = $d['harga']*$d['jumlah'];
                        $total+=$subtotal;
                    ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td>
                            <img src="../assets/img/<?= $d['gambar']; ?>" alt="<?= htmlspecialchars($d['judul']); ?>" style="width:60px; height:80px; object-fit:cover; border-radius:5px;">
                        </td>
                        <td><?= htmlspecialchars($d['judul']); ?></td>
                        <td>Rp <?= number_format($d['harga'],0,',','.'); ?></td>
                        <td><?= $d['jumlah']; ?></td>
                        <td>Rp <?= number_format($subtotal,0,',','.'); ?></td>
                    </tr>
                    <?php endwhile; ?>
                    <tr class="table-light">
                        <td colspan="5" class="text-end fw-bold">Total Bayar:</td>
                        <td class="fw-bold text-success">Rp <?= number_format($total,0,',','.'); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Ubah Status -->
    <?php if($pesanan['status'] != 'selesai' && $pesanan['status'] != 'dibatalkan'): ?>
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">Ubah Status Pesanan</div>
        <div class="card-body">
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Pilih Status</label>
                    <select name="status" class="form-select" required>
                        <option value="diproses" <?= $pesanan['status']=='diproses'?'selected':''; ?>>Diproses</option>
                        <option value="dikirim" <?= $pesanan['status']=='dikirim'?'selected':''; ?>>Dikirim</option>
                        <option value="selesai" <?= $pesanan['status']=='selesai'?'selected':''; ?>>Selesai</option>
                    </select>
                </div>
                <button type="submit" name="ubah_status" class="btn btn-success"><i class="bi bi-save"></i> Simpan</button>
                <a href="kelola_pesanan.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
            </form>
        </div>
    </div>
    <?php else: ?>
        <div class="text-end">
            <a href="kelola_pesanan.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>