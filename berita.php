<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 9;
$offset = ($page - 1) * $limit;

// Get total berita
$stmt = $pdo->query("SELECT COUNT(*) as total FROM berita");
$total = $stmt->fetch()['total'];
$totalPages = ceil($total / $limit);

// Get berita
$limit = intval($limit);
$offset = intval($offset);
$stmt = $pdo->query("SELECT * FROM berita ORDER BY tanggal DESC LIMIT $limit OFFSET $offset");
$berita_list = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- ===== FAVICON / LOGO DI TAB (BULAT TRANSPARAN) ===== -->
    <!-- Favicon utama -->
    <link rel="icon" type="image/png" sizes="32x32" href="<?= BASE_URL ?>assets/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= BASE_URL ?>assets/images/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= BASE_URL ?>assets/images/apple-touch-icon.png">
    <link rel="shortcut icon" href="<?= BASE_URL ?>assets/images/favicon.ico">

    <!-- Meta untuk theme color (agar background tab sesuai) -->
    <meta name="theme-color" content="#2F5233">
    <title>Kabar Bismo - Gunung Bismo via Deroduwur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Outfit', sans-serif; }
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body>

<?php include __DIR__ . '/includes/navbar.php'; ?>

<!-- Header -->
<section class="pt-32 pb-16 bg-[#FAF7F2]">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl md:text-5xl font-bold text-[#2F5233] text-center">Kabar Bismo</h1>
        <p class="text-center text-[#5C5C50] mt-4 max-w-2xl mx-auto">
            Informasi terbaru seputar Gunung Bismo dan Basecamp Deroduwur
        </p>
    </div>
</section>

<!-- Berita List -->
<section class="py-12 bg-white">
    <div class="container mx-auto px-4">
        <?php if (empty($berita_list)): ?>
        <div class="text-center py-12">
            <p class="text-[#5C5C50]">Belum ada berita</p>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($berita_list as $berita): ?>
            <div class="bg-[#FAF7F2] rounded-2xl overflow-hidden shadow-lg card-hover">
                <?php if ($berita['foto']): ?>
                <img src="<?= BASE_URL ?>uploads/berita/<?= htmlspecialchars($berita['foto']) ?>" 
                     alt="<?= htmlspecialchars($berita['judul']) ?>" 
                     class="w-full h-56 object-cover">
                <?php endif; ?>
                <div class="p-6">
                    <p class="text-sm text-[#A9784B] mb-2"><?= formatTanggal($berita['tanggal']) ?></p>
                    <h3 class="text-xl font-bold text-[#2F5233] mb-2"><?= htmlspecialchars($berita['judul']) ?></h3>
                    <p class="text-[#5C5C50] text-sm mb-4"><?= truncateText(strip_tags($berita['isi']), 120) ?></p>
                    <a href="<?= BASE_URL ?>berita-detail.php?id=<?= $berita['id'] ?>" class="text-[#2F5233] font-semibold hover:text-[#4A7A4E] transition duration-300">
                        Baca Selengkapnya →
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="flex justify-center mt-12 space-x-2">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>" 
               class="px-4 py-2 rounded-full <?= $i == $page ? 'bg-[#2F5233] text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?> transition duration-300">
                <?= $i ?>
            </a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
<script src="assets/js/main.js"></script>
</body>
</html>