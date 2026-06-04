<?php
$currentPage = $_GET['page'] ?? 'home';
?>

<div class="sidebar">
  <div class="text-center mb-4">
    <h5 class="text-white fw-bold">Alkestore Admin</h5>
  </div>

  <p class="text-white-50 small mb-1 fw-semibold">MAIN MENU</p>
  <a href="?page=home" class="<?= $currentPage === 'home' ? 'active-link' : '' ?>">
    Dashboard
  </a>

  <p class="text-white-50 small mt-3 mb-1 fw-semibold">PRODUK</p>
 <a href="/aplikasikasir_alkestore/app/views/admin/kategori_produk.php">Kategori Produk</a>
  <a href="?page=produk" class="<?= $currentPage === 'produk' ? 'active-link' : '' ?>">
    Manajemen Produk
  </a>

  <p class="text-white-50 small mt-3 mb-1 fw-semibold">TRANSAKSI</p>
  <a href="?page=penjualan_barang" class="<?= $currentPage === 'penjualan_barang' ? 'active-link' : '' ?>">
    Penjualan Barang
  </a>
    <a href="?page=laporan_transaksi" class="<?= $currentPage === 'laporan_transaksi' ? 'active-link' : '' ?>">
    Laporan 
  </a>
  </a>

  <p class="text-white-50 small mt-3 mb-1 fw-semibold">PENGGUNA</p>
  <a href="?page=data_customer" class="<?= $currentPage === 'data_customer' ? 'active-link' : '' ?>">
    Data Customer
  </a>
  <a href="?page=guestbook" class="<?= $currentPage === 'guestbook' ? 'active-link' : '' ?>">
    Buku Tamu
  </a>
  <a href="?page=feedback" class="<?= $currentPage === 'feedback' ? 'active-link' : '' ?>">
    Feedback
  </a>

<a href="logout_admin.php" style="color: red; text-decoration: none;">
    <i class="fas fa-lock"></i> Logout
</a>
</div>

<style>
  .sidebar {
    height: 100vh;
    background-color: #3b5eb8;
    padding: 20px;
    color: white;
    width: 230px;
    position: fixed;
    overflow-y: auto;
  }

  .sidebar a {
    display: block;
    padding: 8px 12px;
    margin-bottom: 8px;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    transition: 0.2s;
  }

  .sidebar a:hover {
    background-color: #4e71d1;
  }

  .sidebar a.active-link {
    background-color: #1c3faa;
    font-weight: bold;
  }

  .sidebar .logout a {
    display: block;
    margin-top: 20px;
  }
</style>
