-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 03, 2025 at 06:41 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_kasir`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cart`
--

CREATE TABLE `tbl_cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_cart`
--

INSERT INTO `tbl_cart` (`id`, `user_id`, `product_id`, `quantity`, `created_at`) VALUES
(1, 8, 6, 1, '2025-07-02 18:52:52'),
(2, 8, 61, 1, '2025-07-02 18:52:59'),
(3, 8, 60, 1, '2025-07-02 18:59:22'),
(4, 8, 9, 1, '2025-07-02 19:03:06'),
(5, 8, 7, 1, '2025-07-02 19:41:03'),
(6, 2, 8, 1, '2025-07-02 20:04:15'),
(7, 10, 7, 1, '2025-07-02 20:27:15'),
(8, 10, 27, 1, '2025-07-02 20:27:18'),
(9, 10, 60, 1, '2025-07-02 20:27:20'),
(10, 10, 8, 1, '2025-07-02 20:28:03'),
(11, 10, 61, 1, '2025-07-02 20:28:06'),
(12, 2, 6, 1, '2025-07-02 21:19:22'),
(13, 2, 7, 1, '2025-07-02 21:21:22');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_categories`
--

CREATE TABLE `tbl_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_categories`
--

INSERT INTO `tbl_categories` (`id`, `name`) VALUES
(1, 'Alat Kesehatan'),
(2, 'Vitamin & Suplemen'),
(3, 'Perlengkapan Medis'),
(4, 'Obat-obatan'),
(5, 'Alat Diagnostik'),
(6, 'Disinfektan & Sanitasi'),
(7, 'Peralatan Klinik'),
(8, 'Minuman Sehat'),
(10, 'Minuman Berkalori'),
(17, 'Makanan Sehat'),
(19, 'Minuman Herbal'),
(23, 'Makanan Balita'),
(24, 'Alat Terapi');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_feedback`
--

CREATE TABLE `tbl_feedback` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_feedback`
--

INSERT INTO `tbl_feedback` (`id`, `order_id`, `user_id`, `feedback`, `rating`, `created_at`) VALUES
(1, 38, 2, 'Tokonya sangat lengkap, pelayanannya mantepp!!', 5, '2025-06-30 13:53:32'),
(2, 39, 8, 'Adminnya ramah, pelayanannya okee bgtt, sgt recommended <3', 4, '2025-06-30 14:16:56');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_guestbook`
--

