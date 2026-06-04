<?php
session_start();
$conn = new mysqli("localhost", "root", "", "db_kasir");

if (!isset($_SESSION['username'])) {
    die("Akses ditolak: silakan login terlebih dahulu.");
}

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$order = $conn->query("SELECT * FROM tbl_orders WHERE id = $order_id")->fetch_assoc();
$items = $conn->query("SELECT * FROM tbl_order_items WHERE order_id = $order_id");
?>
<html>
<head>
    <title>Laporan Belanja - Alkestore</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #eef2f5;
            padding: 30px;
        }
        .report {
            background: #fff;
            border-radius: 10px;
            padding: 40px;
            max-width: 850px;
            margin: auto;
            box-shadow: 0 4px 25px rgba(0,0,0,0.08);
        }
        h2, h3, h4 {
            text-align: center;
            color: #1d3557;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table th, table td {
            border: 1px solid #ccc;
            padding: 10px 12px;
            text-align: center;
        }
        table th {
            background-color: #f1f5f9;
            color: #333;
        }
        .no-border {
            border: none;
            margin-bottom: 20px;
        }
        .no-border td {
            border: none;
            padding: 6px 0;
        }
        .text-left {
            text-align: left;
        }
        .total {
            margin-top: 30px;
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            color: #1d3557;
        }
        .signature {
            margin-top: 60px;
            text-align: right;
            font-size: 15px;
        }
        .signature strong {
            font-size: 16px;
        }
        .buttons {
            margin-top: 40px;
            text-align: center;
        }
        .buttons button {
            background-color: #4d90fe;
            border: none;
            color: white;
            padding: 10px 20px;
            margin: 5px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 5px;
            transition: background 0.3s ease;
        }
        .buttons button:hover {
            background-color: #0056b3;
        }
        .buttons a button {
            background-color: #6c757d;
            color: white;
        }
        .buttons a button:hover {
            background-color: #5a6268;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="report" id="laporan">
    <h3 style="text-align: center; margin-bottom: 5px;">
        <span style="color:#397eff;">Toko Alat Kesehatan Alkestore</span> 
    </h3>
    <p style="text-align: center; font-size: 16px; margin-top: 0;">
        Terima kasih telah berbelanja. Berikut rincian pembelian Anda:
    </p>

    <table class="no-border">
        <tr>
            <td class="text-left">User ID: <?= $order['user_id'] ?? '-' ?></td>
            <td class="text-left">Tanggal: <?= date("d-m-Y", strtotime($order['order_date'])) ?></td>
        </tr>
        <tr>
            <td class="text-left">Nama: <?= htmlspecialchars($order['user_name']) ?></td>
            <td class="text-left">ID Paypal: <?= $order['paypal_id'] ?? '-' ?></td>
        </tr>
        <tr>
            <td class="text-left">Alamat: <?= htmlspecialchars($order['user_address']) ?></td>
            <td class="text-left">Nama Bank: <?= $order['bank_name'] ?? '-' ?></td>
        </tr>
        <tr>
            <td class="text-left">No HP: <?= $order['contact_no'] ?></td>
            <td class="text-left">Cara Bayar: <?= strtoupper($order['payment_method']) ?></td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Produk dengan ID-nya</th>
                <th>Jumlah</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            $total = 0;
            while ($row = $items->fetch_assoc()):
                $total += $row['subtotal'];
            ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($row['product_name']) . ' (ID: ' . htmlspecialchars($row['product_code']) . ')' ?></td>
                <td><?= $row['quantity'] ?></td>
                <td>Rp <?= number_format($row['subtotal'], 0, ',', '.') ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <p class="total">Total belanja (termasuk pajak): <u>Rp <?= number_format($total, 0, ',', '.') ?></u></p>

    <p class="signature">
        Salam Sejahtera,<br><strong>Alkestore</strong>
    </p>

    <div class="buttons no-print">
        <button onclick="downloadPDF()">Cetak Invoice</button>
        <a href="home_customer.php"><button>Kembali Belanja</button></a>
    </div>
</div>

<script>
function downloadPDF() {
    const element = document.getElementById('laporan');
    const buttons = document.querySelector('.buttons');
    buttons.style.display = 'none';

    setTimeout(() => {
        const opt = {
            margin:       0.3,
            filename:     'Laporan-Belanja-Alkestore.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2 },
            jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' }
        };

        html2pdf().set(opt).from(element).outputPdf('blob').then(blob => {
            buttons.style.display = 'block';

            // Kirim PDF ke server
            const formData = new FormData();
            formData.append('pdf', blob, 'Laporan-Belanja-Alkestore.pdf');

            fetch('kirim_email.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.text())
            .then(msg => {
                console.log(msg);
                alert(msg);
            })
            .catch(err => {
                console.error(err);
                alert("❌ Gagal mengirim email PDF.");
            });

            // Simpan ke komputer user
            html2pdf().set(opt).from(element).save();
        });
    }, 300);
}
</script>

</body>
</html>