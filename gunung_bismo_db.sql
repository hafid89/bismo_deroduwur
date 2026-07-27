-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 27, 2026 at 08:10 PM
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
(4, 'Lutung Budeng', 'Trachypithecus Auratus', 'Lutung Budeng memiliki ciri fisik berwarna hitam legam dengan kilau keperakan untuk dewasa, dan berwarna orange untuk anakan. Habitat lutung budeng berada di hutan tropis, hutan sekunder, hutan pegunungan, hutan pantai di area Pulau Jawa, Bali, Lombok. Lutung Budeng termasuk ke dalam hewan herbivora, folivora (pemakan daun), seperti daun muda, buah-buahan, bunga muda, dan kulit kayu dalam jumlah kecil. Status konservasi: PermenLHK P106/2018 (Dilindungi), IUCN (Least Concern), CITES (Appendix II).', '6a6790a92997a.png', 'Pos 2 hingga Puncak Hastinapura dan Puncak Indraprasta', '2026-07-27 15:59:43', '2026-07-27 17:08:57'),
(5, 'Elang Hitam', 'Ictinaetus Malaiensis', 'Elang Hitam atau Elang Bido memiliki ciri fisik berwarna hitam legam di seluruh tubuh dengan bulu bagian primer yang lebih panjang, sehingga pada saat terbang tampak seperti berjari-jari. Elang Hitam memiliki ciri khas terbang rendah di atas hutan dengan sayap melebar. Habitat Elang Hitam berada pada hutan hujan tropis dan hutan pegunungan. Elang Hitam memangsa mamalia, burung, reptile yang ukurannya lebih kecil, telur burung dan terkadang memakan bangkai. Status konservasi: PermenLHK P106/2018 (Dilindungi), IUCN (Least Concern), CITES (Appendix II).', '6a6790b36e72a.png', 'Pos 2 (Pagi Hari) hingga Puncak Hastinapura (Siang hingga Sore Hari)', '2026-07-27 15:59:43', '2026-07-27 17:09:07'),
(6, 'Wiwik Uncuing', 'Cacomantis Sepulcralis', 'Wiwik Uncuing adalah spesies burung dalam taksonomi ordo Cuculiformes dan genus Cacomantis. Burung ini bersuara mendayu-dayu dengan nada yang makin rendah kadang makin tinggi. Tubuh Wiwik Uncuing berukuran kecil, yakni 23 cm, untuk dewasa kepalanya berwarna abu-abu, punggung sayap dan ekor berwarna cokelat keabu-abuan. Tubuh bagian bawah berwarna merah karat mirip wiwik kelabu tapi lebih gelap. Wiwik Uncuing mempunyai habitat alami di hutan tropis lembab dan hutan dataran rendah yang tersebar di Indonesia, Malaysia, dan Filipina.', '6a6790c15a329.png', 'Hutan Pos 1 – Pos 4', '2026-07-27 15:59:43', '2026-07-27 17:09:21'),
(7, 'Sikatan Ninon', 'Eumyias Indigo', 'Sikatan Ninon adalah spesies burung dari keluarga Muscicapidae dari genus Eumyias. Burung ini merupakan jenis burung pemakan kumbang, larva kunang-kunang, buah-buahan kecil. Sikatan Ninon memiliki habitat di hutan gelap pegunungan dan tersebar pada ketinggian 900 hingga 3.000 MDPL. Sikatan Ninon memiliki ukuran tubuh 14 cm dan berwarna biru nila gelap nyaris hitam disekitar pangkal paruh. Sikatan Ninon tersebar di Pulau Sumatra, Kalimantan dan Jawa. Burung ini berkembang biak di bulan Februari hingga Agustus, dan Desember. Status konservasi: PermenLHK P106/2018 (Dilindungi), IUCN (Least Concern), CITES (Appendix II).', '6a6790db674ef.png', 'Hutan Pos 2 hingga Pos 3', '2026-07-27 15:59:43', '2026-07-27 17:09:47'),
(8, 'Sikatan Belang', 'Ficedula Westermanni', 'Sikatan Belang adalah spesies burung dari keluarga Muscicapidae dari genus Ficedula. Burung ini merupakan jenis burung pemakan serangga kecil seperti lalat, kumbang, ulat dan laba-laba. Sikatan Belang memiliki habitat di hutan gelap pegunungan dan tersebar pada ketinggian 1.000 hingga 2.600 MDPL. Sikatan Belang memiliki ukuran tubuh 11 cm hingga 15 cm. Burung jantan mempunyai alis, garis sayap, pinggir pangkal ekor dan tubuh bagian bawah berwarna putih. Sedangkan burung betina mempunyai ciri-ciri tubuh bagian bawah keputih-putihan dan tubuh bagian atasnya berwarna coklat keabu-abuan. Status konservasi: PermenLHK P106/2018 (Dilindungi), IUCN (Least Concern), CITES (Appendix II).', '6a6790e6affb4.png', 'Hutan gelap Pos 3 hingga Pos 4', '2026-07-27 15:59:43', '2026-07-27 17:09:58'),
(9, 'Bentet Kelabu', 'Lanius Schach', 'Bentet Kelabu kadang disebut Cendet, Pantet atau Toet (The Long Tailed Shrike atau Rufous-Backed Shrike) dalam Bahasa Inggris. Bentet Kelabu adalah spesies burung dari keluarga Laniide, dari genus Lanius. Burung ini merupakan jenis burung pemakan belalang, kumbang, tonggeret, serangga besar, dan memiliki habitat di daerah terbuka, padang rumput, perkebunan, tegalan, dan hutan pegunungan yang tersebar sampai pada ketinggian 1.600 MDPL. Bentet Kelabu dijuluki sebagai jagal pengintai karena perilakunya yang suka berburu. Bentet Kelabu adalah jenis burung berkicau dengan suara merdu. Burung ini juga mampu menirukan suara burung lain bahkan suara binatang lain. Status konservasi: PermenLHK P106/2018 (Dilindungi), IUCN (Endangered), CITES (Appendix II).', '6a6790f4b4fb6.png', 'Hutan Pos 1 hingga Pos 2', '2026-07-27 15:59:43', '2026-07-27 17:10:12'),
(10, 'Cica-Koreng Jawa', 'Megalurus Palustris', 'Cica-Koreng Jawa adalah spesies burung dari keluarga Locustellidae dari genus Megalurus. Burung ini merupakan jenis burung pemakan kumbang, belalang, dan serangga kecil. Burung ini memiliki habitat di lapangan rumput terbuka, rumpun gelagah, bambu, semak sekunder, kebun teh, dan hutan pegunungan yang tersebar sampai ketinggian 2.000 MDPL. Cica-Koreng Jawa memiliki tubuh berukuran sedang dengan panjang tubuh sekitar 22 hingga 28 cm dengan berat tubuh sekitar 38–56 gram. Burung ini memiliki ciri-ciri berwarna coklat, ada coretan hitam tebal pada punggung, alis mata kuning tua, ekor sangat memanjang dan menajam. Status konservasi: PermenLHK P106/2018 (Dilindungi), IUCN (Least Concern), CITES (Appendix II).', '6a67910435999.png', 'Hutan Pos 2 hingga Pos 3', '2026-07-27 15:59:43', '2026-07-27 17:10:28'),
(11, 'Sikep-Madu Asia', 'Pernis Ptilorhynchus', 'Sikep-Madu Asia adalah spesies burung pemangsa dalam famili Accipitriade dan tersebar di Paleartika Timur, India dan Asia Tenggara sampai Sunda Besar. Anggota genus ini mempunyai bulu yang menyerupai burung elang butek remaja atau elang Nisaetus. Ada dugaan bahwa kesamaan tersebut muncul sebagai perlindungan parsial terhadap pemangsa yang lebih besar seperti burung elang-alap. Mereka berkembang biak di daerah beriklim sedang dan hangat, dan merupakan spesialis pemakan larva tawon dan lebah. Mereka berkembang biak di hutan dan sering kali tidak mencolok kecuali saat dipajang. Status konservasi: PermenLHK P106/2018 (Dilindungi), IUCN (Least Concern), CITES (Appendix II).', '6a67910fb5636.png', 'Sekitaran Puncak Gunung', '2026-07-27 15:59:43', '2026-07-27 17:10:39'),
(12, 'Berencet Kerdil', 'Pnoepyga Pusilla', 'Berencet Kerdil adalah spesies burung dari keluarga Timaliidae. Berencet Kerdil dapat ditemukan di Bangladesh, Bhutan, Kamboja, Tiongkok, India, Indonesia, Laos, Malaysia, Myanmar, Nepal, Thailand dan Vietnam. Habitat dari Berencet Kerdil adalah wilayah hutan dataran rendah dengan iklim subtropic atau tropis. Burung ini memiliki ciri tubuh atas coklat gelap, tubuh bawah dengan pola sisik hitam-putih yang rapat memberi kesan seperti hewan pengerat tanpa ekor saat berjalan sembunyi-sembunyi melewati semak yang rapat dan sampah dedaunan. Status konservasi: PermenLHK P106/2018 (Dilindungi), IUCN (Least Concern), CITES (Appendix II).', '6a6791233bd53.png', 'Hutan Pos 2 hingga Pos 3', '2026-07-27 15:59:43', '2026-07-27 17:10:59'),
(13, 'Tesia Jawa', 'Tesia Superciliaris', 'Tesia Jawa adalah spesies burung dari keluarga Cettiidae. Burung ini adalah hewan endemik Indonesia yang hanya ditemukan di Pulau Jawa. Tesia Jawa adalah jenis Tesia berbadan kecil dengan kaki panjang nyaris tanpa ekor. Makanan Tesia Jawa adalah serangga kecil, tempayak, cacing, dan siput. Burung ini mempunyai habitat di daerah pegunungan, tersebar pada ketinggian 1.000–3.000 MDPL. Tesia Jawa mempunyai tubuh sangat kecil hanya 7 cm, berwarna abu-abu kehijauan, ekor sangat pendek, alis mata abu-abu pucat menonjol. Status konservasi: PermenLHK P106/2018 (Dilindungi), IUCN (Least Concern), CITES (Appendix II).', '6a67912e0df70.png', 'Hutan Pos 2 hingga Pos 4', '2026-07-27 15:59:43', '2026-07-27 17:11:10'),
(14, 'Anis Sisik', 'Zoothera Dauma', 'Anis Sisik merupakan salah satu jenis burung berkicau berukuran besar dari keluarga Turdidae dan genus Zoothera. Burung ini merupakan salah satu dari 39 jenis burung anis yang dikenal dengan nama Common Scaly Thrush. Anis Sisik memiliki ukuran cukup besar sekitar 28 sampai 30 cm dengan berat tubuh sekitar 88 hingga 130 gram. Secara umum penampilan burung Anis Sisik berwarna coklat bersisik. Pada tubuh bagian atas berwarna coklat dan bagian bawah berwarna putih, seluruh tubuhnya berenda dengan sisi bulu kuning emas dan hitam. Status konservasi: PermenLHK P106/2018 (Dilindungi), IUCN (Least Concern), CITES (Appendix II).', '6a679139b0dad.png', 'Hutan Pos 2 hingga Pos 4', '2026-07-27 15:59:43', '2026-07-27 17:11:21'),
(15, 'Pipit Pulau Sunda', 'Turdus Javanicus', 'Pipit Pulau Sunda adalah spesies burung pengicau dalam famili Turdidae. Burung ini ditemukan di Indonesia dan Malaysia. Sebelum tahun 2024, burung Pipit Pulau Sunda dianggap sebagai 8 subspesies burung pipit pulau yang terpisah. Burung ini memiliki distribusi terbesar di kompleks burung pipit pulau, mulai dari Sumatra Utara hingga Jawa dengan populasi yang terpisah di Kalimantan Utara. Pipit Pulau Sunda adalah burung sariawan berukuran sedang dengan panjang 21,5–25,5 cm tanpa perbedaan bulu antara jantan dan betina. Kaki, telapak kaki, dan paruhnya berwarna kuning hingga kuning jingga. Status konservasi: PermenLHK P106/2018 (Dilindungi), IUCN (Least Concern), CITES (Appendix II).', '6a67914957e0e.png', 'Hutan Pos 1 hingga seluruh Puncak Gunung Bismo', '2026-07-27 15:59:43', '2026-07-27 17:11:37');

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
(4, 'Edelweiss Longifolia', 'Anaphalis Longifolia', 'Edelweiss Longifolia adalah sejenis tanaman yang termasuk dalam genus Anaphalis dan Famili Asteraceae. Tumbuhan ini khas ditemukan di daerah pegunungan, beradaptasi dengan baik di tanah tandus, serta memiliki peran penting sebagai tumbuhan pionir di lereng bebatuan dan tanah vulkanik muda. Tumbuh di ketinggian pegunungan antara 1.000 – 2.000 meter di atas permukaan laut, membutuhkan cahaya, dan dapat ditemukan di daerah perbatasan antara hutan dan area terbuka. Di wilayah pegunungan Indonesia, termasuk di daerah Sumatera Utara seperti Kabupaten Karo, Samosir dan Toba, serta dapat ditemukan di Taman Nasional Bromo Tengger Semeru. Keberadaan bunga ini terancam oleh kerusakan hutan, pembakaran liar, perubahan fungsi hutan dan aktivitas manusia seperti pemetikan oleh pendaki yang dapat menyebabkan kehilangan habitat.', '6a678e71300c4.png', 'Pos 4 hingga Puncak Gunung Bismo', '2026-07-27 15:59:01', '2026-07-27 16:59:29'),
(5, 'Anggrek Coelogyne Miniata', 'Coelogyne Miniata', 'Coelogyne Miniata merupakan anggrek yang tumbuh di dahan-dahan tinggi pada hutan hujan tropis. Anggrek ini tumbuh pada batang pohon yang ditumbuhi lumut. Tumbuhan ini tumbuh bersamaan dengan jenis anggrek lainnya. Tumbuh dalam kelompok yang banyak sehingga saat berbunga terlihat mencolok dengan warna jingga yang bertebaran di dahan pohon. Pseudobulbs atau umbi semu pada Anggrek ini terletak terpisah dengan panjang 8–10 cm yang merambat dan bercabang rimbun, ovoid atau long ovoid, tinggi 3–4 cm. Umbi semu berwarna hijau saat tumbuh di tempat teduh, sedangkan berwarna kemerahan saat tumbuh di tempat terbuka. Anggrek ini memiliki dua helai daun yang berkembang sebelum umbi semu berkembang. Tumbuhan ini dapat dijumpai di wilayah pegunungan Jawa mulai dari ketinggian 1.000 hingga 2.400 MDPL.', '6a678e8c9ac54.png', 'Hutan Pos 3 hingga Pos 4', '2026-07-27 15:59:01', '2026-07-27 16:59:56'),
(6, 'Anggrek Dendrochilum Cornutum', 'Dendrochilum Cornutum', 'Dendrochilum Cornutum adalah spesies anggrek yang berasal dari hutan awan di Jawa, Sumatra dan Bali, tumbuh pada ketinggian 300–1.200 MDPL. Anggrek ini dikenal dengan rumpun bunga kecil yang harum dan mekar terutama dari musim dingin ke musim semi. Tanaman ini merupakan tanaman yang tumbuh baik dengan cahaya sedang, perawatan yang lembab dan perbedaan suhu antara siang dan malam yang jelas. Anggrek jenis ini memiliki rumpun bunga yang panjangnya 10–15 cm dengan 50–60 bunga-bunga kecil berwarna kuning.', '6a678ea57b71f.png', 'Hutan Pos 3 hingga Pos 4', '2026-07-27 15:59:01', '2026-07-27 17:00:21'),
(7, 'Pakis', 'Dipteris Conjugata', 'Dipteris Conjugata adalah spesies pakis dalam keluarga Dipteridaceae. Tumbuhan ini memiliki rimpang dan 2–3 batang tinggi dengan daun berwarna hijau tengah atau hijau tua yang beberapa bagian hingga lobus bergigi. Tumbuh di lahan terbuka, pegunungan dan tepi hutan, dari Asia tropis dan beriklim sedang, Queensland Utara di Australia, dan beberapa Pulau di Samudra Pasifik. Spesies ini memiliki rimpang menjalar panjang dengan diameter sekitar 1 cm ditutupi rambut hitam berkilau hingga 5 mm. Tangkai daunnya mencapai panjang 2 m dan memiliki sisik seperti rambut di pangkalnya. Pada permukaan bawah daun terdapat banyak sori kecil yang tersebar tidak teratur. Dipteris Conjugata tumbuh di lereng tanah liat, di tempat terbuka, punggung bukit dan tepi hutan.', '6a678ec2da16a.png', 'Sepanjang Jalur Pendakian', '2026-07-27 15:59:01', '2026-07-27 17:00:50'),
(8, 'Pohon Biji Ek', 'Lithocarpus Elegans', 'Lithocarpus Elegans adalah pohon dalam famili Beech Fagaceae. Julukan khusus elegans berarti \"elegan\", merujuk pada biji ek dan cupulesnya. Tumbuh sebagai pohon setinggi 30 m dengan diameter batang mencapai 70 cm. Kulit batangnya yang berwarna coklat keabu-abuan memiliki retakan atau lentisela. Daunnya berwarna koriaceous memiliki panjang hingga 17 cm. Biji ek coklatnya yang dapat dimakan berbentuk bulat telur hingga agak bulat dan berukuran hingga 2,5 cm. Tumbuh secara alami di benua India, Indo-Cina, dan Malaysia. Habitatnya adalah hutan pegunungan rendah hingga ketinggian 1.500 MDPL.', '6a678ed73e87a.png', 'Hutan Pos 2 hingga Pos 4', '2026-07-27 15:59:01', '2026-07-27 17:01:11'),
(9, 'Kantong Semar', 'Nepenthes Gymnamphora', 'Kantong Semar atau periuk kera adalah tumbuhan insektivora yang membentuk genus Nepenthes dan termasuk dalam famili monotipik Nepenthaceae. Tanaman ini memiliki kantong berisi cairan yang dapat melarutkan zat-zat dari makhluk hidup kecil seperti serangga sebagai sumber nutrisi. Kantong Semar biasanya hidup di daerah rawa-rawa. Tanaman ini terdiri dari 130 spesies tidak termasuk jenis hibrida alami maupun buatan. Genus ini merupakan tumbuhan karnivora di kawasan tropis Indonesia, Republik Rakyat Tiongkok bagian Selatan, Indo-Cina, Malaysia, Filipina, Madagaskar bagian Barat, India, Sri Lanka dan Australia. Tumbuhan ini dapat tumbuh mencapai tinggi 15–20 m dengan cara memanjat tanaman lainnya. Pada umumnya Kantong Semar memiliki 3 macam bentuk kantong, yaitu kantong atas, kantong bawah, dan kantong roset.', '6a678ee426a3b.png', 'Hutan Pos 1 hingga Pos 2', '2026-07-27 15:59:01', '2026-07-27 17:01:24'),
(10, 'Bunga Geger Bintang', 'Strobilanthes Cernua', 'Bunga Geger Bintang atau Bubukuan sering ditemukan di kawasan pegunungan Jawa Barat. Habitus berupa semak dengan tinggi hingga 3 m, daun panjang 8–25 cm dan lebar 3–12 cm. Di Jawa Barat, tumbuhan ini sering ditemukan hidup dalam koloni besar dan merupakan komponen tumbuhan bawah yang dominan di hutan hujan pada ketinggian 750–2.100 m. Selain di Jawa, flora unik ini juga ditemukan di Sumatra bagian Tengah. Tumbuhan unik ini juga disebut sebagai \"Putri Yang Muncul\" yang hanya berbunga setiap Sembilan tahun sekali. Strobilanthes Cernua merupakan satu spesies dari 250 anggota genus Strobilanthes dari famili Acanthaceae. Fenomena pembungaan serempak ini disebut Steenis \"Bak Sang Gunung Sedang Mengenakan Busana Pengantin\".', '6a678ef06c557.png', 'Hutan Pos 2', '2026-07-27 15:59:01', '2026-07-27 17:01:36'),
(11, 'Cantigi Epifit', 'Vaccinium Lucidum', 'Cantigi Epifit dapat tumbuh sebagai tanaman epifit, tetapi ia bukanlah epifit sejati. Kondisi ini bergantung pada habitat dan ketersediaan ruangnya. Cantigi dapat ditemukan tumbuh dengan menumpang di pohon lain, terutama di area ketinggian. Hal ini menjadi salah satu strategi untuk mendapatkan sinar matahari yang lebih banyak. Meskipun menumpang, cantigi bukanlah tanaman parasit. Ia tidak mengambil nutrisi dari pohon yang ditempelinya, melainkan membuat makanannya sendiri melalui fotosintesis. Selain epifit, cantigi juga dapat tumbuh di tanah, terutama di daerah sekitar kawah atau puncak gunung yang tahan terhadap asap belerang. Cantigi memiliki kemampuan untuk hidup sebagai epifit maupun di tanah, tergantung kondisi lingkungan tempatnya tumbuh.', '6a678f0cc47f8.png', 'Hutan Pos 3 hingga area sebelum Pos 4', '2026-07-27 15:59:01', '2026-07-27 17:02:04'),
(12, 'Hydrangea Cemara Biru', 'Dichroa Febrifuga', 'Hydrangea Cemara Biru atau Kina Tiongkok dianggap sebagai kerabat Hydrangea karena kemiripan gugusan bunga dengan Hydrangea Macrophylla. Tanaman ini merupakan perdu tropis yang tumbuh tegak dengan tinggi 2 hingga 4 meter. Di Tiongkok, tanaman ini dikenal sebagai \"Chang Shan\" dan telah digunakan dalam pengobatan tradisional selama lebih dari 2.000 tahun untuk mengobati malaria. Tanaman ini tumbuh subur di tempat teduh. Menyukai tanah yang kaya akan humus, dan membutuhkan penyiraman teratur. Tanaman ini berasal dari Asia Timur dan Tenggara. Di Indonesia, tanaman ini dikenal dengan nama Gigil. Bunga Kina Tiongkok tumbuh dalam bentuk perbungaan yang luas dan berwarna ungu-biru metalik yang mengkilap.', '6a678f28a2571.png', 'Hutan Pos 3 hingga Pos 4', '2026-07-27 15:59:01', '2026-07-27 17:02:32'),
(13, 'Rotan Balubuk', 'Calamus Burckianus Beccari', 'Rotan Balubuk adalah jenis rotan yang ditemukan di Pulau Jawa. Tanaman ini juga disebut sebagai \"Howe Balubuk (Sunda)\", Rotan Sepet dan penjalin bakul (Jawa). Secara ilmiah, nama Calamus Burckianus pertama kali dipublikasikan oleh ahli botani Odoardo Beccari pada tahun 1902. Rotan Balubuk termasuk dalam famili palem-paleman (Arecaceae) dan genus Calamus. Rotan Balubuk mempunyai sifat tumbuh dengan cara memanjat pohon atau tumbuhan lain untuk mendapatkan sinar matahari yang cukup. Seperti jenis rotan pada umumnya, Rotan Balubuk memiliki batang yang ditutupi oleh pelepah dan duri. Tumbuhan ini berasal dari bioma beriklim tropis basah dan dapat ditemukan di wilayah Jawa Barat.', '6a678f365b434.png', 'Hutan antara Pos 2 dan Pos 3', '2026-07-27 15:59:01', '2026-07-27 17:02:46'),
(14, 'Cepokogeni', 'Rhododendron Javanicum', 'Cepokogeni adalah spesies tumbuhan yang tergolong ke dalam famili Ericaceae. Spesies ini juga merupakan bagian dari ordo Ericales. Cepokogeni adalah tumbuhan asli Indonesia, Malaysia, dan Filipina. Tanaman ini tumbuh hingga 5 meter dengan bunga berwarna orange cerah di musim semi. Tanaman ini dapat tumbuh di darat atau epifit. Beberapa spesies dari Filipina mungkin memiliki bunga merah atau dua warna. Cepokogeni diyakini juga memiliki manfaat untuk kesehatan. Dimana masyarakat Sabah telah menggunakannya dalam pengobatan tradisional untuk mengatasi malaise. Cepokogeni tumbuh subur di hutan primer dan sekunder, dan bahkan di tanah tandus dekat kawah gunung berapi, dari permukaan laut hingga ketinggian 2.500 MDPL.', '6a678f62d818b.png', 'Hutan antara Pos 2 dan Pos 3', '2026-07-27 15:59:01', '2026-07-27 17:03:30'),
(15, 'Anggrek Daun Pandan', 'Dipodium Pandanum', 'Anggrek Daun Pandan adalah salah satu spesies anggrek semak belukar yang berasal dari kepulauan Maluku, Papuasia dan Queensland. Tumbuh di hutan hujan, merambat di pohon dan terkadang membentuk semak belukar. Tanaman ini merupakan epifit pemanjat sejati dengan batang melingkar di pohon dan ditopang oleh akar yang kuat. Tumbuhan ini menyebar secara vegetatif ketika batang tua yang tidak berdaun patah dan jatuh ke lantai hutan, tempat mereka mengembangkan tunas baru dan tumbuh melalui serasah daun, hingga akhirnya memanjat batu atau pohon.', '6a678f7a60c13.png', 'Hutan Pos 3 menuju Pos 4', '2026-07-27 15:59:01', '2026-07-27 17:03:54');

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
(1, 'Basecamp Deroduwur', 'basecamp', '', '0 (Start)', 'Basecamp pendakian Gunung Bismo via Deroduwur', '6a679dbd1bf44.jpg', 'wilayah', 0, '2026-07-23 17:05:02'),
(2, 'Pos Ojek', 'pos_ojek', '', '', 'Titik akhir kendaraan bermotor', NULL, 'spot', 1, '2026-07-23 17:05:02'),
(3, 'Hutan Pakis', 'pos1', '', '75 menit', 'Kawasan hutan pakis yang rimbun', '6a679b6900782.jpg', 'wilayah', 2, '2026-07-23 17:05:02'),
(4, 'Pos I', 'pos1', '1.555 MDPL', '75 menit', 'Area peristirahatan pertama', '6a679b95dd0a7.jpg', 'spot', 3, '2026-07-23 17:05:02'),
(5, 'Banyu Bismo', 'pos1', '', '', 'Sumber air di jalur pendakian', '6a679becdb342.jpg', 'spot', 4, '2026-07-23 17:05:02'),
(6, 'Kantong Semar', 'pos1', '', '', 'Spot tanaman kantong semar liar', '6a679c096b131.jpg', 'flora', 5, '2026-07-23 17:05:02'),
(7, 'Pos II', 'pos2', '1.765 MDPL', '45 menit', 'Kumbang Alang-alang', '6a679c2187add.jpg', 'spot', 6, '2026-07-23 17:05:02'),
(8, 'Pos III', 'pos3', '1.991 MDPL', '60 menit', 'Camp Area - Area perkemahan', '6a679c323fa19.jpeg', 'spot', 7, '2026-07-23 17:05:02'),
(9, 'Tanjakan Jalak Wangi', 'pos3', '', '', 'Titik tanjakan menantang', '6a679c3d70c9c.jpeg', 'wilayah', 8, '2026-07-23 17:05:02'),
(10, 'Pos IV', 'pos4', '2.204 MDPL', '60 menit', 'Camp Area - Area perkemahan terakhir', 'pos-4.jpg', 'spot', 9, '2026-07-23 17:05:02'),
(11, 'Sunrise Camp', 'sunrise', '', '', 'Spot utama menikmati matahari terbit', '6a679c4a99e2f.jpeg', 'spot', 10, '2026-07-23 17:05:02'),
(12, 'Puncak Hastinapura', 'puncak', '2.338 MDPL', '20 menit', 'Puncak dengan panorama 360 derajat', '6a679c6dbe2e5.jpeg', 'spot', 11, '2026-07-23 17:05:02'),
(13, 'Puncak Indraprasta', 'puncak', '2.365 MDPL', '20 menit', 'Puncak tertinggi Gunung Bismo', '6a679de126d74.jpeg', 'spot', 12, '2026-07-23 17:05:02');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `fauna`
--
ALTER TABLE `fauna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `flora`
--
ALTER TABLE `flora`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `galeri`
--
ALTER TABLE `galeri`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `peraturan`
--
ALTER TABLE `peraturan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

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
