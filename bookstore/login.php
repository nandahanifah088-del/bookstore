<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pilih Login | BookSmart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .card {
      width: 400px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      padding: 30px;
      text-align: center;
    }
    .btn {
      width: 100%;
      border-radius: 8px;
      font-size: 16px;
      padding: 10px;
      margin-top: 15px;
    }
  </style>
</head>
<body>
  <div class="card">
    <h3 class="mb-4">Login ke BookSmart</h3>
    <a href="../admin/login_admin.php" class="btn btn-dark">Login sebagai Admin</a>
    <a href="login_user.php" class="btn btn-primary">Login sebagai User</a>
  </div>
</body>
</html>