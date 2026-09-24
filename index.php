<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$berita_terbaru = getBeritaTerbaru(4);
$galeri_preview = getGaleri(4);
$partner_logos = [
    ['nama' => 'wonosobo', 'file' => 'logo-wonosobo.png'],
    ['nama' => 'derodusur', 'file' => 'logo-desa.png'],
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

    <!-- ===== FAVICON / LOGO DI TAB (BULAT TRANSPARAN) ===== -->
    <!-- Favicon utama -->
    <link rel="icon" type="image/png" sizes="32x32" href="<?= BASE_URL ?>assets/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= BASE_URL ?>assets/images/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= BASE_URL ?>assets/images/apple-touch-icon.png">
    <link rel="shortcut icon" href="<?= BASE_URL ?>assets/images/favicon.ico">

    <!-- Meta untuk theme color (agar background tab sesuai) -->
    <meta name="theme-color" content="#2F5233">

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

        /* Gallery Card */
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

        .kategori-jalur {
            color: #2F5233;
            background: #E8F5E9;
        }

        .kategori-ekosistem {
            color: #1565C0;
            background: #E3F2FD;
        }

        .kategori-kegiatan {
            color: #E65100;
            background: #FFF3E0;
        }

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

        @media (max-width: 768px) {
            .gallery-item .img-wrapper img {
                height: 160px;
            }

            .gallery-item .card-body {
                padding: 12px 14px;
            }
        }

        @media (max-width: 480px) {
            .gallery-item .img-wrapper img {
                height: 130px;
            }

            .gallery-item .card-body {
                padding: 10px 12px;
            }

            .gallery-item .card-body h4 {
                font-size: 0.85rem;
            }

            .gallery-item .card-body .deskripsi {
                font-size: 0.7rem;
            }
        }

        /* News Card */
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

        /* Modal / Lightbox */
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

        .gallery-modal.active {
            display: flex;
        }

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

        .modal-box::-webkit-scrollbar { width: 6px; }
        .modal-box::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
        .modal-box::-webkit-scrollbar-thumb { background: #2F5233; border-radius: 4px; }

        @media (max-width: 768px) {
            .gallery-modal .modal-box {
                max-width: 95%;
                margin: 10px;
            }

            .gallery-modal .modal-img {
                max-height: 300px;
            }

            .gallery-modal .modal-body {
                padding: 16px 18px 20px;
            }
        }

        @media (max-width: 480px) {
            .gallery-modal .modal-box {
                max-width: 98%;
                margin: 5px;
            }

            .gallery-modal .modal-img {
                max-height: 200px;
            }

            .gallery-modal .modal-body {
                padding: 12px 14px 16px;
            }

            .gallery-modal .modal-body h3 {
                font-size: 1.2rem;
            }

            .lightbox-close {
                top: 15px;
                right: 20px;
                font-size: 30px;
            }
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
                            <h1 class="hero-fade text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4">
                                Basecamp Gunung Bismo
                            </h1>
                            <h1 class="hero-fade text-4xl md:text-5xl lg:text-6xl font-bold text-[#E0BE45] mb-8">
                                Via Deroduwur
                            </h1>
                            <p class="hero-fade text-2xl md:text-3xl lg:text-4xl text-white font-semibold mb-6 tagline-bounce whitespace-nowrap">
                                Gerbang Pendakian <span class="text-[#E0BE45]"> Sisi Selatan</span>
                            </p>
                            <p class="hero-fade text-lg md:text-xl text-white/90 mb-8 leading-relaxed max-w-4xl mx-auto">
                                Mari berbincang dalam satu tawa dibawah atap yang sama. Menambah keluarga ceria dalam satu jiwa. Bersama kami membentuk jiwa alami untuk mewujudkan impian bersama.
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
                            <p class="hero-fade text-lg md:text-xl lg:text-2xl text-white/90 mb-8 leading-relaxed max-w-4xl mx-auto">
                                Pesona alam hutan asri dengan keberagaman ekosistem yang tidak pernah gagal menarik perhatian tiap mata. Kehadirannya berperan penting dalam kelestarian hutan dan mata air Gunung Bismo.
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
                            <p class="hero-fade text-lg md:text-xl lg:text-2xl text-white/90 mb-8 leading-relaxed max-w-4xl mx-auto">
                                Persiapkan diri untuk memanjakan mata melihat kekayaan flora dan fauna hutan Bismo yang tidak ada duanya. Temukan primata dan tumbuhan langka yang memberi pemandangan tak biasa.
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
                            <p class="hero-fade text-lg md:text-xl lg:text-2xl text-white/90 mb-8 leading-relaxed max-w-4xl mx-auto">
                                Keindahan view puncak Hastinapura dan Indraprasta sudah menunggu. Hamparan pemandangan 360° siap diabadikan dalam lensamu dan menjadi bagian dari cerita perjalananmu.
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
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-[#2F5233] mb-6">Secarik Perkenalan</h2>
                <p class="text-base md:text-xl lg:text-lg text-[#5C5C50] mb-8 text-center leading-relaxed">
                    Basecamp Deroduwur telah menjadi rumah bagi para pengelola dan pendaki Gunung Bismo sejak beberapa waktu silam. Selayaknya tempat pulang, basecamp kami menjadi tempat naungan yang memberikan kehangatan layaknya keluarga.
                    Pembelajaran alam yang terselip di antara obrolan membuat diri kembali sadar akan kecilnya angan-angan yang dipunya.
                    Perkenalan lebih jauh akan membangun arti kedekatan di antara kita.
                </p>
                <a href="<?= BASE_URL ?>kisah.php" class="inline-block bg-[#2F5233] hover:bg-[#4A7A4E] text-white px-8 py-3 rounded-full font-semibold transition duration-300">
                    Selengkapnya →
                </a>
            </div>
        </div>
    </section>

    <!-- Highlight Nilai Plus -->
    <section class="py-15 bg-cream">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-center text-[#2F5233] mb-6">Keunggulan Basecamp Deroduwur</h2>
            <div class="grid grid-cols-3 md:grid-cols-3 lg:grid-cols-3 gap-2 md:gap-6 lg:gap-8">
                <div class="bg-[#FAF7F2] p-4 md:p-6 lg:p-8 rounded-2xl card-hover text-center reveal-bottom">
                    <div class="w-14 h-14 md:w-16 md:h-16 lg:w-20 lg:h-20 bg-[#2F5233] rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 md:w-8 md:h-8 lg:w-10 lg:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M3 7l6 -3l6 3l6 -3v13l-6 3l-6 -3l-6 3v-13" />
                            <path d="M9 12v.01" />
                            <path d="M6 13v.01" />
                            <path d="M17 15l-4 -4" />
                            <path d="M13 15l4 -4" />
                        </svg>
                    </div>
                    <h3 class="text-base md:text-lg lg:text-xl font-bold text-[#2F5233] mb-2">Jalur Rimbun</h3>
                    <p class="text-xs md:text-sm lg:text-base text-[#5C5C50] justify-align">Menyusuri kanopi pepohonan nan asri, meresapi segarnya udara di sepanjang derap kaki.</p>
                </div>
                <div class="bg-[#FAF7F2] p-4 md:p-6 lg:p-8 rounded-2xl card-hover text-center reveal-bottom">
                    <div class="w-14 h-14 md:w-16 md:h-16 lg:w-20 lg:h-20 bg-[#2F5233] rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 md:w-8 md:h-8 lg:w-10 lg:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M3 20h18l-6.921 -14.612a2.3 2.3 0 0 0 -4.158 0l-6.921 14.612" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.5 11l2 2.5l2.5 -2.5l2 3l2.5 -2" />
                        </svg>
                    </div>
                    <h3 class="text-base md:text-lg lg:text-xl font-bold text-[#2F5233] mb-2">Ekosistem Terjaga</h3>
                    <p class="text-xs md:text-sm lg:text-base text-[#5C5C50] justify-align">Menyaksikan indahnya keragamanhayati yang terjaga dan tumbuh selaras dengan masyarakat.</p>
                </div>
                <div class="bg-[#FAF7F2] p-4 md:p-6 lg:p-8 rounded-2xl card-hover text-center reveal-bottom">
                    <div class="w-14 h-14 md:w-16 md:h-16 lg:w-20 lg:h-20 bg-[#2F5233] rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 md:w-8 md:h-8 lg:w-10 lg:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M13.5 5.49a1.764 1.764 0 0 1 -2.5 -2.49" />
                            <path d="M12 6v3" />
                            <path d="M19 21a8.9 8.9 0 0 0 1 -3.67c0 -2 -.92 -3.25 -3.24 -4.51a17.4 17.4 0 0 1 -4.76 -3.82a17.4 17.4 0 0 1 -4.76 3.82c-2.32 1.26 -3.24 2.55 -3.24 4.51a8.9 8.9 0 0 0 1 3.67h14" />
                        </svg>
                    </div>
                    <h3 class="text-base md:text-lg lg:text-xl font-bold text-[#2F5233] mb-2">Budaya & Religi</h3>
                    <p class="text-xs md:text-sm lg:text-base text-[#5C5C50] justify-align">Memeluk hangatnya nilai tradisi lokal dan nilai spiritual yang mengelilingi sekitar.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Didukung Oleh (Infinite Logo Carousel - Warna Asli, Gerakan Cepat) -->
    <section class="py-20 bg-[#FAF7F2] overflow-hidden">
        <div class="container mx-auto px-4 mb-12">
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-center text-[#2F5233] mb-6">
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
    <section class="py-15 bg-[#FAF7F2]">
        <div class="container mx-auto px-4 max-w-8xl">
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-center text-[#2F5233] mb-12">Jejak Visual</h2>
            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 lg:gap-6">
                <?php foreach ($galeri_preview as $foto):
                    $kat = htmlspecialchars($foto['kategori']);
                    $katClass = 'kategori-' . $kat;
                ?>
                    <div class="gallery-item reveal-bottom"
                        onclick="openModal(
                    '<?= BASE_URL ?>uploads/galeri/<?= htmlspecialchars($foto['foto']) ?>',
                    '<?= htmlspecialchars($foto['judul']) ?>',
                    '<?= ucfirst($kat) ?>',
                    '<?= htmlspecialchars($foto['deskripsi']) ?>',
                    '<?= isset($foto['created_at']) ? formatTanggal($foto['created_at']) : '' ?>'
                )">
                        <div class="img-wrapper">
                            <img src="<?= BASE_URL ?>uploads/galeri/<?= htmlspecialchars($foto['foto']) ?>"
                                alt="<?= htmlspecialchars($foto['judul']) ?>"
                                loading="lazy">
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
            <div class="text-center mt-10">
                <a href="<?= BASE_URL ?>galeri.php" class="inline-block bg-[#2F5233] hover:bg-[#4A7A4E] text-white px-8 py-3 rounded-full font-semibold transition duration-300">
                    Lihat Galeri Lengkap →
                </a>
            </div>
        </div>
    </section>

    <!-- Berita Terbaru -->
    <section class="py-20 bg-cream">
        <div class="container mx-auto px-4 max-w-8xl">
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-center text-[#2F5233] mb-12">Kabar Bismo</h2>
            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 lg:gap-8">
                <?php foreach ($berita_terbaru as $berita): ?>
                    <div class="card-berita reveal-bottom">
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
            <div class="text-center mt-10">
                <a href="<?= BASE_URL ?>berita.php" class="inline-block bg-[#2F5233] hover:bg-[#4A7A4E] text-white px-8 py-3 rounded-full font-semibold transition duration-300">
                    Lihat Semua Berita →
                </a>
            </div>
        </div>
    </section>

    <!-- CTA & Kontak Cepat -->
    <section class="pt-20 pb-15 bg-[#2F5233] text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold mb-6">Memiliki Pertanyaan?</h2>
            <p class="text-base md:text-lg lg:text-xl mb-8 text-white/90">Hubungi kami untuk informasi lebih lanjut</p>
            <div class="flex flex-wrap justify-center gap-6">
                <a href="https://wa.me/6281390195488" target="_blank" class="bg-[#E0BE45] hover:bg-[#C46F2A] text-white px-8 py-4 rounded-full font-semibold transition duration-300 transform hover:scale-105 inline-flex items-center gap-2">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                    </svg>
                    Hubungi via WhatsApp
                </a>
            </div>
        </div>
    </section>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <!-- Gallery Modal -->
    <div id="galleryModal" class="gallery-modal" onclick="closeModal()">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-img-wrapper">
                <img id="modalImage" class="modal-img" src="" alt="" onclick="openLightbox(document.getElementById('modalImage').src)">
                <button class="modal-close" onclick="closeModal()">✕</button>
                <span class="modal-img-hint">Klik gambar untuk ukuran penuh</span>
            </div>
            <div class="modal-body">
                <h3 id="modalTitle"></h3>
                <span class="modal-kategori" id="modalKategori"></span>
                <p class="modal-deskripsi" id="modalDeskripsi"></p>
                <p class="modal-tanggal" id="modalTanggal"></p>
            </div>
        </div>
    </div>

    <!-- Lightbox Modal -->
    <div id="lightboxModal" class="lightbox-modal" onclick="closeLightbox()">
        <button class="lightbox-close" onclick="closeLightbox()">&times;</button>
        <img id="lightboxImage" src="" alt="Full Size Image">
    </div>

    <!-- Swiper JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- scrollreveal -->
    <script src="https://unpkg.com/scrollreveal"></script>

    <!-- Custom JS -->
    <script src="assets/js/main.js"></script>

    <script>
        // Gallery Modal Functions
        function openModal(imageSrc, title, kategori, deskripsi, tanggal) {
            const modal = document.getElementById('galleryModal');
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('modalTitle').textContent = title || 'Tanpa Judul';
            document.getElementById('modalKategori').textContent = kategori || 'Umum';
            document.getElementById('modalDeskripsi').textContent = deskripsi || 'Deskripsi belum tersedia.';
            document.getElementById('modalTanggal').textContent = tanggal ? tanggal : '';
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal = document.getElementById('galleryModal');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }

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
                closeModal();
                closeLightbox();
            }
        });
    </script>

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

            // 3. Smooth Scroll untuk Anchor Links
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