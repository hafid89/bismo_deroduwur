<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

// Pagination settings
$limit = 8;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

// Search query
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$is_searching = !empty($search);

// Ambil data
if ($is_searching) {
    // Ambil hasil flora & fauna, lalu tandai tipenya
    $flora_results = getFloraPaginated(1, 999, $search);
    $fauna_results = getFaunaPaginated(1, 999, $search);

    // Tambahkan penanda _tipe pada setiap item
    foreach ($flora_results as &$f) {
        $f['_tipe'] = 'flora';
    }
    foreach ($fauna_results as &$f) {
        $f['_tipe'] = 'fauna';
    }
    unset($f);

    $all_results = array_merge($flora_results, $fauna_results);

    $total_results = count($all_results);
    $pagination_total_pages = max(1, (int) ceil($total_results / $limit));
    $page = min($page, $pagination_total_pages);
    $offset = ($page - 1) * $limit;
    $search_results = array_slice($all_results, $offset, $limit);
} else {
    // Mode normal: pagination terpisah flora & fauna
    $total_flora = getTotalFlora();
    $total_fauna = getTotalFauna();
    $flora_total_pages = max(1, (int) ceil($total_flora / $limit));
    $fauna_total_pages = max(1, (int) ceil($total_fauna / $limit));
    $pagination_total_pages = max($flora_total_pages, $fauna_total_pages);
    $page = min($page, $pagination_total_pages);
    $flora_page = min($page, $flora_total_pages);
    $fauna_page = min($page, $fauna_total_pages);
    $flora_list = getFloraPaginated($flora_page, $limit);
    $fauna_list = getFaunaPaginated($fauna_page, $limit);
}
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
    <title>Alam Bismo - Gunung Bismo via Deroduwur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

        * {
            font-family: 'Outfit', sans-serif;
        }

        /* Hero Section */
        .hero-alam {
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

        .hero-alam::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(47, 82, 51, 0.6);
            z-index: 1;
        }

        .hero-alam .hero-content {
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

        .hero-fade.delay-1 {
            animation-delay: 0.15s;
        }

        .hero-fade.delay-2 {
            animation-delay: 0.35s;
        }

        .hero-fade.delay-3 {
            animation-delay: 0.55s;
        }

        @keyframes heroFadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Search Section */
        .search-section {
            background: #FAF7F2;
            padding: 24px 0;
        }

        .search-wrapper {
            max-width: 600px;
            margin: 0 auto;
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 14px 50px 14px 20px;
            border-radius: 999px;
            border: 2px solid #e5e7eb;
            background: white;
            font-size: 1rem;
            color: #2F5233;
            outline: none;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .search-input::placeholder {
            color: #94a3b8;
        }

        .search-input:focus {
            border-color: #E0BE45;
            box-shadow: 0 0 0 4px rgba(219, 209, 76, 0.23);
        }

        .search-btn {
            position: absolute;
            right: 6px;
            top: 50%;
            transform: translateY(-50%);
            background: #2F5233;
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .search-btn:hover {
            background: #4A7A4E;
            transform: translateY(-50%) scale(1.05);
        }

        .search-clear {
            position: absolute;
            right: 56px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            color: #94a3b8;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: color 0.3s ease, background 0.3s ease;
            font-size: 18px;
            text-decoration: none;
        }

        .search-clear:hover {
            color: #2F5233;
            background: #f1f5f9;
        }

        .search-info {
            text-align: center;
            margin-top: 12px;
            font-size: 0.9rem;
            color: #5C5C50;
        }

        .search-info strong {
            color: #2F5233;
        }

        .search-info .reset-link {
            color: #E0BE45;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .search-info .reset-link:hover {
            color: #A9784B;
        }

        /* Empty State */
        .empty-state {
            background: white;
            border-radius: 1rem;
            padding: 60px 20px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .empty-state .icon {
            font-size: 4rem;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .empty-state h4 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #2F5233;
            margin-bottom: 8px;
        }

        .empty-state p {
            color: #5C5C50;
            font-size: 0.95rem;
            margin-bottom: 4px;
        }

        .empty-state .btn-reset {
            display: inline-block;
            margin-top: 16px;
            background: #2F5233;
            color: white;
            padding: 10px 24px;
            border-radius: 999px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .empty-state .btn-reset:hover {
            background: #4A7A4E;
            transform: translateY(-2px);
        }

        /* Card Styling */
        .card-alam {
            background: white;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-alam:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        }

        .card-alam .img-wrapper {
            overflow: hidden;
            position: relative;
        }

        .card-alam .img-wrapper img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .card-alam:hover .img-wrapper img {
            transform: scale(1.06);
        }

        .card-alam .badge-category {
            position: absolute;
            top: 12px;
            right: 12px;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(4px);
            color: white;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .card-alam .badge-category.flora {
            background: rgba(47, 82, 51, 0.85);
        }

        .card-alam .badge-category.fauna {
            background: rgba(192, 111, 42, 0.85);
        }

        .card-alam .card-body {
            padding: 14px 16px;
        }

        .card-alam .card-body h4 {
            font-size: 1rem;
            font-weight: 700;
            color: #2F5233;
            margin-bottom: 2px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .card-alam .card-body .nama-ilmiah {
            font-size: 0.75rem;
            color: #A9784B;
            font-style: italic;
            margin-bottom: 6px;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .card-alam .card-body .deskripsi {
            font-size: 0.8rem;
            color: #5C5C50;
            line-height: 1.6;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .card-alam .card-body .lokasi {
            font-size: 0.7rem;
            color: #A9784B;
            margin-top: 8px;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .btn-read-more {
            display: inline-block;
            margin-top: 10px;
            color: #E0BE45;
            font-weight: 600;
            font-size: 0.8rem;
            cursor: pointer;
            transition: color 0.3s ease;
            background: none;
            border: none;
            padding: 0;
        }

        .btn-read-more:hover {
            color: #A9784B;
        }

        .btn-read-more::after {
            content: ' →';
            transition: transform 0.3s ease;
            display: inline-block;
        }

        .btn-read-more:hover::after {
            transform: translateX(4px);
        }

        /* Pagination */
        .pagination {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.75rem;
            margin-top: 2rem;
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

        /* Lightbox */
        .lightbox-modal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 99999;
            background: rgba(0, 0, 0, 0.92);
            justify-content: center;
            align-items: center;
            padding: 20px;
            cursor: pointer;
        }

        .lightbox-modal.active {
            display: flex;
        }

        .lightbox-modal img {
            max-width: 95%;
            max-height: 95%;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        }

        .lightbox-close {
            position: absolute;
            top: 30px;
            right: 40px;
            color: white;
            font-size: 44px;
            background: none;
            border: none;
            cursor: pointer;
            transition: transform 0.3s ease;
            z-index: 100000;
        }

        .lightbox-close:hover {
            transform: scale(1.2);
        }

        /* Section styling */
        .section-alam {
            padding: 20px 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-alam {
                height: 100vh;
                min-height: 450px;
            }

            .card-alam .img-wrapper img {
                height: 160px;
            }

            .card-alam .card-body {
                padding: 12px 14px;
            }

            .card-alam .card-body h4 {
                font-size: 0.95rem;
            }

            .card-alam .card-body .deskripsi {
                font-size: 0.75rem;
                -webkit-line-clamp: 2;
            }

            .section-alam {
                padding: 20px 0;
            }

            .search-section {
                padding: 20px 0;
            }

            .search-input {
                padding: 12px 46px 12px 18px;
                font-size: 0.95rem;
            }

            .search-btn {
                width: 36px;
                height: 36px;
                right: 5px;
            }

            .search-clear {
                right: 50px;
                width: 28px;
                height: 28px;
                font-size: 16px;
            }
        }

        @media (max-width: 480px) {
            .hero-alam {
                height: 100vh;
                min-height: 400px;
            }

            .hero-title {
                font-size: clamp(2rem, 8vw, 2.5rem);
            }

            .hero-subtitle {
                font-size: clamp(0.85rem, 3vw, 1rem);
                padding: 0 15px;
            }

            .card-alam .img-wrapper img {
                height: 130px;
            }

            .card-alam .card-body {
                padding: 10px 12px;
            }

            .card-alam .card-body h4 {
                font-size: 0.85rem;
            }

            .card-alam .card-body .nama-ilmiah {
                font-size: 0.7rem;
            }

            .card-alam .card-body .deskripsi {
                font-size: 0.7rem;
                -webkit-line-clamp: 2;
            }

            .card-alam .card-body .lokasi {
                font-size: 0.65rem;
            }

            .btn-read-more {
                font-size: 0.75rem;
            }

            .lightbox-close {
                top: 15px;
                right: 20px;
                font-size: 30px;
            }

            .search-input {
                padding: 10px 42px 10px 16px;
                font-size: 0.85rem;
            }

            .search-btn {
                width: 32px;
                height: 32px;
                right: 4px;
            }

            .search-btn svg {
                width: 16px;
                height: 16px;
            }

            .search-clear {
                right: 44px;
                width: 24px;
                height: 24px;
                font-size: 14px;
            }

            .empty-state {
                padding: 40px 16px;
            }

            .empty-state .icon {
                font-size: 3rem;
            }

            .empty-state h4 {
                font-size: 1.1rem;
            }
        }
    </style>
</head>

<body>

    <?php include __DIR__ . '/includes/navbar.php'; ?>

    <!-- Hero Section -->
    <section class="hero-alam" style="background-image: url('<?= BASE_URL ?>assets/images/alam/hero-alam.png');">
        <div class="hero-content container mx-auto px-6 md:px-12 lg:px-24">
            <div class="max-w-7xl mx-auto">
                <h1 class="hero-title text-5xl md:text-5xl lg:text-6xl font-bold text-white hero-fade delay-1 mb-10">
                    <span class="text-[#E0BE45]">Alam</span> Bismo
                </h1>
                <p class="hero-subtitle text-lg md:text-lg lg:text-2xl text-white/90 mb-8 leading-relaxed hero-fade delay-2 max-w-4xl mx-auto">
                    Menyusuri rumah bagi flora dan fauna Bismo yang elok, dimana setiap pijakan langkah menyimpan keajaiban yang menanti untuk disapa.
                </p>
            </div>
        </div>
    </section>

    <!-- Intro Ekosistem -->
    <section class="pt-10 bg-[#FAF7F2]">
        <div class="container mx-auto px-4 max-w-4xl">
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-[#2F5233] text-center mb-6">Ekosistem Istimewa Deroduwur</h2>
            <p class="text-base md:text-xl lg:text-lg text-[#5C5C50] mb-8 text-center leading-relaxed">
                Perjalanan di jalur ini memberikan kesempatan bagi kita untuk menyaksikan keindahan alam yang masih terjaga. 
                Keistimewaan ekosistem Bismo, mulai dari flora endemik hingga fauna langka, menjadi panggilan bagi para pendaki untuk melihat keajaiban yang berlangsung di balik hutan rimba.
            </p>
        </div>
    </section>

    <!-- Search Section -->
    <section class="search-section">
        <div class="container mx-auto px-4">
            <div class="search-wrapper">
                <form method="GET" action="" id="searchForm">
                    <input
                        type="text"
                        name="search"
                        id="searchInput"
                        class="search-input"
                        placeholder="Cari flora atau fauna..."
                        value="<?= htmlspecialchars($search) ?>"
                        autocomplete="off">
                    <?php if ($is_searching): ?>
                        <a href="<?= BASE_URL ?>alam.php" class="search-clear" title="Hapus pencarian">✕</a>
                    <?php endif; ?>
                    <button type="submit" class="search-btn" title="Cari">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </form>
            </div>
            <?php if ($is_searching): ?>
                <div class="search-info">
                    Menampilkan hasil untuk: <strong>"<?= htmlspecialchars($search) ?>"</strong>
                    (<?= $total_results ?> hasil ditemukan)
                    <br>
                    <a href="<?= BASE_URL ?>alam.php" class="reset-link">Tampilkan semua</a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php if ($is_searching): ?>

        <!-- ===================== HASIL PENCARIAN (GABUNGAN) ===================== -->
        <section class="section-alam bg-[#FAF7F2]">
            <div class="container mx-auto px-4 max-w-6xl">
                <div class="text-center mb-10">
                    <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-[#2F5233] text-center mb-6">Hasil Pencarian</h2>
                    <p class="text-base md:text-xl lg:text-lg text-[#5C5C50] mb-8 text-center leading-relaxed">
                        Menampilkan flora &amp; fauna yang cocok dengan kata kunci pencarian Anda.
                    </p>
                </div>

                <?php if (empty($search_results)): ?>
                    <!-- Satu empty state saja untuk kedua kategori -->
                    <div class="empty-state">
                        <div class="icon">🔍</div>
                        <h4>Tidak Ada Hasil Ditemukan</h4>
                        <p>Maaf, tidak ada flora maupun fauna yang cocok dengan pencarian "<strong><?= htmlspecialchars($search) ?></strong>".</p>
                        <p>Coba gunakan kata kunci lain atau tampilkan semua data.</p>
                        <a href="<?= BASE_URL ?>alam.php" class="btn-reset">Tampilkan Semua</a>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
                        <?php foreach ($search_results as $item): ?>
                            <?php
                            $tipe = $item['_tipe']; // sudah pasti terisi dari tahap penggabungan
                            $badgeClass = $tipe === 'flora' ? 'flora' : 'fauna';
                            $badgeLabel = $tipe === 'flora' ? 'Flora' : 'Fauna';
                            $detailUrl  = BASE_URL . 'alam-detail.php?type=' . $tipe . '&id=' . $item['id'];
                            ?>
                            <div class="card-alam">
                                <div class="img-wrapper">
                                    <?php if (!empty($item['foto']) && file_exists(UPLOAD_PATH . $tipe . '/' . $item['foto'])): ?>
                                        <img src="<?= BASE_URL ?>uploads/<?= $tipe ?>/<?= htmlspecialchars($item['foto']) ?>"
                                            alt="<?= htmlspecialchars($item['nama']) ?>"
                                            loading="lazy">
                                    <?php else: ?>
                                        <img src="<?= BASE_URL ?>assets/images/default-<?= $tipe ?>.jpg"
                                            alt="Default <?= ucfirst($tipe) ?>"
                                            loading="lazy">
                                    <?php endif; ?>
                                    <span class="badge-category <?= $badgeClass ?>"><?= $badgeLabel ?></span>
                                </div>
                                <div class="card-body">
                                    <h4><?= htmlspecialchars($item['nama']) ?></h4>
                                    <?php if (!empty($item['nama_ilmiah'])): ?>
                                        <p class="nama-ilmiah"><?= htmlspecialchars($item['nama_ilmiah']) ?></p>
                                    <?php endif; ?>
                                    <p class="deskripsi"><?= htmlspecialchars($item['deskripsi'] ?? 'Deskripsi belum tersedia.') ?></p>
                                    <?php if (!empty($item['lokasi'])): ?>
                                        <p class="lokasi">Lokasi: <?= htmlspecialchars($item['lokasi']) ?></p>
                                    <?php endif; ?>
                                    <a href="<?= $detailUrl ?>" class="btn-read-more">
                                        Baca Selengkapnya
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination untuk hasil pencarian -->
                    <?php if ($pagination_total_pages > 1): ?>
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>#pagination">Prev</a>
                            <?php else: ?>
                                <span class="disabled">Prev</span>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $pagination_total_pages; $i++): ?>
                                <?php if ($i === $page): ?>
                                    <span class="active-page"><?= $i ?></span>
                                <?php else: ?>
                                    <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>#pagination"><?= $i ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <?php if ($page < $pagination_total_pages): ?>
                                <a href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>#pagination">Next</a>
                            <?php else: ?>
                                <span class="disabled">Next</span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </section>

    <?php else: ?>

        <!-- ===================== TAMPILAN NORMAL (FLORA & FAUNA TERPISAH) ===================== -->

        <!-- Flora Section -->
        <section id="flora" class="section-alam bg-[#FAF7F2]">
            <div class="container mx-auto px-4 max-w-6xl">
                <div class="text-center mb-10">
                    <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-[#2F5233] text-center mb-6">Puspa Anggun Bismo</h2>
                    <p class="text-base md:text-xl lg:text-lg text-[#5C5C50] mb-8 text-center leading-relaxed">Menyapa cantiknya tumbuhan langka yang mekar anggun di sepanjang perjalanan.</p>
                </div>

                <?php if (empty($flora_list)): ?>
                    <div class="empty-state">
                        <div class="icon">🌿</div>
                        <h4>Belum Ada Data Flora</h4>
                        <p>Data flora belum tersedia saat ini.</p>
                        <p>Silakan tambahkan melalui admin panel.</p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
                        <?php foreach ($flora_list as $flora): ?>
                            <div class="card-alam">
                                <div class="img-wrapper">
                                    <?php if (!empty($flora['foto']) && file_exists(UPLOAD_PATH . 'flora/' . $flora['foto'])): ?>
                                        <img src="<?= BASE_URL ?>uploads/flora/<?= htmlspecialchars($flora['foto']) ?>"
                                            alt="<?= htmlspecialchars($flora['nama']) ?>"
                                            loading="lazy">
                                    <?php else: ?>
                                        <img src="<?= BASE_URL ?>assets/images/default-flora.jpg"
                                            alt="Default Flora"
                                            loading="lazy">
                                    <?php endif; ?>
                                    <span class="badge-category flora">Flora</span>
                                </div>
                                <div class="card-body">
                                    <h4><?= htmlspecialchars($flora['nama']) ?></h4>
                                    <?php if (!empty($flora['nama_ilmiah'])): ?>
                                        <p class="nama-ilmiah"><?= htmlspecialchars($flora['nama_ilmiah']) ?></p>
                                    <?php endif; ?>
                                    <p class="deskripsi"><?= htmlspecialchars($flora['deskripsi'] ?? 'Keindahan flora di jalur Gunung Bismo.') ?></p>
                                    <?php if (!empty($flora['lokasi'])): ?>
                                        <p class="lokasi">Lokasi: <?= htmlspecialchars($flora['lokasi']) ?></p>
                                    <?php endif; ?>
                                    <a href="<?= BASE_URL ?>alam-detail.php?type=flora&id=<?= $flora['id'] ?>" class="btn-read-more">
                                        Baca Selengkapnya
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Fauna Section -->
        <section id="fauna" class="section-alam bg-[#FAF7F2]">
            <div class="container mx-auto px-4 max-w-6xl">
                <div class="text-center mb-10">
                    <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-[#2F5233] text-center mb-6">Satwa Penjaga Bismo</h2>
                    <p class="text-base md:text-xl lg:text-lg text-[#5C5C50] mb-8 text-center leading-relaxed">Menyusuri jejak para penghuni alam yang merawat harmoni dalam keheningan hutan.</p>
                </div>

                <?php if (empty($fauna_list)): ?>
                    <div class="empty-state">
                        <div class="icon">🐾</div>
                        <h4>Belum Ada Data Fauna</h4>
                        <p>Data fauna belum tersedia saat ini.</p>
                        <p>Silakan tambahkan melalui admin panel.</p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
                        <?php foreach ($fauna_list as $fauna): ?>
                            <div class="card-alam">
                                <div class="img-wrapper">
                                    <?php if (!empty($fauna['foto']) && file_exists(UPLOAD_PATH . 'fauna/' . $fauna['foto'])): ?>
                                        <img src="<?= BASE_URL ?>uploads/fauna/<?= htmlspecialchars($fauna['foto']) ?>"
                                            alt="<?= htmlspecialchars($fauna['nama']) ?>"
                                            loading="lazy">
                                    <?php else: ?>
                                        <img src="<?= BASE_URL ?>assets/images/default-fauna.jpg"
                                            alt="Default Fauna"
                                            loading="lazy">
                                    <?php endif; ?>
                                    <span class="badge-category fauna">Fauna</span>
                                </div>
                                <div class="card-body">
                                    <h4><?= htmlspecialchars($fauna['nama']) ?></h4>
                                    <?php if (!empty($fauna['nama_ilmiah'])): ?>
                                        <p class="nama-ilmiah"><?= htmlspecialchars($fauna['nama_ilmiah']) ?></p>
                                    <?php endif; ?>
                                    <p class="deskripsi"><?= htmlspecialchars($fauna['deskripsi'] ?? 'Satwa liar di ekosistem Gunung Bismo.') ?></p>
                                    <?php if (!empty($fauna['lokasi'])): ?>
                                        <p class="lokasi">Lokasi: <?= htmlspecialchars($fauna['lokasi']) ?></p>
                                    <?php endif; ?>
                                    <a href="<?= BASE_URL ?>alam-detail.php?type=fauna&id=<?= $fauna['id'] ?>" class="btn-read-more">
                                        Baca Selengkapnya
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Pagination normal -->
        <?php if ($pagination_total_pages > 1 && (!empty($flora_list) || !empty($fauna_list))): ?>
            <section id="pagination" class="section-alam bg-[#FAF7F2]">
                <div class="container mx-auto px-4 max-w-6xl">
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?= $page - 1 ?>#pagination">Prev</a>
                        <?php else: ?>
                            <span class="disabled">Prev</span>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $pagination_total_pages; $i++): ?>
                            <?php if ($i === $page): ?>
                                <span class="active-page"><?= $i ?></span>
                            <?php else: ?>
                                <a href="?page=<?= $i ?>#pagination"><?= $i ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <?php if ($page < $pagination_total_pages): ?>
                            <a href="?page=<?= $page + 1 ?>#pagination">Next</a>
                        <?php else: ?>
                            <span class="disabled">Next</span>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

    <?php endif; ?>

    <!-- Pesan Kelestarian Alam -->
    <section class="py-15 bg-[#2F5233] text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-64 h-64 bg-[#E0BE45] rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-[#4A7A4E] rounded-full blur-3xl translate-x-1/3 translate-y-1/3"></div>
        </div>

        <div class="container mx-auto px-4 max-w-4xl text-center relative z-10 mt-8">
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold mb-6">Jaga Kelestarian Alam</h2>

            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 md:p-12 border border-white/10 mb-6">
                <blockquote class="text-xl md:text-2xl font-medium text-[#E0BE45] mb-4">
                    "Bawa Turun Kembali Sampah Anda"
                </blockquote>
                <p class="text-white/90 text-base md:text-lg leading-relaxed max-w-2xl mx-auto">
                    Gunung Bismo bukan hanya tujuan pendakian, tetapi juga rumah bagi beragam flora dan fauna
                    yang membutuhkan perlindungan kita. Setiap langkah yang kita ambil adalah tanggung jawab
                    untuk menjaga keindahan alam ini tetap lestari.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                    <div class="text-3xl mb-2">♻️</div>
                    <p class="text-sm text-white/80">Bawa turun semua sampah pribadi</p>
                </div>
                <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                    <div class="text-3xl mb-2">🌱</div>
                    <p class="text-sm text-white/80">Jangan merusak flora dan fauna</p>
                </div>
                <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                    <div class="text-3xl mb-2">🤝</div>
                    <p class="text-sm text-white/80">Hormati alam dan sesama pendaki</p>
                </div>
            </div>

            <p class="text-white/70 text-sm mt-8 max-w-2xl mx-auto">
                Mari kita bersama-sama menjadi pelindung alam. Karena keindahan Gunung Bismo adalah warisan
                yang harus kita jaga untuk generasi mendatang.
            </p>
        </div>
    </section>

    <!-- Lightbox Modal -->
    <div id="lightboxModal" class="lightbox-modal" onclick="closeLightbox()">
        <button class="lightbox-close" onclick="closeLightbox()">&times;</button>
        <img id="lightboxImage" src="" alt="Full Size Image">
    </div>

    <?php include __DIR__ . '/includes/footer.php'; ?>
    <script src="assets/js/main.js"></script>

    <script>
        function openLightbox(imageSrc) {
            const modal = document.getElementById('lightboxModal');
            const img = document.getElementById('lightboxImage');
            if (modal && img) {
                img.src = imageSrc;
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeLightbox() {
            const modal = document.getElementById('lightboxModal');
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLightbox();
            }
        });
    </script>

</body>

</html>