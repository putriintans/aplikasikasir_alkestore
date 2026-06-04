<?php
session_start();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    unset($_SESSION['keranjang'][$id]);

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $conn = new mysqli("localhost", "root", "", "db_kasir");
        if (!$conn->connect_error) {
            $stmt = $conn->prepare("DELETE FROM tbl_cart WHERE user_id = ? AND product_id = ?");
            $stmt->bind_param("ii", $user_id, $id);
            $stmt->execute();
            $stmt->close();
            $conn->close();
        }
    }
}

header("Location: keranjang.php?hapus=berhasil");
exit;