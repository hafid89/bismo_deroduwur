<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

// Ambil semua data statistik
$total_berita = getTotalBerita();
$total_galeri = getTotalGaleri();
$total_flora = getTotalFlora();
$total_fauna = getTotalFauna();
$total_peraturan = getTotalPeraturan();

$stmt = $pdo->query("SELECT COUNT(*) as total FROM spot_jalur");
$total_spot = $stmt->fetch()['total'];

// Statistik spot per jenis
$stmt = $pdo->query("SELECT jenis, COUNT(*) as jumlah FROM spot_jalur GROUP BY jenis");
$spot_stats = $stmt->fetchAll();

// Berita terbaru
$stmt = $pdo->query("SELECT * FROM berita ORDER BY created_at DESC LIMIT 5");
$recent_berita = $stmt->fetchAll();

// Aktivitas terbaru (gabungan dari beberapa tabel)
$aktivitas = [];

// Berita terbaru
foreach ($recent_berita as $b) {
    $aktivitas[] = [
        'type' => 'berita',
        'judul' => $b['judul'],
        'tanggal' => $b['created_at'],
        'id' => $b['id']
    ];
}

// Urutkan berdasarkan tanggal
usort($aktivitas, function($a, $b) {
    return strtotime($b['tanggal']) - strtotime($a['tanggal']);
});
$aktivitas = array_slice($aktivitas, 0, 5);

// Waktu sekarang untuk sapaan
$jam = date('H');
if ($jam >= 5 && $jam < 11) $sapaan = 'Selamat Pagi';
elseif ($jam >= 11 && $jam < 15) $sapaan = 'Selamat Siang';
elseif ($jam >= 15 && $jam < 18) $sapaan = 'Selamat Sore';
else $sapaan = 'Selamat Malam';

