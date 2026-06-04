<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
require_once __DIR__ . '/vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pdf'])) {
    $conn = new mysqli("localhost", "root", "", "db_kasir");

    if (!isset($_SESSION['username'])) {
        http_response_code(403);
        exit("Tidak diizinkan");
    }

    $username = $_SESSION['username'];
    $res = $conn->query("SELECT email FROM tbl_users WHERE username = '$username'");
    $userData = $res->fetch_assoc();
    $customer_email = $userData['email'] ?? '';

    if (!$customer_email) {
        http_response_code(400);
        exit("Email tidak ditemukan.");
    }

    $pdfTmpPath = $_FILES['pdf']['tmp_name'];
    $pdfName = $_FILES['pdf']['name'];

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = '22082010159@student.upnjatim.ac.id';
        $mail->Password = 'ppfqfwwoefwlpmcx'; 
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('22082010159@student.upnjatim.ac.id', 'Alkestore');
        $mail->addAddress($customer_email);
        $mail->isHTML(true);
        $mail->Subject = 'Laporan Pembelian Anda - Alkestore';
        $mail->Body    = "Halo $username,<br><br>Terima kasih telah berbelanja di Alkestore. Silakan lihat lampiran PDF untuk laporan belanja Anda.<br><br>Salam,<br>Alkestore";

        $mail->addAttachment($pdfTmpPath, $pdfName);

        $mail->send();
        echo "✅ PDF berhasil dikirim ke email $customer_email!";
    } catch (Exception $e) {
        echo "❌ Gagal kirim email: {$mail->ErrorInfo}";
    }
} else {
    http_response_code(400);
    echo "Request tidak valid atau file PDF tidak ditemukan.";
}