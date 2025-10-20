<?php
session_start();
include '../bookstore/koneksi.php';

// Pastikan hanya admin
if (!isset($_SESSION['id_admin'])) {
    header("Location: login_admin.php");
    exit;
}

// Batalkan pesanan
if (isset($_GET['batal'])) {
    $id_pesanan = mysqli_real_escape_string($conn, $_GET['batal']);
    $detail_query = mysqli_query($conn, "SELECT * FROM detail_pesanan WHERE id_pesanan='$id_pesanan'");
    while($d = mysqli_fetch_assoc($detail_query)){
        mysqli_query($conn, "UPDATE buku SET stok = stok + {$d['jumlah']} WHERE id_buku={$d['id_buku']}");
    }
    mysqli_query($conn, "UPDATE pesanan SET status='dibatalkan' WHERE id_pesanan='$id_pesanan'");
    echo "<script>alert('Pesanan berhasil dibatalkan.'); window.location='kelola_pesanan.php';</script>";
    exit;
}

// Tentukan status filter
$status_filter = $_GET['status'] ?? 'all';
$where = ($status_filter == 'all') ? '' : "WHERE p.status='$status_filter'";

// Ambil data pesanan
$query = mysqli_query($conn, "
    SELECT p.*, u.nama 
    FROM pesanan p
    JOIN user u ON p.id_user = u.id_user
    $where
    ORDER BY p.id_pesanan DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kelola Pesanan - Admin BookSmart</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/style.css">
<style>
.main-content { margin-left:250px; padding:20px; transition: margin-left 0.3s; }
.main-content.sidebar-collapsed { margin-left:0; }
</style>
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="main-content">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-center position-relative mb-3">
        <button id="toggle-btn" class="btn btn-outline-secondary position-absolute start-0">
            <i class="bi bi-list"></i>
        </button>
        <h2 class="fw-bold mb-0">Kelola Pesanan</h2>
    </div>

    <!-- Tabs Status -->
    <ul class="nav nav-tabs mb-3 justify-content-center">
        <?php
        $tabs = ['all'=>'Semua','diproses'=>'Diproses','dikirim'=>'Dikirim','selesai'=>'Selesai','dibatalkan'=>'Dibatalkan'];
        foreach($tabs as $key => $label){
            $active = ($status_filter==$key)?'active':'';
            echo "<li class='nav-item'><a class='nav-link $active' href='?status=$key'>$label</a></li>";
        }
        ?>
    </ul>

    <!-- Tabel Pesanan -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>No Pesanan</th>
                    <th>Nama User</th>
                    <th>Tanggal</th>
                    <th>Total Bayar</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no=1;
                while($p=mysqli_fetch_assoc($query)):
                    $total_bayar = number_format($p['jumlah_bayar'],0,',','.');
                    $status = $p['status'];
                    [$badgeClass, $icon] = match($status){
                        'diproses'   => ['bg-warning text-dark', '⏳'],
                        'dikirim'    => ['bg-info text-dark', '🚚'],
                        'selesai'    => ['bg-success', '✅'],
                        'dibatalkan' => ['bg-danger', '❌'],
                        default      => ['bg-secondary', 'ℹ️']
                    };
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $p['id_pesanan']; ?></td>
                    <td><?= htmlspecialchars($p['nama']); ?></td>
                    <td><?= date('d M Y, H:i', strtotime($p['tanggal'])); ?></td>
                    <td>Rp <?= $total_bayar; ?></td>
                    <td>
                        <span class="badge rounded-pill <?= $badgeClass; ?> text-capitalize p-2">
                            <?= $icon . ' ' . htmlspecialchars($status); ?>
                        </span>
                    </td>
                    <td>
                        <a href="detail_pesanan_admin.php?id_pesanan=<?= $p['id_pesanan']; ?>" class="btn btn-sm btn-info">Detail</a>
                        <?php if($p['status'] != 'dibatalkan' && $p['status'] != 'selesai'): ?>
                            <a href="?batal=<?= $p['id_pesanan']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin membatalkan pesanan ini?');">Batalkan</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php if(mysqli_num_rows($query)==0): ?>
                <tr><td colspan="7">Belum ada pesanan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
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