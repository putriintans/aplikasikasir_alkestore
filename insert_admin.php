<?php
include_once 'config/database.php'; 

$username = "admin";
$password_plain = "admin12345";
$password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);
$email = "admin@example.com";
$date_of_birth = "1990-01-01";
$gender = "Male";
$address = "Jl. Admin Contoh No.1";
$city = "Surabaya";
$contact_no = "081234567890";
$paypal_id = "admin@example.com";
$role = "admin";

$stmt = $conn->prepare("INSERT INTO tbl_users 
(username, password, email, date_of_birth, gender, address, city, contact_no, paypal_id, role) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("ssssssssss", 
  $username, $password_hashed, $email, $date_of_birth, $gender, 
  $address, $city, $contact_no, $paypal_id, $role);

if ($stmt->execute()) {
    echo "✅ Admin berhasil ditambahkan.";
} else {
    echo "❌ Gagal: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>