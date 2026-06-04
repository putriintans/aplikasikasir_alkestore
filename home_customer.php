<?php
session_start();

// if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'customer') {
//     header("Location: /aplikasikasir_alkestore/login_form.php");
//     exit();
// }

$conn = new mysqli("localhost", "root", "", "db_kasir");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$jumlah_pesanan = 0;
if (isset($_SESSION['user_id'])) {
    $conn = new mysqli("localhost", "root", "", "db_kasir");
    $user_id = $_SESSION['user_id'];
    $query = $conn->query("SELECT COUNT(*) as total FROM tbl_orders WHERE user_id = $user_id");
    $result = $query->fetch_assoc();
    $jumlah_pesanan = $result['total'];
}

$jumlah_keranjang = 0;
if (isset($_SESSION['keranjang']) && is_array($_SESSION['keranjang'])) {
    $jumlah_keranjang = array_sum($_SESSION['keranjang']);
}

$categories = $conn->query("SELECT * FROM tbl_categories ORDER BY name ASC");

$whereClause = "1=1";
if (isset($_GET['category_id']) && $_GET['category_id'] != '') {
    $category_id = intval($_GET['category_id']);
    $whereClause .= " AND category_id = $category_id";
}
if (isset($_GET['search']) && $_GET['search'] != '') {
    $search = $conn->real_escape_string($_GET['search']);
    $whereClause .= " AND name LIKE '%$search%'";
}

