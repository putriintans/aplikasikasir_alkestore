<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_kasir";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $sql = "SELECT * FROM tbl_users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['address'] = $user['address'];
        $_SESSION['gender'] = $user['gender'];
        $_SESSION['city'] = $user['city'];

        if ($user['role'] === 'customer') {
            $_SESSION['keranjang'] = [];

            $cart_query = mysqli_prepare($conn, "SELECT product_id, quantity FROM tbl_cart WHERE user_id = ?");
            mysqli_stmt_bind_param($cart_query, "i", $user['id']);
            mysqli_stmt_execute($cart_query);
            $cart_result = mysqli_stmt_get_result($cart_query);

            while ($cart = mysqli_fetch_assoc($cart_result)) {
                $_SESSION['keranjang'][$cart['product_id']] = $cart['quantity'];
            }

            mysqli_stmt_close($cart_query);
        }

        if ($user['role'] === 'admin') {
            header("Location: ../dashboard_admin.php");
        } elseif ($user['role'] === 'customer') {
            header("Location: ../home_customer.php");
        } else {
            header("Location: ../home_customer.php");
        }
        exit;
    } else {
        // Redirect kembali ke form login dengan pesan error
        header("Location: ../app/views/auth/login_form.php?error=1");
        exit;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
