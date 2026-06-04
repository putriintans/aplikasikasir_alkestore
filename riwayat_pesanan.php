<?php
session_start();
$conn = new mysqli("localhost", "root", "", "db_kasir");

if (!isset($_SESSION['user_id'])) {
    header("Location: /aplikasikasir_alkestore/app/views/auth/login_form.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM tbl_orders WHERE user_id = $user_id ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pesanan - Alkestore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .order-card {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            background: white;
            margin-bottom: 20px;
        }
        .order-header {
            background-color: #397eff;
            color: white;
            padding: 10px 15px;
            font-weight: bold;
        }
        .order-body {
            padding: 15px;
        }
        .btn-kembali {
            display: flex;
            justify-content: center;
            margin-top: 40px;
        }
        .btn-kembali a {
            padding: 12px 24px;
            font-size: 18px;
            border-radius: 6px;
        }
        h2 {
            text-align: center;
            margin-bottom: 40px;
            color: #333;
        }
    </style>
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Riwayat Pesanan Anda</h2>

    <div class="row">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-6">
                    <div class="order-card">
                        <div class="order-header">
                            Tanggal Pesanan: <?= date('d-m-Y H:i', strtotime($row['created_at'])) ?>
                        </div>
                        <div class="order-body">
                            <p><strong>Total:</strong> <span style="font-size: 18px; color: #000;">Rp <?= number_format($row['total'], 0, ',', '.') ?></span></p>
                            <p><strong>Metode Bayar:</strong> <?= $row['payment_method'] ?: 'Tidak diketahui' ?></p>
                            <p><strong>Status Pengiriman:</strong> 
                                <?php
                                    $status = $row['status_pengiriman'];
                                    $badge = match ($status) {
                                        'pending'   => '<span class="badge bg-warning text-dark">⏳ Pending</span>',
                                        'diproses'  => '<span class="badge bg-info text-white">🔄 Diproses</span>',
                                        'dikirim'   => '<span class="badge bg-primary">📦 Dikirim</span>',
                                        'selesai'   => '<span class="badge bg-success">✅ Selesai</span>',
                                        'batal'     => '<span class="badge bg-danger">❌ Dibatalkan</span>',
                                        default     => '<span class="badge bg-secondary">❔ Tidak Diketahui</span>',
                                    };
                                    echo $badge;
                                ?>
                            </p>

                            <?php if ($status === 'pending'): ?>
                                <a href="batalkan_pesanan.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
                                   onclick="return confirm('Yakin ingin membatalkan pesanan ini?')">Batalkan Pesanan</a>
                            <?php else: ?>
                                <button class="btn btn-sm btn-secondary" disabled>Pesanan Sedang <?= ucfirst($status) ?></button>
                            <?php endif; ?>

                            <a href="detail_pesanan.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary ms-2">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="alert alert-info text-center">Belum ada pesanan yang tercatat.</div>
        <?php endif; ?>
    </div>

<div class="d-flex justify-content-center mt-4 mb-2">
    <a href="home_customer.php" class="btn btn-primary btn-lg px-4 shadow">
        Kembali
    </a>
</div>


<?php if (isset($_GET['cancel']) && $_GET['cancel'] == 'success'): ?>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Pesanan Dibatalkan',
        text: 'Pesanan berhasil dibatalkan.',
        timer: 2000,
        showConfirmButton: false
    });
</script>
<?php endif; ?>
</body>
</html>
