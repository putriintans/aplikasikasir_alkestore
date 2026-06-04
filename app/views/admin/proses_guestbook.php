<?php
// File: app/views/admin/proses_guestbook.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include_once __DIR__ . '/../../../config/database.php';

    // Tangkap dan sanitasi input
    $name    = mysqli_real_escape_string($conn, $_POST['name']);
    $email   = mysqli_real_escape_string($conn, $_POST['email']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // Validasi dasar
    if (empty($name) || empty($email) || empty($message)) {
        header("Location: ../../../index.php?guestbook=empty");
        exit;
    }

    // Validasi email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../../../index.php?guestbook=invalid_email");
        exit;
    }

    // Simpan ke database
    $sql = "INSERT INTO tbl_guestbook (name, email, message) VALUES ('$name', '$email', '$message')";
    if (mysqli_query($conn, $sql)) {
        header("Location: ../../../index.php?guestbook=success");
        exit;
    } else {
        header("Location: ../../../index.php?guestbook=error");
        exit;
    }
} else {
    // Jika akses langsung tanpa POST
    header("Location: ../../../index.php");
    exit;
}
