<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

// Pagination settings
$limit = 9;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// Filter
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

// Get total data with filter
if ($filter !== 'all') {
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM galeri WHERE kategori = ?");
    $stmt->execute([$filter]);
} else {
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM galeri");
}
$total = $stmt->fetch()['total'];
$totalPages = ceil($total / $limit);

if ($page > $totalPages && $totalPages > 0) {
    $page = $totalPages;
    $offset = ($page - 1) * $limit;
}

$limit = intval($limit);
$offset = intval($offset);

if ($filter !== 'all') {
    $sql = "SELECT * FROM galeri WHERE kategori = ? ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$filter]);
} else {
    $sql = "SELECT * FROM galeri ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
    $stmt = $pdo->query($sql);
}
$galeri = $stmt->fetchAll();

/* =========================================================
   AMBIL FOTO TAMBAHAN (SLIDE) UNTUK GALERI DI HALAMAN INI
   ========================================================= */
$galeriIds = array_column($galeri, 'id');
$extraPhotos = []; // [galeri_id => [ ['foto'=>..,'urutan'=>..], ... ]]

if (!empty($galeriIds)) {
    try {
        $placeholders = implode(',', array_fill(0, count($galeriIds), '?'));
        $stmt = $pdo->prepare("SELECT galeri_id, foto, urutan FROM galeri_foto WHERE galeri_id IN ($placeholders) ORDER BY galeri_id, urutan, id");
        $stmt->execute($galeriIds);
        foreach ($stmt->fetchAll() as $row) {
            $extraPhotos[$row['galeri_id']][] = $row['foto'];
        }
    } catch (PDOException $e) {
        // Tabel galeri_foto belum dibuat, lewati
    }
}

/* =========================================================
   BANGUN DATA SLIDES PER GALERI (untuk JS modal)
   ========================================================= */
