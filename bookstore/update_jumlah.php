<?php
include 'koneksi.php';

if (isset($_POST['id_keranjang'], $_POST['jumlah'])) {
    $id_keranjang = $_POST['id_keranjang'];
    $jumlah = $_POST['jumlah'];

    $update = mysqli_query($conn, "UPDATE keranjang SET jumlah='$jumlah' WHERE id_keranjang='$id_keranjang'");
    echo $update ? "success" : "error";
}
?>