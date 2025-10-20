<?php
include 'koneksi.php';

if (isset($_POST['register'])) {
    // Escape input untuk keamanan dasar
    $nama  = mysqli_real_escape_string($conn, trim($_POST['nama']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = mysqli_real_escape_string($conn, $_POST['password']); // SIMPAN PLAIN TEXT 
    
    // cek apakah email sudah dipakai
    $cek = mysqli_query($conn, "SELECT * FROM user WHERE email='$email'");
    if (mysqli_num_rows($cek) > 0) {
        $error = "Email sudah terdaftar!";
    } else {
        $query = mysqli_query($conn, "INSERT INTO user (nama, email, password, telepon, alamat, foto) VALUES ('$nama','$email','$password','','','')");
        if ($query) {
            echo "<script>alert('Pendaftaran berhasil! Silakan login.'); window.location='login_user.php';</script>";
            exit;
        } else {
            $error = "Pendaftaran gagal! Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register User | BookSmart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .card {
      width: 400px;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>
  <div class="card">
    <h3 class="text-center mb-4">Daftar Akun User</h3>
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="POST">
      <div class="mb-3">
        <label>Nama Lengkap</label>
        <input type="text" name="nama" class="form-control" required value="<?php echo isset($nama) ? htmlspecialchars($nama) : ''; ?>">
      </div>
      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
      </div>
      <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" name="register" class="btn btn-success w-100">Daftar</button>
      <p class="mt-3 text-center">
        Sudah punya akun? <a href="login_user.php">Login</a>
      </p>
    </form>
  </div>
</body>
</html>