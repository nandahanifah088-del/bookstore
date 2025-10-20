<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login_user.php");
    exit;
}
// notif
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
$id_user = $_SESSION['id_user'];
// saat user buka halaman pesan, ubah semua balasan dari admin jadi sudah dibaca
mysqli_query($conn, "
    UPDATE pesan 
    SET dibaca_user = 1 
    WHERE id_user = '$id_user' 
      AND balasan IS NOT NULL
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pesan Saya</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"> 
<link rel="stylesheet" href="../assets/css/style.css">
<style>
    .navbar {
      position: relative;
      z-index: 1030;
    }

    /* Dropdown menu muncul di atas semua elemen */
    .profile-dropdown .dropdown-menu {
      position: absolute;
      z-index: 2000 !important;
    }
.chat-box { background-color: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); max-height: 500px; overflow-y: auto; }
.chat-container { max-width: 800px; margin: 0 auto; }
.back-btn { position: fixed; top: 20px; left: 20px; z-index: 1000; background-color: #6c757d; color: white; border: none; padding: 8px 12px; border-radius: 10px; cursor: pointer; font-size: 1.2rem; box-shadow: 0 2px 6px rgba(0,0,0,0.2); transition: background-color 0.3s; }
.back-btn:hover { background-color: #5a6268; }
.bubble-user { background-color: #0d6efd; color: white; border-radius: 15px; padding: 10px 15px; display: inline-block; max-width: 70%; text-align: left; }
.bubble-admin { background-color: #e9ecef; border-radius: 15px; padding: 10px 15px; display: inline-block; max-width: 70%; text-align: left; }
.msg-user { text-align: right; margin-bottom: 15px; }
.msg-admin { text-align: left; margin-bottom: 15px; }
.timestamp { font-size: 0.8em; color: #6c757d; }
#chatForm .form-control { border: 1px solid #dee2e6; background-color: #f8f9fa; transition: all 0.2s ease-in-out; }
#chatForm .form-control:focus { background-color: #fff; border-color: #0d6efd; box-shadow: 0 0 0 0.2rem rgba(13,110,253,0.25); }
#chatForm .btn-primary { background-color: #0d6efd; border: none; transition: background-color 0.2s ease-in-out; }
#chatForm .btn-primary:hover { background-color: #0b5ed7; }
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
        <li class="nav-item"><a class="nav-link" href="cart.php">Keranjang</a></li>
        <li class="nav-item"><a class="nav-link active" href="contact.php">Kontak</a></li>
      </ul>

      <?php if (isset($_SESSION['nama'])): ?>
        <div class="dropdown profile-dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
            <img src="profil.png" class="profile-img" alt="Profil">
            <span class="text-white ms-2"><?php echo $_SESSION['nama']; ?></span>
            <?php if ($unread_count > 0): ?>
            <span class="badge bg-danger ms-2" style="font-size: 0.7em;"><?php echo $unread_count; ?></span>
          <?php endif; ?>
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
<div class="main-content p-4">
    <h2 class="text-center mb-4">Pesan Saya</h2>

    <div class="chat-container">
        <div class="chat-box" id="chatBox"></div>
    </div>
    <!-- Form kirim pesan baru -->
<form id="chatForm" class="mt-4">
  <div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-3">
      <div class="row g-2 align-items-center">
        <div class="col-md-3">
          <input 
            type="text" 
            name="subjek" 
            class="form-control rounded-pill" 
            placeholder="Subjek" 
            required>
        </div>
        <div class="col-md-7">
          <input 
            type="text" 
            name="isi" 
            class="form-control rounded-pill" 
            placeholder="Tulis pesan..." 
            required>
        </div>
        <div class="col-md-2 d-grid">
          <button 
            type="submit" 
            class="btn btn-primary rounded-pill fw-semibold">
            <i class="bi bi-send-fill me-1"></i> Kirim
          </button>
        </div>
      </div>
    </div>
  </div>
</form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function loadChat() {
    $.get('load_pesan.php', function(data){
        $('#chatBox').html(data);
        $('#chatBox').scrollTop($('#chatBox')[0].scrollHeight);
    });
} 
// Load chat pertama kali
loadChat();

// Auto-refresh chat tiap 3 detik
setInterval(loadChat, 3000);

// Kirim pesan via AJAX
$('#chatForm').on('submit', function(e){
    e.preventDefault();
    $.post('kirim_pesan_ajax.php', $(this).serialize(), function(){
        $('#chatForm')[0].reset();
        loadChat();
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>