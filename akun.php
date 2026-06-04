<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "db_kasir");

$user_id = $_SESSION['user_id'];
$query = "SELECT username, email, gender, date_of_birth, address, city, contact_no FROM tbl_users WHERE id = $user_id";
$result = $conn->query($query);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profil Akun</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: #f0f2f5;
            padding: 40px;
        }
        .card {
            background: #fff;
            border-radius: 12px;
            padding: 30px 40px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        }
        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }
        label {
            font-weight: bold;
            color: #34495e;
        }
        .btn-primary {
            background-color: #3498db;
            border: none;
        }
        .btn-primary:hover {
            background-color: #2980b9;
        }
        .btn-secondary {
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>Ubah Data Akun</h2>
    <form method="POST" action="proses_update_profil.php">
        <div class="mb-3">
            <label>Username:</label>
            <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Jenis Kelamin:</label>
            <select name="gender" class="form-control">
                <option value="male" <?= $user['gender'] == 'male' ? 'selected' : '' ?>>Laki-laki</option>
                <option value="female" <?= $user['gender'] == 'female' ? 'selected' : '' ?>>Perempuan</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Tanggal Lahir:</label>
            <input type="date" name="date_of_birth" value="<?= $user['date_of_birth'] ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label>Alamat:</label>
            <input type="text" name="address" value="<?= htmlspecialchars($user['address']) ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label>Kota:</label>
            <input type="text" name="city" value="<?= htmlspecialchars($user['city']) ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label>No HP:</label>
            <input type="text" name="contact_no" value="<?= htmlspecialchars($user['contact_no']) ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label>Ubah Password (kosongkan jika tidak diubah):</label>
            <input type="password" name="new_password" class="form-control" placeholder="Masukkan password baru">
        </div>
        <button type="submit" class="btn btn-primary w-100">Simpan</button>
        <a href="home_customer.php" class="btn btn-secondary w-100">Kembali</a>
    </form>
</div>

</body>
</html>