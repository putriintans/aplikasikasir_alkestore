<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$conn = new mysqli("localhost", "root", "", "db_kasir");

// Tangkap filter
$keyword = isset($_GET['keyword']) ? $conn->real_escape_string($_GET['keyword']) : '';
$rating_filter = isset($_GET['rating_filter']) ? intval($_GET['rating_filter']) : 0;

// Query dasar
$query = "SELECT f.*, o.user_name, o.id as order_id 
          FROM tbl_feedback f
          JOIN tbl_orders o ON o.id = f.order_id 
          WHERE 1=1";

// Filter keyword
if ($keyword) {
    $query .= " AND f.feedback LIKE '%$keyword%'";
}

// Filter rating
if ($rating_filter > 0) {
    $query .= " AND f.rating = $rating_filter";
}

$query .= " ORDER BY f.created_at DESC";
$result = $conn->query($query);

// Hitung rata-rata rating
$avgResult = $conn->query("SELECT AVG(rating) as avg_rating FROM tbl_feedback");
$avgData = $avgResult->fetch_assoc();
$avgRating = round($avgData['avg_rating'], 2);
?>

<h4 class="mb-4">Feedback Customer</h4>

<!-- Rata-rata rating -->
<div class="alert alert-primary" role="alert">
    ⭐ Rata-rata Rating: <strong><?= $avgRating ?></strong> dari 5
</div>

<!-- Filter form -->
<form method="GET" action="" class="row g-2 mb-4">
    <input type="hidden" name="page" value="feedback">
    <div class="col-auto">
        <input type="text" name="keyword" class="form-control form-control-sm" placeholder="Cari feedback..."
            value="<?= htmlspecialchars($keyword) ?>">
    </div>
    <div class="col-auto">
        <select name="rating_filter" class="form-select form-select-sm">
            <option value="">-- Semua Rating --</option>
            <?php for ($i = 5; $i >= 1; $i--): ?>
                <option value="<?= $i ?>" <?= $rating_filter == $i ? 'selected' : '' ?>>
                    <?= $i ?> Bintang
                </option>
            <?php endfor; ?>
        </select>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
    </div>
</form>


<!-- Tabel feedback -->
<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="table-primary text-center">
            <tr>
                <th>No</th>
                <th>Nama Customer</th>
                <th>ID Pesanan</th>
                <th>Feedback</th>
                <th>Rating</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            while ($row = $result->fetch_assoc()): 
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['user_name']) ?></td>
                <td><?= $row['order_id'] ?></td>
                <td><?= htmlspecialchars($row['feedback']) ?></td>
                <td class="text-center">
                    <?php for ($s = 1; $s <= $row['rating']; $s++): ?>
                        <i class="fa fa-star text-warning"></i>
                    <?php endfor; ?>
                </td>
                <td><?= date('d-m-Y H:i', strtotime($row['created_at'])) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<style>
    table td, table th {
        vertical-align: middle !important;
    }
</style>

<!-- Font Awesome for stars -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
