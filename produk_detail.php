<?php
$conn = new mysqli("localhost", "root", "", "db_kasir");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Produk tidak ditemukan.");
}

$id = intval($_GET['id']);
$query = $conn->query("SELECT * FROM tbl_products WHERE id = $id");

if ($query->num_rows === 0) {
    die("Produk tidak ditemukan.");
}

$product = $query->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Produk - <?= htmlspecialchars($product['name']) ?></title>
    <style>
        body {
            font-family: sans-serif;
            background: #f5f5f5;
            padding: 40px;
        }

        .detail-container {
            max-width: 700px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        }

        .detail-container img {
            width: 100%;
            max-height: 300px;
            object-fit: contain;
            border-radius: 8px;
        }

        .detail-container h2 {
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .detail-container p {
            margin: 10px 0;
            line-height: 1.6;
        }

        .detail-container .price {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
        }

        .detail-container .back-btn {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #fff;
            background: #007bff;
            padding: 10px 15px;
            border-radius: 5px;
        }

        .detail-container .back-btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<div class="detail-container">
    <img src="assets/images/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
    <h2><?= htmlspecialchars($product['name']) ?></h2>
    <p class="price">Rp <?= number_format($product['price'], 0, ',', '.') ?></p>
    <p><strong>Stok:</strong> <?= $product['stock'] ?> unit</p>
    <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>

    <a class="back-btn" href="home_customer.php"> Kembali</a>
</div>
</body>
</html>