<?php
session_start();
$conn = new mysqli("localhost", "root", "", "db_kasir");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$items = [];
$total = 0;

if (!empty($_SESSION['keranjang'])) {
    $ids = implode(',', array_keys($_SESSION['keranjang']));
    $result = $conn->query("SELECT * FROM tbl_products WHERE id IN ($ids)");

    while ($row = $result->fetch_assoc()) {
        $row['jumlah'] = $_SESSION['keranjang'][$row['id']];
        $row['subtotal'] = $row['price'] * $row['jumlah'];
        $items[] = $row;
        $total += $row['subtotal'];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Keranjang Belanja</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            padding: 30px;
        }

        .cart-box {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
            font-weight: normal;
        }

        .qty-input {
            width: 60px;
            padding: 6px;
            border: 1px solid #ccc;
            border-radius: 4px;
            text-align: center;
        }

        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: white;
            text-decoration: none;
        }

        .btn-hapus {
            background-color: #dc3545;
        }

        .btn-lanjut, .btn-kembali {
            padding: 10px 18px;
            text-decoration: none;
            color: white;
            border-radius: 5px;
            margin-top: 20px;
            display: inline-block;
        }

        .btn-kembali {
            background: #6c757d;
        }

        .btn-lanjut {
            background: #007bff;
            margin-left: 10px;
        }

        .total-box {
            text-align: left;
            margin-top: 15px;
            font-size: 18px;
            font-weight: bold;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="cart-box">
    <h2>Keranjang Belanja</h2>

    <?php if (count($items) > 0): ?>
        <div>
            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td>Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                            <td>
                                <input type="number" class="qty-input" data-id="<?= $item['id'] ?>" value="<?= $item['jumlah'] ?>" min="1">
                            </td>
                            <td class="subtotal" id="subtotal-<?= $item['id'] ?>">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                            <td>
                                <a href="hapus_keranjang.php?id=<?= $item['id'] ?>" class="btn btn-hapus">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="total-box">
                <strong>Total Belanja (termasuk pajak): <span id="total-belanja">Rp <?= number_format($total, 0, ',', '.') ?></span></strong>
            </div>

            <div style="margin-top: 20px;">
                <a href="home_customer.php" class="btn btn-kembali">Kembali Belanja</a>
                <a href="checkout.php" class="btn btn-lanjut">Lanjut Checkout </a>
            </div>
        </div>
    <?php else: ?>
        <p style="text-align:center;">Keranjang masih kosong.</p>
        <div style="text-align:center;">
            <a href="home_customer.php" class="btn btn-kembali">Belanja Sekarang</a>
        </div>
    <?php endif; ?>
</div>

<script>
document.querySelectorAll('.qty-input').forEach(input => {
    input.addEventListener('change', function() {
        const productId = this.dataset.id;
        const quantity = this.value;

        fetch('update_jumlah.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `id=${productId}&jumlah=${quantity}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('subtotal-' + productId).innerText = 'Rp ' + data.subtotal;
                document.getElementById('total-belanja').innerText = 'Rp ' + data.total;
                 Swal.fire({
        icon: 'success',
        title: 'Jumlah Diperbarui',
        text: 'Jumlah produk berhasil diubah.',
        timer: 1500,
        showConfirmButton: false
    });
            }
        });
    });
});
</script>
<?php if (isset($_GET['hapus']) && $_GET['hapus'] === 'berhasil'): ?>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Item Dihapus',
        text: 'Produk berhasil dihapus dari keranjang.',
        timer: 1500,
        showConfirmButton: false
    });
</script>
<?php endif; ?>

</body>
</html>
