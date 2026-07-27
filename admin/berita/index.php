<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

if (!isLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

// Ambil pesan dari session
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
$message_type = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : '';
unset($_SESSION['message']);
unset($_SESSION['message_type']);

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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
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
        .card-table {
            background: #fff;
            border-radius: 16px;
            padding: 20px 24px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
            border: 1px solid rgba(0,0,0,0.03);
        }
        .card-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .card-table th {
            text-align: left;
            padding: 12px 8px 12px 0;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #8a7e72;
            border-bottom: 1px solid #f0ebe6;
        }
        .card-table td {
            padding: 14px 8px 14px 0;
            font-size: 14px;
            color: #2d241c;
            border-bottom: 1px solid #f6f2ed;
        }
        .card-table tr:last-child td { border-bottom: none; }

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

        /* Scrollbar */
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

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #8a7e72;
        }
        .empty-state .icon { font-size: 48px; margin-bottom: 12px; }
    </style>
</head>
<body>
<div class="flex h-screen overflow-hidden">

    <!-- ==================== SIDEBAR ==================== -->
    <aside class="sidebar w-[220px] flex-shrink-0 h-full flex flex-col p-4">
        <div class="flex items-center gap-3 px-2 py-4 mb-6">
            <div class="w-10 h-10 rounded-xl bg-[#E0BE45]/20 flex items-center justify-center text-xl"><i class="bi bi-mountain text-lg"></i></div>
            <div>
                <p class="text-white font-bold text-sm leading-tight">Gunung Bismo</p>
                <p class="text-[#b8c9b0] text-[10px] font-medium tracking-wider">PANEL ADMIN</p>
            </div>
        </div>

        <nav class="flex-1 space-y-1">
            <a href="../dashboard.php" class="nav-link"><span class="icon"><i class="bi bi-bar-chart"></i></span> Dashboard</a>
            <a href="index.php" class="nav-link active"><span class="icon"><i class="bi bi-newspaper"></i></span> Berita <span class="badge"><?= $total ?></span></a>
            <a href="../galeri/index.php" class="nav-link"><i class="bi bi-image"></i> Galeri</a>
            <a href="../flora/index.php" class="nav-link"><span class="icon"><i class="bi bi-leaf"></i></span> Flora</a>
            <a href="../fauna/index.php" class="nav-link"><span class="icon"><i class="bi bi-paw"></i></span> Fauna</a>
            <a href="../peraturan/index.php" class="nav-link"><span class="icon"><i class="bi bi-list-check"></i></span> Peraturan</a>
            <a href="../spot-jalur/index.php" class="nav-link"><span class="icon"><i class="bi bi-geo-alt"></i></span> Spot Jalur</a>
        </nav>

        <div class="pt-4 border-t border-white/10 mt-auto">
            <a href="../logout.php" class="nav-link text-red-300/70 hover:text-red-300"><span class="icon"><i class="bi bi-box-arrow-left"></i></span> Keluar</a>
            <p class="text-[10px] text-white/30 text-center mt-3 tracking-wider">v1.0 • KKN 84.384</p>
        </div>
    </aside>

    <!-- ==================== MAIN ==================== -->
    <main class="flex-1 overflow-y-auto p-6 md:p-8">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 animate-in delay-1">
            <div>
                <p class="text-sm text-[#8a7e72] font-medium"><i class="bi bi-newspaper"></i> Manajemen Konten</p>
                <h1 class="text-2xl font-bold text-[#1e3a2a]">Kelola Berita</h1>
                <p class="text-sm text-[#8a7e72]">Kelola semua berita dan informasi terkini</p>
            </div>
            <a href="tambah.php" class="btn-primary-custom flex items-center gap-2">
                <span>+</span> Tambah Berita
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
                <p class="label">Total Berita</p>
                <p class="num"><?= $total ?></p>
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
                <p class="label"><i class="bi bi-calendar3"></i> Terakhir</p>
                <p class="num text-sm font-medium" style="font-size:14px; color:#4a7a4e;">
                    <?php 
                    $last = $pdo->query("SELECT tanggal FROM berita ORDER BY created_at DESC LIMIT 1")->fetch();
                    echo $last ? formatTanggal($last['tanggal']) : '-';
                    ?>
                </p>
            </div>
        </div>

        <!-- Tabel -->
        <div class="card-table animate-in delay-3">
            <div class="overflow-x-auto">
                <table>
                    <thead>
                        <tr>
                            <th style="width:50px">#</th>
                            <th>Judul</th>
                            <th style="width:130px">Tanggal</th>
                            <th style="width:80px">Foto</th>
                            <th style="width:140px; text-align:right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($berita_list)): ?>
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <div class="icon"><i class="bi bi-inbox"></i></div>
                                    <p class="font-medium text-[#2d241c]">Belum ada berita</p>
                                    <p class="text-sm">Mulai dengan menambahkan berita pertama</p>
                                    <a href="tambah.php" class="btn-primary-custom mt-3 text-sm inline-block">+ Tambah Berita</a>
                                </div>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($berita_list as $index => $berita): ?>
                        <tr>
                            <td class="text-[#8a7e72] text-sm"><?= $offset + $index + 1 ?></td>
                            <td class="font-medium text-[#2d241c]"><?= htmlspecialchars($berita['judul']) ?></td>
                            <td class="text-[#8a7e72] text-sm"><?= formatTanggal($berita['tanggal']) ?></td>
                            <td>
                                <?php if ($berita['foto']): ?>
                                <img src="<?= BASE_URL ?>uploads/berita/<?= htmlspecialchars($berita['foto']) ?>" 
                                     class="w-14 h-10 object-cover rounded-md border border-gray-100">
                                <?php else: ?>
                                <span class="text-[#b8aaa0] text-xs">—</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align:right">
                                <a href="edit.php?id=<?= $berita['id'] ?>" class="btn-edit">Edit</a>
                                <a href="hapus.php?id=<?= $berita['id'] ?>" class="btn-delete" onclick="return confirm('Yakin ingin menghapus berita ini?')">Hapus</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="flex flex-wrap items-center justify-center gap-2 mt-6 animate-in delay-4">
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

        <!-- Footer -->
        <p class="text-center text-[10px] text-[#b8aaa0] mt-8 tracking-wider border-t border-[#f0ebe6] pt-4">
            © <?= date('Y') ?> Gunung Bismo via Deroduwur · KKN 84.384 UPNVYK
        </p>
    </main>
</div>
</body>
</html>
