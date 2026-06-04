<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include_once __DIR__ . '/../../../config/database.php';

// Ambil langsung dari tabel guestbook
$guestbook = mysqli_query($conn, "SELECT * FROM tbl_guestbook ORDER BY created_at DESC");
?>

<h4 class="mb-4">Buku Tamu</h4>
<table class="table table-bordered">
  <thead class="table-primary">
    <tr>
      <th>No</th>
      <th>Nama</th>
      <th>Email</th>
      <th>Pesan</th>
      <th>Waktu</th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1; while ($row = mysqli_fetch_assoc($guestbook)): ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= htmlspecialchars($row['name']) ?></td>
      <td><?= htmlspecialchars($row['email']) ?></td>
      <td><?= htmlspecialchars($row['message']) ?></td>
      <td><?= date('d-m-Y H:i', strtotime($row['created_at'])) ?></td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>
