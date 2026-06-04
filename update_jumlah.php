<?php
session_start();
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "db_kasir");
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'DB connection failed']);
    exit;
}

if (isset($_POST['id']) && isset($_POST['jumlah'])) {
    $id = intval($_POST['id']);
    $jumlah = max(1, intval($_POST['jumlah']));

    $_SESSION['keranjang'][$id] = $jumlah;

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $check = $conn->prepare("SELECT * FROM tbl_cart WHERE user_id = ? AND product_id = ?");
        $check->bind_param("ii", $user_id, $id);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $stmt = $conn->prepare("UPDATE tbl_cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
            $stmt->bind_param("iii", $jumlah, $user_id, $id);
            $stmt->execute();
            $stmt->close();
        } else {
            $stmt = $conn->prepare("INSERT INTO tbl_cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $user_id, $id, $jumlah);
            $stmt->execute();
            $stmt->close();
        }
        $check->close();
    }

    $produk = $conn->query("SELECT price FROM tbl_products WHERE id = $id")->fetch_assoc();
    $subtotal = $produk['price'] * $jumlah;

    $total = 0;
    foreach ($_SESSION['keranjang'] as $prodId => $qty) {
        $res = $conn->query("SELECT price FROM tbl_products WHERE id = $prodId")->fetch_assoc();
        $total += $res['price'] * $qty;
    }

    echo json_encode([
        'success' => true,
        'subtotal' => number_format($subtotal, 0, ',', '.'),
        'total' => number_format($total, 0, ',', '.')
    ]);
    exit;
}

echo json_encode(['success' => false]);