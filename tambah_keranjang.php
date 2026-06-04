<?php
session_start();
$conn = new mysqli("localhost", "root", "", "db_kasir");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: produk.php");
    exit;
}

$id = intval($_GET['id']);

if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

if (isset($_SESSION['keranjang'][$id])) {
    $_SESSION['keranjang'][$id]++;
} else {
    $_SESSION['keranjang'][$id] = 1;
}

if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'customer') {
    $user_id = $_SESSION['user_id'];
    $jumlah = $_SESSION['keranjang'][$id];

    $cek = $conn->prepare("SELECT id FROM tbl_cart WHERE user_id = ? AND product_id = ?");
    $cek->bind_param("ii", $user_id, $id);
    $cek->execute();
    $result = $cek->get_result();

    if ($result->num_rows > 0) {
        $update = $conn->prepare("UPDATE tbl_cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $update->bind_param("iii", $jumlah, $user_id, $id);
        $update->execute();
        $update->close();
    } else {
        $insert = $conn->prepare("INSERT INTO tbl_cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $insert->bind_param("iii", $user_id, $id, $jumlah);
        $insert->execute();
        $insert->close();
    }

    $cek->close();
}

header("Location: keranjang.php?status=added");
exit;
?>