<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login_form.php");
    exit;
}

if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    header("Location: dashboard_admin.php?page=kategori");
    exit;
}

include_once __DIR__ . '/../../../config/database.php';

$notif = '';
$type = '';

if (isset($_POST['tambah'])) {
    $nama = trim($_POST['nama']);
    if ($nama !== '') {
        mysqli_query($conn, "INSERT INTO tbl_categories (name) VALUES ('$nama')");
        $notif = 'Kategori berhasil ditambahkan';
        $type = 'success';
    }
}

if (isset($_POST['edit'])) {
    $id   = (int) $_POST['id'];
    $nama = trim($_POST['nama']);
    if ($nama !== '') {
        mysqli_query($conn, "UPDATE tbl_categories SET name = '$nama' WHERE id = $id");
        $notif = 'Kategori berhasil diubah';
        $type = 'success';
    }
}

if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM tbl_categories WHERE id = $id");
    $notif = 'Kategori berhasil dihapus';
    $type = 'success';
}

$kategori = mysqli_query($conn, "SELECT * FROM tbl_categories ORDER BY name ASC");
?>

<!-- Layout sama -->
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Manajemen Kategori Produk</h3>
</div>

<div class="card mb-4">
  <div class="card-header">Tambah Kategori Baru</div>
  <div class="card-body">
    <form method="POST">
      <div class="row g-2">
        <div class="col-md-8">
          <input type="text" name="nama" class="form-control" placeholder="Nama Kategori" required>
        </div>
        <div class="col-md-4">
          <button type="submit" name="tambah" class="btn btn-primary w-100">Tambah</button>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-header">Daftar Kategori</div>
  <div class="card-body p-0">
    <table class="table table-striped mb-0">
      <thead class="table-light">
        <tr>
          <th style="width: 10%">#</th>
          <th>Nama Kategori</th>
          <th style="width: 25%">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php $no = 1; while ($row = mysqli_fetch_assoc($kategori)): ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td>
            <div class="row g-1">
              <div class="col-6">
                <button class="btn btn-warning btn-sm w-100" 
                  onclick="editKategori(<?= $row['id'] ?>, '<?= addslashes($row['name']) ?>')">
                  Edit
                </button>
              </div>
              <div class="col-6">
                <button class="btn btn-danger btn-sm w-100" 
                  onclick="hapusKategori(<?= $row['id'] ?>)">
                  Hapus
                </button>
              </div>
            </div>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Kategori</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="editId">
        <input type="text" name="nama" id="editNama" class="form-control" required>
      </div>
      <div class="modal-footer">
        <button type="submit" name="edit" class="btn btn-success">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function editKategori(id, nama) {
  document.getElementById('editId').value = id;
  document.getElementById('editNama').value = nama;
  var modalEl = document.getElementById('modalEdit');
  var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
  modal.show();
}

function hapusKategori(id) {
  Swal.fire({
    title: 'Hapus Kategori?',
    text: "Data akan dihapus permanen!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Ya, hapus!'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location = '?page=kategori&hapus=' + id;
    }
  })
}

<?php if ($notif): ?>
Swal.fire({
  icon: '<?= $type ?>',
  title: '<?= $type === 'success' ? 'Berhasil' : 'Oops!' ?>',
  text: '<?= $notif ?>',
  timer: 2000,
  showConfirmButton: false
});
<?php endif; ?>
</script>