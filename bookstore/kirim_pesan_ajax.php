<?php
session_start();
include 'koneksi.php';

$id_user = $_SESSION['id_user'];

$subjek = mysqli_real_escape_string($conn, $_POST['subjek']);
$isi = mysqli_real_escape_string($conn, $_POST['isi']);
$tanggal = date('Y-m-d H:i:s');

mysqli_query($conn, "INSERT INTO pesan (id_user, subjek, isi, tanggal) VALUES ('$id_user', '$subjek', '$isi', '$tanggal')");