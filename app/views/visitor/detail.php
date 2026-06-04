<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include_once __DIR__ . '/../../../config/database.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = mysqli_query($conn, "SELECT * FROM tbl_products WHERE id=$id");
$p = mysqli_fetch_assoc($product);

if (!$p) {
    echo "<p>Produk tidak ditemukan!</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Detail Produk - Alkestore</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
<div class="container mt-4">
  <h3><?= htmlspecialchars($p['name']) ?></h3>
  <div class="row mt-3">
    <div class="col-md-5">
      <img src="/aplikasikasir_alkestore/assets/images/<?= htmlspecialchars($p['image']) ?>" class="img-fluid">
    </div>
    <div class="col-md-7">
      <h4>Rp<?= number_format($p['price'], 0, ',', '.') ?></h4>
      <p><?= htmlspecialchars($p['description']) ?></p>
      <p><strong>Stok:</strong> <?= $p['stock'] ?></p>
    </div>
  </div>
  <a href="home.php" class="btn btn-secondary mt-4">Kembali</a>
</div>
</body>
</html>
