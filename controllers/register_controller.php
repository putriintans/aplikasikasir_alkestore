<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    require_once("../config/database.php"); // jika kamu sudah punya file koneksi, atau gunakan langsung di sini

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $retype_password = $_POST['retype_password'] ?? '';
    $email = $_POST['email'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $address = $_POST['address'] ?? '';
    $city = $_POST['city'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $paypal_id = $_POST['paypal_id'] ?? '';

    if ($password !== $retype_password) {
        echo "Password tidak cocok!";
        exit;
    }

    $conn = mysqli_connect("localhost", "root", "", "db_kasir");
    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $role = "customer"; // default role

    $sql = "INSERT INTO tbl_users 
            (username, password, email, date_of_birth, gender, address, city, contact_no, paypal_id, role) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssssss", 
        $username, $hashed_password, $email, $dob, $gender, 
        $address, $city, $contact, $paypal_id, $role
    );

    if (mysqli_stmt_execute($stmt)) {
        header("Location: /aplikasikasir_alkestore/app/views/auth/login_form.php?status=registered");
        exit;
    } else {
        echo "Error saat menyimpan data: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    
} else {
    echo "Akses langsung tidak diperbolehkan!";
}