CREATE TABLE `tbl_guestbook` (
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_guestbook`
--

INSERT INTO `tbl_guestbook` (`name`, `email`, `message`, `created_at`) VALUES
('Putri Intan', 'putriintanss12@gmail.com', 'Websitenya mudah digunakan dan produk yang dijual juga lengkap', '2025-06-30 19:10:43'),
('Octavia', '22082010159@student.upnjatim.ac.id', 'Websitenya bagus dan mudah digunakna', '2025-06-30 20:16:19'),
('Putan', 'putriintanss12@gmail.com', 'Websitenya menarik dan warnanya menarik', '2025-07-02 18:11:42'),
('Ashley Gualey', 'ashley123@gmail.com', 'Websitenye keren saya sangat suka', '2025-07-03 20:51:04');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_orders`
--

CREATE TABLE `tbl_orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `paypal_id` varchar(100) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `payment_method` enum('Prepaid','Postpaid') DEFAULT NULL,
  `total` decimal(12,2) DEFAULT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `user_address` text DEFAULT NULL,
  `contact_no` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `status_pengiriman` enum('pending','diproses','dikirim','selesai','batal') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_orders`
--

INSERT INTO `tbl_orders` (`id`, `user_id`, `order_date`, `paypal_id`, `bank_name`, `payment_method`, `total`, `user_name`, `user_address`, `contact_no`, `created_at`, `status_pengiriman`) VALUES
(1, 2, '2025-06-26 13:53:02', 'PLGN01', 'Bank BRI', 'Prepaid', 760000.00, 'putri', 'Medokan', '081319522067', '2025-06-27 12:50:56', 'batal'),
(2, 2, '2025-06-26 14:02:49', 'PLGN01', 'Bank BRI', 'Prepaid', 80000.00, 'putri', 'Medokan asri', '081319522067', '2025-06-27 12:50:56', 'dikirim'),
(5, 2, '2025-06-26 15:51:04', 'PLGN01', 'Bank BRI', 'Prepaid', 1660000.00, 'putri', 'Medan', '081319522067', '2025-06-27 12:50:56', 'batal'),
(38, 2, '2025-06-30 10:06:11', 'COD', 'COD', 'Postpaid', 73000.00, 'Putri Intan Sipayung', 'Jalan Medokan Asri Barat VI', '081319522067', '2025-06-30 10:06:11', 'dikirim'),
(39, 8, '2025-06-30 11:12:25', 'PLGN02', 'Bank BCA', 'Prepaid', 90000.00, 'Octavia', 'Jalan Gatot Subroto', '081319522067', '2025-06-30 11:12:25', 'dikirim'),
(40, 2, '2025-06-30 17:36:22', 'PLGN01', 'Bank BRI', 'Prepaid', 195000.00, 'Yakult', 'Jalan Rungkut Asri Timur XIII', '081319522067', '2025-06-30 17:36:22', 'diproses'),
(41, 2, '2025-06-30 19:33:50', 'COD', 'COD', 'Postpaid', 12000.00, 'Putri Intan Sipayung', 'Jalan Medokan', '081319522067', '2025-06-30 19:33:50', 'diproses'),
(42, 2, '2025-06-30 20:26:18', 'COD', 'COD', 'Postpaid', 30000.00, 'Putri Intan', 'Jl Medokan Asri Barat IV ', '081319522067', '2025-06-30 20:26:18', 'pending'),
(43, 8, '2025-07-02 18:13:32', 'PLGN02', 'Bank BRI', 'Prepaid', 227000.00, 'Octavia', 'Jalan Gatot Subroto ', '081319522067', '2025-07-02 18:13:32', 'pending'),
(44, 8, '2025-07-02 19:41:09', 'COD', 'COD', 'Postpaid', 342000.00, 'Octavia', 'Jalan Gatot Subroto ', '081319522067', '2025-07-02 19:41:09', 'pending'),
(45, 8, '2025-07-02 19:57:56', 'COD', 'COD', 'Postpaid', 12000.00, 'Octavia', 'Jalan Gatot Subroto ', '081319522067', '2025-07-02 19:57:56', 'pending'),
(46, 10, '2025-07-02 20:27:26', 'COD', 'COD', 'Postpaid', 282000.00, 'Lala', 'Jl Medokan Asri  Timur 21', '081319522067', '2025-07-02 20:27:26', 'pending'),
(47, 2, '2025-07-02 21:12:41', 'COD', 'COD', 'Postpaid', 25000.00, 'Putri Intan', 'Jl Medokan Asri Barat IV ', '081319522067', '2025-07-02 21:12:41', 'pending'),
(48, 2, '2025-07-02 21:19:34', 'PLGN01', 'Bank BRI', 'Prepaid', 220000.00, 'Putri Intan', 'Jl Medokan Asri Barat IV ', '081319522067', '2025-07-02 21:19:34', 'pending'),
(49, 2, '2025-07-03 22:16:12', 'COD', 'COD', 'Postpaid', 232000.00, 'Putri Intan', 'Jl Medokan Asri Barat IV ', '081319522067', '2025-07-03 22:16:12', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_items`
--

CREATE TABLE `tbl_order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_name` varchar(100) DEFAULT NULL,
  `product_code` varchar(50) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_order_items`
--

INSERT INTO `tbl_order_items` (`id`, `order_id`, `product_name`, `product_code`, `quantity`, `price`, `subtotal`) VALUES
(4, 2, 'Thermometer Digital', 'DRPro-002', 2, 40000.00, 80000.00),
(8, 5, 'Tensimeter Digital', 'DRPro-001', 2, 180000.00, 360000.00),
(9, 5, 'Kursi Roda Travel', 'Kursi-003', 1, 1300000.00, 1300000.00),
(50, 38, 'Kapas Medis', 'Kapas-015', 1, 5000.00, 5000.00),
(51, 38, 'Tisu Basah Antiseptik', 'Tisu-017', 1, 8000.00, 8000.00),
(52, 38, 'Oximeter', 'Oximeter-026', 1, 60000.00, 60000.00),
(53, 39, 'Masker N95', 'N95-009', 1, 15000.00, 15000.00),
(54, 39, 'Teh Herbal Pegagan', 'TehHerbalPegagan-062', 1, 30000.00, 30000.00),
(55, 39, 'Vitamin C Effervescent', 'VitaminCEffervescent-065', 1, 45000.00, 45000.00),
(56, 40, 'Alat Cek Gula Darah', 'Gula-006', 1, 195000.00, 195000.00),
(57, 41, 'Hand Sanitizer 100ml', 'Hand-007', 1, 12000.00, 12000.00),
(58, 42, 'Obat Batuk Sirup', 'Sirup-021', 2, 15000.00, 30000.00),
(59, 43, 'Hand Sanitizer 100ml', 'Hand-007', 1, 12000.00, 12000.00),
(60, 43, 'Alat Inhaler', 'Inhaler-020', 1, 200000.00, 200000.00),
(61, 43, 'Obat Batuk Sirup', 'Sirup-021', 1, 15000.00, 15000.00),
(62, 44, 'Alat Cek Gula Darah', 'Gula-006', 1, 195000.00, 195000.00),
(63, 44, 'Hand Sanitizer 100ml', 'Hand-007', 1, 12000.00, 12000.00),
(64, 44, 'Masker N95', 'N95-009', 1, 15000.00, 15000.00),
(65, 44, 'Infus Set', 'InfusSet-060', 1, 50000.00, 50000.00),
(66, 44, ' Susu Formula Balita', 'SusuFormulaBalita-061', 1, 70000.00, 70000.00),
(67, 45, 'Hand Sanitizer 100ml', 'Hand-007', 1, 12000.00, 12000.00),
(68, 46, 'Hand Sanitizer 100ml', 'Hand-007', 1, 12000.00, 12000.00),
(69, 46, 'Nebulizer Portable', 'Nebulizer-027', 1, 220000.00, 220000.00),
(70, 46, 'Infus Set', 'InfusSet-060', 1, 50000.00, 50000.00),
(71, 47, 'Vitamin C 1000mg', 'VitC-008', 1, 25000.00, 25000.00),
(72, 48, 'Alat Cek Gula Darah', 'Gula-006', 1, 195000.00, 195000.00),
(73, 48, 'Vitamin C 1000mg', 'VitC-008', 1, 25000.00, 25000.00),
(74, 49, 'Alat Cek Gula Darah', 'Gula-006', 1, 195000.00, 195000.00),
(75, 49, 'Hand Sanitizer 100ml', 'Hand-007', 1, 12000.00, 12000.00),
(76, 49, 'Vitamin C 1000mg', 'VitC-008', 1, 25000.00, 25000.00);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_products`
--

CREATE TABLE `tbl_products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_products`
--

INSERT INTO `tbl_products` (`id`, `name`, `code`, `description`, `price`, `stock`, `image`, `category_id`) VALUES
(3, 'Kursi Roda Travel', 'Kursi-003', 'Kursi roda ringan dan portabel untuk mobilitas sehari-hari.', 1300000.00, 5, 'kursiroda.jpg', 1),
(4, 'Stetoskop', 'Stetho-004', 'Stetoskop medis berkualitas tinggi untuk keperluan klinis.', 150000.00, 20, 'stetoskop.jpg', 1),
(5, 'Masker Medis', 'Masker-005', 'Masker 3 lapis dengan filtrasi tinggi untuk perlindungan optimal.', 2500.00, 100, 'masker.jpg', 3),
(6, 'Alat Cek Gula Darah', 'Gula-006', 'Alat untuk memeriksa kadar gula darah secara cepat dan akurat.', 195000.00, 15, 'glukometer.jpg', 5),
(7, 'Hand Sanitizer 100ml', 'Hand-007', 'Pembersih tangan berbahan alkohol efektif membunuh kuman.', 12000.00, 100, 'handsanitizer.jpg', 6),
(8, 'Vitamin C 1000mg', 'VitC-008', 'Suplemen vitamin C untuk daya tahan tubuh.', 25000.00, 50, 'vitaminc.jpg', 2),
(9, 'Masker N95', 'N95-009', 'Masker pelindung dengan filtrasi tinggi untuk tenaga medis.', 15000.00, 200, 'maskern95.jpg', 7),
(10, 'Sarung Tangan Medis', 'Sarung-010', 'Sarung tangan lateks steril untuk keperluan medis.', 5000.00, 300, 'sarungtangan.jpg', 3),
(11, 'Face Shield', 'FaceS-011', 'Pelindung wajah untuk mengurangi risiko droplet.', 10000.00, 150, 'faceshield.jpg', 3),
(12, 'Termometer Infrared', 'Infrared-012', 'Mengukur suhu tubuh tanpa kontak langsung.', 75000.00, 30, 'thermoinfrared.jpg', 1),
(13, 'Alkohol 70%', 'Alkohol-013', 'Antiseptik untuk keperluan medis dan rumah tangga.', 10000.00, 250, 'alkohol70.jpg', 6),
(14, 'Disinfektan Spray', 'Spray-014', 'Cairan pembersih permukaan dari virus dan bakteri.', 18000.00, 120, 'disinfektan.jpg', 6),
(15, 'Kapas Medis', 'Kapas-015', 'Kapas steril untuk keperluan luka dan injeksi.', 5000.00, 400, 'kapas.jpg', 3),
(16, 'Plester Luka', 'Plester-016', 'Plester penutup luka anti air berbagai ukuran.', 3000.00, 600, 'plester.jpg', 3),
(17, 'Tisu Basah Antiseptik', 'Tisu-017', 'Tisu basah dengan kandungan antiseptik.', 8000.00, 250, 'tisubasah.jpg', 3),
(18, 'Vitamin D3', 'VitD3-018', 'Suplemen kesehatan tulang dan imun tubuh.', 30000.00, 100, 'vitamind3.jpg', 2),
(19, 'Minyak Kayu Putih', 'Minyak-019', 'Minyak penghangat tubuh dan pereda masuk angin.', 12000.00, 80, 'kayuputih.jpg', 4),
(20, 'Alat Inhaler', 'Inhaler-020', 'Alat bantu napas untuk penderita asma.', 200000.00, 20, 'inhaler.jpg', 1),
(21, 'Obat Batuk Sirup', 'Sirup-021', 'Obat batuk anak dan dewasa rasa madu.', 15000.00, 60, 'obatbatuk.jpg', 4),
(22, 'Kotak P3K', 'P3K-022', 'Kotak pertolongan pertama isi standar', 50000.00, 40, 'p3k.jpg', 7),
(23, 'Korset Pinggang', 'Korset-023', 'Penopang pinggang untuk terapi dan postur.', 95000.00, 23, 'korset.jpg', 7),
(24, 'Alat Cek Kolesterol', 'Kolesterol-024', 'Alat tes kadar kolesterol darah di rumah.', 240000.00, 24, 'kolesterol.jpg', 5),
(25, 'Kursi Lipat Pasien', 'Lipat-025', 'Kursi pasien portable untuk mobilisasi ringan.', 180000.00, 10, 'kursilipat.jpg', 7),
(26, 'Oximeter', 'Oximeter-026', 'Alat pengukur kadar oksigen dalam darah.', 60000.00, 70, 'oximeter.jpg', 1),
(27, 'Nebulizer Portable', 'Nebulizer-027', 'Alat uap untuk pengobatan saluran pernapasan.', 220000.00, 27, 'nebulizer.jpg', 1),
(28, 'Tensimeter Manual', 'TensiManual-028', 'Alat ukur tekanan darah klasik dengan pompa tangan.', 80000.00, 22, 'tensimanual.jpg', 1),
(29, 'Obat Luka Spray', 'LukaSpray-029', 'Obat semprot antiseptik untuk luka luar.', 35000.00, 91, 'obatluka.jpg', 4),
(57, 'Yakult', 'Yakult-057', 'Yakult minuman sehat untuk usus anda', 11000.00, 20, 'yakult.jpg', 17),
(58, 'Buavita', 'Buavita-058', 'Buavita minuman sehat rasa buah yang berfungsi untuk menambah nutrisi tubuh', 15000.00, 15, 'Buavita.jpg', 8),
(59, 'Anlene', 'Anlene-059', 'Susu Anlene merupakan susu  yang kaya akan nutrisi serta rendah lemak. Susu ini juga memiliki kandungan kalsium yang tinggi sehingga bagus untuk menguatkan tulang. ', 20000.00, 20, 'Anlene.jpg', 8),
(60, 'Infus Set', 'InfusSet-060', 'Perlengkapan untuk memberikan cairan secara intravena kepada pasien. Terdiri dari jarum, selang, dan chamber.', 50000.00, 20, 'Infus.jpg', 7),
(61, ' Susu Formula Balita', 'SusuFormulaBalita-061', 'Susu pertumbuhan dengan nutrisi lengkap untuk anak usia 1–3 tahun.', 70000.00, 100, 'SusuBalita.jpg', 23),
(62, 'Teh Herbal Pegagan', 'TehHerbalPegagan-062', ' Minuman herbal alami dari daun pegagan yang dipercaya membantu konsentrasi dan sirkulasi darah.', 30000.00, 50, 'Teh.jpg', 19),
(63, 'Suplemen Zinc Anak', 'SuplemenZincAnak-063', 'Suplemen harian untuk menunjang imunitas anak dari bahan alami dan aman dikonsumsi.', 25000.00, 50, 'Zinc.jpg', 2),
(64, 'Minuman Isotonik Rendah Kalori', 'MinumanIsotonikRendahKalori-064', 'Minuman elektrolit rendah gula untuk mengganti cairan tubuh yang hilang setelah aktivitas berat.', 15000.00, 50, 'Pocari.jpg', 10),
(65, 'Vitamin C Effervescent', 'VitaminCEffervescent-065', ' Tablet vitamin C larut air yang membantu meningkatkan daya tahan tubuh dan mempercepat pemulihan.', 45000.00, 100, 'Redoxon.jpg', 2),
(66, 'Makanan Pendamping ASI', 'MakananPendampingASI-066', 'Makanan bergizi tinggi dan mudah dicerna untuk anak usia 6 bulan ke atas.', 35000.00, 100, 'Promina.jpg', 23),
(67, 'Dancow Susu', 'Dancow-067', 'Susu Bubuk yang mengandung banyak nutrisi sehat', 89000.00, 65, 'Dancow.jpg', 8),
(68, 'Kursi Pijat Zensure', 'Zensure-068', 'Zensure II adalah kursi Pijat dengan teknologi yang di kembangkan dari sebelumnya untuk mendapatkan sebuah kesempurnaan teknik terapi. Sebuah kombinasi antara kemewanan dan gaya hidup untuk kenyamanan hidup.', 25000000.00, 11, 'kursipijat.jpg', 24);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `contact_no` varchar(20) DEFAULT NULL,
  `paypal_id` varchar(100) DEFAULT NULL,
  `role` enum('admin','customer') DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `username`, `password`, `email`, `date_of_birth`, `gender`, `address`, `city`, `contact_no`, `paypal_id`, `role`) VALUES
(1, 'putriintans', '$2y$10$8PaMNLkGzvh4d0ruDrr4FO9i1aPslvR/3VMmbFW9Fo9lehauqvlOC', 'putriintanss12@gmail.com', '2003-07-17', 'Female', 'Jl Medokan Asri', 'Surabaya', '081319522067', 'PLGN01', 'customer'),
(2, 'Putri Intan', '$2y$10$M35SSGUr9HiarRCHrAIOve5hUO/xBi6I5WX5Co1RVvNpStb3Fm8pW', 'putriintanss12@gmail.com', '2003-07-17', 'Male', 'Jl Medokan Asri Barat IV ', 'Surabaya', '081319522067', 'PLGN01', 'customer'),
(6, 'admin_brillian', '$2y$10$iEpVXAIWSHyzHp4jE3WOZ.lPWZTQLGQhV4YhkIL52wiAdHdaX.boW', 'admin@example.com', '1990-01-01', 'Male', 'Jl. Admin Contoh No.1', 'Surabaya', '081234567890', 'admin@example.com', 'admin'),
(8, 'Octavia', '$2y$10$y603L819FmtTPiYaZQdtTeNyZUhQCUNwC1p3o965MvQSBaSqlFrSO', '22082010159@student.upnjatim.ac.id', '2003-07-19', 'Male', 'Jalan Gatot Subroto ', 'Jakarta', '081319522067', 'PLGN02', 'customer'),
(10, 'Lala', '$2y$10$gEPNZUXXrIMaukklp1uaqeFVnBpaVn.0.s1aecofGzsdFhKSs2vjW', 'lalaluliss20@gmail.com', '2002-06-19', 'Female', 'Jl Medokan Asri  Timur 21', 'Bandung', '081319522067', 'PLGN03', 'customer'),
(11, 'Ashley', '$2y$10$4rzwc6fbAnTxbZgQHwfncuUkUlS/TEOWkt7016h8XkJbQiN201mzm', '22082010159@student.upnjatim.ac.id', '1999-03-06', 'Female', 'Jalan Cempaka Putih', 'Surabaya', '081319522067', 'PLGN04', 'customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_cart`
--
ALTER TABLE `tbl_cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `tbl_categories`
--
ALTER TABLE `tbl_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_feedback`
--
ALTER TABLE `tbl_feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_order_items`
--
ALTER TABLE `tbl_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `tbl_products`
--
ALTER TABLE `tbl_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_cart`
--
ALTER TABLE `tbl_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_categories`
--
ALTER TABLE `tbl_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `tbl_feedback`
--
ALTER TABLE `tbl_feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `tbl_order_items`
--
ALTER TABLE `tbl_order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `tbl_products`
--
ALTER TABLE `tbl_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_cart`
--
ALTER TABLE `tbl_cart`
  ADD CONSTRAINT `tbl_cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `tbl_products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  ADD CONSTRAINT `tbl_orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_order_items`
--
ALTER TABLE `tbl_order_items`
  ADD CONSTRAINT `tbl_order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `tbl_orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
