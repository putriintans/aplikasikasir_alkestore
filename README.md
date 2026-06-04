# Aplikasi Kasir Alat Kesehatan (Alkestore)

## Deskripsi

Alkestore merupakan aplikasi kasir berbasis web yang dirancang untuk membantu proses penjualan alat kesehatan secara online. Sistem ini menyediakan fitur pengelolaan produk, keranjang belanja, transaksi pembelian, pembuatan invoice PDF, pengiriman invoice melalui email, serta pengelolaan data pengguna berdasarkan hak akses masing-masing.

## Hak Akses Pengguna

Sistem memiliki tiga jenis pengguna:

### 1. Visitor

* Mengakses halaman utama tanpa login.
* Melihat informasi produk yang tersedia.
* Melakukan registrasi akun customer.
* Login ke dalam sistem.

### 2. Customer

* Login ke sistem.
* Melihat daftar produk.
* Menambahkan produk ke keranjang.
* Menyimpan keranjang ke database.
* Melakukan checkout.
* Mencetak invoice dalam format PDF.
* Menerima invoice melalui email.
* Mengelola profil akun.
* Mengubah password.
* Mengisi buku tamu.

### 3. Admin

* Login ke dashboard admin.
* Mengelola data produk.
* Mengelola data customer.
* Melihat data transaksi.
* Melihat data buku tamu.
* Memantau aktivitas sistem.

---

## Teknologi yang Digunakan

* PHP Native
* MySQL
* HTML
* CSS
* JavaScript
* Bootstrap
* Composer
* DomPDF
* PHPMailer
* XAMPP

---

## Fitur Utama

### Manajemen Pengguna

* Registrasi akun customer.
* Login berdasarkan role pengguna.
* Edit profil customer.
* Ubah password customer.

### Manajemen Produk

* Menampilkan daftar produk alat kesehatan.
* Menambahkan produk ke keranjang.
* Menyimpan data keranjang ke database.

### Transaksi

* Checkout produk.
* Penyimpanan data transaksi.
* Riwayat pembelian customer.

### Invoice

* Generate invoice dalam format PDF menggunakan DomPDF.
* Pengiriman invoice otomatis ke email customer menggunakan PHPMailer.

### Buku Tamu

* Customer dapat mengirim pesan melalui buku tamu.
* Admin dapat melihat pesan buku tamu pada dashboard.

---

## Instalasi dan Menjalankan Project

### 1. Clone Repository

```bash
git clone https://github.com/putriintans/aplikasikasir_alkestore.git
```

### 2. Pindahkan ke Folder htdocs

Letakkan project pada folder:

```text
xampp/htdocs/aplikasikasir_alkestore
```

### 3. Install Dependency

Buka terminal pada folder project kemudian jalankan:

```bash
composer install
```

### 4. Import Database

1. Jalankan Apache dan MySQL melalui XAMPP.
2. Buka phpMyAdmin.
3. Buat database baru dengan nama:

```text
db_kasir
```

4. Pilih menu Import.
5. Import file:

```text
database/db_kasir.sql
```

### 5. Konfigurasi Email

Buat file `.env` pada root project dan sesuaikan konfigurasi berikut:

```env
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@example.com
MAIL_PASSWORD=your_app_password
MAIL_FROM=your_email@example.com
MAIL_FROM_NAME=Alkestore
```

### 6. Jalankan Aplikasi

Buka browser dan akses:

```text
http://localhost/aplikasikasir_alkestore
```

---

## Fitur Tambahan

* Penyimpanan keranjang ke database.
* Sistem login multi-role (Visitor, Customer, dan Admin).
* Generate invoice PDF menggunakan DomPDF.
* Pengiriman invoice otomatis melalui email menggunakan PHPMailer.
* Buku tamu terintegrasi dengan dashboard admin.

---

## Developer

**Putri Intan**

📧 Email: [putriintanss12@gmail.com](mailto:putriintanss12@gmail.com)

---

## Repository

https://github.com/putriintans/aplikasikasir_alkestore
