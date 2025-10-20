<?php
session_start();
include 'koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
    // Kalau belum login, arahkan ke halaman login
    echo "<script>alert('Silakan login terlebih dahulu untuk menambahkan ke keranjang!');</script>";
    echo "<script>window.location='login_user.php';</script>";
    exit;
}

// Ambil id_user dari session dan id_buku dari URL
$id_user = $_SESSION['id_user'];
$id_buku = $_GET['id'] ?? null;

if (!$id_buku) {
    echo "<script>alert('ID buku tidak ditemukan!');</script>";
    echo "<script>window.location='daftar_buku.php';</script>";
    exit;
}

// Cek apakah buku ini sudah ada di keranjang user
$cek = mysqli_query($conn, "SELECT * FROM keranjang WHERE id_user='$id_user' AND id_buku='$id_buku'");
if (mysqli_num_rows($cek) > 0) {
    // Kalau sudah ada, tambahkan jumlah +1
    mysqli_query($conn, "UPDATE keranjang SET jumlah = jumlah + 1 WHERE id_user='$id_user' AND id_buku='$id_buku'");
} else {
    // Kalau belum ada, tambahkan sebagai item baru
    $tanggal = date('Y-m-d');
    mysqli_query($conn, "INSERT INTO keranjang (id_user, id_buku, jumlah, tanggal_ditambahkan)
                         VALUES ('$id_user', '$id_buku', 1, '$tanggal')");
}

// Kembali ke halaman sebelumnya
echo "<script>alert('Buku berhasil ditambahkan ke keranjang!');</script>";
echo "<script>window.location='cart.php';</script>";
exit;
?>