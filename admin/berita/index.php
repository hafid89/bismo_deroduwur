<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

if (!isLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$stmt = $pdo->query("SELECT COUNT(*) as total FROM berita");
$total = $stmt->fetch()['total'];
$totalPages = ceil($total / $limit);

$limit = intval($limit);
$offset = intval($offset);
$stmt = $pdo->query("SELECT * FROM berita ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
$berita_list = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Berita - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#FAF7F2]">
<div class="flex h-screen">
    <!-- Sidebar -->
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
                <h1 class="text-3xl font-bold text-[#2F5233]">Kelola Berita</h1>
                <a href="tambah.php" class="bg-[#2F5233] hover:bg-[#4A7A4E] text-white px-6 py-2 rounded-full transition duration-300">
                    + Tambah Berita
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-[#FAF7F2] border-b border-gray-200">
                                <th class="text-left py-3 px-6 text-[#5C5C50] font-semibold">#</th>
                                <th class="text-left py-3 px-6 text-[#5C5C50] font-semibold">Judul</th>
                                <th class="text-left py-3 px-6 text-[#5C5C50] font-semibold">Tanggal</th>
                                <th class="text-left py-3 px-6 text-[#5C5C50] font-semibold">Foto</th>
                                <th class="text-left py-3 px-6 text-[#5C5C50] font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($berita_list as $index => $berita): ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition duration-300">
                                <td class="py-3 px-6"><?= $offset + $index + 1 ?></td>
                                <td class="py-3 px-6 font-medium"><?= htmlspecialchars($berita['judul']) ?></td>
                                <td class="py-3 px-6 text-[#5C5C50]"><?= formatTanggal($berita['tanggal']) ?></td>
                                <td class="py-3 px-6">
                                    <?php if ($berita['foto']): ?>
                                    <img src="<?= BASE_URL ?>uploads/berita/<?= htmlspecialchars($berita['foto']) ?>" class="w-16 h-12 object-cover rounded">
                                    <?php else: ?>
                                    <span class="text-gray-400">Tidak ada</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-6">
                                    <a href="edit.php?id=<?= $berita['id'] ?>" class="text-blue-600 hover:text-blue-800 mr-3 transition duration-300">Edit</a>
                                    <a href="hapus.php?id=<?= $berita['id'] ?>" class="text-red-600 hover:text-red-800 transition duration-300" onclick="return confirm('Yakin ingin menghapus berita ini?')">Hapus</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
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