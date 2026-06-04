<?php
session_start();
$conn = new mysqli("localhost", "root", "", "db_kasir");

$orderId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$order = $conn->query("SELECT * FROM tbl_orders WHERE id = $orderId")->fetch_assoc();
$items = $conn->query("SELECT * FROM tbl_order_items WHERE order_id = $orderId");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['feedback'], $_POST['rating'])) {
    $feedback = $conn->real_escape_string($_POST['feedback']);
    $rating = intval($_POST['rating']);
    $userId = $_SESSION['user_id'];
    $conn->query("INSERT INTO tbl_feedback (order_id, user_id, feedback, rating) 
                  VALUES ($orderId, $userId, '$feedback', $rating)");
    echo "<script>alert('Terima kasih atas feedbacknya!');window.location='riwayat_pesanan.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pesanan Anda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fc;
            font-family: 'Segoe UI', sans-serif;
        }
        .order-card {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        .order-header {
            background: #397eff;
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 0.75rem 0.75rem 0 0;
            margin: -1.5rem -1.5rem 1.5rem -1.5rem;
        }
        .badge-status {
            padding: 0.35em 0.75em;
            border-radius: 0.5rem;
            font-size: 0.9em;
        }
        .badge-pending { background: #f6c23e; color: #fff; }
        .badge-diproses { background: #36b9cc; color: #fff; }
        .badge-dikirim { background: #397eff; color: #fff; }
        .badge-selesai { background: #1cc88a; color: #fff; }
        .badge-batal { background: #e74a3b; color: #fff; }
        .btn-feedback {
            background: #397eff;
            color: white;
        }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="order-card">
        <div class="order-header">
            <h5 class="mb-0">Detail Pesanan Anda</h5>
        </div>
        <p><strong>Tanggal Pesanan:</strong> <?= date('d-m-Y H:i', strtotime($order['order_date'])) ?></p>
        <p><strong>Status:</strong> 
            <span class="badge-status 
                <?= $order['status_pengiriman']=='pending'?'badge-pending': 
                    ($order['status_pengiriman']=='diproses'?'badge-diproses':
                    ($order['status_pengiriman']=='dikirim'?'badge-dikirim':
                    ($order['status_pengiriman']=='selesai'?'badge-selesai':'badge-batal'))) ?>">
                <?= ucfirst($order['status_pengiriman']) ?>
            </span>
        </p>
        
        <table class="table table-hover mt-4">
            <thead class="table-light">
                <tr>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
            <?php while($item = $items->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($item['product_name']) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>Rp <?= number_format($item['price'],0,',','.') ?></td>
                    <td>Rp <?= number_format($item['price']*$item['quantity'],0,',','.') ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

<?php if (in_array($order['status_pengiriman'], ['dikirim', 'selesai'])): ?>
    <div class="order-card">
        <div class="order-header">
            <h5 class="mb-0">Beri Feedback</h5>
        </div>
        <form method="POST">
            <div class="mb-3">
                <textarea name="feedback" class="form-control" rows="3" placeholder="Tulis feedback..." required></textarea>
            </div>
            <div class="mb-3">
                <select name="rating" class="form-select" required>
                    <option value="">-- Pilih Rating --</option>
                    <option value="5">⭐⭐⭐⭐⭐</option>
                    <option value="4">⭐⭐⭐⭐</option>
                    <option value="3">⭐⭐⭐</option>
                    <option value="2">⭐⭐</option>
                    <option value="1">⭐</option>
                </select>
            </div>
            <button type="submit" class="btn btn-feedback">Kirim Feedback</button>
        </form>
    </div>
    <?php endif; ?>    
   
<div class="text-center mt-4">
    <a href="riwayat_pesanan.php" class="btn btn-secondary btn-lg px-5">
        Kembali 
    </a>
</div>
</body>
</html>