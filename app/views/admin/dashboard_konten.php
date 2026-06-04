<?php
include_once __DIR__ . '/../../../config/database.php';

// Data statistik
$total_produk     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM tbl_products"))['total'];
$total_transaksi  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM tbl_orders"))['total'];
$total_user       = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM tbl_users WHERE role = 'customer'"))['total'];
$total_today      = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM tbl_orders WHERE DATE(created_at) = CURDATE()"))['total'];
$total_kategori   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM tbl_categories"))['total'];
$total_guestbook  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM tbl_guestbook"))['total'];
$total_feedback    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM tbl_feedback"))['total'];

// Data grafik 7 hari terakhir
$labels = [];
$data   = [];
for ($i = 6; $i >= 0; $i--) {
    $tanggal = date('Y-m-d', strtotime("-$i days"));
    $labels[] = date('d M', strtotime($tanggal));
    $query = "SELECT COUNT(*) AS total FROM tbl_orders WHERE DATE(created_at) = '$tanggal'";
    $jumlah = mysqli_fetch_assoc(mysqli_query($conn, $query))['total'];
    $data[] = $jumlah;
}

// Pie Chart data
$statusData = [];
$statusLabels = [];
$statusColors = [
    'pending' => '#f1c40f',
    'diproses' => '#3498db',
    'dikirim' => '#9b59b6',
    'selesai' => '#2ecc71',
    'gagal' => '#e74c3c',
];
$res_status = mysqli_query($conn, "SELECT status_pengiriman, COUNT(*) as total FROM tbl_orders GROUP BY status_pengiriman");
while ($row = mysqli_fetch_assoc($res_status)) {
    $statusLabels[] = ucfirst($row['status_pengiriman']);
    $statusData[] = $row['total'];
}

// Feedback chart
$feedbackLabels = [];
$feedbackData = [];
$res_feedback = mysqli_query($conn, "SELECT DATE(created_at) as tanggal, COUNT(*) as total FROM tbl_feedback WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) GROUP BY DATE(created_at)");
while ($row = mysqli_fetch_assoc($res_feedback)) {
    $feedbackLabels[] = date('d M', strtotime($row['tanggal']));
    $feedbackData[] = $row['total'];
}

// Produk terlaris
$produkTerlaris = mysqli_query($conn, "SELECT p.name, SUM(oi.quantity) as total_terjual FROM tbl_order_items oi JOIN tbl_products p ON oi.product_code = p.code GROUP BY p.name ORDER BY total_terjual DESC LIMIT 5");
?>

<!-- KOTAK STATISTIK -->
<div class="d-flex justify-content-between gap-3 flex-wrap">
  <?php
  $cards = [
    ['label' => 'Total Produk', 'value' => $total_produk, 'color' => 'primary'],
    ['label' => 'Total Transaksi', 'value' => $total_transaksi, 'color' => 'success'],
    ['label' => 'User Terdaftar', 'value' => $total_user, 'color' => 'warning'],
    ['label' => 'Transaksi Hari Ini', 'value' => $total_today, 'color' => 'info'],
    ['label' => 'Jumlah Kategori', 'value' => $total_kategori, 'color' => 'dark'],
    ['label' => 'Buku Tamu', 'value' => $total_guestbook, 'color' => 'secondary'],
    ['label' => 'Feedback Masuk', 'value' => $total_feedback, 'color' => 'danger'],
  ];
  foreach ($cards as $card) {
    echo "<div class='bg-white p-4 rounded shadow-sm text-center flex-fill' style='min-width: 180px;'>
            <p class='mb-1 text-secondary'>{$card['label']}</p>
            <h4 class='text-{$card['color']} fw-bold'>{$card['value']}</h4>
          </div>";
  }
  ?>
</div>

<!-- ROW: CHART DAN TABEL PENGIRIMAN -->
<div class="row mt-5">
  <!-- GRAFIK PENJUALAN -->
  <div class="col-md-6 mb-4">
    <div class="card shadow-sm h-70">
      <div class="card-body">
        <h5 class="card-title">Grafik Transaksi 7 Hari Terakhir</h5>
        <canvas id="salesChart" height="100"></canvas>
      </div>
    </div>
  </div>

<!-- PIE STATUS PENGIRIMAN -->
<div class="col-md-6 mb-4">
  <div class="card shadow-sm h-100">
    <div class="card-body">
      <h5 class="card-title">Distribusi Status Pengiriman</h5>
      <div style="height:300px; max-width:300px; margin:auto;">
        <canvas id="pieChart"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- ROW: FEEDBACK & PRODUK TERLARIS -->
<div class="row">
  <div class="col-md-6 mb-4">
    <div class="card shadow-sm h-70">
      <div class="card-body">
        <h5 class="card-title">Feedback 7 Hari Terakhir</h5>
        <canvas id="feedbackChart" height="100"></canvas>
      </div>
    </div>
  </div>

  <div class="col-md-6 mb-4">
    <div class="card shadow-sm h-70">
      <div class="card-body">
        <h5 class="card-title">Produk Terlaris</h5>
        <ul class="list-group">
          <?php while($row = mysqli_fetch_assoc($produkTerlaris)): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <?= htmlspecialchars($row['name']) ?>
              <span class="badge bg-success rounded-pill"><?= $row['total_terjual'] ?> terjual</span>
            </li>
          <?php endwhile; ?>
        </ul>
      </div>
    </div>
  </div>
</div>

<!-- CHART.JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Sales Chart
new Chart(document.getElementById('salesChart').getContext('2d'), {
  type: 'bar',
  data: {
    labels: <?= json_encode($labels) ?>,
    datasets: [{
      label: 'Jumlah Transaksi',
      data: <?= json_encode($data) ?>,
      backgroundColor: 'rgba(54, 162, 235, 0.5)',
      borderRadius: 4
    }]
  },
  options: {
    scales: {
      y: {
        beginAtZero: true,
        ticks: {
          precision: 0 // <<< ini membuat angka dibulatkan tanpa .0
        }
      }
    }
  }
});

// Pie Chart
new Chart(document.getElementById('pieChart').getContext('2d'), {
  type: 'pie',
  data: {
    labels: <?= json_encode($statusLabels) ?>,
    datasets: [{
      label: 'Status',
      data: <?= json_encode($statusData) ?>,
      backgroundColor: Object.values(<?= json_encode($statusColors) ?>)
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        position: 'top'
      }
    }
  }
});

// Feedback Chart
new Chart(document.getElementById('feedbackChart').getContext('2d'), {
  type: 'bar',
  data: {
    labels: <?= json_encode($feedbackLabels) ?>,
    datasets: [{
      label: 'Feedback Masuk',
      data: <?= json_encode($feedbackData) ?>,
      backgroundColor: 'rgba(255, 99, 132, 0.5)',
      borderRadius: 6
    }]
  },
  options: {
    scales: {
      y: {
        beginAtZero: true,
        ticks: {
          precision: 0, // 👉 Hilangkan desimal (1.0 → 1)
          callback: function(value) {
            return Number.isInteger(value) ? value : '';
          }
        }
      }
    },
    plugins: {
      legend: {
        display: true,
        position: 'top'
      }
    }
  }
});

</script>
