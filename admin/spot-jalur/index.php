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

// Hitung total data
$stmt = $pdo->query("SELECT COUNT(*) as total FROM spot_jalur");
$total = $stmt->fetch()['total'];
$totalPages = ceil($total / $limit);

// Ambil data
$stmt = $pdo->query("SELECT * FROM spot_jalur ORDER BY urutan ASC, id ASC LIMIT $limit OFFSET $offset");
$spots = $stmt->fetchAll();

// Statistik per jenis
$stmt = $pdo->query("SELECT jenis, COUNT(*) as jumlah FROM spot_jalur GROUP BY jenis");
$jenis_stats = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Spot Jalur - Admin</title>
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

        /* Stat Card */
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

        /* Badge Jenis */
        .badge-spot { background: #e8f5e9; color: #2e7d32; padding: 3px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .badge-flora { background: #e8f5e9; color: #2e7d32; padding: 3px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .badge-fauna { background: #fff3e0; color: #e65100; padding: 3px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .badge-wilayah { background: #f3e5f5; color: #6a1b9a; padding: 3px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; }

        /* Table */
        .table-wrap {
            background: #fff;
            border-radius: 16px;
            padding: 20px 24px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
            border: 1px solid rgba(0,0,0,0.03);
        }
        .table-wrap table {
            width: 100%;
            border-collapse: collapse;
        }
        .table-wrap th {
            text-align: left;
            padding: 12px 8px 12px 0;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #8a7e72;
            border-bottom: 1px solid #f0ebe6;
        }
        .table-wrap td {
            padding: 12px 8px 12px 0;
            font-size: 14px;
            color: #2d241c;
            border-bottom: 1px solid #f6f2ed;
        }
        .table-wrap tr:last-child td { border-bottom: none; }

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
            padding: 40px 20px;
            color: #8a7e72;
        }
        .empty-state .icon { font-size: 48px; margin-bottom: 12px; display: block; }

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
            <a href="../flora/index.php" class="nav-link"><span class="icon">🌿</span> Flora</a>
            <a href="../fauna/index.php" class="nav-link"><span class="icon">🐾</span> Fauna</a>
            <a href="../peraturan/index.php" class="nav-link"><span class="icon">📋</span> Peraturan</a>
            <a href="index.php" class="nav-link active"><span class="icon">📍</span> Spot Jalur <span class="badge"><?= $total ?></span></a>
            <a href="../logout.php" class="nav-link text-red-300/70 hover:text-red-300"><span class="icon">🚪</span> Keluar</a>
        </nav>

        <div class="pt-4 border-t border-white/10 mt-auto">
            <p class="text-[10px] text-white/30 text-center mt-3 tracking-wider">v1.0 • KKN 84.384</p>
        </div>
    </aside>

    <!-- ==================== MAIN ==================== -->
    <main class="flex-1 overflow-y-auto p-6 md:p-8">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 animate-in delay-1">
            <div>
                <p class="text-sm text-[#8a7e72] font-medium">📍 Manajemen Rute</p>
                <h1 class="text-2xl font-bold text-[#1e3a2a]">Kelola Spot Jalur</h1>
                <p class="text-sm text-[#8a7e72]">Kelola spot-spot di sepanjang jalur pendakian</p>
            </div>
            <a href="tambah.php" class="btn-primary-custom flex items-center gap-2">
                <span>+</span> Tambah Spot
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
                <p class="label">Total Spot</p>
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
                <p class="label">🔢 Urutan</p>
                <p class="num text-sm font-medium" style="font-size:14px; color:#4a7a4e;">
                    <?= $total > 0 ? $total : 0 ?>
                </p>
            </div>
        </div>

        <!-- Jenis Stats -->
        <?php if (!empty($jenis_stats)): ?>
        <div class="flex flex-wrap gap-2 mb-6 animate-in delay-3">
            <?php 
            $badgeClass = [
                'spot' => 'badge-spot', 
                'flora' => 'badge-flora', 
                'fauna' => 'badge-fauna', 
                'wilayah' => 'badge-wilayah'
            ];
            $icons = ['spot' => '📍', 'flora' => '🌿', 'fauna' => '🐾', 'wilayah' => '🌄'];
            foreach ($jenis_stats as $j):
            ?>
            <span class="<?= $badgeClass[$j['jenis']] ?? 'badge-spot' ?>">
                <?= $icons[$j['jenis']] ?? '📍' ?> <?= ucfirst($j['jenis']) ?>: <?= $j['jumlah'] ?>
            </span>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Tabel -->
        <div class="table-wrap animate-in delay-4">
            <div class="overflow-x-auto">
                <table>
                    <thead>
                        <tr>
                            <th style="width:50px">#</th>
                            <th>Nama</th>
                            <th style="width:120px">Posisi</th>
                            <th style="width:100px">Ketinggian</th>
                            <th style="width:100px">Jenis</th>
                            <th style="width:70px">Urutan</th>
                            <th style="width:140px; text-align:right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($spots)): ?>
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <span class="icon">📍</span>
                                    <p class="font-medium text-[#2d241c]">Belum ada spot jalur</p>
                                    <p class="text-sm">Mulai dengan menambahkan spot pertama</p>
                                    <a href="tambah.php" class="btn-primary-custom mt-3 text-sm inline-block">+ Tambah Spot</a>
                                </div>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($spots as $index => $spot): 
                            $badgeClass = $spot['jenis'] == 'spot' ? 'badge-spot' : 
                                         ($spot['jenis'] == 'flora' ? 'badge-flora' : 
                                         ($spot['jenis'] == 'fauna' ? 'badge-fauna' : 'badge-wilayah'));
                            $icon = $spot['jenis'] == 'spot' ? '📍' : 
                                   ($spot['jenis'] == 'flora' ? '🌿' : 
                                   ($spot['jenis'] == 'fauna' ? '🐾' : '🌄'));
                        ?>
                        <tr>
                            <td class="text-[#8a7e72] text-sm"><?= $offset + $index + 1 ?></td>
                            <td class="font-medium text-[#1e3a2a]"><?= htmlspecialchars($spot['nama']) ?></td>
                            <td class="text-[#8a7e72] text-sm"><?= htmlspecialchars($spot['posisi']) ?></td>
                            <td class="text-[#8a7e72] text-sm"><?= htmlspecialchars($spot['ketinggian'] ?: '—') ?></td>
                            <td>
                                <span class="<?= $badgeClass ?>"><?= $icon ?> <?= ucfirst(htmlspecialchars($spot['jenis'])) ?></span>
                            </td>
                            <td class="text-[#8a7e72] text-sm font-medium">#<?= $spot['urutan'] ?></td>
                            <td style="text-align:right">
                                <a href="edit.php?id=<?= $spot['id'] ?>" class="btn-edit">Edit</a>
                                <a href="hapus.php?id=<?= $spot['id'] ?>" class="btn-delete" onclick="return confirm('Yakin ingin menghapus spot ini?')">Hapus</a>
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
        <div class="flex flex-wrap items-center justify-center gap-2 mt-6 animate-in delay-5">
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