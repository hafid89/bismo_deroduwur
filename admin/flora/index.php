<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

if (!isLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 12;
$offset = ($page - 1) * $limit;

$total = getTotalFlora();
$totalPages = ceil($total / $limit);
$flora_list = getFloraPaginated($page, $limit);

// Pesan sukses/error dari session
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
$message_type = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : '';
unset($_SESSION['message']);
unset($_SESSION['message_type']);

// Ambil statistik tambahan
$stmt = $pdo->query("SELECT COUNT(*) as total FROM flora");
$total_flora = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT DISTINCT lokasi FROM flora WHERE lokasi IS NOT NULL AND lokasi != '' LIMIT 5");
$lokasi_list = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Flora - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; transition: all 0.2s ease; }

        body {
            background: #f5f0eb;
            background-image: radial-gradient(circle at 10% 20%, rgba(74, 122, 78, 0.03) 0%, transparent 50%);
        }

        /* Sidebar */
        .sidebar {
            background: linear-gradient(180deg, #1e3a2a 0%, #2a4a35 100%);
            box-shadow: 4px 0 20px rgba(0,0,0,0.08);
        }
        .sidebar .nav-link {
            padding: 10px 16px;
            border-radius: 10px;
            color: rgba(255,255,255,0.65);
            font-weight: 500;
            font-size: 14px;
            transition: all 0.25s ease;
            display: block;
        }
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.08);
            color: #fff;
            transform: translateX(4px);
        }
        .sidebar .nav-link.active {
            background: rgba(224, 190, 69, 0.15);
            color: #E0BE45;
            box-shadow: inset 3px 0 0 #E0BE45;
        }
        .sidebar .nav-link .icon { margin-right: 10px; }
        .sidebar .nav-link .badge {
            background: rgba(224, 190, 69, 0.2);
            color: #E0BE45;
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 20px;
            float: right;
        }

        /* Card */
        .stat-card {
            background: #fff;
            border-radius: 14px;
            padding: 16px 18px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            border: 1px solid rgba(0,0,0,0.03);
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(47, 82, 51, 0.06);
        }
        .stat-card .num {
            font-size: 24px;
            font-weight: 800;
            color: #1e3a2a;
            line-height: 1.1;
        }
        .stat-card .label {
            font-size: 11px;
            font-weight: 500;
            color: #8a7e72;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .flora-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            border: 1px solid rgba(0,0,0,0.03);
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .flora-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 48px rgba(47, 82, 51, 0.08);
            border-color: rgba(47, 82, 51, 0.08);
        }
        .flora-card .image-wrap {
            height: 180px;
            overflow: hidden;
            background: #f0ebe6;
            position: relative;
        }
        .flora-card .image-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .flora-card:hover .image-wrap img {
            transform: scale(1.04);
        }
        .flora-card .body {
            padding: 16px 18px 18px;
        }
        .flora-card .badge-lokasi {
            background: #f0ebe6;
            color: #8a7e72;
            font-size: 10px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
            display: inline-block;
        }

        .btn-edit {
            color: #4a7a4e;
            font-weight: 500;
            font-size: 13px;
            margin-right: 12px;
            text-decoration: none;
        }
        .btn-edit:hover { color: #2a4a35; }

        .btn-delete {
            color: #c0392b;
            font-weight: 500;
            font-size: 13px;
            text-decoration: none;
        }
        .btn-delete:hover { color: #a93226; }

        .btn-primary-custom {
            background: #2F5233;
            color: #fff;
            padding: 10px 22px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary-custom:hover {
            background: #1e3a2a;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(47, 82, 51, 0.2);
        }

        .alert-success {
            background: #e8f5e9;
            border-left: 4px solid #4caf50;
            color: #1e3a2a;
            padding: 12px 16px;
            border-radius: 10px;
        }
        .alert-danger {
            background: #fce4ec;
            border-left: 4px solid #ef5350;
            color: #5c1a1a;
            padding: 12px 16px;
            border-radius: 10px;
        }

        .pagination-btn {
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            background: #fff;
            color: #5c4e42;
            border: 1px solid #f0ebe6;
            transition: all 0.25s ease;
            text-decoration: none;
            display: inline-block;
        }
        .pagination-btn:hover {
            background: #f5f0eb;
            border-color: #d5cdc4;
        }
        .pagination-btn.active {
            background: #2F5233;
            color: #fff;
            border-color: #2F5233;
        }

        .empty-state {
            text-align: center;
            padding: 48px 20px;
            color: #8a7e72;
        }
        .empty-state .icon { font-size: 56px; margin-bottom: 12px; display: block; }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f0ebe6; border-radius: 8px; }
        ::-webkit-scrollbar-thumb { background: #d5cdc4; border-radius: 8px; }
        ::-webkit-scrollbar-thumb:hover { background: #b8aaa0; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in {
            animation: fadeUp 0.45s ease forwards;
            opacity: 0;
        }
        .delay-1 { animation-delay: 0.05s; }
        .delay-2 { animation-delay: 0.1s; }
        .delay-3 { animation-delay: 0.15s; }
        .delay-4 { animation-delay: 0.2s; }
        .delay-5 { animation-delay: 0.25s; }
        .delay-6 { animation-delay: 0.3s; }
    </style>
</head>
<body>
<div class="flex h-screen overflow-hidden">

    <!-- ==================== SIDEBAR ==================== -->
    <aside class="sidebar w-[220px] flex-shrink-0 h-full flex flex-col p-4">
        <div class="flex items-center gap-3 px-2 py-4 mb-6">
            <div class="w-10 h-10 rounded-xl bg-[#E0BE45]/20 flex items-center justify-center text-xl">🏔️</div>
            <div>
                <p class="text-white font-bold text-sm leading-tight">Gunung Bismo</p>
                <p class="text-[#b8c9b0] text-[10px] font-medium tracking-wider">PANEL ADMIN</p>
            </div>
        </div>

        <nav class="flex-1 space-y-1">
            <a href="../dashboard.php" class="nav-link"><span class="icon">📊</span> Dashboard</a>
            <a href="../berita/index.php" class="nav-link"><span class="icon">📰</span> Berita</a>
            <a href="../galeri/index.php" class="nav-link"><span class="icon">🖼️</span> Galeri</a>
            <a href="index.php" class="nav-link active"><span class="icon">🌿</span> Flora <span class="badge"><?= $total_flora ?></span></a>
            <a href="../fauna/index.php" class="nav-link"><span class="icon">🐾</span> Fauna</a>
            <a href="../peraturan/index.php" class="nav-link"><span class="icon">📋</span> Peraturan</a>
            <a href="../spot-jalur/index.php" class="nav-link"><span class="icon">📍</span> Spot Jalur</a>
        </nav>

        <div class="pt-4 border-t border-white/10 mt-auto">
            <a href="../logout.php" class="nav-link text-red-300/70 hover:text-red-300"><span class="icon">🚪</span> Keluar</a>
            <p class="text-[10px] text-white/30 text-center mt-3 tracking-wider">v1.0 • KKN 84.384</p>
        </div>
    </aside>

    <!-- ==================== MAIN ==================== -->
    <main class="flex-1 overflow-y-auto p-6 md:p-8">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 animate-in delay-1">
            <div>
                <p class="text-sm text-[#8a7e72] font-medium">🌿 Manajemen Tumbuhan</p>
                <h1 class="text-2xl font-bold text-[#1e3a2a]">Kelola Flora</h1>
                <p class="text-sm text-[#8a7e72]">Kelola data flora di Gunung Bismo</p>
            </div>
            <a href="tambah.php" class="btn-primary-custom flex items-center gap-2">
                <span>+</span> Tambah Flora
            </a>
        </div>

        <!-- Alert -->
        <?php if ($message): ?>
        <div class="mb-5 animate-in delay-2 <?= $message_type == 'success' ? 'alert-success' : 'alert-danger' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
        <?php endif; ?>

        <!-- Statistik -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
            <div class="stat-card animate-in delay-2">
                <p class="label">Total Flora</p>
                <p class="num"><?= $total_flora ?></p>
            </div>
            <div class="stat-card animate-in delay-3">
                <p class="label">Halaman</p>
                <p class="num"><?= $page ?> / <?= $totalPages ?: 1 ?></p>
            </div>
            <div class="stat-card animate-in delay-4">
                <p class="label">Per Halaman</p>
                <p class="num"><?= $limit ?></p>
            </div>
            <div class="stat-card animate-in delay-5">
                <p class="label">📍 Lokasi</p>
                <p class="num text-sm font-medium" style="font-size:14px; color:#4a7a4e;">
                    <?= count($lokasi_list) ?> area
                </p>
            </div>
        </div>

        <!-- Grid Flora -->
        <?php if (empty($flora_list)): ?>
        <div class="bg-white rounded-xl shadow-lg p-12 text-center animate-in delay-3">
            <div class="empty-state">
                <span class="icon">🌿</span>
                <h3 class="text-lg font-bold text-[#2d241c] mb-1">Belum Ada Data Flora</h3>
                <p class="text-sm text-[#8a7e72] mb-4">Mulai dengan menambahkan data flora pertama</p>
                <a href="tambah.php" class="btn-primary-custom">+ Tambah Flora</a>
            </div>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            <?php foreach ($flora_list as $index => $flora): 
                $delay = 'delay-' . (min(($index % 6) + 1, 6));
            ?>
            <div class="flora-card animate-in <?= $delay ?>">
                <div class="image-wrap">
                    <?php if (!empty($flora['foto']) && file_exists(UPLOAD_PATH . 'flora/' . $flora['foto'])): ?>
                    <img src="<?= BASE_URL ?>uploads/flora/<?= htmlspecialchars($flora['foto']) ?>" 
                         alt="<?= htmlspecialchars($flora['nama']) ?>">
                    <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center text-5xl text-[#b8aaa0]">
                        🌿
                    </div>
                    <?php endif; ?>
                </div>
                <div class="body">
                    <div class="flex items-start justify-between gap-2">
                        <h4 class="font-bold text-[#1e3a2a] text-sm leading-tight"><?= htmlspecialchars($flora['nama']) ?></h4>
                        <?php if (!empty($flora['lokasi'])): ?>
                        <span class="badge-lokasi text-xs whitespace-nowrap">📍 <?= htmlspecialchars($flora['lokasi']) ?></span>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($flora['nama_ilmiah'])): ?>
                    <p class="text-xs text-[#8a7e72] italic mt-0.5"><?= htmlspecialchars($flora['nama_ilmiah']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($flora['deskripsi'])): ?>
                    <p class="text-xs text-[#8a7e72] mt-1 line-clamp-2"><?= htmlspecialchars(substr($flora['deskripsi'], 0, 80)) ?><?= strlen($flora['deskripsi']) > 80 ? '…' : '' ?></p>
                    <?php endif; ?>
                    <div class="mt-3 pt-3 border-t border-[#f0ebe6] flex items-center gap-3">
                        <a href="edit.php?id=<?= $flora['id'] ?>" class="btn-edit text-xs">Edit</a>
                        <span class="text-[#e0d8d0]">|</span>
                        <a href="hapus.php?id=<?= $flora['id'] ?>" class="btn-delete text-xs" onclick="return confirm('Yakin ingin menghapus data flora ini?')">Hapus</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="flex flex-wrap items-center justify-center gap-2 mt-6 animate-in delay-6">
            <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>" class="pagination-btn">‹</a>
            <?php endif; ?>

            <?php 
            $start = max(1, $page - 2);
            $end = min($totalPages, $page + 2);
            if ($start > 1) { echo '<a href="?page=1" class="pagination-btn">1</a>'; if ($start > 2) echo '<span class="text-[#b8aaa0] px-1">…</span>'; }
            for ($i = $start; $i <= $end; $i++):
            ?>
            <a href="?page=<?= $i ?>" class="pagination-btn <?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; 
            if ($end < $totalPages) { if ($end < $totalPages - 1) echo '<span class="text-[#b8aaa0] px-1">…</span>'; echo '<a href="?page='.$totalPages.'" class="pagination-btn">'.$totalPages.'</a>'; }
            ?>

            <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>" class="pagination-btn">›</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <?php endif; ?>

        <!-- Footer -->
        <p class="text-center text-[10px] text-[#b8aaa0] mt-8 tracking-wider border-t border-[#f0ebe6] pt-4">
            © <?= date('Y') ?> Gunung Bismo via Deroduwur · KKN 84.384 UPNVYK
        </p>
    </main>
</div>
</body>
</html>