<style>
.sidebar {
    width: 250px;
    background-color: #2c2c2c;
    color: #fff;
    height: 100vh;
    position: fixed;
    transition: width 0.3s ease;
    z-index: 1000;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  .sidebar.collapsed {
    width: 80px;
  }

  /* --- Title --- */
  .sidebar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    font-weight: 700;
    color: #fff;
    padding: 20px;
    transition: all 0.3s ease;
  }

  .sidebar-title i {
    font-size: 24px;
    transition: transform 0.3s ease;
  }

  .sidebar.collapsed .sidebar-text {
    display: none;
  }
  .main-content {
    margin-left: 250px;
    padding: 30px;
    transition: margin-left 0.3s ease;
  }

  .main-content.collapsed {
    margin-left: 80px;
  }

  /* --- Menu --- */
  .sidebar ul {
    list-style: none;
    padding-left: 0;
    margin: 0;
  }

  .sidebar ul li {
    padding: 12px 20px;
  }

  .sidebar ul li a {
    color: #fff;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 500;
    transition: all 0.3s ease;
  }

  .sidebar ul li a:hover {
    background-color: #3d3d3d;
    border-radius: 8px;
    box-shadow: 0 0 8px rgba(255,255,255,0.2);
  }
  .sidebar ul li a span {
  white-space: nowrap;
  overflow: hidden;
  transition: opacity 0.2s ease;
}

  .sidebar.collapsed ul li a span {
    display: none;
  }

  .sidebar .logout {
    margin-bottom: 20px;
    padding: 15px 20px;
  }

  .sidebar .logout a {
    color: #dc3545;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .sidebar .logout a:hover {
    background-color: #3d3d3d;
    border-radius: 8px;
    box-shadow: 0 0 8px rgba(255,255,255,0.2);
  }

  .sidebar.collapsed .logout a span {
    display: none;
  }
  #toggle-btn {
    background: none;
    border: none;
    color: #2c2c2c;
    font-size: 24px;
    cursor: pointer;
  }
  /* ===== Responsive ===== */
  @media (max-width: 992px) {
    .sidebar {
      width: 80px;
    }

    .sidebar.collapsed {
      width: 250px;
    }

    .main-content {
      margin-left: 80px;
    }

    .main-content.collapsed {
      margin-left: 250px;
    }

    .sidebar.collapsed .sidebar-text {
      display: inline;
    }

    .sidebar.collapsed ul li a span {
      display: inline;
    }
  }
</style>
<!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <div>
      <h4 class="text-center sidebar-title">
        <span class="sidebar-logo"><i class="bi bi-book"></i></span>
        <span class="sidebar-text">BookSmart</span>
      </h4>
      <ul>
        <li><a href="dashboard.php"><i class="bi bi-speedometer2"></i> <span>Dashboard</span></a></li>
        <li><a href="kategori.php"><i class="bi bi-tags"></i> <span>Kelola Kategori</span></a></li>
        <li><a href="kelola_buku.php"><i class="bi bi-book"></i> <span>Kelola Buku</span></a></li>
        <li><a href="user.php"><i class="bi bi-people"></i> <span>Kelola User</span></a></li>
        <li><a href="kelola_pesanan.php"><i class="bi bi-bag"></i> <span>Kelola Pesanan</span></a></li>
        <li><a href="pesan.php"><i class="bi bi-chat-dots"></i> <span>Pesan Masuk</span></a></li>
      </ul>
    </div>
    <div class="logout">
      <a href="logout_admin.php"><i class="bi bi-box-arrow-right"></i> <span>Logout</span></a>
    </div>
  </div>
  