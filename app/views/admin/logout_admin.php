<?php
session_start();
session_destroy(); // Hapus semua session

// Arahkan kembali ke halaman home customer di root
header("Location: /aplikasikasir_alkestore/home_customer.php");
exit();
?>
