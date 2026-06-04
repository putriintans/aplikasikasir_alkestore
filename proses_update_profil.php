<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "db_kasir");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id    = $_SESSION['user_id'];
    $username   = $conn->real_escape_string($_POST['username']);
    $email      = $conn->real_escape_string($_POST['email']);

    $gender_input = $_POST['gender'];
    $allowed_gender = ['male', 'female'];
    $gender = in_array($gender_input, $allowed_gender) ? $gender_input : 'male';

    $dob        = $conn->real_escape_string($_POST['date_of_birth']);
    $address    = $conn->real_escape_string($_POST['address']);
    $city       = $conn->real_escape_string($_POST['city']);
    $contact    = $conn->real_escape_string($_POST['contact_no']);
    $new_pass   = $_POST['new_password'];

    $sql = "UPDATE tbl_users SET 
                username = '$username', 
                email = '$email', 
                gender = '$gender', 
                date_of_birth = '$dob', 
                address = '$address', 
                city = '$city', 
                contact_no = '$contact' 
            WHERE id = $user_id";

    if ($conn->query($sql)) {
        if (!empty($new_pass)) {
            $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
            $conn->query("UPDATE tbl_users SET password = '$hashed' WHERE id = $user_id");
        }

        echo "<script>
                alert('Profil berhasil diperbarui!');
                window.location.href = 'akun.php';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('Gagal memperbarui profil: " . $conn->error . "');
                window.location.href = 'akun.php';
              </script>";
        exit;
    }
} else {
    header("Location: akun.php");
    exit;
}
?>