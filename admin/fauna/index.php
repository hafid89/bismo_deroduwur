<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

if (!isLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$total = getTotalFauna();
$totalPages = ceil($total / $limit);
$fauna_list = getFaunaPaginated($page, $limit);

// Pesan sukses/error dari session
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
$message_type = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : '';
unset($_SESSION['message']);
unset($_SESSION['message_type']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Fauna - Admin</title>
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
        .badge-lokasi {
            background: #FAF7F2;
            color: #A9784B;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
        .alert-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .alert-danger {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
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
            <a href="../flora/index.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300">Kelola Flora</a>
            <a href="index.php" class="block py-2 px-4 bg-white/10 rounded-lg hover:bg-white/20 transition duration-300">Kelola Fauna</a>
            <a href="../peraturan/index.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300">Kelola Peraturan</a>
            <a href="../logout.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300 text-red-300">Logout</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto">
        <div class="p-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-[#2F5233]">🐾 Kelola Fauna</h1>
                    <p class="text-[#5C5C50] text-sm mt-1">Kelola data fauna di Gunung Bismo</p>
                </div>
                <a href="tambah.php" class="bg-[#2F5233] hover:bg-[#4A7A4E] text-white px-6 py-2.5 rounded-full transition duration-300 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Fauna
                </a>
            </div>

            <!-- Alert Message -->
            <?php if ($message): ?>
            <div class="p-4 rounded-lg mb-6 <?= $message_type == 'success' ? 'alert-success' : 'alert-danger' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
            <?php endif; ?>

            <!-- Grid Fauna -->
            <?php if (empty($fauna_list)): ?>
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <div class="text-6xl mb-4">🐾</div>
                <h3 class="text-xl font-bold text-[#2F5233] mb-2">Belum Ada Data Fauna</h3>
                <p class="text-[#5C5C50] mb-4">Silakan tambahkan data fauna pertama Anda</p>
                <a href="tambah.php" class="bg-[#2F5233] hover:bg-[#4A7A4E] text-white px-6 py-2 rounded-full transition duration-300 inline-block">
                    + Tambah Fauna
                </a>
            </div>
            <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($fauna_list as $fauna): ?>
                <div class="gallery-item bg-white rounded-xl shadow-lg overflow-hidden">
                    <?php if (!empty($fauna['foto']) && file_exists(UPLOAD_PATH . 'fauna/' . $fauna['foto'])): ?>
                    <img src="<?= BASE_URL ?>uploads/fauna/<?= htmlspecialchars($fauna['foto']) ?>" 
                         alt="<?= htmlspecialchars($fauna['nama']) ?>" 
                         class="w-full h-48 object-cover">
                    <?php else: ?>
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-5xl text-gray-400">
                        🐾
                    </div>
                    <?php endif; ?>
                    <div class="p-4">
                        <div class="flex justify-between items-start">
                            <h4 class="font-bold text-[#2F5233] text-lg"><?= htmlspecialchars($fauna['nama']) ?></h4>
                            <?php if (!empty($fauna['lokasi'])): ?>
                            <span class="badge-lokasi text-xs">📍 <?= htmlspecialchars($fauna['lokasi']) ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($fauna['nama_ilmiah'])): ?>
                        <p class="text-sm text-[#A9784B] italic"><?= htmlspecialchars($fauna['nama_ilmiah']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($fauna['deskripsi'])): ?>
                        <p class="text-sm text-[#5C5C50] mt-2 line-clamp-2"><?= htmlspecialchars($fauna['deskripsi']) ?></p>
                        <?php endif; ?>
                        <div class="mt-3 flex space-x-3 pt-3 border-t border-gray-100">
                            <a href="edit.php?id=<?= $fauna['id'] ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium transition duration-300 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>
                            <a href="hapus.php?id=<?= $fauna['id'] ?>" class="text-red-600 hover:text-red-800 text-sm font-medium transition duration-300 flex items-center gap-1" onclick="return confirm('Yakin ingin menghapus data fauna ini?')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Hapus
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <div class="flex justify-center mt-8 space-x-2">
                <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>" class="px-4 py-2 rounded-full bg-white text-gray-700 hover:bg-gray-300 transition duration-300">←</a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>" 
                   class="px-4 py-2 rounded-full <?= $i == $page ? 'bg-[#2F5233] text-white' : 'bg-white text-gray-700 hover:bg-gray-300' ?> transition duration-300">
                    <?= $i ?>
                </a>
                <?php endfor; ?>
                
                <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?>" class="px-4 py-2 rounded-full bg-white text-gray-700 hover:bg-gray-300 transition duration-300">→</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>