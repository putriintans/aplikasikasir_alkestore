<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$conn = new mysqli("localhost", "root", "", "db_kasir");

// Handle update status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['new_status'])) {
    $orderId = intval($_POST['order_id']);
    $newStatus = $conn->real_escape_string($_POST['new_status']);
    $conn->query("UPDATE tbl_orders SET status_pengiriman = '$newStatus' WHERE id = $orderId");
}

// Handle filter
$filterStatus = isset($_GET['status']) ? $conn->real_escape_string($_GET['status']) : '';
$where = "";
if ($filterStatus && in_array($filterStatus, ['pending', 'diproses', 'dikirim', 'selesai', 'batal'])) {
    $where = "WHERE status_pengiriman = '$filterStatus'";
}

// Hitung rekap
$statusCounts = [];
$resultCount = $conn->query("SELECT status_pengiriman, COUNT(*) as total FROM tbl_orders GROUP BY status_pengiriman");
while ($r = $resultCount->fetch_assoc()) {
    $statusCounts[$r['status_pengiriman']] = $r['total'];
}

// Data pesanan
$orders = $conn->query("SELECT * FROM tbl_orders $where ORDER BY created_at DESC");

$allStatuses = ['pending' => 'warning', 'diproses' => 'primary', 'dikirim' => 'info', 'selesai' => 'success', 'batal' => 'danger'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Penjualan Barang - Alkestore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container-fluid px-0 mt-4">

    <div class="px-3">
        <h3 class="mb-4">Transaksi Penjualan Barang</h3>

        <!-- Ringkasan -->
        <div class="mb-4 d-flex flex-wrap gap-3">
            <?php foreach ($allStatuses as $status => $color): ?>
                <div class="card border-<?= $color ?> border-2 shadow-sm" style="width: 160px;">
                    <div class="card-body text-center p-2">
                        <h6 class="mb-1"><?= ucfirst($status) ?></h6>
                        <span class="badge bg-<?= $color ?>"><?= $statusCounts[$status] ?? 0 ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Tabel -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-primary text-center">
                <tr>
                    <th>No</th>
                    <th>Nama Customer</th>
                    <th>Tanggal Order</th>
                    <th>Total</th>
                    <th>Metode Bayar</th>
                    <th>Status</th>
                    <th>Ubah Status</th>
                </tr>
                </thead>
                <tbody>
                <?php $no = 1; while ($row = $orders->fetch_assoc()): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['user_name']) ?></td>
                        <td><?= date('d-m-Y H:i', strtotime($row['order_date'])) ?></td>
                        <td>Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                        <td><?= htmlspecialchars($row['payment_method']) ?></td>
                        <td class="text-center">
                            <span class="badge bg-<?= match($row['status_pengiriman']) {
                                'pending' => 'warning',
                                'diproses' => 'primary',
                                'dikirim' => 'info',
                                'selesai' => 'success',
                                'batal' => 'danger',
                                default => 'secondary'
                            } ?>">
                                <?= ucfirst($row['status_pengiriman']) ?>
                            </span>
                        </td>
                        <td>
                            <form method="POST" class="d-flex">
                                <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                                <select name="new_status" class="form-select form-select-sm me-2" required>
                                    <option disabled selected>Pilih</option>
                                    <?php foreach (array_keys($allStatuses) as $status): ?>
                                        <option value="<?= $status ?>"><?= ucfirst($status) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="btn btn-sm btn-success">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
</body>
</html>
