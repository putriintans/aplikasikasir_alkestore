<?php
include_once __DIR__ . '/../../../config/database.php';

$notif = '';
$type = '';

$search = '';
$where = "WHERE role = 'customer'";

if (isset($_GET['search']) && $_GET['search'] !== '') {
  $search = mysqli_real_escape_string($conn, $_GET['search']);
  $where .= " AND (username LIKE '%$search%' 
              OR email LIKE '%$search%'
              OR city LIKE '%$search%')";
}

// Hapus customer
if (isset($_GET['hapus'])) {
  $id = (int)$_GET['hapus'];
  mysqli_query($conn, "DELETE FROM tbl_users WHERE id = $id");
  $notif = 'Customer berhasil dihapus!';
  $type = 'success';
}

// Update Customer
if (isset($_POST['edit'])) {
  $id = (int)$_POST['id'];
  $username = $_POST['username'];
  $email = $_POST['email'];
  $contact_no = $_POST['contact_no'];
  $gender = $_POST['gender'];
  $date_of_birth = $_POST['date_of_birth'];
  $address = $_POST['address'];
  $city = $_POST['city'];
  $paypal_id = $_POST['paypal_id'];

  mysqli_query($conn, "UPDATE tbl_users SET
    username='$username',
    email='$email',
    contact_no='$contact_no',
    gender='$gender',
    date_of_birth='$date_of_birth',
    address='$address',
    city='$city',
    paypal_id='$paypal_id'
    WHERE id=$id
  ");
  $notif = 'Data customer berhasil diperbarui!';
  $type = 'success';
}

// Ambil data customer
$customers = mysqli_query($conn, "
    SELECT id, username, email, contact_no, gender, date_of_birth, address, city, paypal_id
    FROM tbl_users
    $where
    ORDER BY username ASC
");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-semibold">Data Customer</h4>
</div>

<form method="GET" class="mb-3">
  <input type="hidden" name="page" value="data_customer">
  <div class="input-group">
    <input type="text" name="search" class="form-control" placeholder="Cari username, email, kota..." value="<?= htmlspecialchars($search) ?>">
    <button class="btn btn-primary" type="submit">Search</button>
    <?php if ($search !== ''): ?>
      <a href="?page=data_customer" class="btn btn-secondary">Reset</a>
    <?php endif; ?>
  </div>
</form>

<div class="card shadow-sm">
  <div class="card-body p-0">
    <table class="table table-striped mb-0">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Username</th>
          <th>Email</th>
          <th>No. HP</th>
          <th>Gender</th>
          <th>Tgl Lahir</th>
          <th>Alamat</th>
          <th>Kota</th>
          <th>PayPal ID</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (mysqli_num_rows($customers) > 0): ?>
          <?php $no = 1; while ($row = mysqli_fetch_assoc($customers)): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['contact_no']) ?></td>
            <td><?= htmlspecialchars($row['gender']) ?></td>
            <td><?= htmlspecialchars($row['date_of_birth']) ?></td>
            <td><?= htmlspecialchars($row['address']) ?></td>
            <td><?= htmlspecialchars($row['city']) ?></td>
            <td><?= htmlspecialchars($row['paypal_id']) ?></td>
            <td>
              <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">Edit</button>
              <a href="?page=data_customer&hapus=<?= $row['id'] ?>" 
                onclick="return confirm('Hapus customer ini?')" 
                class="btn btn-danger btn-sm">
                Hapus
              </a>
            </td>
          </tr>

          <!-- Modal Edit -->
          <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $row['id'] ?>" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <form method="POST">
                  <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel<?= $row['id'] ?>">Edit Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <div class="mb-2">
                      <label class="form-label">Username</label>
                      <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($row['username']) ?>" required>
                    </div>
                    <div class="mb-2">
                      <label class="form-label">Email</label>
                      <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($row['email']) ?>" required>
                    </div>
                    <div class="mb-2">
                      <label class="form-label">No. HP</label>
                      <input type="text" name="contact_no" class="form-control" value="<?= htmlspecialchars($row['contact_no']) ?>" required>
                    </div>
                    <div class="mb-2">
                      <label class="form-label">Gender</label>
                      <select name="gender" class="form-control" required>
                        <option value="Male" <?= $row['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= $row['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                      </select>
                    </div>
                    <div class="mb-2">
                      <label class="form-label">Tanggal Lahir</label>
                      <input type="date" name="date_of_birth" class="form-control" value="<?= htmlspecialchars($row['date_of_birth']) ?>" required>
                    </div>
                    <div class="mb-2">
                      <label class="form-label">Alamat</label>
                      <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($row['address']) ?>" required>
                    </div>
                    <div class="mb-2">
                      <label class="form-label">Kota</label>
                      <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($row['city']) ?>" required>
                    </div>
                    <div class="mb-2">
                      <label class="form-label">PayPal ID</label>
                      <input type="text" name="paypal_id" class="form-control" value="<?= htmlspecialchars($row['paypal_id']) ?>" required>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" name="edit" class="btn btn-primary">Simpan Perubahan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="10" class="text-center">Data tidak ditemukan.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
