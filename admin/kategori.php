<?php
session_start();
include '../bookstore/koneksi.php';

$editMode = false;
$kategori = ['id_kategori' => '', 'nama_kategori' => ''];
//hapus kategori
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM kategori WHERE id_kategori='$id'");
    header("Location: kategori.php");
    exit;
}
// ngambil data kategori ketika di klik tombol edit
if (isset($_GET['edit'])){
    $editMode = true;
    $id = $_GET['edit'];
    $query = mysqli_query($conn, "SELECT * FROM kategori WHERE id_kategori='$id'");
    $kategori_edit = mysqli_fetch_assoc($query);
}
//proses edit kategori
if (isset($_POST['update'])) {
    $id_edit = $_POST['id_kategori'];
    $nama_baru = mysqli_real_escape_string($conn, $_POST['nama_kategori']);
    mysqli_query($conn, "UPDATE kategori SET nama_kategori='$nama_baru' WHERE id_kategori='$id_edit'");
    echo "<script>
            alert('Data kategori berhasil diperbarui!');
            window.location.href = 'kategori.php';
          </script>";
    exit;
}

//tambah kategori
if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_kategori']);
    mysqli_query($conn, "INSERT INTO kategori (nama_kategori) VALUES ('$nama')");
    header("Location: kategori.php");
    exit;
}
//ambil semua kategori
$result = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kategori - Admin BookSmart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"> 
    <style>
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
    <div class="main-content p-4">
        <div class="d-flex align-items-center mb-4">
            <button id="toggle-btn" class="btn btn-outline-secondary"><i class="bi bi-list"></i></button>
            <h3 class="fw-bold mb-0">
                <?= $editMode ? 'Edit Kategori' : 'Tambah Kategori' ?>
            </h3>
        </div>
        <!-- 👉 Form Tambah / Edit Kategori -->
        <form method="POST" class="d-flex gap-2 mb-4">
            <?php if ($editMode): ?>
                <input type="hidden" name="id_kategori" value="<?= $kategori_edit['id_kategori'] ?>">
            <?php endif; ?>

            <input type="text" name="nama_kategori"class="form-control" placeholder="Nama Kategori" required value="<?= $editMode ? htmlspecialchars($kategori_edit['nama_kategori']) : '' ?>">

            <?php if ($editMode) : ?>
             <button type="submit" name="update" class="btn btn-warning">Update</button>
                <a href="kategori.php" class="btn btn-secondary">Batal</a>
            <?php else: ?>
            <button type="submit" name="tambah" class="btn btn-primary">Tambah</button>
            <?php endif ?>
        </form>
       
        <div class="card-body table-responsive">
        <table class="table table-bordered table-striped align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no =1; while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                        <td class="action-btn">
                            <a href="kategori.php?edit=<?= $row['id_kategori'] ?>" class="edit-btn"><i class="bi bi-pencil-square"></i></a>
                            <a href="kategori.php?hapus=<?= $row['id_kategori'] ?>" class="delete-btn" onclick="return confirm('Yakin ingin menghapus kategori ini?')"><i class="bi bi-trash"></i></a>  
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
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