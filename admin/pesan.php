<?php
session_start();
include '../bookstore/koneksi.php';

// Pastikan hanya admin yang login
if (!isset($_SESSION['id_admin'])) {
    header("Location: login_admin.php");
    exit;
}
// tombol hapus semua 
if (isset($_POST['hapus_semua'])) {
    mysqli_query($conn, "TRUNCATE TABLE pesan");
    echo "<script>alert('Semua pesan berhasil dihapus dan ID direset!'); window.location='pesan.php';</script>";
    exit;
}
// Ambil daftar user yang pernah kirim pesan
// jadi MAX() biar nama yang tidak kosong diambil
$userQuery = mysqli_query($conn, "
    SELECT id_user, COALESCE(MAX(nama_pengirim), 'User') AS nama_pengirim
    FROM pesan
    GROUP BY id_user
    ORDER BY nama_pengirim ASC
");

// Proses balasan admin
if (isset($_POST['balas'])) {
    $id_pesan = $_POST['id_pesan'];
    $balasan = mysqli_real_escape_string($conn, $_POST['balasan']);

    mysqli_query($conn, "
        UPDATE pesan 
        SET balasan='$balasan', status='Sudah Dibalas', dibaca_user='0' 
        WHERE id_pesan='$id_pesan'
    ");


    echo "<script>alert('Balasan terkirim!'); window.location='pesan.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesan User - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
    .chat-box {
        max-height: 300px;
        overflow-y: auto;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 10px;
        background-color: #f8f9fa;
    }
    .bubble-user {
        background-color: #e9ecef;
        border-radius: 15px;
        padding: 8px 12px;
        display: inline-block;
        max-width: 70%;
        text-align: left;
    }
    .bubble-admin {
        background-color: #0d6efd;
        color: white;
        border-radius: 15px;
        padding: 8px 12px;
        display: inline-block;
        max-width: 70%;
        text-align: left;
    }
    .msg-user {
        text-align: left;
        margin-bottom: 10px;
    }
    .msg-admin {
        text-align: right;
        margin-bottom: 10px;
    }
    .timestamp {
        font-size: 0.8em;
        color: #6c757d;
    }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="main-content p-4">
    <div class="container-fluid mt-4">
        <div class="d-flex align-items-center justify-content-center position-relative mb-4">
            <button id="toggle-btn" class="btn btn-outline-secondary position-absolute start-0"><i class="bi bi-list"></i></button>
            <h2 class="fw-bold mb-0">Pesan dari User</h2>
<!-- tombol hapus -->        
<form method="POST" onsubmit="return confirm('Yakin ingin menghapus semua pesan? Data tidak bisa dikembalikan!');"
    class="position-absolute end-0 m-0">
    <button type="submit" name="hapus_semua" class="btn btn-danger mb-3">
        <i class="bi bi-trash"></i> Hapus Semua Pesan
    </button>
</form>
</div>
    <?php while($u = mysqli_fetch_assoc($userQuery)): ?>
        <?php
            // tandai hanya pesan dari user (bukan balasan admin) yang sudah dibaca admin
            mysqli_query($conn, "
                UPDATE pesan 
                SET dibaca_admin = 1 
                WHERE id_user = '{$u['id_user']}' 
                AND balasan IS NULL
            ");
        ?>
        <div class="mb-4">
            <h5 class="mb-2">
                👤 <?= htmlspecialchars($u['nama_pengirim'] ?? 'User') ?>
            </h5>
            <div class="chat-box mb-2">
                <?php
                $pesanQuery = mysqli_query($conn, "
                    SELECT * FROM pesan 
                    WHERE id_user = '{$u['id_user']}' 
                    ORDER BY tanggal DESC
                ");
                while($p = mysqli_fetch_assoc($pesanQuery)):
                ?>
                    <!-- Pesan User -->
                    <div class="msg-user">
                        <div class="bubble-user">
                            <?= nl2br(htmlspecialchars($p['isi'])) ?>
                        </div>
                        <div class="timestamp"><?= date('d M Y H:i', strtotime($p['tanggal'])) ?></div>
                    </div>

                    <!-- Balasan Admin -->
                    <?php if($p['balasan']): ?>
                        <div class="msg-admin">
                            <div class="bubble-admin">
                                <?= nl2br(htmlspecialchars($p['balasan'])) ?>
                            </div>
                            <div class="timestamp"><?= date('d M Y H:i', strtotime($p['tanggal'])) ?> (balasan)</div>
                        </div>
                    <?php else: ?>
                        <!-- Form Balasan -->
                        <form method="POST" class="msg-admin">
                            <input type="hidden" name="id_pesan" value="<?= $p['id_pesan'] ?>">
                            <div class="input-group" style="max-width: 70%; float: right;">
                                <input type="text" name="balasan" class="form-control" placeholder="Ketik balasan..." required>
                                <button type="submit" name="balas" class="btn btn-primary">Balas</button>
                            </div>
                        </form>
                        <div style="clear: both;"></div>
                    <?php endif; ?>

                <?php endwhile; ?>
            </div>
        </div>
    <?php endwhile; ?>

    <?php if(mysqli_num_rows($userQuery) == 0): ?>
        <div class="text-center text-muted mt-4">Belum ada pesan dari user.</div>
    <?php endif; ?>
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