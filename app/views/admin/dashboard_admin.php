<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user sudah login dan punya role admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: /aplikasikasir_alkestore/app/views/auth/login_form.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f8f9fc;
    }

    .main-content {
      margin-left: 230px; /* Menyesuaikan lebar sidebar */
      width: calc(100% - 230px);
      min-height: 100vh;
    }

    .header-box {
      background-color: #4f79f6;
      color: white;
      padding: 14px 20px;
      font-weight: bold;
      border-bottom: 1px solid #dee2e6;
    }

    .content-box {
      background-color: white;
      padding: 25px;
      margin-top: 0;
    }
  </style>
</head>
<body>

  <!-- SIDEBAR -->
  <?php include_once __DIR__ . '/sidebar.php'; ?>

  <!-- MAIN CONTENT -->
  <div class="main-content">
    <div class="header-box">
      Dashboard Admin
    </div>
    <div class="content-box">
     <?php
$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'laporan_transaksi':
        include __DIR__ . '/laporan_transaksi.php';
        break;

    case 'produk':
        include __DIR__ . '/manajemen_produk.php';
        break;

    case 'penjualan_barang':
        include __DIR__ . '/penjualan_barang.php';
        break;

    case 'kategori':
        include __DIR__ . '/kategori_produk.php';
        break;

    case 'pengiriman':
        include __DIR__ . '/pengiriman.php';
        break;

    case 'data_customer':
        include __DIR__ . '/data_customer.php';
        break;

    case 'guestbook':
        include __DIR__ . '/guestbook.php';
        break;

    case 'feedback':
        include __DIR__ . '/feedback.php'; // <-- INI AKTIFKAN HALAMAN FEEDBACK
        break;

    case 'home':
    default:
        include __DIR__ . '/dashboard_konten.php';
        break;
}
?>
    </div>
  </div>

</body>
</html>
