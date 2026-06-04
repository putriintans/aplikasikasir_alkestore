<?php
session_start();
$conn = new mysqli("localhost", "root", "", "db_kasir");

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: home_customer.php");
    exit;
}

$order_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

$cek = $conn->query("SELECT * FROM tbl_orders WHERE id = $order_id AND user_id = $user_id AND status_pengiriman = 'pending'");

if ($cek->num_rows === 1) {
   $conn->query("UPDATE tbl_orders SET status_pengiriman = 'batal' WHERE id = $order_id");
}

header("Location: riwayat_pesanan.php?cancel=success");
exit;
?>