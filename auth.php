<?php
session_start();
$userid = $_POST['userid'] ?? '';
$password = $_POST['password'] ?? '';

if ($userid === 'admin' && $password === '123456') {
    $_SESSION['user_id'] = $userid;
    header('Location: dashboard.php');
} else {
    echo "Login gagal. <a href='login.php'>Coba lagi</a>";
}