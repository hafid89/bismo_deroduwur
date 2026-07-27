-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 24, 2026 at 06:45 AM
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `email`, `password`, `full_name`, `created_at`, `reset_token`, `reset_token_expires`) VALUES
(1, 'admin', 'admin@gunungbismo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', '2026-07-23 16:06:05', NULL, NULL),
(2, 'hafis', '123230051@student.upnyk.ac.id', '$2y$10$2Lzw1WZ/mm.23zfhDoU0MuWH0ZQB14jycxok5BnuOAHs/KVp4jVU.', 'hafid', '2026-07-23 16:42:28', NULL, NULL);

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
(1, 'Pendakian Gunung Bismo Dibuka Kembali', 'pendakian-gunung-bismo-dibuka-kembali', '<p>Setelah beberapa waktu ditutup akibat cuaca ekstrem, jalur pendakian Gunung Bismo via Deroduwur resmi dibuka kembali untuk umum. Pendaki diimbau untuk tetap memperhatikan kondisi cuaca dan membawa perlengkapan yang cukup.</p><p>Jalur pendakian melalui Deroduwur menawarkan pengalaman mendaki yang asri dengan pemandangan alam yang masih terjaga. Basecamp Deroduwur telah menyiapkan berbagai fasilitas untuk kenyamanan pendaki.</p>', '6a62e81f971b2.jpg', '2026-07-23', 'Admin', '2026-07-23 16:06:05', '2026-07-24 04:20:47'),
(2, 'Penanaman Pohon di Jalur Pendakian', 'penanaman-pohon-di-jalur-pendakian', '<p>Dalam rangka menjaga kelestarian hutan, pengelola basecamp Deroduwur mengadakan kegiatan penanaman pohon di sepanjang jalur pendakian. Kegiatan ini diikuti oleh puluhan relawan dan komunitas pecinta alam.</p><p>Kegiatan ini merupakan bagian dari komitmen pengelola untuk menjaga kelestarian alam Gunung Bismo dan ekosistem di sekitarnya.</p>', 'berita2.jpg', '2026-07-23', 'Admin', '2026-07-23 16:06:05', '2026-07-23 16:06:05'),
(3, 'Tips Mendaki Gunung Bismo untuk Pemula', 'tips-mendaki-gunung-bismo-untuk-pemula', '<p>Bagi Anda yang baru pertama kali mendaki Gunung Bismo, berikut tips penting yang perlu diperhatikan:</p><ul><li>Persiapan fisik yang matang</li><li>Perlengkapan yang tepat dan sesuai standar</li><li>Mengikuti aturan yang berlaku</li><li>Menjaga kebersihan lingkungan</li><li>Membawa cukup air dan makanan</li></ul>', 'berita3.jpg', '2026-07-23', 'Admin', '2026-07-23 16:06:05', '2026-07-23 16:06:05');

-- --------------------------------------------------------

--
-- Table structure for table `fauna`
--

CREATE TABLE `fauna` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nama_ilmiah` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `lokasi` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fauna`
--

INSERT INTO `fauna` (`id`, `nama`, `nama_ilmiah`, `deskripsi`, `foto`, `lokasi`, `created_at`, `updated_at`) VALUES
(1, 'Elang Jawa', 'Nisaetus bartelsi', 'Burung pemangsa endemik', NULL, 'Sekitar puncak', '2026-07-24 04:23:56', '2026-07-24 04:23:56'),
(2, 'Kera Ekor Panjang', 'Macaca fascicularis', 'Ditemukan di sekitar basecamp', NULL, 'Basecamp & Hutan Pinus', '2026-07-24 04:23:56', '2026-07-24 04:23:56'),
(3, 'Lutung Jawa', 'Trachypithecus auratus', 'Lutung Jawa (Trachypithecus auratus) adalah satwa liar endemik  dan simbol konservasi di jalur pendakian Gunung Bismo, dikenal lewat bulu hitam legam serta bayi oranye keemasan.\r\n\r\nCiri-Ciri Fisik\r\n1. Dewasa: Bulu hitam pekat atau kelabu gelap, berat 7–9 kg, panjang tubuh hingga 65 cm dengan ekor sangat panjang.\r\n2. Bayi: Berwarna jingga atau oranye keemasan yang mencolok saat lahir.\r\n3. Kelompok: Hidup berkoloni di pepohonan hutan tropis Gunung Bismo. \r\n\r\nPerilaku dan Makanan \r\n1. Makanan Utama: Hewan pemakan daun, buah, bunga, dan biji-bijian hutan.\r\n2. Aktivitas: Suka berada di atas pohon (arboreal) dan aktif pada siang hari (diurnal).\r\n3. Sifat: Pemalu terhadap manusia, sering terdengar bersuara nyaring saat kaget atau merasa terganggu.', '6a62ec9a41ae2.jpg', 'Pos III Jalur pendakian Gunung Bismo Via Deroduwur', '2026-07-24 04:23:56', '2026-07-24 04:39:54');

