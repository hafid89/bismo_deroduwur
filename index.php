<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$berita_terbaru = getBeritaTerbaru(3);
$galeri_preview = getGaleri(6);
$partner_logos = [
    ['nama' => 'wonosobo', 'file' => 'logo-wonosobo.png'],
    ['nama' => 'wonosobo', 'file' => 'logo-wonosobo.png'],
    ['nama' => 'Perhutani', 'file' => 'logo-perhutani.png'],
    ['nama' => 'oemah-alam', 'file' => 'logo-oemah-alam.png'],
    ['nama' => 'kkn-upnyk-384', 'file' => 'logo-kkn.png'],
];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gunung Bismo via Deroduwur - Basecamp Pendakian</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Swiper CSS (Untuk Carousel Hero) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

        * {
            font-family: 'Outfit', sans-serif;
        }

        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        /* Efek zoom untuk card galeri dan berita */
        .zoom-card {
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .zoom-card img {
            transition: transform 0.5s ease;
        }

        .zoom-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .zoom-card:hover img {
            transform: scale(1.08);
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        /* Bouncing slow untuk tagline carousel */
        .bounce-slow {
            animation: bounceSlow 2.5s ease-in-out infinite;
        }

        @keyframes bounceSlow {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        /* Animasi teks hero — muncul fade-up bertahap tiap slide aktif */
        .hero-swiper .swiper-slide .slide-content .hero-fade {
            opacity: 0;
            transform: translateY(30px);
        }

        .hero-swiper .swiper-slide-active .slide-content .hero-fade {
            animation: heroFadeUp 0.9s ease forwards;
        }

        .hero-swiper .swiper-slide-active .slide-content .hero-fade:nth-child(1) {
            animation-delay: 0.15s;
        }

        .hero-swiper .swiper-slide-active .slide-content .hero-fade:nth-child(2) {
            animation-delay: 0.35s;
        }

        .hero-swiper .swiper-slide-active .slide-content .hero-fade:nth-child(3) {
            animation-delay: 0.55s;
        }

        .hero-swiper .swiper-slide-active .slide-content .hero-fade:nth-child(4) {
            animation-delay: 0.75s;
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

        /* Infinite marquee — logo mitra kerja sama (warna asli, gerakan lebih cepat) */
        .marquee-wrapper {
            overflow: hidden;
            position: relative;
            width: 100%;
        }

        .marquee-track {
            display: flex;
            align-items: center;
            gap: 48px;
            width: max-content;
            animation: marqueeScroll 15s linear infinite;
        }

        .marquee-wrapper:hover .marquee-track {
            animation-play-state: paused;
        }

        @keyframes marqueeScroll {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .marquee-item {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 120px;
            padding: 0 20px;
        }

        .marquee-item img {
            max-height: 80px;
            max-width: 200px;
            object-fit: contain;
            filter: none !important;
            /* Warna asli, tidak grayscale */
            transition: transform 0.3s ease;
        }

        .marquee-item img:hover {
            transform: scale(1.15);
        }

        /* Swiper Custom Styles */
        .hero-swiper {
            height: 100vh;
            min-height: 600px;
        }

        .hero-swiper .swiper-slide {
            display: flex;
            align-items: center;
            justify-content: center;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .hero-swiper .swiper-slide::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(47, 82, 51, 0.65);
            z-index: 1;
        }

        .hero-swiper .swiper-slide .slide-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        /* Button CTA di tengah */
        .hero-swiper .swiper-slide .slide-content .cta-group {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 16px;
        }

        /* Navigation Buttons */
        .swiper-button-prev,
        .swiper-button-next {
            width: 50px !important;
            height: 50px !important;
            background: rgba(255, 255, 255, 0.15) !important;
            backdrop-filter: blur(8px) !important;
            -webkit-backdrop-filter: blur(8px) !important;
            border-radius: 50% !important;
            border: 2px solid rgba(255, 255, 255, 0.3) !important;
            transition: all 0.3s ease !important;
            z-index: 30 !important;
        }

        .swiper-button-prev:hover,
        .swiper-button-next:hover {
            background: #2F5233 !important;
            border-color: #2F5233 !important;
            transform: scale(1.1);
        }

        .swiper-button-prev::after,
        .swiper-button-next::after {
            font-size: 20px !important;
            color: white !important;
            font-weight: bold;
        }

        .swiper-button-prev {
            left: 20px !important;
        }

        .swiper-button-next {
            right: 20px !important;
        }

        @media (max-width: 768px) {

            .swiper-button-prev,
            .swiper-button-next {
                width: 40px !important;
                height: 40px !important;
            }

            .swiper-button-prev {
                left: 10px !important;
            }

            .swiper-button-next {
                right: 10px !important;
            }

            .swiper-button-prev::after,
            .swiper-button-next::after {
                font-size: 16px !important;
            }
        }

        /* Pagination Bullets */
        .swiper-pagination {
            bottom: 30px !important;
            z-index: 30 !important;
        }

        .swiper-pagination-bullet {
            width: 12px !important;
            height: 12px !important;
            background: rgba(255, 255, 255, 0.5) !important;
            opacity: 1 !important;
            transition: all 0.3s ease !important;
        }

        .swiper-pagination-bullet-active {
            background: #E0BE45 !important;
            width: 30px !important;
            border-radius: 6px !important;
        }

        /* Autoplay Progress Bar */
        .autoplay-progress {
            position: absolute;
            right: 16px;
            bottom: 16px;
            z-index: 40;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(4px);
            border-radius: 50%;
            cursor: pointer;
        }

        .autoplay-progress svg {
            --progress: 0;
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            transform: rotate(-90deg);
        }

        .autoplay-progress svg circle {
            fill: none;
            stroke: #E0BE45;
            stroke-width: 3;
            stroke-dasharray: 113.097;
            stroke-dashoffset: calc(113.097 * (1 - var(--progress)));
            transition: stroke-dashoffset 0.3s ease;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-swiper .swiper-slide .slide-content .cta-group {
                justify-content: center;
            }
        }

        /* Tagline bounce effect */
        .tagline-bounce {
            animation: bounceSlow 2.5s ease-in-out infinite;
        }
    </style>
</head>

<body class="bg-cream text-ink">

    <?php include __DIR__ . '/includes/navbar.php'; ?>

    <!-- Hero Section Carousel -->
    <section class="relative h-screen min-h-[600px] overflow-hidden bg-black">
        <div class="swiper hero-swiper w-full h-full">
            <div class="swiper-wrapper">

                <!-- Slide 1: Basecamp Bismo Via Deroduwur -->
                <div class="swiper-slide" style="background-image: url('<?= BASE_URL ?>assets/images/spots/slide1-basecamp.png');">
                    <div class="slide-content container mx-auto px-6 md:px-12 lg:px-24">
                        <div class="max-w-7xl mx-auto">
                            <h1 class="hero-fade text-5xl md:text-6xl lg:text-7xl font-bold text-white mb-8">
                                Basecamp Bismo <span class="text-[#E0BE45]">Via Deroduwur</span>
                            </h1>
                            <p class="hero-fade text-xl md:text-2xl lg:text-3xl text-[#E0BE45] font-semibold mb-6 tagline-bounce whitespace-nowrap">
                                Gerbang Pendakian Sisi Selatan
                            </p>
                            <p class="hero-fade text-lg md:text-xl text-white/90 mb-8 leading-relaxed max-w-3xl mx-auto">
                                Mari berbincang dalam satu tawa dan kata. Bersama kami membentuk memori hangat yang membekas di hati.
                            </p>
                            <div class="hero-fade cta-group mt-8">
                                <a href="<?= BASE_URL ?>jalur.php" class="group bg-[#E0BE45] hover:bg-[#C46F2A] text-white px-8 py-4 rounded-full font-semibold transition duration-300 transform hover:scale-105 inline-flex items-center gap-2">
                                    Telusur Jalur Pendakian
                                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </a>
                                <a href="https://wa.me/6281390195488" target="_blank" class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-8 py-4 rounded-full font-semibold transition duration-300 border-2 border-white inline-flex items-center gap-2">
                                    Hubungi Kami
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 2: Tentang Bismo via Deroduwur -->
                <div class="swiper-slide" style="background-image: url('<?= BASE_URL ?>assets/images/spots/slide2-jalurasri.png');">
                    <div class="slide-content container mx-auto px-6 md:px-12 lg:px-24">
                        <div class="max-w-7xl mx-auto">
                            <h1 class="hero-fade text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-8">
                                Jalur Asri, <span class="text-[#E0BE45]">Berhati Vegetasi</span>
                            </h1>
                            <p class="hero-fade text-lg md:text-xl lg:text-2xl text-white/90 mb-8 leading-relaxed max-w-3xl mx-auto">
                                Pesona alam hutan asri dengan keberagaman ekosistem yang tidak pernah gagal menarik perhatian tiap manik yang hadir.
                            </p>
                            <div class="hero-fade flex flex-wrap gap-4 justify-center mt-8">
                                <a href="<?= BASE_URL ?>kisah.php" class="group bg-[#E0BE45] hover:bg-[#C46F2A] text-white px-8 py-4 rounded-full font-semibold transition duration-300 transform hover:scale-105 inline-flex items-center gap-2">
                                    Tentang Basecamp
                                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 3: Flora & Fauna -->
                <div class="swiper-slide" style="background-image: url('<?= BASE_URL ?>assets/images/spots/slide3-flora.png');">
                    <div class="slide-content container mx-auto px-6 md:px-12 lg:px-24">
                        <div class="max-w-7xl mx-auto">
                            <h1 class="hero-fade text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-8">
                                Kekayaan <span class="text-[#E0BE45]">Flora & Fauna</span> Hutan Bismo
                            </h1>
                            <p class="hero-fade text-lg md:text-xl lg:text-2xl text-white/90 mb-8 leading-relaxed max-w-3xl mx-auto">
                                Persiapkan diri untuk kekayaan flora dan fauna hutan Bismo yang tidak ada duanya. Temukan primata Lutung Jawa dan Anggrek langka yang memukau mata.
                            </p>
                            <div class="hero-fade flex flex-wrap gap-4 justify-center mt-8">
                                <a href="<?= BASE_URL ?>alam.php" class="group bg-[#E0BE45] hover:bg-[#C46F2A] text-white px-8 py-4 rounded-full font-semibold transition duration-300 transform hover:scale-105 inline-flex items-center gap-2">
                                    Jelajahi Alam Bismo
                                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 4: Spot Favorit & Puncak -->
                <div class="swiper-slide" style="background-image: url('<?= BASE_URL ?>assets/images/spots/slide4-spot.png');">
                    <div class="slide-content container mx-auto px-6 md:px-12 lg:px-24">
                        <div class="max-w-7xl mx-auto">
                            <h1 class="hero-fade text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-8">
                                Spot <span class="text-[#E0BE45]">Ikonik & Panorama</span> Puncak
                            </h1>
                            <p class="hero-fade text-lg md:text-xl lg:text-2xl text-white/90 mb-8 leading-relaxed max-w-3xl mx-auto">
                                Keindahan view puncak Hastinapura dan Indraprasta sudah menunggu. Hamparan pemandangan siap diabadikan dalam lensamu.
                            </p>
                            <div class="hero-fade flex flex-wrap gap-4 justify-center mt-8">
                                <a href="<?= BASE_URL ?>galeri.php" class="group bg-[#E0BE45] hover:bg-[#C46F2A] text-white px-8 py-4 rounded-full font-semibold transition duration-300 transform hover:scale-105 inline-flex items-center gap-2">
                                    Lihat Spot Foto
                                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Tombol Navigasi Panah -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>

            <!-- Pagination Bullets -->
            <div class="swiper-pagination"></div>

            <!-- Autoplay Progress -->
            <div class="autoplay-progress">
                <svg viewBox="0 0 48 48">
                    <circle cx="24" cy="24" r="18"></circle>
                </svg>
                <span>⏸</span>
            </div>

        </div>
    </section>

    <!-- Sekilas Basecamp -->
    <section class="py-20 bg-[#FAF7F2]">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl md:text-4xl font-bold text-[#2F5233] mb-6">Secarik Perkenalan</h2>
                <p class="text-lg text-[#5C5C50] mb-8 leading-relaxed">
                    Basecamp Deroduwur telah menjadi rumah bagi para pengelola dan pendaki via Deroduwur sejak beberapa tahun berlalu. Selayaknya rumah, basecamp kami menjadi tempat naungan yang memberikan kehangatan layaknya keluarga bagi para pendaki. Perkenalan lebih jauh akan membangun arti kedekatan di antara kita
                </p>
                <a href="<?= BASE_URL ?>kisah.php" class="inline-block bg-[#2F5233] hover:bg-[#4A7A4E] text-white px-8 py-3 rounded-full font-semibold transition duration-300">
                    Selengkapnya →
                </a>
            </div>
        </div>
    </section>

    <!-- Highlight Nilai Plus -->
    <section class="py-20 bg-cream">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-center text-[#2F5233] mb-12">Keunggulan Basecamp Deroduwur</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-[#FAF7F2] p-8 rounded-2xl card-hover text-center reveal-bottom">
                    <div class="w-20 h-20 bg-[#2F5233] rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#2F5233] mb-2">Jalur Rimbun</h3>
                    <p class="text-[#5C5C50]">Jalur pendakian yang asri dengan pepohonan rindang dan udara segar</p>
                </div>
                <div class="bg-[#FAF7F2] p-8 rounded-2xl card-hover text-center reveal-bottom">
                    <div class="w-20 h-20 bg-[#2F5233] rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#2F5233] mb-2">Ekosistem Terjaga</h3>
                    <p class="text-[#5C5C50]">Keanekaragaman hayati yang masih terjaga dengan baik</p>
                </div>
                <div class="bg-[#FAF7F2] p-8 rounded-2xl card-hover text-center reveal-bottom">
                    <div class="w-20 h-20 bg-[#2F5233] rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#2F5233] mb-2">Flora & Fauna</h3>
                    <p class="text-[#5C5C50]">Beragam jenis tumbuhan dan satwa liar yang unik</p>
                </div>
                <div class="bg-[#FAF7F2] p-8 rounded-2xl card-hover text-center reveal-bottom">
                    <div class="w-20 h-20 bg-[#2F5233] rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#2F5233] mb-2">Budaya & Religi</h3>
                    <p class="text-[#5C5C50]">Nilai budaya dan religi yang kental di sekitar basecamp</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Didukung Oleh (Infinite Logo Carousel - Warna Asli, Gerakan Cepat) -->
    <section class="py-20 bg-[#FAF7F2] overflow-hidden">
        <div class="container mx-auto px-4 mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-center text-[#2F5233]">
                Didukung Oleh
            </h2>
        </div>
        <div class="marquee-wrapper">
            <div class="marquee-track">
                <?php
                // Duplicate logos 6x untuk loop yang lebih smooth
                $logos_loop = array_merge($partner_logos, $partner_logos, $partner_logos, $partner_logos, $partner_logos, $partner_logos);
                foreach ($logos_loop as $logo):
                ?>
                    <div class="marquee-item">
                        <img src="<?= BASE_URL ?>assets/images/<?= htmlspecialchars($logo['file']) ?>"
                            alt="<?= htmlspecialchars($logo['nama']) ?>"
                            loading="lazy">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Preview Galeri dengan Modal & Efek Zoom -->
    <section class="py-20 bg-[#FAF7F2]">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-center text-[#2F5233] mb-12">Jejak Visual</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($galeri_preview as $foto): ?>
                    <div class="zoom-card rounded-2xl shadow-lg bg-white cursor-pointer reveal-bottom"
                        onclick="openModal(
                    '<?= BASE_URL ?>uploads/galeri/<?= htmlspecialchars($foto['foto']) ?>',
                    '<?= htmlspecialchars($foto['judul']) ?>',
                    '<?= ucfirst(htmlspecialchars($foto['kategori'])) ?>',
                    '<?= htmlspecialchars($foto['deskripsi']) ?>',
                    '<?= isset($foto['created_at']) ? formatTanggal($foto['created_at']) : '' ?>'
                )">
                        <img src="<?= BASE_URL ?>uploads/galeri/<?= htmlspecialchars($foto['foto']) ?>"
                            alt="<?= htmlspecialchars($foto['judul']) ?>"
                            class="w-full h-64 object-cover">
                        <?php if ($foto['judul']): ?>
                            <div class="p-4">
                                <p class="text-[#2F5233] font-semibold"><?= htmlspecialchars($foto['judul']) ?></p>
                                <p class="text-xs text-[#A9784B]">#<?= htmlspecialchars($foto['kategori']) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center mt-10">
                <a href="<?= BASE_URL ?>galeri.php" class="inline-block bg-[#2F5233] hover:bg-[#4A7A4E] text-white px-8 py-3 rounded-full font-semibold transition duration-300">
                    Lihat Galeri Lengkap →
                </a>
            </div>
        </div>
    </section>

    <!-- Berita Terbaru dengan Efek Zoom -->
    <section class="py-20 bg-cream">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-center text-[#2F5233] mb-12">Kabar Bismo</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php foreach ($berita_terbaru as $berita): ?>
                    <div class="zoom-card bg-[#FAF7F2] rounded-2xl shadow-lg reveal-bottom">
                        <?php if ($berita['foto']): ?>
                            <img src="<?= BASE_URL ?>uploads/berita/<?= htmlspecialchars($berita['foto']) ?>"
                                alt="<?= htmlspecialchars($berita['judul']) ?>"
                                class="w-full h-48 object-cover">
                        <?php endif; ?>
                        <div class="p-6">
                            <p class="text-sm text-[#A9784B] mb-2"><?= formatTanggal($berita['tanggal']) ?></p>
                            <h3 class="text-xl font-bold text-[#2F5233] mb-2"><?= htmlspecialchars($berita['judul']) ?></h3>
                            <p class="text-[#5C5C50] text-sm mb-4"><?= truncateText(strip_tags($berita['isi']), 100) ?></p>
                            <a href="<?= BASE_URL ?>berita-detail.php?id=<?= $berita['id'] ?>" class="text-[#2F5233] font-semibold hover:text-[#4A7A4E] transition duration-300">
                                Baca Selengkapnya →
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center mt-10">
                <a href="<?= BASE_URL ?>berita.php" class="inline-block bg-[#2F5233] hover:bg-[#4A7A4E] text-white px-8 py-3 rounded-full font-semibold transition duration-300">
                    Lihat Semua Berita →
                </a>
            </div>
        </div>
    </section>

    <!-- CTA & Kontak Cepat -->
    <section class="py-20 bg-[#2F5233] text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">Siap Mendaki Gunung Bismo?</h2>
            <p class="text-xl mb-8 text-white/90">Hubungi kami untuk informasi lebih lanjut tentang jalur pendakian</p>
            <div class="flex flex-wrap justify-center gap-6">
                <a href="https://wa.me/6281390195488" target="_blank" class="bg-[#E0BE45] hover:bg-[#C46F2A] text-white px-8 py-4 rounded-full font-semibold transition duration-300 transform hover:scale-105 inline-flex items-center gap-2">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                    </svg>
                    Hubungi via WhatsApp
                </a>
                <a href="<?= BASE_URL ?>kontak.php" class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-8 py-4 rounded-full font-semibold transition duration-300 border-2 border-white inline-flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Temui Kami
                </a>
            </div>
        </div>
    </section>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <!-- Swiper JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- scrollreveal -->
    <script src="https://unpkg.com/scrollreveal"></script>

    <!-- Custom JS -->
    <script src="assets/js/main.js"></script>
    <!-- Modal Popup -->
    <script src="assets/js/modal.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // 1. Inisialisasi Swiper Carousel Hero Section
            if (document.querySelector('.hero-swiper')) {
                const progressCircle = document.querySelector('.autoplay-progress svg circle');
                const progressContent = document.querySelector('.autoplay-progress span');

                const heroSwiper = new Swiper('.hero-swiper', {
                    loop: true,
                    speed: 800,
                    effect: 'fade',
                    fadeEffect: {
                        crossFade: true
                    },
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                        pauseOnMouseEnter: true,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    on: {
                        autoplayTimeLeft: function(s, time, progress) {
                            if (progressCircle) {
                                progressCircle.style.setProperty('--progress', 1 - progress);
                            }
                            if (progressContent) {
                                progressContent.textContent = Math.ceil(time / 1000) + 's';
                            }
                        }
                    }
                });

                // Toggle autoplay on progress click
                const progressEl = document.querySelector('.autoplay-progress');
                if (progressEl) {
                    progressEl.addEventListener('click', function() {
                        if (heroSwiper.autoplay.running) {
                            heroSwiper.autoplay.stop();
                            this.querySelector('span').textContent = '▶';
                        } else {
                            heroSwiper.autoplay.start();
                            this.querySelector('span').textContent = '⏸';
                        }
                    });
                }
            }

            // 2. ScrollReveal
            if (typeof ScrollReveal !== 'undefined') {
                ScrollReveal().reveal('.reveal-bottom', {
                    distance: '40px',
                    duration: 800,
                    easing: 'ease-out',
                    origin: 'bottom',
                    interval: 100,
                    reset: false,
                    mobile: true
                });
            }

            // 3. Mobile Menu Toggle
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const mobileMenu = document.getElementById('mobileMenu');

            if (mobileMenuBtn && mobileMenu) {
                mobileMenuBtn.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }

            // 4. Smooth Scroll untuk Anchor Links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    const targetId = this.getAttribute('href');
                    if (targetId !== '#') {
                        e.preventDefault();
                        const target = document.querySelector(targetId);
                        if (target) {
                            target.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    }
                });
            });

            // 5. Navbar Scroll Effect
            const navbar = document.querySelector('nav');
            if (navbar) {
                window.addEventListener('scroll', function() {
                    if (window.pageYOffset > 100) {
                        navbar.classList.add('shadow-lg');
                    } else {
                        navbar.classList.remove('shadow-lg');
                    }
                });
            }
        });
    </script>

</body>

</html>