$nama_admin = htmlspecialchars($_SESSION['admin_full_name'] ?? 'Admin');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Gunung Bismo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        
        /* Smooth Transition Global */
        * { transition: all 0.2s ease-in-out; }

        body {
            background: #f5f0eb;
            background-image: radial-gradient(circle at 10% 20%, rgba(74, 122, 78, 0.03) 0%, transparent 50%);
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            background: linear-gradient(180deg, #1e3a2a 0%, #2a4a35 100%);
            box-shadow: 4px 0 20px rgba(0,0,0,0.08);
        }
        .sidebar .nav-link {
            position: relative;
            padding: 10px 16px;
            border-radius: 10px;
            color: rgba(255,255,255,0.65);
            font-weight: 500;
            font-size: 14px;
            transition: all 0.25s ease;
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
        .sidebar .nav-link .icon {
            width: 22px;
            text-align: center;
            margin-right: 10px;
            font-size: 16px;
        }
        .sidebar .nav-link .badge {
            background: rgba(224, 190, 69, 0.2);
            color: #E0BE45;
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 20px;
            margin-left: auto;
        }

        /* Stat Card */
        .stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 18px 20px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
            border: 1px solid rgba(0,0,0,0.03);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: default;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(47, 82, 51, 0.08);
            border-color: rgba(47, 82, 51, 0.1);
        }
        .stat-card .number {
            font-size: 28px;
            font-weight: 800;
            color: #1e3a2a;
            line-height: 1.1;
        }
        .stat-card .label {
            font-size: 12px;
            font-weight: 500;
            color: #8a7e72;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        /* Quick Action */
        .quick-action {
            background: #fff;
            border-radius: 14px;
            padding: 16px 20px;
            border: 1px solid rgba(0,0,0,0.04);
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            transition: all 0.3s ease;
            cursor: pointer;
            text-align: center;
        }
        .quick-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(47, 82, 51, 0.08);
            border-color: rgba(47, 82, 51, 0.15);
        }
        .quick-action .emoji {
            font-size: 24px;
            display: block;
            margin-bottom: 4px;
        }
        .quick-action .label {
            font-size: 12px;
            font-weight: 600;
            color: #1e3a2a;
        }

        /* Table */
        .table-container {
            background: #fff;
            border-radius: 16px;
            padding: 20px 24px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
            border: 1px solid rgba(0,0,0,0.03);
        }
        .table-container table {
            width: 100%;
            border-collapse: collapse;
        }
        .table-container th {
            text-align: left;
            padding: 12px 8px 12px 0;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #8a7e72;
            border-bottom: 1px solid #f0ebe6;
        }
        .table-container td {
            padding: 12px 8px 12px 0;
            font-size: 14px;
            color: #2d241c;
            border-bottom: 1px solid #f6f2ed;
        }
        .table-container tr:last-child td {
            border-bottom: none;
        }
        .table-container .btn-edit {
            color: #4a7a4e;
            font-weight: 500;
            font-size: 13px;
            margin-right: 12px;
            text-decoration: none;
        }
        .table-container .btn-edit:hover { color: #2a4a35; }
        .table-container .btn-delete {
            color: #c0392b;
            font-weight: 500;
            font-size: 13px;
            text-decoration: none;
        }
        .table-container .btn-delete:hover { color: #a93226; }

        /* Aktivitas Card */
        .activity-item {
            padding: 12px 0;
            border-bottom: 1px solid #f6f2ed;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .activity-item:last-child { border-bottom: none; }
        .activity-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .activity-dot.berita { background: #2F5233; }
        .activity-dot.galeri { background: #E0BE45; }
        .activity-dot.flora { background: #3F7D4F; }
        .activity-dot.fauna { background: #C46F2A; }

        /* Tombol utama */
        .btn-primary-custom {
            background: #2F5233;
            color: #fff;
            padding: 10px 24px;
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

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f0ebe6; border-radius: 8px; }
        ::-webkit-scrollbar-thumb { background: #b8aaa0; border-radius: 8px; }
        ::-webkit-scrollbar-thumb:hover { background: #a09085; }

        /* Animasi masuk */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in {
            animation: fadeUp 0.5s ease forwards;
        }
        .delay-1 { animation-delay: 0.05s; opacity: 0; }
        .delay-2 { animation-delay: 0.1s; opacity: 0; }
        .delay-3 { animation-delay: 0.15s; opacity: 0; }
        .delay-4 { animation-delay: 0.2s; opacity: 0; }
        .delay-5 { animation-delay: 0.25s; opacity: 0; }
        .delay-6 { animation-delay: 0.3s; opacity: 0; }

        .greeting-text {
            background: linear-gradient(135deg, #1e3a2a 0%, #4a7a4e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body>
<div class="flex h-screen overflow-hidden">

    <!-- ==================== SIDEBAR ==================== -->
    <aside class="sidebar w-[220px] flex-shrink-0 h-full flex flex-col p-4">
        <!-- Brand -->
        <div class="flex items-center gap-3 px-2 py-4 mb-6">
            <div class="w-10 h-10 rounded-xl bg-[#E0BE45]/20 flex items-center justify-center text-xl">
                🏔️
            </div>
            <div>
                <p class="text-white font-bold text-sm leading-tight">Gunung Bismo</p>
                <p class="text-[#b8c9b0] text-[10px] font-medium tracking-wider">PANEL ADMIN</p>
            </div>
        </div>

        <!-- Nav -->
        <nav class="flex-1 space-y-1">
            <a href="dashboard.php" class="nav-link active flex items-center">
                <span class="icon">📊</span> Dashboard
            </a>
            <a href="berita/index.php" class="nav-link flex items-center">
                <span class="icon">📰</span> Berita
                <span class="badge"><?= $total_berita ?></span>
            </a>
            <a href="galeri/index.php" class="nav-link flex items-center">
                <span class="icon">🖼️</span> Galeri
                <span class="badge"><?= $total_galeri ?></span>
            </a>
            <a href="flora/index.php" class="nav-link flex items-center">
                <span class="icon">🌿</span> Flora
                <span class="badge"><?= $total_flora ?></span>
            </a>
            <a href="fauna/index.php" class="nav-link flex items-center">
                <span class="icon">🐾</span> Fauna
                <span class="badge"><?= $total_fauna ?></span>
            </a>
            <a href="peraturan/index.php" class="nav-link flex items-center">
                <span class="icon">📋</span> Peraturan
                <span class="badge"><?= $total_peraturan ?></span>
            </a>
            <a href="spot-jalur/index.php" class="nav-link flex items-center">
                <span class="icon">📍</span> Spot Jalur
                <span class="badge"><?= $total_spot ?></span>
            </a>
        </nav>

        <!-- Bottom -->
        <div class="pt-4 border-t border-white/10 mt-auto">
            <a href="logout.php" class="nav-link flex items-center text-red-300/70 hover:text-red-300">
                <span class="icon">🚪</span> Keluar
            </a>
            <p class="text-[10px] text-white/30 text-center mt-3 tracking-wider">
                v1.0 • KKN 84.384
            </p>
        </div>
    </aside>

    <!-- ==================== MAIN CONTENT ==================== -->
    <main class="flex-1 overflow-y-auto p-6 md:p-8">

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8 animate-in">
            <div>
                <p class="text-sm text-[#8a7e72] font-medium"><?= $sapaan ?></p>
                <h1 class="text-2xl md:text-3xl font-bold greeting-text">Halo, <?= $nama_admin ?> 👋</h1>
                <p class="text-sm text-[#8a7e72] mt-0.5">Selamat datang di panel admin Gunung Bismo via Deroduwur</p>
            </div>
            <div class="flex items-center gap-3 text-sm text-[#8a7e72] bg-white/70 backdrop-blur px-4 py-2 rounded-xl shadow-sm border border-white/50">
                <span>📅</span>
                <span><?= date('l, d F Y') ?></span>
                <span class="w-px h-4 bg-gray-200"></span>
                <span>🕐</span>
                <span id="clock"><?= date('H:i') ?></span>
            </div>
        </div>

        <!-- ===== STATISTIK ===== -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
            <div class="stat-card animate-in delay-1">
                <p class="label">Berita</p>
                <p class="number"><?= $total_berita ?></p>
            </div>
            <div class="stat-card animate-in delay-2">
                <p class="label">Galeri</p>
                <p class="number"><?= $total_galeri ?></p>
            </div>
            <div class="stat-card animate-in delay-3">
                <p class="label">Flora</p>
                <p class="number"><?= $total_flora ?></p>
            </div>
            <div class="stat-card animate-in delay-4">
                <p class="label">Fauna</p>
                <p class="number"><?= $total_fauna ?></p>
            </div>
            <div class="stat-card animate-in delay-5">
                <p class="label">Peraturan</p>
                <p class="number"><?= $total_peraturan ?></p>
            </div>
            <div class="stat-card animate-in delay-6">
                <p class="label">Spot Jalur</p>
                <p class="number"><?= $total_spot ?></p>
            </div>
        </div>

        <!-- ===== DETAIL SPOT ===== -->
        <?php if (!empty($spot_stats)): ?>
        <div class="flex flex-wrap gap-3 mb-6 animate-in delay-3">
            <?php foreach ($spot_stats as $s):
                $emoji = $s['jenis'] == 'spot' ? '📍' : ($s['jenis'] == 'flora' ? '🌿' : ($s['jenis'] == 'fauna' ? '🐾' : '🌄'));
                $color = $s['jenis'] == 'spot' ? '#2F5233' : ($s['jenis'] == 'flora' ? '#3F7D4F' : ($s['jenis'] == 'fauna' ? '#C46F2A' : '#A9784B'));
            ?>
            <div class="bg-white/80 backdrop-blur rounded-xl px-4 py-2 flex items-center gap-2 shadow-sm border border-white/50 text-sm">
                <span><?= $emoji ?></span>
                <span class="font-medium text-[#2d241c]"><?= ucfirst($s['jenis'])?></span>
                <span class="text-[#8a7e72]">·</span>
                <span class="font-bold" style="color: <?= $color ?>"><?= $s['jumlah'] ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- ===== TWO COLUMN LAYOUT ===== -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- ===== BERITA TERBARU ===== -->
            <div class="lg:col-span-2 table-container animate-in delay-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-bold text-[#1e3a2a]">📰 Berita Terbaru</h3>
                    <a href="berita/index.php" class="text-sm text-[#4a7a4e] font-medium hover:underline">Lihat semua</a>
                </div>
                <table>
                    <thead>
                        <tr><th>Judul</th><th>Tanggal</th><th style="text-align:right">Aksi</th></tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recent_berita)): ?>
                        <tr><td colspan="3" class="text-center text-[#8a7e72] py-4">Belum ada berita</td></tr>
                        <?php else: ?>
                        <?php foreach ($recent_berita as $b): ?>
                        <tr>
                            <td class="font-medium text-[#2d241c]"><?= htmlspecialchars($b['judul']) ?></td>
                            <td class="text-[#8a7e72] text-sm"><?= formatTanggal($b['tanggal']) ?></td>
                            <td style="text-align:right">
                                <a href="berita/edit.php?id=<?= $b['id'] ?>" class="btn-edit">Edit</a>
                                <a href="berita/hapus.php?id=<?= $b['id'] ?>" class="btn-delete" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- ===== AKTIVITAS TERBARU ===== -->
            <div class="table-container animate-in delay-5">
                <h3 class="text-base font-bold text-[#1e3a2a] mb-4">⚡ Aktivitas Terbaru</h3>
                <?php if (empty($aktivitas)): ?>
                <p class="text-sm text-[#8a7e72] text-center py-4">Belum ada aktivitas</p>
                <?php else: ?>
                <?php foreach ($aktivitas as $a): ?>
                <div class="activity-item">
                    <span class="activity-dot <?= $a['type'] ?>"></span>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-[#2d241c]"><?= htmlspecialchars($a['judul'] ?? 'Aktivitas') ?></p>
                        <p class="text-[10px] text-[#8a7e72]"><?= date('d M Y, H:i', strtotime($a['tanggal'])) ?></p>
                    </div>
                    <span class="text-[10px] font-medium text-[#4a7a4e] uppercase"><?= $a['type'] ?></span>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- ===== QUICK ACTIONS ===== -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6 animate-in delay-6">
            <a href="berita/tambah.php" class="quick-action">
                <span class="emoji">📰</span>
                <span class="label">Tambah Berita</span>
            </a>
            <a href="galeri/tambah.php" class="quick-action">
                <span class="emoji">🖼️</span>
                <span class="label">Tambah Galeri</span>
            </a>
            <a href="spot-jalur/tambah.php" class="quick-action">
                <span class="emoji">📍</span>
                <span class="label">Tambah Spot</span>
            </a>
            <a href="peraturan/tambah.php" class="quick-action">
                <span class="emoji">📋</span>
                <span class="label">Tambah Peraturan</span>
            </a>
        </div>

        <!-- Footer -->
        <p class="text-center text-[10px] text-[#b8aaa0] mt-8 tracking-wider border-t border-[#f0ebe6] pt-4">
            © <?= date('Y') ?> Gunung Bismo via Deroduwur · KKN 84.384 UPNVYK
        </p>
    </main>
</div>

<!-- Jam Real-Time -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const clock = document.getElementById('clock');
    if (clock) {
        setInterval(() => {
            const now = new Date();
            clock.textContent = now.toTimeString().slice(0,5);
        }, 1000);
    }

    // Animasi masuk dengan delay
    document.querySelectorAll('.animate-in').forEach((el, i) => {
        const delay = (i * 60) + 100;
        setTimeout(() => {
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, delay);
    });
});
</script>
</body>
</html>