-- --------------------------------------------------------

--
-- Table structure for table `flora`
--

CREATE TABLE `flora` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nama_ilmiah` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `lokasi` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flora`
--

INSERT INTO `flora` (`id`, `nama`, `nama_ilmiah`, `deskripsi`, `foto`, `lokasi`, `created_at`, `updated_at`) VALUES
(1, 'Kantong Semar', 'Nepenthes sp.', 'Tanaman karnivora endemik', NULL, 'Dekat Pos I', '2026-07-24 04:23:56', '2026-07-24 04:23:56'),
(2, 'Edelweiss', 'Anaphalis javanica', 'Bunga abadi yang ditemukan di area puncak', NULL, 'Area Puncak', '2026-07-24 04:23:56', '2026-07-24 04:23:56'),
(3, 'Anggrek Hutan', 'Orchidaceae', 'Berbagai jenis anggrek liar di sepanjang jalur', NULL, 'Sepanjang Jalur', '2026-07-24 04:23:56', '2026-07-24 04:23:56');

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

-- --------------------------------------------------------

--
-- Table structure for table `peraturan`
--

CREATE TABLE `peraturan` (
  `id` int(11) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `teks` text NOT NULL,
  `denda` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peraturan`
--

INSERT INTO `peraturan` (`id`, `kategori`, `teks`, `denda`, `created_at`) VALUES
(1, 'kewajiban', 'Setiap Pendaki Harus Dalam Kondisi Sehat', NULL, '2026-07-23 17:05:02'),
(2, 'kewajiban', 'Wajib Mengisi Buku Registrasi', NULL, '2026-07-23 17:05:02'),
(3, 'kewajiban', 'Wajib Menyerahkan Kartu Identitas Yang Berlaku', NULL, '2026-07-23 17:05:02'),
(4, 'kewajiban', 'Wajib Membawa Kantong Sampah', NULL, '2026-07-23 17:05:02'),
(5, 'kewajiban', 'Wajib Membawa Alat Standar Pendakian', NULL, '2026-07-23 17:05:02'),
(6, 'kewajiban', 'Wajib Mengisi Form Logistik', NULL, '2026-07-23 17:05:02'),
(7, 'kewajiban', 'Wajib Menaati Semua Peraturan', NULL, '2026-07-23 17:05:02'),
(8, 'larangan', 'Masuk Tanpa Ijin', 'Rp. 1.025.000', '2026-07-23 17:05:02'),
(9, 'larangan', 'Membuang Sampah Sembarangan', 'Rp. 1.025.000', '2026-07-23 17:05:02'),
(10, 'larangan', 'Membuat Api Unggun', 'Rp. 1.025.000', '2026-07-23 17:05:02'),
(11, 'larangan', 'Menebang Pohon', 'Rp. 1.025.000', '2026-07-23 17:05:02'),
(12, 'larangan', 'Membawa Senjata Tajam Lebih Dari 20 Cm', 'Rp. 1.025.000', '2026-07-23 17:05:02'),
(13, 'larangan', 'Membawa Senjata Api', 'Rp. 1.025.000', '2026-07-23 17:05:02'),
(14, 'larangan', 'Mendirikan Tenda Di Jalur Pendakian', 'Rp. 1.025.000', '2026-07-23 17:05:02'),
(15, 'larangan', 'Membawa Tisu Basah', 'Rp. 1.025.000', '2026-07-23 17:05:02'),
(16, 'larangan', 'Membawa Alat Musik', 'Rp. 1.025.000', '2026-07-23 17:05:02'),
(17, 'larangan', 'Membawa Kembang Api/Petasan', 'Rp. 1.025.000', '2026-07-23 17:05:02'),
(18, 'larangan', 'Membawa Minuman Keras', 'Rp. 1.025.000', '2026-07-23 17:05:02'),
(19, 'larangan', 'Memetik Bunga Edelwis/Tumbuhan Lainnya', 'Rp. 1.025.000', '2026-07-23 17:05:02'),
(20, 'larangan', 'Mencoret Pohon/Batu', 'Rp. 1.025.000', '2026-07-23 17:05:02'),
(21, 'larangan', 'Membuat Jalur Sendiri/Trobosan', 'Rp. 1.025.000', '2026-07-23 17:05:02'),
(22, 'larangan', 'Tidak Membawa Turun Sampah', 'Rp. 1.025.000', '2026-07-23 17:05:02'),
(23, 'larangan', 'Berzinah', 'Rp. 1.025.000', '2026-07-23 17:05:02'),
(24, 'larangan', 'Solo Hiking', 'Rp. 1.025.000', '2026-07-23 17:05:02'),
(25, 'larangan', 'Camp Bukan Ditempat Camp Yang Sudah Di Sediakan', 'Rp. 1.025.000', '2026-07-23 17:05:02'),
(26, 'fasilitas', 'Tiket Masuk Kawasan', NULL, '2026-07-23 17:05:02'),
(27, 'fasilitas', 'Cek Kesehatan', NULL, '2026-07-23 17:05:02'),
(28, 'fasilitas', 'Wifi', NULL, '2026-07-23 17:05:02'),
(29, 'fasilitas', 'Toilet', NULL, '2026-07-23 17:05:02'),
(30, 'fasilitas', 'Tempat Istirahat', NULL, '2026-07-23 17:05:02'),
(31, 'fasilitas', 'Penitipan Barang', NULL, '2026-07-23 17:05:02'),
(32, 'fasilitas', 'Mushola', NULL, '2026-07-23 17:05:02');

-- --------------------------------------------------------

--
-- Table structure for table `reset_password`
--

CREATE TABLE `reset_password` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `spot_jalur`
--

CREATE TABLE `spot_jalur` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `posisi` varchar(50) NOT NULL,
  `ketinggian` varchar(50) DEFAULT NULL,
  `estimasi_waktu` varchar(50) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `jenis` varchar(50) DEFAULT 'spot',
  `urutan` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `spot_jalur`
--

INSERT INTO `spot_jalur` (`id`, `nama`, `posisi`, `ketinggian`, `estimasi_waktu`, `deskripsi`, `foto`, `jenis`, `urutan`, `created_at`) VALUES
(1, 'Basecamp Deroduwur', 'basecamp', NULL, '0 (Start)', 'Basecamp pendakian Gunung Bismo via Deroduwur', 'basecamp.jpg', 'spot', 0, '2026-07-23 17:05:02'),
(2, 'Pos Ojek / Gerbang Batas Hutan', 'pos_ojek', NULL, NULL, 'Titik akhir kendaraan bermotor', NULL, 'spot', 1, '2026-07-23 17:05:02'),
(3, 'Hutan Pakis', 'pos1', NULL, '75 menit', 'Kawasan hutan pakis yang rimbun', NULL, 'wilayah', 2, '2026-07-23 17:05:02'),
(4, 'Pos I', 'pos1', '1.555 MDPL', '75 menit', 'Area peristirahatan pertama', 'pos-1.jpg', 'spot', 3, '2026-07-23 17:05:02'),
(5, 'Banyu Bismo', 'pos1', NULL, NULL, 'Sumber air di jalur pendakian', NULL, 'spot', 4, '2026-07-23 17:05:02'),
(6, 'Kantong Semar', 'pos1', NULL, NULL, 'Spot tanaman kantong semar liar', 'kantong-semar.jpg', 'flora', 5, '2026-07-23 17:05:02'),
(7, 'Pos II', 'pos2', '1.765 MDPL', '45 menit', 'Kumbang Alang-alang', NULL, 'spot', 6, '2026-07-23 17:05:02'),
(8, 'Pos III', 'pos3', '1.991 MDPL', '60 menit', 'Camp Area - Area perkemahan', NULL, 'spot', 7, '2026-07-23 17:05:02'),
(9, 'Tanjakan Jalak Wangi', 'pos3', NULL, NULL, 'Titik tanjakan menantang', NULL, 'wilayah', 8, '2026-07-23 17:05:02'),
(10, 'Pos IV', 'pos4', '2.204 MDPL', '60 menit', 'Camp Area - Area perkemahan terakhir', 'pos-4.jpg', 'spot', 9, '2026-07-23 17:05:02'),
(11, 'Sunrise Camp', 'sunrise', NULL, NULL, 'Spot utama menikmati matahari terbit', NULL, 'spot', 10, '2026-07-23 17:05:02'),
(12, 'Puncak Hastinapura', 'puncak', '2.338 MDPL', '20 menit', 'Puncak dengan panorama 360 derajat', 'puncak-hastinapura.jpg', 'spot', 11, '2026-07-23 17:05:02'),
(13, 'Puncak Indraprasta', 'puncak', '2.365 MDPL', '20 menit', 'Puncak tertinggi Gunung Bismo', 'puncak-indrapasta.jpg', 'spot', 12, '2026-07-23 17:05:02');

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
-- Indexes for table `fauna`
--
ALTER TABLE `fauna`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `flora`
--
ALTER TABLE `flora`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `galeri`
--
ALTER TABLE `galeri`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `peraturan`
--
ALTER TABLE `peraturan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reset_password`
--
ALTER TABLE `reset_password`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `spot_jalur`
--
ALTER TABLE `spot_jalur`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `berita`
--
ALTER TABLE `berita`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `fauna`
--
ALTER TABLE `fauna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `flora`
--
ALTER TABLE `flora`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `galeri`
--
ALTER TABLE `galeri`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `peraturan`
--
ALTER TABLE `peraturan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `reset_password`
--
ALTER TABLE `reset_password`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `spot_jalur`
--
ALTER TABLE `spot_jalur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reset_password`
--
ALTER TABLE `reset_password`
  ADD CONSTRAINT `reset_password_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
