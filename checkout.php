<?php
session_start();
$conn = new mysqli("localhost", "root", "", "db_kasir");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    die("User belum login. Silakan login terlebih dahulu.");
}
$user_id = $_SESSION['user_id'];

$user_query = $conn->query("SELECT username, address, contact_no FROM tbl_users WHERE id = $user_id");
$user_data = $user_query->fetch_assoc();

$items = [];
$total = 0;

if (!empty($_SESSION['keranjang'])) {
    $ids = implode(',', array_keys($_SESSION['keranjang']));
    $result = $conn->query("SELECT * FROM tbl_products WHERE id IN ($ids)");
    while ($row = $result->fetch_assoc()) {
        $qty = $_SESSION['keranjang'][$row['id']];
        $subtotal = $row['price'] * $qty;
        $items[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'code' => $row['code'],
            'price' => $row['price'],
            'qty' => $qty,
            'subtotal' => $subtotal
        ];
        $total += $subtotal;
    }
}

$selected_metode = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $conn->real_escape_string($_POST['nama']);
    $alamat = $conn->real_escape_string($_POST['alamat']);
    $kontak = $conn->real_escape_string($_POST['kontak']);
    $metode = $_POST['metode'];
    $selected_metode = $metode;

    if ($metode === 'prepaid') {
        $paypal_id = $conn->real_escape_string($_POST['paypal_id'] ?? '');
        $bank_name = $conn->real_escape_string($_POST['bank_name'] ?? '');
        $payment_method = 'Prepaid';
    } else {
        $paypal_id = 'COD';
        $bank_name = 'COD';
        $payment_method = 'Postpaid';
    }

    if (!empty($items)) {
        $stmt = $conn->prepare("INSERT INTO tbl_orders (user_id, order_date, paypal_id, bank_name, payment_method, total, user_name, user_address, contact_no) VALUES (?, NOW(), ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssdsss", $user_id, $paypal_id, $bank_name, $payment_method, $total, $nama, $alamat, $kontak);
        $stmt->execute();
        $order_id = $stmt->insert_id;

        $stmt_items = $conn->prepare("INSERT INTO tbl_order_items (order_id, product_name, product_code, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?, ?)");
        foreach ($items as $item) {
            $stmt_items->bind_param("issidd", $order_id, $item['name'], $item['code'], $item['qty'], $item['price'], $item['subtotal']);
            $stmt_items->execute();
        }

        $_SESSION['keranjang'] = [];
        header("Location: checkout_sukses.php?order_id=$order_id");
        exit;
    } else {
        echo "<script>alert('Keranjang kosong!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <style>
        body { font-family: sans-serif; background: #f2f2f2; padding: 20px; }
        .checkout-box { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 10px; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 10px; border-bottom: 1px solid #ccc; text-align: center; }
        .form-group { margin-bottom: 10px; }
        label { display: block; margin-bottom: 5px; }
        input, textarea, select { width: 100%; padding: 8px; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer; border-radius: 5px; }
        button:hover { background: #0056b3; }
    </style>
    <script>
        function togglePrepaidFields() {
            const metode = document.querySelector("select[name='metode']").value;
            const prepaidFields = document.getElementById("prepaid-fields");
            prepaidFields.style.display = metode === 'prepaid' ? 'block' : 'none';
        }
        window.onload = togglePrepaidFields;
    </script>
</head>
<body>
<div class="checkout-box">
    <h2>Checkout</h2>

    <form method="POST">
        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" required value="<?= htmlspecialchars($user_data['username']) ?>">
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat" rows="3" required><?= htmlspecialchars($user_data['address']) ?></textarea>
        </div>
        <div class="form-group">
            <label>No. Kontak</label>
            <input type="text" name="kontak" required value="<?= htmlspecialchars($user_data['contact_no']) ?>">
        </div>
        <div class="form-group">
            <label>Metode Pembayaran</label>
            <select name="metode" onchange="togglePrepaidFields()" required>
                <option value="">-- Pilih Metode --</option>
                <option value="prepaid" <?= $selected_metode == 'prepaid' ? 'selected' : '' ?>>Prepaid (Kartu/PayPal)</option>
                <option value="cod" <?= $selected_metode == 'cod' ? 'selected' : '' ?>>Postpaid (Bayar di Tempat)</option>
            </select>
        </div>

        <div id="prepaid-fields" style="display:none;">
            <div class="form-group">
                <label>ID PayPal</label>
                <input type="text" name="paypal_id">
            </div>
            <div class="form-group">
                <label>Nama Bank</label>
                <input type="text" name="bank_name">
            </div>
        </div>

        <h3>Ringkasan Pesanan</h3>
        <table>
            <thead>
                <tr><th>Produk</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th></tr>
            </thead>
            <tbody>
                <?php if (!empty($items)): ?>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td>Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                            <td><?= $item['qty'] ?></td>
                            <td>Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4">Keranjang kosong</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <p style="text-align:right; font-weight:bold;">Total: Rp <?= number_format($total, 0, ',', '.') ?></p>

        <div style="text-align:center; margin-top: 20px;">
            <a href="keranjang.php" style="margin-right: 10px; padding: 10px 20px; background: #6c757d; color: white; border-radius: 5px; text-decoration: none;">Kembali</a>
            <button type="submit">Proses Pesanan</button>
        </div>
    </form>
</div>
</body>
</html>
