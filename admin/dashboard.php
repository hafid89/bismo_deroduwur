<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

$total_berita = getTotalBerita();
$total_galeri = getTotalGaleri();
$total_flora = getTotalFlora();
$total_fauna = getTotalFauna();
$total_peraturan = getTotalPeraturan();

$stmt = $pdo->query("SELECT * FROM berita ORDER BY created_at DESC LIMIT 5");
$recent_berita = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Gunung Bismo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }
        .stat-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-[#FAF7F2]">
<div class="flex h-screen">
    <!-- Sidebar -->
    <div class="w-64 bg-[#2F5233] text-white p-6">
        <h2 class="text-2xl font-bold mb-8">Panel Admin</h2>
        <nav class="space-y-2">
            <a href="dashboard.php" class="block py-2 px-4 bg-white/10 rounded-lg hover:bg-white/20 transition duration-300">
                Dashboard
            </a>
            <a href="berita/index.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300">
                Kelola Berita
            </a>
            <a href="galeri/index.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300">
                Kelola Galeri
            </a>
            <a href="flora/index.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300">
                Kelola Flora
            </a>
            <a href="fauna/index.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300">
                Kelola Fauna
            </a>
            <a href="peraturan/index.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300">
                Kelola Peraturan
            </a>
            <a href="logout.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300 text-red-300">
                Logout
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto">
        <div class="p-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-[#2F5233]">Dashboard</h1>
                <p class="text-[#5C5C50]">Halo, <?= htmlspecialchars($_SESSION['admin_full_name']) ?></p>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
                <div class="stat-card bg-white p-4 rounded-xl shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[#5C5C50] text-xs">Berita</p>
                            <p class="text-2xl font-bold text-[#2F5233]"><?= $total_berita ?></p>
                        </div>
                        <div class="w-10 h-10 bg-[#2F5233] rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="stat-card bg-white p-4 rounded-xl shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[#5C5C50] text-xs">Galeri</p>
                            <p class="text-2xl font-bold text-[#2F5233]"><?= $total_galeri ?></p>
                        </div>
                        <div class="w-10 h-10 bg-[#E0BE45] rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="stat-card bg-white p-4 rounded-xl shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[#5C5C50] text-xs">Flora</p>
                            <p class="text-2xl font-bold text-[#2F5233]"><?= $total_flora ?></p>
                        </div>
                        <div class="w-10 h-10 bg-[#3F7D4F] rounded-full flex items-center justify-center">
                            <span class="text-white text-lg">🌿</span>
                        </div>
                    </div>
                </div>
                <div class="stat-card bg-white p-4 rounded-xl shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[#5C5C50] text-xs">Fauna</p>
                            <p class="text-2xl font-bold text-[#2F5233]"><?= $total_fauna ?></p>
                        </div>
                        <div class="w-10 h-10 bg-[#C46F2A] rounded-full flex items-center justify-center">
                            <span class="text-white text-lg">🐾</span>
                        </div>
                    </div>
                </div>
                <div class="stat-card bg-white p-4 rounded-xl shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[#5C5C50] text-xs">Peraturan</p>
                            <p class="text-2xl font-bold text-[#2F5233]"><?= $total_peraturan ?></p>
                        </div>
                        <div class="w-10 h-10 bg-[#A9784B] rounded-full flex items-center justify-center">
                            <span class="text-white text-lg">📋</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Berita -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-[#2F5233] mb-4">Berita Terbaru</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 text-[#5C5C50] font-semibold">Judul</th>
                                <th class="text-left py-3 px-4 text-[#5C5C50] font-semibold">Tanggal</th>
                                <th class="text-left py-3 px-4 text-[#5C5C50] font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_berita as $berita): ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition duration-300">
                                <td class="py-3 px-4"><?= htmlspecialchars($berita['judul']) ?></td>
                                <td class="py-3 px-4 text-[#5C5C50]"><?= formatTanggal($berita['tanggal']) ?></td>
                                <td class="py-3 px-4">
                                    <a href="berita/edit.php?id=<?= $berita['id'] ?>" class="text-blue-600 hover:text-blue-800 mr-2">Edit</a>
                                    <a href="berita/hapus.php?id=<?= $berita['id'] ?>" class="text-red-600 hover:text-red-800" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>