<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

// Pagination
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 9;
$offset = ($page - 1) * $limit;

// Get total berita
$stmt = $pdo->query("SELECT COUNT(*) as total FROM berita");
$total = $stmt->fetch()['total'];
$totalPages = ceil($total / $limit);

// Pastikan page tidak melebihi totalPages
if ($page > $totalPages && $totalPages > 0) {
    $page = $totalPages;
    $offset = ($page - 1) * $limit;
}

// Get berita - FIXED
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
    <link rel="icon" type="image/png" sizes="32x32" href="<?= BASE_URL ?>assets/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= BASE_URL ?>assets/images/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= BASE_URL ?>assets/images/apple-touch-icon.png">
    <link rel="shortcut icon" href="<?= BASE_URL ?>assets/images/favicon.ico">
    <meta name="theme-color" content="#2F5233">
    <title>Kabar Bismo - Gunung Bismo via Deroduwur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Outfit', sans-serif; }

        /* Hero Section - Sama seperti halaman lainnya */
        .hero-berita {
            height: 100vh;
            min-height: 600px;
            max-height: 1000px;
            background-size: cover;
            background-position: center;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-berita::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(47, 82, 51, 0.6);
            z-index: 1;
        }

        .hero-berita .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .hero-title {
            font-size: clamp(2.5rem, 6vw, 5rem);
            line-height: 1.1;
            margin-bottom: 0.5rem;
        }

        .hero-subtitle {
            font-size: clamp(1rem, 1.8vw, 1.5rem);
            line-height: 1.6;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-fade {
            opacity: 0;
            transform: translateY(30px);
            animation: heroFadeUp 0.9s ease forwards;
        }

        .hero-fade.delay-1 { animation-delay: 0.15s; }
        .hero-fade.delay-2 { animation-delay: 0.35s; }
        .hero-fade.delay-3 { animation-delay: 0.55s; }

        @keyframes heroFadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Card Styling - Konsisten */
        .card-berita {
            background: white;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .card-berita:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.12);
        }

        .card-berita .img-wrapper {
            overflow: hidden;
            position: relative;
        }

        .card-berita .img-wrapper img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .card-berita:hover .img-wrapper img {
            transform: scale(1.06);
        }

        .card-berita .card-body {
            padding: 14px 16px;
        }

        .card-berita .card-body .tanggal {
            font-size: 0.8rem;
            color: #A9784B;
            margin-bottom: 6px;
        }

        .card-berita .card-body h3 {
            font-size: 1rem;
            font-weight: 700;
            color: #2F5233;
            margin-bottom: 8px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .card-berita .card-body .deskripsi {
            font-size: 0.8rem;
            color: #5C5C50;
            line-height: 1.7;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 12px;
        }

        @media (min-width: 768px) {
            .card-berita .card-body h3 {
                font-size: 1rem;
            }

            .card-berita .card-body .deskripsi {
                font-size: 0.8rem;
            }
        }

        @media (max-width: 768px) {
            .card-berita .img-wrapper img {
                height: 160px;
            }

            .card-berita .card-body {
                padding: 12px 14px;
            }
        }

        @media (max-width: 480px) {
            .card-berita .img-wrapper img {
                height: 130px;
            }

            .card-berita .card-body {
                padding: 10px 12px;
            }

            .card-berita .card-body h3 {
                font-size: 0.85rem;
            }

            .card-berita .card-body .deskripsi {
                font-size: 0.7rem;
            }
        }

        .card-berita .card-body .btn-read {
            color: #E0BE45;
            font-weight: 700;
            font-size: 0.9rem;
            transition: color 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .card-berita .card-body .btn-read:hover {
            color: #A9784B;
        }

        .card-berita .card-body .btn-read::after {
            content: ' →';
            transition: transform 0.3s ease;
            display: inline-block;
        }

        .card-berita .card-body .btn-read:hover::after {
            transform: translateX(4px);
        }

        /* Pagination - SAMA SEPERTI alam.php */
        .pagination {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.75rem;
            margin-top: 2.5rem;
        }

        .pagination a,
        .pagination span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 44px;
            padding: 0.75rem 1rem;
            border-radius: 999px;
            border: 1px solid #E5E7EB;
            background: white;
            color: #374151;
            font-size: 0.95rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .pagination a:hover {
            background: #F8FAFC;
            border-color: #D1D5DB;
            transform: translateY(-1px);
        }

        .pagination .active-page {
            background: #2F5233;
            color: white;
            border-color: #2F5233;
        }

        .pagination .disabled {
            opacity: 0.5;
            cursor: default;
            pointer-events: none;
        }

        @media (max-width: 768px) {
            .hero-berita {
                height: 100vh;
                min-height: 450px;
            }
            .card-berita .img-wrapper img {
                height: 160px;
            }
            .card-berita .card-body {
                padding: 12px 14px;
            }
            .card-berita .card-body h3 {
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            .hero-berita {
                height: 100vh;
                min-height: 400px;
            }
            .hero-title {
                font-size: clamp(1.8rem, 7vw, 2.2rem);
            }
            .hero-subtitle {
                font-size: clamp(0.8rem, 2.5vw, 0.95rem);
                padding: 0 15px;
            }
            .card-berita .img-wrapper img {
                height: 150px;
            }
            .card-berita .card-body {
                padding: 14px 16px;
            }
        }
    </style>
</head>
<body>

<?php include __DIR__ . '/includes/navbar.php'; ?>

<!-- Hero Section - Style sama seperti halaman lainnya -->
<section class="hero-berita" style="background-image: url('<?= BASE_URL ?>assets/images/berita/hero_berita.jpg');">
    <div class="hero-content container mx-auto px-6 md:px-12 lg:px-24">
        <div class="max-w-7xl mx-auto">
            <h1 class="hero-title text-4xl md:text-5xl lg:text-6xl font-bold text-white hero-fade delay-1 mb-10">
                <span class="text-[#E0BE45]">Kabar</span> Bismo
            </h1>
            <p class="hero-subtitle text-lg md:text-lg lg:text-2xl text-white/90 mb-8 leading-relaxed hero-fade delay-2 max-w-4xl mx-auto">
                Tiap jejak peristiwa menjadi coretan histori yang patut diabadikan. Ikuti perkembangan kami dalam menghidupkan kolaborasi bersama di Gunung Bismo.
            </p>
        </div>
    </div>
</section>

<!-- Berita List -->
<section class="py-16 bg-[#FAF7F2]">
    <div class="container mx-auto px-4 max-w-6xl">
        <div class="text-center mb-10">
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-[#2F5233] mb-6">Kabar Rimbun Dari Balik Kabut</h2>
            <p class="text-base md:text-xl lg:text-lg text-[#5C5C50] mb-8 text-center leading-relaxed">Cerita Bismo Via Deroduwur yang terus berlanjut</p>
        </div>

        <?php if (empty($berita_list)): ?>
        <div class="bg-white rounded-2xl shadow-lg p-16 text-center">
            <div class="text-5xl mb-4">📰</div>
            <p class="text-lg text-[#5C5C50]">Belum ada berita</p>
            <p class="text-sm text-gray-400 mt-1">Silakan periksa kembali nanti</p>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-2 gap-3 md:gap-4 lg:grid-cols-3 lg:gap-6">
            <?php foreach ($berita_list as $berita): ?>
            <div class="card-berita">
                <div class="img-wrapper">
                    <?php if ($berita['foto']): ?>
                    <img src="<?= BASE_URL ?>uploads/berita/<?= htmlspecialchars($berita['foto']) ?>" 
                         alt="<?= htmlspecialchars($berita['judul']) ?>"
                         loading="lazy">
                    <?php else: ?>
                    <img src="<?= BASE_URL ?>assets/images/default-news.jpg" 
                         alt="Default Berita"
                         loading="lazy">
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <p class="tanggal"><?= formatTanggal($berita['tanggal']) ?></p>
                    <h3><?= htmlspecialchars($berita['judul']) ?></h3>
                    <p class="deskripsi"><?= truncateText(strip_tags($berita['isi']), 120) ?></p>
                    <a href="<?= BASE_URL ?>berita-detail.php?id=<?= $berita['id'] ?>" class="btn-read">
                        Baca Selengkapnya
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination - SAMA SEPERTI alam.php -->
        <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>#beritaGrid">Prev</a>
            <?php else: ?>
                <span class="disabled">Prev</span>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php if ($i === $page): ?>
                    <span class="active-page"><?= $i ?></span>
                <?php else: ?>
                    <a href="?page=<?= $i ?>#beritaGrid"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?>#beritaGrid">Next</a>
            <?php else: ?>
                <span class="disabled">Next</span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
<script src="assets/js/main.js"></script>
</body>
</html>