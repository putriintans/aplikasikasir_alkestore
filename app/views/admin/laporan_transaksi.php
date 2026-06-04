<?php
$conn = new mysqli("localhost", "root", "", "db_kasir");

// Data rekap total bulanan
$result = $conn->query("
    SELECT 
        DATE_FORMAT(created_at, '%M %Y') AS bulan,
        COUNT(*) AS jumlah_transaksi,
        SUM(total) AS total_pemasukan
    FROM tbl_orders
    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
    ORDER BY created_at DESC
");

// Untuk tabel rekap
$rekap = [];
while ($row = $result->fetch_assoc()) {
    $rekap[] = $row;
}

// Data untuk grafik line 12 bulan terakhir
$resLine = $conn->query("
    SELECT DATE_FORMAT(created_at, '%M %Y') AS bulan, SUM(total) AS total_pemasukan
    FROM tbl_orders
    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
    ORDER BY created_at DESC LIMIT 12
");
$labelsLine = [];
$dataLine = [];
while ($r = $resLine->fetch_assoc()) {
    array_unshift($labelsLine, $r['bulan']);
    array_unshift($dataLine, $r['total_pemasukan']);
}

// Data untuk pie chart metode bayar bulan ini
$resPie = $conn->query("
    SELECT payment_method, COUNT(*) AS total
    FROM tbl_orders
    WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())
    GROUP BY payment_method
");
$labelsPie = [];
$dataPie = [];
while ($r = $resPie->fetch_assoc()) {
    $labelsPie[] = $r['payment_method'] ?: 'Tidak Diketahui';
    $dataPie[] = $r['total'];
}

// Hitung ringkasan bulan ini
$resSummary = $conn->query("
    SELECT COUNT(*) AS total_transaksi, SUM(total) AS total_pemasukan
    FROM tbl_orders
    WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())
");
$summary = $resSummary->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Bulanan</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container-fluid py-4">
    <h2 class="mb-4">Laporan Transaksi Bulanan</h2>

    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="border border-primary p-3 rounded text-center shadow-sm">
                <h6>Total Transaksi Bulan Ini</h6>
                <h2 class="text-primary"><?= $summary['total_transaksi'] ?? 0 ?></h2>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="border border-success p-3 rounded text-center shadow-sm">
                <h6>Total Pemasukan Bulan Ini</h6>
                <h2 class="text-success">Rp <?= number_format($summary['total_pemasukan'] ?? 0, 0, ',', '.') ?></h2>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-8 mb-3">
            <div class="card shadow-sm">
                <div class="card-header">Grafik Total Pemasukan 12 Bulan Terakhir</div>
                <div class="card-body">
                    <canvas id="lineChart" height="80"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-header">Metode Pembayaran Bulan Ini</div>
                <div class="card-body">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">Rekap Transaksi Bulanan</div>
        <div class="card-body p-0">
            <table class="table mb-0 table-striped">
                <thead class="table-light">
                    <tr>
                        <th>Bulan</th>
                        <th>Jumlah Transaksi</th>
                        <th>Total Pemasukan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rekap as $r): ?>
                    <tr>
                        <td><?= $r['bulan'] ?></td>
                        <td><?= $r['jumlah_transaksi'] ?></td>
                        <td>Rp <?= number_format($r['total_pemasukan'], 0, ',', '.') ?></td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
const ctxLine = document.getElementById('lineChart').getContext('2d');
new Chart(ctxLine, {
    type: 'line',
    data: {
        labels: <?= json_encode($labelsLine) ?>,
        datasets: [{
            label: 'Total Pemasukan',
            data: <?= json_encode($dataLine) ?>,
            borderColor: '#007bff',
            backgroundColor: 'rgba(0, 123, 255, 0.2)',
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#007bff',
            pointRadius: 5,
            pointHoverRadius: 7
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { labels: { font: { size: 14 }}},
            tooltip: {
                backgroundColor: '#343a40',
                titleColor: '#fff',
                bodyColor: '#fff'
            }
        },
        scales: {
            y: { beginAtZero: true, grid: { color: "rgba(0,0,0,0.1)" }},
            x: { grid: { color: "rgba(0,0,0,0.05)" }}
        }
    }
});

const ctxPie = document.getElementById('pieChart').getContext('2d');
new Chart(ctxPie, {
    type: 'pie',
    data: {
        labels: <?= json_encode($labelsPie) ?>,
        datasets: [{
            data: <?= json_encode($dataPie) ?>,
            backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1']
        }]
    },
    options: {
        plugins: {
            legend: {
                position: 'right',
                labels: { font: { size: 14 }}
            }
        }
    }
});
</script>
</body>
</html>