$products = $conn->query("SELECT * FROM tbl_products WHERE $whereClause");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Beranda Customer - Alkestore</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            background: #f9f9f9;
        }

        header {
            background-color: #397eff;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left {
            font-size: 16px;
        }

        .header-right a {
            color: white;
            margin-left: 15px;
            font-size: 18px;
            text-decoration: none;
            position: relative;
        }

        .header-right .badge {
            position: absolute;
            top: -5px;
            right: -10px;
            background: red;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            position: absolute;
            top: 28px;
            right: 0;
            background-color: white;
            color: black;
            min-width: 150px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            z-index: 1;
            display: none;
        }

        .dropdown-content a {
            padding: 10px 12px;
            display: block;
            text-decoration: none;
            color: black;
        }

        .dropdown-content a:hover {
            background-color: #eee;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        h2 {
            text-align: center;
            margin-top: 20px;
        }

        .container {
            display: flex;
            gap: 20px;
            padding: 20px;
        }

        .products {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            flex: 3;
        }

        .product-box {
            border: 1px solid #ccc;
            padding: 10px;
            background: #fff;
            border-radius: 8px;
            text-align: center;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .product-box:hover {
            transform: scale(1.03);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .product-box img {
            max-width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .product-box h4 {
            margin: 10px 0 5px;
        }

        .product-box p {
            margin: 5px 0;
        }

        .product-box a button {
            margin: 5px;
            padding: 6px 12px;
            background: #4d90fe;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.2s ease, background 0.3s ease;
        }

        .product-box a button:hover {
            transform: scale(1.05);
            background: #2962cc;
        }

        .sidebar {
            width: 250px;
        }

        .category-box {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .search-box form {
            display: flex;
            gap: 5px;
            margin-bottom: 15px;
        }

        .search-box input {
            flex: 1;
            padding: 6px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .search-box button {
            background: #28a745;
            color: white;
            border: none;
            padding: 6px 10px;
            border-radius: 4px;
        }

        .category-box ul {
            list-style: none;
            padding-left: 0;
        }

        .category-box li {
            margin-bottom: 6px;
        }

        .category-box a {
            color: #333;
            text-decoration: none;
        }

        .category-box a:hover {
            text-decoration: underline;
        }
        .promo-card {
  min-width: 180px;
  max-width: 180px;
  background: #fff;
  padding: 12px;
  border-radius: 12px;
  text-align: center;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  transition: transform 0.2s ease;
}
.promo-card:hover {
  transform: scale(1.04);
}
.promo-card img {
  width: 100%;
  height: 120px;
  object-fit: contain;
  border-radius: 10px;
  background-color: #f4f7fc;
  padding: 8px;
}
.promo-card h4 {
  font-size: 15px;
  margin: 10px 0 5px;
  color: #333;
}
.promo-card p {
  font-size: 13px;
  color: #555;
  margin: 0;
}
.promo-card del {
  color: #aaa;
}
.promo-card span {
  color: #e53935;
  font-weight: bold;
}
.promo-btn {
  position: absolute;
  top: 40%;
  transform: translateY(-50%);
  background: #397eff;
  color: white;
  border: none;
  width: 34px;
  height: 34px;
  font-size: 18px;
  border-radius: 50%;
  cursor: pointer;
  z-index: 2;
  box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}
    </style>
</head>
<body>

<header>
    <div class="header-left">
        <?php if (isset($_SESSION['username']) && $_SESSION['role'] === 'customer'): ?>
            Halo, <?= htmlspecialchars($_SESSION['username']) ?>
        <?php else: ?>
            Selamat datang, Visitor
        <?php endif; ?>
    </div>
    <div class="header-right">
        <?php if (isset($_SESSION['username']) && $_SESSION['role'] === 'customer'): ?>

<a href="riwayat_pesanan.php" title="Riwayat Pesanan">
    <i class="fas fa-clock-rotate-left"></i>
    <?php if ($jumlah_pesanan > 0): ?>
        <span class="badge"><?= $jumlah_pesanan ?></span>
    <?php endif; ?>
</a>

        
            <a href="keranjang.php" title="Keranjang">
                <i class="fas fa-shopping-cart"></i>
                <?php if ($jumlah_keranjang > 0): ?>
                    <span class="badge"><?= $jumlah_keranjang ?></span>
                <?php endif; ?>
            </a>

<a href="akun.php" title="Akun Saya">
    <i class="fas fa-user"></i>
</a>

<a href="logout_akun.php" title="Logout">
    <i class="fas fa-sign-out-alt"></i>
</a>


        <?php else: ?>
            <a href="/aplikasikasir_alkestore/app/views/auth/login_form.php" class="btn btn-primary" style="padding:6px 12px; background:#28a745; border-radius:4px;">Login</a>
        <?php endif; ?>
    </div>
</header>


<section style="text-align: center; margin: 30px 0;">
    <h1 style="font-size: 28px; color: #333;">Selamat Datang di <span style="color: #397eff;">Alkestore</span> </h1>
    <p style="font-size: 16px; color: #555;">Temukan berbagai produk alat kesehatan terbaik dengan harga terjangkau dan kualitas terpercaya.</p>
</section>

<section style="background: #fffef8; padding: 30px 10px;">
  <h1 style="text-align: center; font-size: 24px; color: #397eff; margin-bottom: 15px;">
    Promo Spesial Hari Ini
  </h1>

  <div style="position: relative; max-width: 100%; overflow: hidden; padding: 0 40px;">
    <div id="promo-slider" style="display: flex; gap: 16px; transition: transform 0.4s ease;">
      <div class="promo-card"><img src="assets/images/glukometer.jpg"><h4>Alat Cek Gula Darah</h4><p><del>Rp 195rb</del><br><span>Rp 165rb</span></p></div>
      <div class="promo-card"><img src="assets/images/stetoskop.jpg"><h4>Stetoskop</h4><p><del>Rp 150rb</del><br><span>Rp 105rb</span></p></div>
      <div class="promo-card"><img src="assets/images/thermoinfrared.jpg"><h4>Termometer</h4><p><del>Rp 75rb</del><br><span>Rp 60rb</span></p></div>
      <div class="promo-card"><img src="assets/images/kursipijat.jpg"><h4>Kursi Pijat</h4><p><del>Rp 22jt</del><br><span>Rp 15jt</span></p></div>
      <div class="promo-card"><img src="assets/images/vitaminc.jpg"><h4>Vitamin C 100mg</h4><p><del>Rp 25rb</del><br><span>Rp 22rb</span></p></div>
      <div class="promo-card"><img src="assets/images/p3k.jpg"><h4>Kotak P3K</h4><p><del>Rp 50rb</del><br><span>Rp 40rb</span></p></div>
      <div class="promo-card"><img src="assets/images/maskern95.jpg"><h4>Masker Medis</h4><p><del>Rp 15rb</del><br><span>Rp 11rb</span></p></div>
      <div class="promo-card"><img src="assets/images/timbangan.jpg"><h4>Timbangan</h4><p><del>Rp 200rb</del><br><span>Rp 140rb</span></p></div>
      <div class="promo-card"><img src="assets/images/SusuBalita.jpg"><h4>Susu Balita</h4><p><del>Rp 70rb</del><br><span>Rp 56rb</span></p></div>
      <div class="promo-card"><img src="assets/images/Promina.jpg"><h4>Promina</h4><p><del>Rp 35rb</del><br><span>Rp 25rb</span></p></div>
      <div class="promo-card"><img src="assets/images/kursiroda.jpg"><h4>Kursi Roda</h4><p><del>Rp 1,3jt</del><br><span>Rp 1jt</span></p></div>
    </div>

    <button onclick="slidePromo(-1)" class="promo-btn" style="left: 0;">❮</button>
    <button onclick="slidePromo(1)" class="promo-btn" style="right: 0;">❯</button>
  </div>
</section>

<script>
let promoIndex = 0;
const promoSlider = document.getElementById('promo-slider');
function slidePromo(dir) {
  const cardWidth = 196; 
  const maxScroll = promoSlider.scrollWidth - promoSlider.clientWidth;
  promoIndex += dir;
  const newX = Math.max(0, Math.min(promoIndex * cardWidth, maxScroll));
  promoSlider.style.transform = `translateX(-${newX}px)`;
}
</script>

<div class="container">
    <div class="products">
        <?php if ($products->num_rows > 0): ?>
            <?php while ($row = $products->fetch_assoc()): ?>
                <div class="product-box">
                    <img src="assets/images/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                    <h4><?= htmlspecialchars($row['name']) ?></h4>
                    <p>Rp <?= number_format($row['price'], 0, ',', '.') ?></p>
                    <a href="produk_detail.php?id=<?= $row['id'] ?>"><button>View</button></a>

                    <?php if (isset($_SESSION['username']) && $_SESSION['role'] === 'customer'): ?>
                        <a href="tambah_keranjang.php?id=<?= $row['id'] ?>"><button>Buy</button></a>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="grid-column: 1 / -1; text-align: center;">Produk tidak ditemukan.</p>
        <?php endif; ?>
    </div>

    <div class="sidebar">
        <div class="search-box">
            <form method="GET">
                <input type="text" name="search" placeholder="Cari produk..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                <button type="submit">Cari</button>
            </form>
        </div>
        <div class="category-box">
            <h4>Product Category</h4>
            <ul>
                <li><a href="home_customer.php">Semua</a></li>
                <?php while ($cat = $categories->fetch_assoc()): ?>
                    <li><a href="home_customer.php?category_id=<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></a></li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>
</div> 

<?php if (isset($_GET['status']) && $_GET['status'] === 'added'): ?>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Produk telah ditambahkan ke keranjang.',
        timer: 1500,
        showConfirmButton: false
    });
</script>
<?php endif; ?>

<?php if (!isset($_SESSION['username'])): ?>
<section style="display: flex; justify-content: center; align-items: center; background: #eef5ff; padding: 80px 20px; font-family: 'Poppins', sans-serif;">
    <div style="display: flex; flex-wrap: wrap; max-width: 960px; background: white; border-radius: 20px; box-shadow: 0 8px 24px rgba(0,0,0,0.12); overflow: hidden; width: 100%;">
        
        <div style="flex: 1; min-width: 280px; background: #397eff; color: white; padding: 40px; display: flex; flex-direction: column; justify-content: center;">
            <h2 style="font-size: 30px; margin-bottom: 15px;">Kesan & Pesan Anda <span style="font-size: 28px;">💬</span></h2>
            <p style="font-size: 15px; line-height: 1.6;">Silakan tinggalkan pesan atau saran untuk kami.<br>Tim kami akan membacanya dengan senang hati!</p>
        </div>

        <div style="flex: 1; min-width: 280px; padding: 40px; background: #fff;">
            <form action="app/views/admin/proses_guestbook.php" method="POST">
                <label style="font-weight: 500; margin-bottom: 5px; display: block;">Nama</label>
                <input type="text" name="name" required placeholder="Nama Anda"
                    style="width: 100%; padding: 12px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 10px; transition: all 0.2s ease-in-out;">
                
                <label style="font-weight: 500; margin-bottom: 5px; display: block;">Email</label>
                <input type="email" name="email" required placeholder="email@anda.com"
                    style="width: 100%; padding: 12px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 10px; transition: all 0.2s ease-in-out;">
                
                <label style="font-weight: 500; margin-bottom: 5px; display: block;">Pesan</label>
                <textarea name="message" rows="5" required placeholder="Tulis pesan Anda di sini..."
                    style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 10px; resize: vertical; transition: all 0.2s;"></textarea>
                
                <button type="submit"
                    style="margin-top: 25px; background: #397eff; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; box-shadow: 0 4px 10px rgba(57,126,255,0.3); transition: 0.3s;">
                    Kirim Pesan
                </button>
            </form>
        </div>
    </div>
</section>
<?php endif; ?>

<script>
    let currentIndex = 0;
    const slider = document.getElementById('promo-slider');

    function slidePromo(direction) {
        const cardWidth = 220 + 20; // lebar kartu + gap
        const visibleCount = Math.floor(slider.parentElement.offsetWidth / cardWidth);
        const maxIndex = slider.children.length - visibleCount;

        currentIndex += direction;
        if (currentIndex < 0) currentIndex = 0;
        if (currentIndex > maxIndex) currentIndex = maxIndex;

        slider.style.transform = `translateX(-${currentIndex * cardWidth}px)`;
    }
</script>

</body>
</html>