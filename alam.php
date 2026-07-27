<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

// Pagination settings
$limit = 8;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

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
?>



<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alam Bismo - Gunung Bismo via Deroduwur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

        * {
            font-family: 'Outfit', sans-serif;
        }

        /* Hero Section - Sama seperti halaman lainnya */
        .hero-alam {
            height: 100vh;
            min-height: 600px;
            max-height: 800px;
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

        /* Card Styling - Konsisten dengan halaman lain */
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
            height: 220px;
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
            padding: 16px 18px;
        }

        .card-alam .card-body h4 {
            font-size: 1.05rem;
            font-weight: 700;
            color: #2F5233;
            margin-bottom: 2px;
        }

        .card-alam .card-body .nama-ilmiah {
            font-size: 0.8rem;
            color: #A9784B;
            font-style: italic;
            margin-bottom: 6px;
        }

        .card-alam .card-body .deskripsi {
            font-size: 0.875rem;
            color: #5C5C50;
            line-height: 1.6;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .card-alam .card-body .lokasi {
            font-size: 0.75rem;
            color: #A9784B;
            margin-top: 8px;
        }

        .btn-read-more {
            display: inline-block;
            margin-top: 10px;
            color: #2F5233;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: color 0.3s ease;
            background: none;
            border: none;
            padding: 0;
        }

        .btn-read-more:hover {
            color: #4A7A4E;
        }

        .btn-read-more::after {
            content: ' →';
            transition: transform 0.3s ease;
            display: inline-block;
        }

        .btn-read-more:hover::after {
            transform: translateX(4px);
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

        /* Modal Detail */
        .detail-modal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(8px);
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .detail-modal.active {
            display: flex;
        }

        .detail-modal .modal-box {
            background: white;
            border-radius: 20px;
            max-width: 600px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            padding: 30px;
            position: relative;
            animation: modalIn 0.3s ease;
        }

        @keyframes modalIn {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(20px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .detail-modal .modal-close {
            position: sticky;
            top: 0;
            float: right;
            background: #f1f5f9;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-size: 24px;
            cursor: pointer;
            transition: background 0.3s ease;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .detail-modal .modal-close:hover {
            background: #e2e8f0;
        }

        .detail-modal .modal-img {
            width: 100%;
            height: 280px;
            object-fit: cover;
            border-radius: 12px;
            cursor: pointer;
            transition: transform 0.3s ease;
            margin-bottom: 16px;
        }

        .detail-modal .modal-img:hover {
            transform: scale(1.02);
        }

        .detail-modal .modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2F5233;
            margin-bottom: 2px;
        }

        .detail-modal .modal-ilmiah {
            color: #A9784B;
            font-style: italic;
            font-size: 0.95rem;
            margin-bottom: 12px;
        }

        .detail-modal .modal-desc {
            color: #5C5C50;
            line-height: 1.8;
            font-size: 0.95rem;
        }

        .detail-modal .modal-lokasi {
            color: #A9784B;
            font-size: 0.85rem;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #f1f5f9;
        }

        /* Lightbox untuk full gambar */
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

        /* Section styling konsisten */
        .section-alam {
            padding: 20px 0;
        }

        .section-alam .section-title {
            font-size: clamp(1.8rem, 3.5vw, 2.8rem);
            font-weight: 700;
            color: #2F5233;
            margin-bottom: 6px;
        }

        .section-alam .section-subtitle {
            color: #5C5C50;
            margin-bottom: 40px;
            max-width: 600px;
        }

        /* Scroll untuk modal detail */
        .detail-modal .modal-box::-webkit-scrollbar {
            width: 6px;
        }

        .detail-modal .modal-box::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        .detail-modal .modal-box::-webkit-scrollbar-thumb {
            background: #2F5233;
            border-radius: 4px;
        }

        @media (max-width: 768px) {
            .hero-alam {
                height: 85vh;
                min-height: 450px;
            }

            .card-alam .img-wrapper img {
                height: 180px;
            }

            .detail-modal .modal-box {
                padding: 20px;
                margin: 10px;
            }

            .detail-modal .modal-img {
                height: 200px;
            }

            .section-alam {
                padding: 20px 0;
            }
        }

        @media (max-width: 480px) {
            .hero-alam {
                height: 80vh;
                min-height: 400px;
            }

            .hero-title {
                font-size: clamp(1.8rem, 7vw, 2.2rem);
            }

            .hero-subtitle {
                font-size: clamp(0.8rem, 2.5vw, 0.95rem);
                padding: 0 15px;
            }

            .card-alam .img-wrapper img {
                height: 150px;
            }

            .detail-modal .modal-box {
                padding: 16px;
            }

            .detail-modal .modal-img {
                height: 160px;
            }

            .lightbox-close {
                top: 15px;
                right: 20px;
                font-size: 30px;
            }
        }
    </style>
</head>

<body>

    <?php include __DIR__ . '/includes/navbar.php'; ?>

    <!-- Hero Section - Style sama seperti halaman lainnya -->
    <section class="hero-alam" style="background-image: url('<?= BASE_URL ?>assets/images/alam/hero-alam.png');">
        <div class="hero-content container mx-auto px-6 md:px-12 lg:px-24">
            <div class="max-w-7xl mx-auto">
                <h1 class="hero-title text-5xl md:text-6xl lg:text-7xl font-bold text-white hero-fade delay-1 mb-10">
                    <span class="text-[#E0BE45]">Alam</span> Bismo
                </h1>
                <p class="hero-subtitle text-base md:text-lg lg:text-2xl text-white/90 leading-relaxed hero-fade delay-2 max-w-3xl mx-auto">
                    Keanekaragaman flora dan fauna di jalur pendakian Gunung Bismo via Deroduwur.
                    Setiap langkah menyimpan keajaiban alam yang menunggu untuk dijelajahi.
                </p>
            </div>
        </div>
    </section>

    <!-- Intro Ekosistem -->
    <section class="section-alam bg-[#FAF7F2]">
        <div class="container mx-auto px-4 max-w-4xl">
            <div class="bg-white p-8 md:p-12 rounded-2xl text-center border border-gray-100 shadow-sm">
                <h2 class="text-xl md:text-2xl font-bold text-[#2F5233] mb-4"> Ekosistem Istimewa Deroduwur</h2>
                <p class="text-[#5C5C50] leading-relaxed text-base md:text-lg">
                    Jalur pendakian Gunung Bismo via Deroduwur memiliki keanekaragaman hayati yang luar biasa.
                    Dari flora endemik hingga fauna langka, setiap langkah di jalur ini menawarkan kesempatan
                    untuk menyaksikan keindahan alam yang masih terjaga. Keistimewaan ekosistem ini menjadi
                    salah satu daya tarik utama bagi para pendaki dan pecinta alam.
                </p>
            </div>
        </div>
    </section>

    <!-- Flora Section -->
    <section id="flora" class="section-alam bg-[#FAF7F2]">
        <div class="container mx-auto px-4 max-w-6xl">
            <div class="text-center mb-10">
                <h2 class="section-title">Flora</h2>
                <p class="section-subtitle mx-auto">Keindahan tumbuhan endemik di sepanjang jalur pendakian Gunung Bismo</p>
            </div>

            <?php if (empty($flora_list)): ?>
                <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                    <div class="text-5xl mb-4"></div>
                    <p class="text-lg text-[#5C5C50]">Belum ada data flora</p>
                    <p class="text-sm text-gray-400 mt-1">Silakan tambahkan melalui admin panel</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
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
                                    <p class="lokasi">📍 <?= htmlspecialchars($flora['lokasi']) ?></p>
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
                <h2 class="section-title">Fauna</h2>
                <p class="section-subtitle mx-auto">Satwa liar yang menghuni kawasan hutan Gunung Bismo</p>
            </div>

            <?php if (empty($fauna_list)): ?>
                <div class="bg-[#FAF7F2] rounded-2xl shadow-lg p-12 text-center">
                    <div class="text-5xl mb-4">🐾</div>
                    <p class="text-lg text-[#5C5C50]">Belum ada data fauna</p>
                    <p class="text-sm text-gray-400 mt-1">Silakan tambahkan melalui admin panel</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
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
                                    <p class="lokasi">📍 <?= htmlspecialchars($fauna['lokasi']) ?></p>
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

    <!-- Pesan Kelestarian Alam - Diperbesar dan Diperbagus -->
    <section class="py-15 bg-[#2F5233] text-white relative overflow-hidden">
        <!-- Dekorasi Background -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-64 h-64 bg-[#E0BE45] rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-[#4A7A4E] rounded-full blur-3xl translate-x-1/3 translate-y-1/3"></div>
        </div>

        <div class="container mx-auto px-4 max-w-4xl text-center relative z-10 mt-8">
            <div class="mb-6 text-6xl"></div>
            <h2 class="text-3xl md:text-5xl font-bold mb-6">Jaga Kelestarian Alam</h2>

            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 md:p-12 border border-white/10 mb-8">
                <blockquote class="text-2xl md:text-3xl font-medium text-[#E0BE45] mb-4">
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

    <!-- Detail Modal -->
    <div id="detailModal" class="detail-modal">
        <div class="modal-box">
            <button class="modal-close" onclick="closeDetailModal()">✕</button>
            <div id="modalContent">
                <!-- Akan diisi dengan JavaScript -->
            </div>
        </div>
    </div>

    <!-- Lightbox Modal -->
    <div id="lightboxModal" class="lightbox-modal" onclick="closeLightbox()">
        <button class="lightbox-close" onclick="closeLightbox()">&times;</button>
        <img id="lightboxImage" src="" alt="Full Size Image">
    </div>

    <?php include __DIR__ . '/includes/footer.php'; ?>
    <script src="assets/js/main.js"></script>

    <script>
        // Detail Modal Functions
        function openDetailModal(type, id) {
            const modal = document.getElementById('detailModal');
            const content = document.getElementById('modalContent');

            // Fetch data via AJAX
            fetch(`<?= BASE_URL ?>api/get-alam-detail.php?type=${type}&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const item = data.data;
                        const imagePath = item.foto ? `<?= BASE_URL ?>uploads/${type}/${item.foto}` : `<?= BASE_URL ?>assets/images/default-${type}.jpg`;
                        const categoryLabel = type === 'flora' ? '🌿 Flora' : '🐾 Fauna';

                        content.innerHTML = `
                    <img src="${imagePath}" alt="${item.nama}" class="modal-img" onclick="openLightbox('${imagePath}')">
                    <h3 class="modal-title">${item.nama}</h3>
                    ${item.nama_ilmiah ? `<p class="modal-ilmiah">${item.nama_ilmiah}</p>` : ''}
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-xs bg-[#FAF7F2] px-3 py-1 rounded-full text-[#5C5C50]">${categoryLabel}</span>
                    </div>
                    <p class="modal-desc">${item.deskripsi || 'Deskripsi belum tersedia.'}</p>
                    ${item.lokasi ? `<p class="modal-lokasi">📍 ${item.lokasi}</p>` : ''}
                    <p class="text-xs text-gray-400 mt-4">💡 Klik gambar untuk melihat ukuran penuh</p>
                `;

                        modal.classList.add('active');
                        document.body.style.overflow = 'hidden';
                    } else {
                        alert('Data tidak ditemukan');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal memuat data');
                });
        }

        function closeDetailModal() {
            const modal = document.getElementById('detailModal');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Lightbox functions
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

        // Close modals with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDetailModal();
                closeLightbox();
            }
        });

        // Close detail modal when clicking outside
        document.getElementById('detailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDetailModal();
            }
        });
    </script>

</body>

</html>