$galleryData = [];
foreach ($galeri as $foto) {
    // Kumpulkan semua foto: sampul + tambahan
    $semuaFoto = [];
    if (!empty($foto['foto'])) {
        $semuaFoto[] = $foto['foto'];
    }
    if (!empty($extraPhotos[$foto['id']])) {
        $semuaFoto = array_merge($semuaFoto, $extraPhotos[$foto['id']]);
    }

    // Bangun array slides dengan URL lengkap
    $slides = array_map(function($file) {
        return BASE_URL . 'uploads/galeri/' . $file;
    }, $semuaFoto);

    $galleryData[] = [
        'id' => (int)$foto['id'],
        'title' => $foto['judul'] ?: 'Tanpa Judul',
        'category' => ucfirst($foto['kategori'] ?: 'Umum'),
        'description' => $foto['deskripsi'] ?: 'Deskripsi belum tersedia.',
        'date' => isset($foto['created_at']) ? formatTanggal($foto['created_at']) : '',
        'cover' => !empty($foto['foto']) ? BASE_URL . 'uploads/galeri/' . $foto['foto'] : '',
        'slides' => $slides, // Array URL semua foto (sampul + tambahan)
        'totalPhoto' => count($slides),
    ];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= BASE_URL ?>assets/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= BASE_URL ?>assets/images/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= BASE_URL ?>assets/images/apple-touch-icon.png">
    <link rel="shortcut icon" href="<?= BASE_URL ?>assets/images/favicon.ico">
    <meta name="theme-color" content="#2F5233">
    <title>Jejak Visual - Gunung Bismo via Deroduwur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Outfit', sans-serif; }

        .hero-galeri {
            height: 100vh;
            min-height: 800px;
            max-height: 1000px;
            background-size: cover;
            background-position: center;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .hero-galeri::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(47, 82, 51, 0.6);
            z-index: 1;
        }
        .hero-galeri .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }
        .hero-title {
            font-size: clamp(2.5rem, 6vw, 5rem);
            line-height: 1.1;
            margin-bottom: 0.75rem;
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

        .gallery-item {
            background: white;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }
        .gallery-item:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.12);
        }
        .gallery-item .img-wrapper {
            overflow: hidden;
            position: relative;
        }
        .gallery-item .img-wrapper img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .gallery-item:hover .img-wrapper img {
            transform: scale(1.06);
        }

        /* Badge jumlah foto */
        .photo-count-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0,0,0,0.65);
            backdrop-filter: blur(4px);
            color: #fff;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            z-index: 2;
        }

        .gallery-item .card-body {
            padding: 14px 16px;
        }
        .gallery-item .card-body h4 {
            font-size: 1rem;
            font-weight: 700;
            color: #2F5233;
            margin-bottom: 2px;
        }
        .gallery-item .card-body .kategori {
            font-size: 0.75rem;
            display: inline-block;
            padding: 2px 12px;
            border-radius: 20px;
            font-weight: 600;
        }
        .kategori-jalur { color: #2F5233; background: #E8F5E9; }
        .kategori-ekosistem { color: #1565C0; background: #E3F2FD; }
        .kategori-kegiatan { color: #E65100; background: #FFF3E0; }

        .gallery-item .card-body .deskripsi {
            font-size: 0.8rem;
            color: #5C5C50;
            line-height: 1.6;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-top: 4px;
        }
        @media (min-width: 768px) {
            .gallery-item .card-body h4 { font-size: 1rem; }
            .gallery-item .card-body .deskripsi { font-size: 0.8rem; }
        }
        @media (max-width: 768px) {
            .gallery-item .img-wrapper img { height: 160px; }
            .gallery-item .card-body { padding: 12px 14px; }
        }
        @media (max-width: 480px) {
            .gallery-item .img-wrapper img { height: 130px; }
            .gallery-item .card-body { padding: 10px 12px; }
            .gallery-item .card-body h4 { font-size: 0.85rem; }
            .gallery-item .card-body .deskripsi { font-size: 0.7rem; }
        }

        .filter-btn {
            transition: all 0.3s ease;
            padding: 8px 24px;
            border-radius: 999px;
            font-weight: 500;
            font-size: 0.9rem;
            background: #e5e7eb;
            color: #5C5C50;
            border: 2px solid transparent;
            cursor: pointer;
        }
        .filter-btn:hover:not(.active) {
            background: #d1d5db;
            transform: translateY(-2px);
        }
        .filter-btn.active {
            background: #2F5233;
            color: white;
            border-color: #2F5233;
            box-shadow: 0 4px 15px rgba(47, 82, 51, 0.3);
        }

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

        /* Modal */
        .gallery-modal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(8px);
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .gallery-modal.active { display: flex; }
        .gallery-modal .modal-box {
            background: white;
            border-radius: 20px;
            max-width: 800px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            animation: modalIn 0.3s ease;
        }
        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.95) translateY(20px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        .gallery-modal .modal-img-wrapper {
            position: relative;
            overflow: hidden;
        }
        .gallery-modal .modal-close {
            position: absolute;
            top: 12px;
            right: 12px;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(4px);
            border: none;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            font-size: 28px;
            cursor: pointer;
            transition: background 0.3s ease;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .gallery-modal .modal-close:hover {
            background: rgba(0,0,0,0.8);
            transform: rotate(90deg);
        }
        .gallery-modal .modal-img {
            width: 100%;
            max-height: 500px;
            object-fit: cover;
            cursor: pointer;
            display: block;
        }
        .gallery-modal .modal-img-hint {
            position: absolute;
            bottom: 8px;
            right: 12px;
            font-size: 0.7rem;
            color: rgba(255,255,255,0.85);
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(4px);
            padding: 4px 10px;
            border-radius: 6px;
            pointer-events: none;
        }
        .gallery-modal .modal-body {
            padding: 24px 30px 30px;
        }
        .gallery-modal .modal-body h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2F5233;
            margin-bottom: 4px;
        }
        .gallery-modal .modal-body .modal-kategori {
            display: inline-block;
            padding: 4px 16px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            background: #FAF7F2;
            color: #A9784B;
            margin-bottom: 12px;
        }
        .gallery-modal .modal-body .modal-deskripsi {
            color: #5C5C50;
            line-height: 1.8;
            font-size: 0.95rem;
        }
        .gallery-modal .modal-body .modal-tanggal {
            color: #A9784B;
            font-size: 0.8rem;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #f1f5f9;
        }

        /* Thumbnail navigator di modal */
        .modal-thumbs {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding: 12px 30px 0;
            background: #FAF7F2;
            border-bottom: 1px solid #f1f5f9;
        }
        .modal-thumbs::-webkit-scrollbar { height: 5px; }
        .modal-thumbs::-webkit-scrollbar-thumb { background: #d5cdc4; border-radius: 4px; }
        .modal-thumb {
            flex-shrink: 0;
            width: 60px;
            height: 60px;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.25s ease;
            opacity: 0.6;
        }
        .modal-thumb:hover { opacity: 1; transform: translateY(-2px); }
        .modal-thumb.active {
            border-color: #2F5233;
            opacity: 1;
            box-shadow: 0 4px 12px rgba(47, 82, 51, 0.3);
        }
        .modal-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Arrow nav */
        .modal-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            background: rgba(0,0,0,0.55);
            backdrop-filter: blur(4px);
            border: none;
            color: white;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            font-size: 26px;
            line-height: 1;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.25s ease, transform 0.25s ease;
        }
        .modal-nav:hover {
            background: rgba(0,0,0,0.8);
            transform: translateY(-50%) scale(1.08);
        }
        .modal-nav.prev { left: 12px; }
        .modal-nav.next { right: 12px; }

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
        .lightbox-modal.active { display: flex; }
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
        .lightbox-close:hover { transform: scale(1.2); }

        .modal-box::-webkit-scrollbar { width: 6px; }
        .modal-box::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
        .modal-box::-webkit-scrollbar-thumb { background: #2F5233; border-radius: 4px; }

        @media (max-width: 768px) {
            .hero-galeri { height: 100vh; min-height: 450px; }
            .gallery-item .img-wrapper img { height: 200px; }
            .gallery-modal .modal-box { max-width: 95%; margin: 10px; }
            .gallery-modal .modal-img { max-height: 300px; }
            .gallery-modal .modal-body { padding: 16px 18px 20px; }
            .filter-btn { padding: 6px 16px; font-size: 0.8rem; }
            .modal-thumbs { padding: 10px 16px 0; }
            .modal-thumb { width: 50px; height: 50px; }
        }
        @media (max-width: 480px) {
            .hero-galeri { height: 100vh; min-height: 400px; }
            .hero-title { font-size: clamp(2rem, 8vw, 2.5rem); }
            .hero-subtitle { font-size: clamp(0.8rem, 2.5vw, 0.95rem); padding: 0 15px; }
            .gallery-item .img-wrapper img { height: 160px; }
            .gallery-modal .modal-box { max-width: 98%; margin: 5px; }
            .gallery-modal .modal-img { max-height: 200px; }
            .gallery-modal .modal-body { padding: 12px 14px 16px; }
            .gallery-modal .modal-body h3 { font-size: 1.2rem; }
            .lightbox-close { top: 15px; right: 20px; font-size: 30px; }
            .modal-nav { width: 36px; height: 36px; font-size: 22px; }
        }
    </style>
</head>
<body>

<?php include __DIR__ . '/includes/navbar.php'; ?>

<!-- Hero Section -->
<section class="hero-galeri" style="background-image: url('<?= BASE_URL ?>assets/images/galeri/hero-galeri.jpeg');">
    <div class="hero-content container mx-auto px-6 md:px-12 lg:px-24">
        <div class="max-w-7xl mx-auto">
             <h1 class="hero-title text-4xl md:text-5xl lg:text-6xl font-bold text-white hero-fade delay-1 mb-10">
                Jejak <span class="text-[#E0BE45]">Visual</span>
            </h1>
            <p class="hero-subtitle text-lg md:text-lg lg:text-2xl text-white/90 mb-8 leading-relaxed hero-fade delay-2 max-w-4xl mx-auto">
                Merekam syahdunya Gunung Bismo bersama hangatnya kebersamaan Basecamp Deroduwur. Sebuah perjalanan yang bernapas dalam setiap jepretan kamera.
            </p>
        </div>
    </div>
</section>

<!-- Filter Section -->
<section class="py-8 bg-[#FAF7F2] border-b border-gray-200">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap justify-center gap-2 md:gap-3">
            <button class="filter-btn <?= $filter === 'all' ? 'active' : '' ?>" data-filter="all">Semua</button>
            <button class="filter-btn <?= $filter === 'jalur' ? 'active' : '' ?>" data-filter="jalur">Track Pendakian</button>
            <button class="filter-btn <?= $filter === 'ekosistem' ? 'active' : '' ?>" data-filter="ekosistem">Ragam Hayati</button>
            <button class="filter-btn <?= $filter === 'kegiatan' ? 'active' : '' ?>" data-filter="kegiatan"> Potret Kebersamaan</button>
        </div>
    </div>
</section>

<!-- Gallery Grid -->
<section class="py-16 bg-[#FAF7F2]">
    <div class="container mx-auto px-4 max-w-6xl">
        <?php if (empty($galeri)): ?>
        <div class="bg-white rounded-2xl shadow-lg p-16 text-center">
            <div class="text-5xl mb-4">📷</div>
            <p class="text-lg text-[#5C5C50]">Belum ada foto di kategori ini</p>
            <p class="text-sm text-gray-400 mt-1">Silakan periksa kategori lain atau tambahkan foto</p>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-2 gap-3 md:gap-4 lg:grid-cols-3 lg:gap-6" id="galleryGrid">
            <?php foreach ($galeri as $pageIndex => $foto):
                $kat = htmlspecialchars($foto['kategori']);
                $katClass = 'kategori-' . $kat;
                $jumlahFoto = 1 + count($extraPhotos[$foto['id']] ?? []);
            ?>
            <div class="gallery-item" data-category="<?= $kat ?>" onclick="openSlide(<?= $pageIndex ?>)">
                <div class="img-wrapper">
                    <img src="<?= BASE_URL ?>uploads/galeri/<?= htmlspecialchars($foto['foto']) ?>" 
                         alt="<?= htmlspecialchars($foto['judul']) ?>" 
                         loading="lazy">
                    <?php if ($jumlahFoto > 1): ?>
                    <span class="photo-count-badge">
                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                        </svg>
                        <?= $jumlahFoto ?> Foto
                    </span>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <h4><?= htmlspecialchars($foto['judul']) ?></h4>
                    <span class="kategori <?= $katClass ?>"><?= ucfirst($kat) ?></span>
                    <?php if ($foto['deskripsi']): ?>
                    <p class="deskripsi"><?= htmlspecialchars($foto['deskripsi']) ?></p>
                    <?php endif; ?>
                    <p class="text-xs text-gray-400 mt-2">Klik untuk detail</p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>&filter=<?= $filter ?>#galleryGrid">Prev</a>
            <?php else: ?>
                <span class="disabled">Prev</span>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php if ($i === $page): ?>
                    <span class="active-page"><?= $i ?></span>
                <?php else: ?>
                    <a href="?page=<?= $i ?>&filter=<?= $filter ?>#galleryGrid"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?>&filter=<?= $filter ?>#galleryGrid">Next</a>
            <?php else: ?>
                <span class="disabled">Next</span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

<!-- Gallery Modal -->
<div id="galleryModal" class="gallery-modal" onclick="closeModal()">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-img-wrapper">
            <img id="modalImage" class="modal-img" src="" alt="" onclick="openLightbox(document.getElementById('modalImage').src)">
            <button class="modal-close" onclick="closeModal()">✕</button>
            <button type="button" class="modal-nav prev" aria-label="Foto sebelumnya" onclick="navigateSlide(-1, event)">&lsaquo;</button>
            <button type="button" class="modal-nav next" aria-label="Foto berikutnya" onclick="navigateSlide(1, event)">&rsaquo;</button>
            <span class="modal-img-hint">Klik gambar untuk ukuran penuh</span>
        </div>
        <div id="modalThumbs" class="modal-thumbs"></div>
        <div class="modal-body">
            <h3 id="modalTitle"></h3>
            <span class="modal-kategori" id="modalKategori"></span>
            <p class="modal-deskripsi" id="modalDeskripsi"></p>
            <p class="modal-tanggal" id="modalTanggal"></p>
            <p class="mt-3 text-xs text-gray-400" id="slideCounter" aria-live="polite"></p>
        </div>
    </div>
</div>

<!-- Lightbox Modal -->
<div id="lightboxModal" class="lightbox-modal" onclick="closeLightbox()">
    <button class="lightbox-close" onclick="closeLightbox()">&times;</button>
    <img id="lightboxImage" src="" alt="Full Size Image">
</div>

<script src="assets/js/main.js"></script>

<script>
/* =========================================================
   DATA GALERI: setiap galeri punya array slides sendiri
   ========================================================= */
const galleryData = <?= json_encode($galleryData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;

let currentGalleryIndex = 0; // galeri yang sedang dibuka
let currentSlideIndex = 0;   // slide ke berapa di dalam galeri tsb

/* Render slide berdasarkan galeri & index slide */
function renderSlide(galleryIndex, slideIndex) {
    if (!galleryData.length) return;
    const galeri = galleryData[galleryIndex];
    if (!galeri) return;

    const slides = galeri.slides || [];
    if (!slides.length) return;

    // Normalisasi index slide
    currentGalleryIndex = galleryIndex;
    currentSlideIndex = (slideIndex + slides.length) % slides.length;

    const imgSrc = slides[currentSlideIndex];

    // Update gambar utama
    const modalImg = document.getElementById('modalImage');
    modalImg.src = imgSrc;
    modalImg.alt = galeri.title;

    // Update info galeri (judul, kategori, deskripsi, tanggal)
    document.getElementById('modalTitle').textContent = galeri.title;
    document.getElementById('modalKategori').textContent = galeri.category;
    document.getElementById('modalDeskripsi').textContent = galeri.description;
    document.getElementById('modalTanggal').textContent = galeri.date;
    document.getElementById('slideCounter').textContent =
        `Slide ${currentSlideIndex + 1} dari ${slides.length} foto`;

    // Render thumbnail navigator
    renderThumbs(galeri, currentSlideIndex);
}

/* Render thumbnail di bawah gambar utama */
function renderThumbs(galeri, activeIndex) {
    const box = document.getElementById('modalThumbs');
    box.innerHTML = '';

    // Kalau hanya 1 foto, sembunyikan strip thumbnail
    if (!galeri.slides || galeri.slides.length <= 1) {
        box.style.display = 'none';
        return;
    }
    box.style.display = 'flex';

    galeri.slides.forEach(function(src, i) {
        const thumb = document.createElement('div');
        thumb.className = 'modal-thumb' + (i === activeIndex ? ' active' : '');
        thumb.onclick = function(e) {
            e.stopPropagation();
            renderSlide(currentGalleryIndex, i);
        };
        const img = document.createElement('img');
        img.src = src;
        img.alt = 'Foto ' + (i + 1);
        img.loading = 'lazy';
        thumb.appendChild(img);
        box.appendChild(thumb);
    });

    // Scroll thumbnail aktif ke view
    const activeThumb = box.children[activeIndex];
    if (activeThumb) {
        activeThumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
    }
}

/* Buka modal untuk galeri tertentu (dipanggil dari onclick item) */
function openSlide(galleryIndex) {
    currentGalleryIndex = galleryIndex;
    currentSlideIndex = 0;
    renderSlide(galleryIndex, 0);

    const modal = document.getElementById('galleryModal');
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

/* Navigasi slide berikutnya/sebelumnya */
function navigateSlide(direction, event) {
    if (event) event.stopPropagation();
    const galeri = galleryData[currentGalleryIndex];
    if (!galeri || !galeri.slides) return;
    renderSlide(currentGalleryIndex, currentSlideIndex + direction);
}

/* Tutup modal */
function closeModal() {
    const modal = document.getElementById('galleryModal');
    modal.classList.remove('active');
    document.body.style.overflow = 'auto';
}

/* Lightbox */
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

/* Keyboard navigation */
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
        closeLightbox();
    }
    if (document.getElementById('galleryModal').classList.contains('active')) {
        if (e.key === 'ArrowLeft') { e.preventDefault(); navigateSlide(-1, e); }
        if (e.key === 'ArrowRight') { e.preventDefault(); navigateSlide(1, e); }
    }
});

/* Klik area luar modal untuk tutup */
document.getElementById('galleryModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

/* Filter gallery */
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const filter = this.dataset.filter;
        window.location.href = '<?= BASE_URL ?>galeri.php?filter=' + filter + '#galleryGrid';
    });
});
</script>

</body>
</html>