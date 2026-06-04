<?php
include_once __DIR__ . '/../../../config/database.php';

$notif = '';
$type = '';

$kategoriList = mysqli_query($conn, "SELECT * FROM tbl_categories ORDER BY name ASC");

// TAMBAH PRODUK
if (isset($_POST['tambah'])) {
  $nama = $_POST['nama'];
  $kode = $_POST['kode'];
  $deskripsi = $_POST['deskripsi'];
  $harga = (float)$_POST['harga'];
  $stok = (int)$_POST['stok'];
  $kategori = (int)$_POST['kategori'];
  $gambarName = $_FILES['gambar']['name'];
  $gambarTmp = $_FILES['gambar']['tmp_name'];

  $targetPath = __DIR__ . '/../../../assets/images/' . basename($gambarName);
  if (move_uploaded_file($gambarTmp, $targetPath)) {
    mysqli_query($conn, "INSERT INTO tbl_products (name, code, description, price, stock, image, category_id)
      VALUES ('$nama', '$kode', '$deskripsi', $harga, $stok, '$gambarName', $kategori)");
    $notif = 'Produk berhasil ditambahkan!';
    $type = 'success';
  } else {
    $notif = 'Gagal upload gambar!';
    $type = 'error';
  }
}

// HAPUS PRODUK
if (isset($_GET['hapus'])) {
  $id = (int)$_GET['hapus'];
  mysqli_query($conn, "DELETE FROM tbl_products WHERE id = $id");
  $notif = 'Produk berhasil dihapus!';
  $type = 'success';
}

// EDIT PRODUK
if (isset($_POST['edit'])) {
  $id = (int)$_POST['id'];
  $nama = $_POST['nama'];
  $kode = $_POST['kode'];
  $deskripsi = $_POST['deskripsi'];
  $harga = (float)$_POST['harga'];
  $stok = (int)$_POST['stok'];
  $kategori = (int)$_POST['kategori'];
  $gambarName = $_FILES['gambar']['name'];
  $gambarTmp = $_FILES['gambar']['tmp_name'];

  if ($gambarName !== '') {
    $targetPath = __DIR__ . '/../../../assets/images/' . basename($gambarName);
    move_uploaded_file($gambarTmp, $targetPath);
    $query = "UPDATE tbl_products SET name='$nama', code ='$kode', description='$deskripsi', price=$harga, stock=$stok, image='$gambarName', category_id=$kategori WHERE id=$id";
  } else {
    $query = "UPDATE tbl_products SET name='$nama', code ='$kode', description='$deskripsi', price=$harga, stock=$stok, category_id=$kategori WHERE id=$id";
  }

  mysqli_query($conn, $query);
  $notif = 'Produk berhasil diperbarui!';
  $type = 'success';
}

// Ambil ulang data produk untuk tabel dan modal
$produk = mysqli_query($conn, "
  SELECT p.*, c.name AS kategori
  FROM tbl_products p
  LEFT JOIN tbl_categories c ON p.category_id = c.id
  ORDER BY p.id DESC
");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-semibold">Manajemen Produk</h4>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Produk</button>
</div>

<div class="card shadow-sm">
  <div class="card-body p-0">
    <table class="table table-striped mb-0">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Nama</th>
          <th>Harga</th>
          <th>Stok</th>
          <th>Kategori</th>
          <th>Gambar</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php $no = 1; while ($row = mysqli_fetch_assoc($produk)): ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td>Rp<?= number_format($row['price'], 0, ',', '.') ?></td>
          <td><?= $row['stock'] ?></td>
          <td><?= htmlspecialchars($row['kategori']) ?></td>
          <td><img src="/aplikasikasir_alkestore/assets/images/<?= htmlspecialchars($row['image']) ?>" width="40"></td>
        <td>
  <div class="row gx-1">
    <div class="col">
      <button class="btn btn-warning btn-sm w-100" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id'] ?>">Edit</button>
    </div>
    <div class="col">
      <a href="?page=produk&hapus=<?= $row['id'] ?>" onclick="return confirm('Hapus produk ini?')" class="btn btn-danger btn-sm w-100">Hapus</a>
    </div>
  </div>
</td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal Tambah Produk -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form method="POST" enctype="multipart/form-data" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Produk Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label>Nama Produk</label>
            <input type="text" name="nama" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label>Kode Produk</label>
            <input type="text" name="kode" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label>Kategori</label>
            <select name="kategori" class="form-select" required>
              <option value="">-- Pilih Kategori --</option>
              <?php mysqli_data_seek($kategoriList, 0); while ($kat = mysqli_fetch_assoc($kategoriList)): ?>
                <option value="<?= $kat['id'] ?>"><?= htmlspecialchars($kat['name']) ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label>Stok</label>
            <input type="number" name="stok" class="form-control" required>
          </div>
          <div class="col-12">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control" required></textarea>
          </div>
          <div class="col-12">
            <label>Upload Gambar</label>
            <input type="file" name="gambar" class="form-control" required>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="tambah" class="btn btn-success">Simpan Produk</button>
      </div>
    </form>
  </div>
</div>


<!-- Modal Edit Produk DITARUH DI LUAR TABEL -->
<?php mysqli_data_seek($produk, 0); while ($row = mysqli_fetch_assoc($produk)): ?>
<div class="modal fade" id="modalEdit<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form method="POST" enctype="multipart/form-data" class="modal-content">
      <input type="hidden" name="id" value="<?= $row['id'] ?>">
      <div class="modal-header">
        <h5 class="modal-title">Edit Produk</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label>Nama Produk</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($row['name']) ?>" class="form-control" required>
          </div>
          <div class="col-md-6">
  <label>Kode Produk</label>
  <input type="text" name="kode" value="<?= htmlspecialchars($row['code']) ?>" class="form-control" required>
</div>
          <div class="col-md-6">
            <label>Kategori</label>
            <select name="kategori" class="form-select" required>
              <?php mysqli_data_seek($kategoriList, 0); while ($kat = mysqli_fetch_assoc($kategoriList)): ?>
                <option value="<?= $kat['id'] ?>" <?= $kat['id'] == $row['category_id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($kat['name']) ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label>Harga</label>
            <input type="number" name="harga" value="<?= $row['price'] ?>" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label>Stok</label>
            <input type="number" name="stok" value="<?= $row['stock'] ?>" class="form-control" required>
          </div>
          <div class="col-12">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control" required><?= htmlspecialchars($row['description']) ?></textarea>
          </div>
          <div class="col-12">
            <label>Upload Gambar (opsional)</label>
            <input type="file" name="gambar" class="form-control">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="edit" class="btn btn-warning">Update Produk</button>
      </div>
    </form>
  </div>
</div>
<?php endwhile; ?>

<?php if ($notif): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({
  icon: '<?= $type ?>',
  title: '<?= $type === 'success' ? 'Berhasil' : 'Oops!' ?>',
  text: '<?= $notif ?>',
  timer: 2000,
  showConfirmButton: false
});
</script>
<?php endif; ?>

<!-- Bootstrap bundle untuk modal -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
