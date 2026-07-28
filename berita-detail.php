<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM berita WHERE id = ?");
$stmt->execute([$id]);
$berita = $stmt->fetch();

if (!$berita) {
    header('Location: ' . BASE_URL . 'berita.php');
    exit();
}

// Get related berita
$stmt = $pdo->prepare("SELECT * FROM berita WHERE id != ? ORDER BY tanggal DESC LIMIT 3");
$stmt->execute([$id]);
$related = $stmt->fetchAll();
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
    <title><?= htmlspecialchars($berita['judul']) ?> - Gunung Bismo via Deroduwur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        .content img {
            max-width: 100%;
            height: auto;
            border-radius: 0.75rem;
            margin: 1.5rem 0;
        }
        .content p {
            margin-bottom: 1rem;
            line-height: 1.8;
            color: #5C5C50;
        }
        .content h2, .content h3, .content h4 {
            color: #2F5233;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
            font-weight: 600;
        }
        .content ul, .content ol {
            margin-bottom: 1rem;
            padding-left: 1.5rem;
            color: #5C5C50;
        }
        .content li {
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>

<?php include __DIR__ . '/includes/navbar.php'; ?>

<!-- Detail Berita -->
<section class="pt-32 pb-16 bg-[#FAF7F2]">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <?php if ($berita['foto']): ?>
            <img src="<?= BASE_URL ?>uploads/berita/<?= htmlspecialchars($berita['foto']) ?>" 
                 alt="<?= htmlspecialchars($berita['judul']) ?>" 
                 class="w-full h-96 object-cover">
            <?php endif; ?>
            <div class="p-8 md:p-12">
                <p class="text-sm text-[#A9784B] mb-3"><?= formatTanggal($berita['tanggal']) ?></p>
                <h1 class="text-3xl md:text-4xl font-bold text-[#2F5233] mb-6"><?= htmlspecialchars($berita['judul']) ?></h1>
                <div class="content">
                    <?= $berita['isi'] ?>
                </div>
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <a href="<?= BASE_URL ?>berita.php" class="inline-block bg-[#2F5233] hover:bg-[#4A7A4E] text-white px-6 py-2 rounded-full transition duration-300">
                        ← Kembali ke Kabar Bismo
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Berita -->
<?php if ($related): ?>
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <h2 class="text-2xl md:text-3xl font-bold text-[#2F5233] text-center mb-10">Kabar Bismo Lainnya</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php foreach ($related as $item): ?>
            <div class="bg-[#FAF7F2] rounded-2xl overflow-hidden shadow-lg card-hover">
                <?php if ($item['foto']): ?>
                <img src="<?= BASE_URL ?>uploads/berita/<?= htmlspecialchars($item['foto']) ?>" 
                     alt="<?= htmlspecialchars($item['judul']) ?>" 
                     class="w-full h-48 object-cover">
                <?php endif; ?>
                <div class="p-6">
                    <p class="text-sm text-[#A9784B] mb-2"><?= formatTanggal($item['tanggal']) ?></p>
                    <h3 class="text-lg font-bold text-[#2F5233] mb-2"><?= htmlspecialchars($item['judul']) ?></h3>
                    <a href="<?= BASE_URL ?>berita-detail.php?id=<?= $item['id'] ?>" class="text-[#2F5233] font-semibold hover:text-[#4A7A4E] transition duration-300">
                        Baca Selengkapnya →
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>

<script src="assets/js/main.js"></script>
</body>
</html>