-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 23, 2026 at 06:26 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gunung_bismo_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `email`, `password`, `full_name`, `created_at`) VALUES
(1, 'admin', 'admin@gunungbismo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', '2026-07-23 16:06:05');

-- --------------------------------------------------------

--
-- Table structure for table `berita`
--

CREATE TABLE `berita` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `penulis` varchar(100) DEFAULT 'Admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `berita`
--

INSERT INTO `berita` (`id`, `judul`, `slug`, `isi`, `foto`, `tanggal`, `penulis`, `created_at`, `updated_at`) VALUES
(1, 'Pendakian Gunung Bismo Dibuka Kembali', 'pendakian-gunung-bismo-dibuka-kembali', '<p>Setelah beberapa waktu ditutup akibat cuaca ekstrem, jalur pendakian Gunung Bismo via Deroduwur resmi dibuka kembali untuk umum. Pendaki diimbau untuk tetap memperhatikan kondisi cuaca dan membawa perlengkapan yang cukup.</p><p>Jalur pendakian melalui Deroduwur menawarkan pengalaman mendaki yang asri dengan pemandangan alam yang masih terjaga. Basecamp Deroduwur telah menyiapkan berbagai fasilitas untuk kenyamanan pendaki.</p>', 'berita1.jpg', '2026-07-23', 'Admin', '2026-07-23 16:06:05', '2026-07-23 16:06:05'),
(2, 'Penanaman Pohon di Jalur Pendakian', 'penanaman-pohon-di-jalur-pendakian', '<p>Dalam rangka menjaga kelestarian hutan, pengelola basecamp Deroduwur mengadakan kegiatan penanaman pohon di sepanjang jalur pendakian. Kegiatan ini diikuti oleh puluhan relawan dan komunitas pecinta alam.</p><p>Kegiatan ini merupakan bagian dari komitmen pengelola untuk menjaga kelestarian alam Gunung Bismo dan ekosistem di sekitarnya.</p>', 'berita2.jpg', '2026-07-23', 'Admin', '2026-07-23 16:06:05', '2026-07-23 16:06:05'),
(3, 'Tips Mendaki Gunung Bismo untuk Pemula', 'tips-mendaki-gunung-bismo-untuk-pemula', '<p>Bagi Anda yang baru pertama kali mendaki Gunung Bismo, berikut tips penting yang perlu diperhatikan:</p><ul><li>Persiapan fisik yang matang</li><li>Perlengkapan yang tepat dan sesuai standar</li><li>Mengikuti aturan yang berlaku</li><li>Menjaga kebersihan lingkungan</li><li>Membawa cukup air dan makanan</li></ul>', 'berita3.jpg', '2026-07-23', 'Admin', '2026-07-23 16:06:05', '2026-07-23 16:06:05');

-- --------------------------------------------------------

--
-- Table structure for table `galeri`
--

CREATE TABLE `galeri` (
  `id` int(11) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `kategori` varchar(50) DEFAULT 'jalur',
  `tag` varchar(50) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `galeri`
--

INSERT INTO `galeri` (`id`, `foto`, `judul`, `kategori`, `tag`, `deskripsi`, `created_at`) VALUES
(1, 'galeri1.jpg', 'Pemandangan Puncak Indraprasta', 'jalur', 'puncak', 'Pemandangan indah dari puncak Gunung Bismo', '2026-07-23 16:06:06'),
(2, 'galeri2.jpg', 'Kantong Semar Liar', 'ekosistem', 'flora', 'Tanaman kantong semar di dekat Pos I', '2026-07-23 16:06:06'),
(3, 'galeri3.jpg', 'Kegiatan Basecamp', 'kegiatan', 'basecamp', 'Kegiatan pendaki di basecamp Deroduwur', '2026-07-23 16:06:06'),
(4, 'galeri4.jpg', 'Hutan Pakis', 'jalur', 'spot', 'Kawasan hutan pakis sebelum Pos I', '2026-07-23 16:06:06'),
(5, 'galeri5.jpg', 'Sunrise Camp', 'jalur', 'spot', 'Spot utama menikmati matahari terbit', '2026-07-23 16:06:06'),
(6, 'galeri6.jpg', 'Burung Endemik', 'ekosistem', 'fauna', 'Burung khas Gunung Bismo', '2026-07-23 16:06:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `galeri`
--
ALTER TABLE `galeri`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `berita`
--
ALTER TABLE `berita`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `galeri`
--
ALTER TABLE `galeri`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
