<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

if (!isLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$total = getTotalFlora();
$totalPages = ceil($total / $limit);
$flora_list = getFloraPaginated($page, $limit);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Flora - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }
        .gallery-item {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .gallery-item:hover {
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
            <a href="../dashboard.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300">Dashboard</a>
            <a href="../berita/index.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300">Kelola Berita</a>
            <a href="../galeri/index.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300">Kelola Galeri</a>
            <a href="index.php" class="block py-2 px-4 bg-white/10 rounded-lg hover:bg-white/20 transition duration-300">Kelola Flora</a>
            <a href="../fauna/index.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300">Kelola Fauna</a>
            <a href="../peraturan/index.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300">Kelola Peraturan</a>
            <a href="../logout.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300 text-red-300">Logout</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto">
        <div class="p-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-[#2F5233]">🌿 Kelola Flora</h1>
                <a href="tambah.php" class="bg-[#2F5233] hover:bg-[#4A7A4E] text-white px-6 py-2 rounded-full transition duration-300">
                    + Tambah Flora
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($flora_list as $flora): ?>
                <div class="gallery-item bg-white rounded-xl shadow-lg overflow-hidden">
                    <?php if (!empty($flora['foto']) && file_exists(UPLOAD_PATH . 'flora/' . $flora['foto'])): ?>
                    <img src="<?= BASE_URL ?>uploads/flora/<?= htmlspecialchars($flora['foto']) ?>" 
                         alt="<?= htmlspecialchars($flora['nama']) ?>" 
                         class="w-full h-48 object-cover">
                    <?php else: ?>
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-4xl text-gray-400">
                        🌿
                    </div>
                    <?php endif; ?>
                    <div class="p-4">
                        <h4 class="font-bold text-[#2F5233]"><?= htmlspecialchars($flora['nama']) ?></h4>
                        <?php if (!empty($flora['nama_ilmiah'])): ?>
                        <p class="text-sm text-[#A9784B]"><?= htmlspecialchars($flora['nama_ilmiah']) ?></p>
                        <?php endif; ?>
                        <p class="text-sm text-[#5C5C50] mt-1 line-clamp-2"><?= htmlspecialchars($flora['deskripsi']) ?></p>
                        <div class="mt-3 flex space-x-2">
                            <a href="edit.php?id=<?= $flora['id'] ?>" class="text-blue-600 hover:text-blue-800 text-sm transition duration-300">Edit</a>
                            <a href="hapus.php?id=<?= $flora['id'] ?>" class="text-red-600 hover:text-red-800 text-sm transition duration-300" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php if ($totalPages > 1): ?>
            <div class="flex justify-center mt-8 space-x-2">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>" 
                   class="px-4 py-2 rounded-full <?= $i == $page ? 'bg-[#2F5233] text-white' : 'bg-white text-gray-700 hover:bg-gray-300' ?> transition duration-300">
                    <?= $i ?>
                </a>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>