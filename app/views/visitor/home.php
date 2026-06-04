<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'customer') {
    header("Location: /aplikasikasir_alkestore/app/views/auth/login_form.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "db_kasir");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = $conn->real_escape_string($_POST['message']);
    $user_id = $_SESSION['user_id'];

    $conn->query("INSERT INTO tbl_guestbook (user_id, message) VALUES ($user_id, '$message')");
    $notif = "Pesan buku tamu berhasil dikirim!";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Beranda Customer</title>
    <style>
        body { font-family: Arial; padding: 30px; background: #f9f9f9; }
        .container { max-width: 600px; margin: auto; background: #fff; padding: 25px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        textarea { width: 100%; padding: 10px; }
        button { padding: 8px 15px; background: #4d90fe; color: white; border: none; cursor: pointer; }
        .notif { color: green; margin-bottom: 15px; }
    </style>
</head>
<body>
<div class="container">
    <h2>Halo, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
    <p>Selamat datang di Toko Alkestore. Anda login sebagai <strong>customer</strong>.</p>

    <?php if (isset($notif)) echo "<div class='notif'>$notif</div>"; ?>

    <h3>Isi Buku Tamu</h3>
    <form method="POST">
        <textarea name="message" rows="4" required placeholder="Tulis pesan..."></textarea><br><br>
        <button type="submit">Kirim Pesan</button>
    </form>
</div>
</body>
</html>