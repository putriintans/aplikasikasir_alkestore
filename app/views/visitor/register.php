<?php
include_once __DIR__ . '/../../../config/database.php';

$notif = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $cek = mysqli_query($conn, "SELECT id FROM tbl_users WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        $notif = "Username sudah digunakan!";
    } else {
        mysqli_query($conn, "INSERT INTO tbl_users (username, email, password, role) VALUES ('$username', '$email', '$password', 'customer')");
        header("Location: /aplikasikasir_alkestore/app/views/auth/login_form.php?success=1");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register - Alkestore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5">
    <h3>Daftar Akun Baru</h3>
    <?php if ($notif): ?>
        <div class="alert alert-danger"><?= $notif ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button class="btn btn-success">Daftar</button>
    </form>
    <p class="mt-3">Sudah punya akun? <a href="/aplikasikasir_alkestore/app/views/auth/login_form.php">Login di sini</a></p>
</div>
</body>
</html>