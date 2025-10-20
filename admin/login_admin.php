<?php
session_start();
include '../bookstore/koneksi.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // cek email di database admin
    $result = mysqli_query($conn, "SELECT * FROM admin WHERE email='$email'");
    $data = mysqli_fetch_assoc($result);

    if ($data) {
        // verifikasi password tanpa hash
        if ($password === $data['password']) {
            $_SESSION['id_admin'] = $data['id_admin'];
            $_SESSION['nama_admin'] = $data['nama_admin'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Email tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin | BookSmart</title>
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
    <h3 class="text-center mb-4">Login Admin</h3>
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST">
      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
      <p class="mt-3 text-center">
        Kembali ke <a href="../bookstore/login.php">halaman utama</a>
      </p>
    </form>
  </div>
</body>
</html>