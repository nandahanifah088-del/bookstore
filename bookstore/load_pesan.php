<?php
session_start();
include 'koneksi.php';

$id_user = $_SESSION['id_user'];

$query = mysqli_query($conn, "SELECT * FROM pesan WHERE id_user='$id_user' ORDER BY tanggal DESC");

while($p = mysqli_fetch_assoc($query)):
?>
<div class="msg-user">
    <div class="bubble-user">
        <strong>Saya</strong><br>
        <small><em><?= htmlspecialchars($p['subjek']) ?></em></small><br>
        <?= nl2br(htmlspecialchars($p['isi'])) ?>
    </div>
    <div class="timestamp"><?= date('d M Y H:i', strtotime($p['tanggal'])) ?></div>
</div>
<?php if($p['balasan']): ?>
<div class="msg-admin">
    <div class="bubble-admin">
        <strong>Admin</strong><br>
        <?= nl2br(htmlspecialchars($p['balasan'])) ?>
    </div>
    <div class="timestamp"><?= date('d M Y H:i', strtotime($p['tanggal'])) ?> (balasan)</div>
</div>
<?php endif; ?>
<?php endwhile; ?>
<?php if(mysqli_num_rows($query) == 0): ?>
<div class="text-center text-muted mt-4">Belum ada pesan.</div>
<?php endif; ?>