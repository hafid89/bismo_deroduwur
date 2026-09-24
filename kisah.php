
<?php require_once __DIR__ . '/includes/config.php'; ?>
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
    <title>Kisah Kami - Gunung Bismo via Deroduwur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Outfit', sans-serif; }

        /* Hero Section Style sama seperti index */
        .hero-kisah {
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

        .hero-kisah::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(47, 82, 51, 0.65);
            z-index: 1;
        }

        .hero-kisah .hero-content {
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

        /* Efek zoom untuk card suasana */
        .zoom-card {
            overflow: hidden;
            border-radius: 1rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .zoom-card img {
            transition: transform 0.5s ease;
            width: 100%;
            height: 280px;
            object-fit: cover;
        }

        .zoom-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .zoom-card:hover img {
            transform: scale(1.08);
        }

        /* Timeline Style */
        .timeline-item {
            position: relative;
            padding-left: 30px;
            padding-bottom: 30px;
            border-left: 3px solid #2F5233;
            transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.35s ease, border-color 0.35s ease;
            transform-origin: top left;
        }

        .timeline-item:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 28px 50px rgba(0, 0, 0, 0.12);
            border-color: #E0BE45;
        }

        .timeline-item:hover::before {
            background: #E0BE45;
            box-shadow: 0 0 0 6px rgba(224, 190, 69, 0.16);
        }

        .timeline-item:hover .timeline-desc {
            transform: scale(1.04);
            color: #3a4a3a;
            transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1), color 0.35s ease;
        }

        .timeline-item:last-child {
            border-left: 3px solid transparent;
            padding-bottom: 0;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -7px;
            top: 5px;
            width: 14px;
            height: 14px;
            background: #E0BE45;
            border-radius: 50%;
            border: 3px solid #2F5233;
        }

        .timeline-year {
            font-size: 1.125rem;
            font-weight: 700;
            color: #2F5233;
        }

        .timeline-desc {
            font-size: 0.875rem;
            color: #5C5C50;
            margin-top: 4px;
            line-height: 1.6;
        }

        @media (min-width: 768px) {
            .timeline-year {
                font-size: 1.25rem;
            }

            .timeline-desc {
                font-size: 1rem;
            }
        }

        /* Lightbox Modal */
        .lightbox-modal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(0, 0, 0, 0.9);
            justify-content: center;
            align-items: center;
            padding: 20px;
            cursor: pointer;
        }

        .lightbox-modal.active {
            display: flex;
        }

        .lightbox-modal img {
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        }

        .lightbox-close {
            position: absolute;
            top: 30px;
            right: 40px;
            color: white;
            font-size: 40px;
            background: none;
            border: none;
            cursor: pointer;
            transition: transform 0.3s ease;
            z-index: 10000;
        }

        .lightbox-close:hover {
            transform: scale(1.2);
        }

        @media (max-width: 768px) {
            .hero-kisah {
                height: 100vh;
                min-height: 450px;
            }

            .zoom-card img {
                height: 180px;
            }

            .lightbox-close {
                top: 20px;
                right: 20px;
                font-size: 30px;
            }
        }

        @media (max-width: 480px) {
            .hero-kisah {
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

            .zoom-card img {
                height: 140px;
            }

            .timeline-item {
                padding-left: 20px;
            }
        }
    </style>
</head>
<body class="bg-[#FAF7F2]">

<?php include __DIR__ . '/includes/navbar.php'; ?>

<!-- Hero Section  -->
<section class="hero-kisah" style="background-image: url('<?= BASE_URL ?>assets/images/kisah/pengelola.jpg');">
    <div class="hero-content container mx-auto px-6 md:px-12 lg:px-24">
        <div class="max-w-7xl mx-auto">
            <h1 class="hero-title text-4xl md:text-5xl lg:text-6xl font-bold text-white hero-fade delay-1 mb-10">
                Kisah <span class="text-[#E0BE45]">Basecamp Deroduwur</span>
            </h1>
            <p class="hero-subtitle text-lg md:text-lg lg:text-2xl text-white/90 mb-8 leading-relaxed hero-fade delay-2 max-w-4xl mx-auto">
                Dalam padunya langkah, teranyam pondasi kuat penuh berkah. 
                Rasakan lebih dalam kehangatan cerita kebersamaan Basecamp Deroduwur dari awal perjalanan hingga titik sekarang.
            </p>
        </div>
    </div>
</section>

<!-- Sejarah Basecamp dengan Timeline -->
<section class="py-10 bg-[#FAF7F2]">
    <div class="container mx-auto px-4 max-w-4xl">
        <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-center text-[#2F5233] mb-6">Sejarah Basecamp</h2>
        <p class="text-base md:text-xl lg:text-lg text-[#5C5C50] mb-8 text-center leading-relaxed">
            Kisah panjang Basecamp Deroduwur dalam melayani pendakian dan menjaga kelestarian alam Gunung Bismo.
        </p>

        <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12">
            <div class="space-y-0">
                <!-- Timeline Item 1 -->
                <div class="timeline-item">
                    <div class="timeline-year">2018 — Awal Mula</div>
                    <p class="timeline-desc">Berawal dari kepedulian pemuda Dusun Buntu bersama masyarakat sekitar, bekerja sama dengan pemerintah Desa Deroduwur, terhadap potensi wisata alam di Gunung Bismo. 
                    Perjalanan dibuka dengan pembersihan jalur pendakian tradisional yang selama ini digunakan oleh warga lokal.</p>
                </div>

                <!-- Timeline Item 2 -->
                <div class="timeline-item">
                    <div class="timeline-year">2020 — Jeda Rehat</div>
                    <p class="timeline-desc">Perjalanan basecamp Deroduwur perlu berhenti sejenak sebagai upaya pencegahan penularan COVID-19. 
                    Seluruh jalur pendakian Gunung Bismo dihimbau untuk melakukan penutupan sementara.</p>
                </div>

                <!-- Timeline Item 3 -->
                <div class="timeline-item">
                    <div class="timeline-year">2022 — Kembali Dimulai</div>
                    <p class="timeline-desc">Basecamp Deroduwur kembali membuka diri bagi para pendaki setelah rehat selama 2 tahun. 
                        Tiap untaian masukan dari Dinas Pariwisata dan Para Pecinta Pendakian serta dukungan dari Pemerintah Desa Deroduwur dan rekan-rekan basecamp lain menjadi pemantik semangat teman-teman basecamp untuk kembali menjalankan Basecamp Deroduwur.</p>
                </div>

                <!-- Timeline Item 4 -->
                <div class="timeline-item">
                    <div class="timeline-year">2026 — Rumah Menetap</div>
                    <p class="timeline-desc">
                   Dalam perjalanan akan memerlukan titik singgah sebagai peristirahatan. 
                   Basecamp Deroduwur turut dibangun dengan uluran tangan warga sekitar hingga akhirnya jalinan kerja sama bersama Pemerintah Desa Deroduwur membawa basecamp ke rumah tetap pertamanya.</p>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- Suasana Basecamp - Grid 4 Foto -->
<section class="py-10 bg-[#FAF7F2]">
    <div class="container mx-auto px-4 max-w-6xl">
        <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-center text-[#2F5233] mb-6">Lentera Kebersamaan</h2>
        <p class="text-base md:text-xl lg:text-lg text-[#5C5C50] mb-8 text-center leading-relaxed">
            Menyalakan kehangatan di ruang persinggahan dan merekam kebersamaan sebelum pengembaraan. 
        </p>

        <div class="grid grid-cols-2 md:grid-cols-2 gap-3 md:gap-4 lg:gap-6">
            <!-- Foto 1 -->
            <div class="zoom-card" onclick="openLightbox('<?= BASE_URL ?>assets/images/kisah/suasana/suasana1.jpg')">
                <img src="<?= BASE_URL ?>assets/images/kisah/suasana/suasana1.jpg" alt="Suasana Basecamp 1" loading="lazy">
            </div>
            <!-- Foto 2 -->
            <div class="zoom-card" onclick="openLightbox('<?= BASE_URL ?>assets/images/kisah/suasana/suasana2.jpg')">
                <img src="<?= BASE_URL ?>assets/images/kisah/suasana/suasana2.jpg" alt="Suasana Basecamp 2" loading="lazy">
            </div>
            <!-- Foto 3 -->
            <div class="zoom-card" onclick="openLightbox('<?= BASE_URL ?>assets/images/kisah/suasana/suasana3.jpg')">
                <img src="<?= BASE_URL ?>assets/images/kisah/suasana/suasana3.jpg" alt="Suasana Basecamp 3" loading="lazy">
            </div>
            <!-- Foto 4 -->
            <div class="zoom-card" onclick="openLightbox('<?= BASE_URL ?>assets/images/kisah/suasana/suasana4.jpg')">
                <img src="<?= BASE_URL ?>assets/images/kisah/suasana/suasana4.jpg" alt="Suasana Basecamp 4" loading="lazy">
            </div>
        </div>

        <p class="text-center text-sm text-[#A9784B] mt-6">* Klik foto untuk melihat ukuran penuh</p>
    </div>
</section>

<!-- Lokasi -->
<section class="py-10 bg-[#FAF7F2]">
    <div class="container mx-auto px-4 max-w-4xl">
        <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-center text-[#2F5233] mb-6">Lokasi Basecamp</h2>
        <p class="text-base md:text-xl lg:text-lg text-[#5C5C50] mb-8 text-center leading-relaxed">
            Ketuk akses di bawah ini untuk melihat petunjuk koordinat resmi Basecamp Deroduwur.
        </p>
        <div class="flex justify-center mb-6">
            <a href="https://www.google.com/maps/search/?api=1&query=-7.27725,109.8824445" 
               target="_blank" 
               rel="noopener noreferrer" 
               class="inline-flex items-center gap-2 text-sm bg-[#2F5233] hover:bg-[#4A7A4E] text-white px-4 py-2.5 rounded-lg transition shadow">
                <span>Buka Google Maps</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </a>
        </div>

        <!-- Google Maps Embed -->
        <div class="rounded-2xl overflow-hidden shadow-xl border border-gray-200">
            <iframe 
                src="https://maps.google.com/maps?q=-7.27725,109.8824445&hl=id&z=17&t=k&output=embed" 
                width="100%" 
                height="420" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>

        <p class="text-center text-[#5C5C50] mt-4">📍Dusun Buntu, Desa Deroduwur, Kec. Mojotengah, Kab. Wonosobo, Jawa Tengah</p>
    </div>
</section>

<!-- Nilai Plus -->
<section class="py-10 bg-[#2F5233] text-white">
    <div class="container mx-auto px-4 max-w-4xl">
        <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-center mb-6">Keistimewaan Bismo Via Deroduwur</h2>
        <p class="text-base md:text-lg lg:text-xl text-white/90 leading-relaxed mb-8 max-w-6xl mx-auto text-center">
            Menghadirkan alam yang tetap murni, kearifan lokal yang abadi, serta kehangatan gotong royong warga yang mengelola sepenuh hati. 
        </p>
        <div class="space-y-6">
            <div class="bg-white/10 backdrop-blur-sm p-6 rounded-xl border border-white/10 hover:bg-white/15 transition duration-300">
                <h3 class="text-lg md:text-xl lg:text-2xl font-bold text-[#E0BE45] mb-2">Jalur Nyaman bagi Pendaki</h3>
                <p class="text-sm md:text-base lg:text-lg text-gray-200 leading-relaxed">Jalur via Deroduwur memberikan track dengan pemandangan hutan pakis dan lumut yang sangat istimewa. 
                    Keberadaan 2 (dua) titik mata air di dua pos pertama juga menjadi keuntungan tersendiri bagi pendaki. 
                    Hal ini menjadikan Deroduwur sebagai lokasi pendakian yang cocok bagi para pendaki yang ingin merasakan suasana asri Gunung Bismo tanpa melalui jalur yang berat.</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm p-6 rounded-xl border border-white/10 hover:bg-white/15 transition duration-300">
                <h3 class="text-lg md:text-xl lg:text-2xl font-bold text-[#E0BE45] mb-2">Habitat Flora dan Fauna Eksotis</h3>
                <p class="text-sm md:text-base lg:text-lg text-gray-200 leading-relaxed">Keasrian alam via Deroduwur menjadikannya sebagai habitat bagi fauna langka seperti Lutung Jawa dan beberapa spesies burung, 
                    serta flora lainnya seperti Kantong Semar dan Anggrek Gunung. 
                    Keberagaman ini menjadikan Gunung Bismo Via Deroduwur sebagai lokasi strategis untuk penelitian dan konservasi alam.</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm p-6 rounded-xl border border-white/10 hover:bg-white/15 transition duration-300">
                <h3 class="text-xl font-bold text-[#E0BE45] mb-2">Pemuda Dusun Peduli Potensi Bismo</h3>
                <p class="text-sm md:text-base lg:text-lg text-gray-200 leading-relaxed">Basecamp Gunung Bismo Via Deroduwur bergerak tidak semata-mata hanya untuk menghidupkan semangat menjaga alam. 
                    Namun juga menumbuhkan hubungan kekeluargaan kolektif melalui Basecamp Deroduwur. 
                    Terbentuknya basecamp pendakian ini diharapkan tidak hanya sebagai wadah namun juga sebagai upaya menyejahterakan warga melalui pemanfaatan alam sekitar.</p>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

<!-- Lightbox Modal -->
<div id="lightboxModal" class="lightbox-modal" onclick="closeLightbox()">
    <button class="lightbox-close" onclick="closeLightbox()">&times;</button>
    <img id="lightboxImage" src="" alt="Full Size Image">
</div>

<script src="assets/js/main.js"></script>
<script>
    // Lightbox functions
    function openLightbox(imageSrc) {
        const modal = document.getElementById('lightboxModal');
        const img = document.getElementById('lightboxImage');
        img.src = imageSrc;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        const modal = document.getElementById('lightboxModal');
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    // Close lightbox with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLightbox();
        }
    });
</script>
</body>
</html>