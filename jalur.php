<?php require_once __DIR__ . '/includes/config.php'; 
// Get spot data (urutan dari Database: Basecamp -> Pos 1 -> ... -> Puncak)
$stmt = $pdo->query("SELECT * FROM spot_jalur ORDER BY urutan ASC");
$spots = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telusur Jalur - Gunung Bismo via Deroduwur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Outfit', sans-serif; }

        /* Hero Section - Sama seperti index dan kisah */
        .hero-jalur {
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

        .hero-jalur::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(47, 82, 51, 0.6);
            z-index: 1;
        }

        .hero-jalur .hero-content {
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

        /* Animasi fade untuk hero */
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

        /* Toggle Button */
        .toggle-btn {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .toggle-btn.active {
            background: #2F5233;
            color: white;
            box-shadow: 0 4px 15px rgba(47, 82, 51, 0.3);
        }

        .toggle-btn:not(.active) {
            background: #e5e7eb;
            color: #5C5C50;
        }

        .toggle-btn:not(.active):hover {
            background: #d1d5db;
        }

        /* Modal Overlay */
        .modal-overlay {
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease-in-out;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            transform: translateY(20px);
            transition: transform 0.3s ease-in-out;
        }

        .modal-overlay.active .modal-content {
            transform: translateY(0);
        }

        /* View Container */
        .view-container {
            display: none;
            animation: fadeIn 0.5s ease;
        }

        .view-container.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* SVG Trail Container - Interaktif */
        .trail-svg-container {
            position: relative;
            width: 100%;
            max-width: 850px;
            margin: 0 auto;
            padding: 20px 0 40px 0;
        }

        #trail-svg {
            width: 100%;
            height: auto;
            overflow: visible;
            background: #f8fafc;
            border-radius: 16px;
            box-shadow: inset 0 2px 10px rgba(0,0,0,0.05);
        }

        .path-bg {
            stroke: #CBD5E1;
            stroke-width: 8;
            stroke-linecap: round;
            stroke-linejoin: round;
            fill: none;
        }

        .path-active {
            stroke: #ef4444;
            stroke-width: 8;
            stroke-linecap: round;
            stroke-linejoin: round;
            fill: none;
            transition: stroke-dashoffset 0.1s linear;
        }

        /* Spot Pin Styling */
        .spot-pin {
            position: absolute;
            transform: translate(-50%, -50%);
            opacity: 0;
            scale: 0.5;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .spot-pin.active {
            opacity: 1;
            scale: 1;
        }

        .pin-dot {
            width: 24px;
            height: 24px;
            background: #22c55e;
            border: 3px solid #ffffff;
            border-radius: 50%;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
            transition: transform 0.3s ease;
            cursor: pointer;
            position: relative;
            z-index: 10;
        }

        .spot-pin:hover .pin-dot, .spot-pin.expanded .pin-dot {
            transform: scale(1.4);
            background: #eab308;
        }

        .pin-dot.puncak {
            background: #ef4444;
            width: 30px;
            height: 30px;
            border-color: #fef08a;
        }

        .pin-dot.basecamp {
            background: #3b82f6;
            width: 30px;
            height: 30px;
            border-color: #93c5fd;
        }

        .pin-label {
            position: absolute;
            top: 35px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0,0,0,0.75);
            backdrop-filter: blur(4px);
            color: white;
            font-size: 10px;
            font-weight: 600;
            padding: 3px 12px;
            border-radius: 20px;
            white-space: nowrap;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 5;
        }

        .spot-pin.active .pin-label {
            opacity: 1;
        }

        .spot-card {
            position: absolute;
            width: 260px;
            max-width: 75vw;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(8px);
            border-radius: 14px;
            padding: 12px 14px;
            box-shadow: 0 10px 30px -4px rgba(0, 0, 0, 0.2);
            border-left: 5px solid #2F5233;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }

        .spot-pin.pos-left .spot-card { 
            right: 40px; 
            top: 50%;
            transform: translateY(-50%) translateX(-10px);
        }

        .spot-pin.pos-right .spot-card { 
            left: 40px; 
            top: 50%;
            transform: translateY(-50%) translateX(10px);
        }

        .spot-pin.active .spot-card {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(-50%) translateX(0);
        }

        .card-details {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: all 0.4s ease-in-out;
        }

        .spot-pin:hover .spot-card, 
        .spot-pin.expanded .spot-card {
            width: 300px;
            box-shadow: 0 20px 40px -5px rgba(0, 0, 0, 0.3);
            z-index: 100 !important;
        }

        .spot-pin:hover .card-details,
        .spot-pin.expanded .card-details {
            max-height: 300px;
            opacity: 1;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px dashed #e2e8f0;
        }

        .spot-img-container {
            width: 100%;
            height: 120px;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 6px;
            background-color: #e2e8f0;
        }

        .spot-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .spot-img-container img:hover {
            transform: scale(1.05);
        }

        /* Poster View - Gambar Peta */
        .poster-container {
            position: relative;
            width: 100%;
            max-width: 850px;
            margin: 0 auto;
        }

        .poster-container img {
            width: 100%;
            height: auto;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .poster-container img:hover {
            transform: scale(1.01);
        }

        .poster-overlay {
            position: absolute;
            bottom: 20px;
            right: 20px;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(4px);
            padding: 8px 16px;
            border-radius: 10px;
            color: white;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .poster-overlay:hover {
            background: rgba(0,0,0,0.85);
        }

        /* Lightbox */
        .lightbox-modal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 9999;
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
            z-index: 10000;
        }

        .lightbox-close:hover {
            transform: scale(1.2);
        }

        /* Custom Scrollbar */
        .modal-scroll-body::-webkit-scrollbar { width: 8px; }
        .modal-scroll-body::-webkit-scrollbar-track { background: #F1F5F9; border-radius: 4px; }
        .modal-scroll-body::-webkit-scrollbar-thumb { background: #2F5233; border-radius: 4px; }

        @media (max-width: 768px) {
            .hero-jalur {
                height: 85vh;
                min-height: 450px;
            }
            .spot-card {
                width: 180px !important;
                padding: 8px 10px;
            }
            .spot-pin:hover .spot-card, 
            .spot-pin.expanded .spot-card {
                width: 220px !important;
            }
            .pin-label {
                font-size: 8px;
                padding: 2px 8px;
                top: 28px;
            }
            .pin-dot {
                width: 18px;
                height: 18px;
            }
            .pin-dot.puncak, .pin-dot.basecamp {
                width: 22px;
                height: 22px;
            }
            .lightbox-close {
                top: 20px;
                right: 20px;
                font-size: 30px;
            }
            .toggle-btn {
                font-size: 13px;
                padding: 8px 16px !important;
            }
        }

        @media (max-width: 480px) {
            .hero-jalur {
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
            .spot-card {
                width: 150px !important;
                padding: 6px 8px;
            }
            .spot-pin:hover .spot-card, 
            .spot-pin.expanded .spot-card {
                width: 180px !important;
            }
            .spot-img-container {
                height: 70px;
            }
            .pin-label {
                display: none;
            }
            .poster-overlay {
                font-size: 10px;
                padding: 4px 10px;
                bottom: 10px;
                right: 10px;
            }
        }
    </style>
</head>
<body class="bg-[#FAF7F2]">

<?php include __DIR__ . '/includes/navbar.php'; ?>

<!-- Hero Section -->
<section class="hero-jalur" style="background-image: url('<?= BASE_URL ?>assets/images/telusurjalur/hero-jalur.png');">
    <div class="hero-content container mx-auto px-6 md:px-12 lg:px-24">
        <div class="max-w-7xl mx-auto">
            <h1 class="hero-title text-5xl md:text-6xl lg:text-7xl font-bold text-white hero-fade delay-1 mb-10">
                Telusur <span class="text-[#E0BE45]">Jalur Pendakian</span>
            </h1>
            <p class="hero-subtitle text-base md:text-lg lg:text-2xl text-white/90 leading-relaxed hero-fade delay-2 max-w-3xl mx-auto">
                Jelajahi setiap pos dan spot menarik di sepanjang jalur pendakian Gunung Bismo via Deroduwur. 
                Pilih mode tampilan sesuai keinginan Anda.
            </p>
        </div>
    </div>
</section>

<!-- Visualisasi Jalur -->
<section class="py-12 bg-white">
    <div class="container mx-auto px-4 max-w-6xl">
        <div class="text-center mb-8">
            <h2 class="text-3xl md:text-4xl font-bold text-[#2F5233]">Peta & Visualisasi Jalur</h2>
            <p class="text-[#5C5C50] mt-2">Pilih mode tampilan: Interaktif atau Poster</p>
        </div>

        <!-- Toggle Button -->
        <div class="flex justify-center mb-8">
            <div class="inline-flex bg-gray-100 rounded-2xl p-1.5 shadow-md">
                <button id="toggleInteraktif" class="toggle-btn active px-6 py-3 rounded-xl font-semibold text-sm transition duration-300">
                    Interaktif
                </button>
                <button id="togglePoster" class="toggle-btn px-6 py-3 rounded-xl font-semibold text-sm transition duration-300">
                    Poster
                </button>
            </div>
        </div>

        <!-- View Interaktif -->
        <div id="viewInteraktif" class="view-container active">
            <div class="bg-[#FAF7F2] rounded-2xl shadow-xl p-4 md:p-6 border border-gray-200">
                
                <!-- Tombol Aksi -->
                <div class="flex flex-wrap justify-center gap-4 mb-6">
                    <button onclick="openLightbox('<?= BASE_URL ?>assets/images/peta-jalur-bismo.jpg')" 
                            class="inline-flex items-center gap-2 bg-[#2F5233] hover:bg-[#4A7A4E] text-white px-6 py-3 rounded-xl font-semibold transition duration-300 shadow-md hover:shadow-lg">
                     Lihat Peta Full
                    </button>
                    <a href="<?= BASE_URL ?>assets/images/peta-jalur-bismo.jpg" download="Peta-Jalur-Gunung-Bismo-Deroduwur.jpg" 
                       class="inline-flex items-center gap-2 bg-[#E0BE45] hover:bg-[#C46F2A] text-white px-6 py-3 rounded-xl font-semibold transition duration-300 shadow-md hover:shadow-lg">
                    Download Peta
                    </a>
                </div>

                <!-- SVG Visualisasi Interaktif dengan Background Peta -->
                <div class="trail-svg-container" id="trailContainer">
                    
                    <svg id="trail-svg" viewBox="0 0 800 1600" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Background Peta (Grid/Garis Kontur) -->
                        <rect width="800" height="1600" fill="#f0f4f8" rx="16"/>
                        
                        <!-- Garis Kontur / Grid -->
                        <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                            <path d="M 40 0 L 0 0 0 40" fill="none" stroke="#e2e8f0" stroke-width="0.5"/>
                        </pattern>
                        <rect width="800" height="1600" fill="url(#grid)"/>
                        
                        <!-- Garis Kontur Topografi (Dekoratif) -->
                        <path d="M 100 200 Q 300 150 500 250 T 700 200" stroke="#d1d5db" stroke-width="1" fill="none" opacity="0.5"/>
                        <path d="M 50 400 Q 250 350 450 420 T 750 380" stroke="#d1d5db" stroke-width="1" fill="none" opacity="0.5"/>
                        <path d="M 80 600 Q 280 550 480 620 T 720 580" stroke="#d1d5db" stroke-width="1" fill="none" opacity="0.5"/>
                        <path d="M 60 800 Q 260 750 460 820 T 740 780" stroke="#d1d5db" stroke-width="1" fill="none" opacity="0.5"/>
                        <path d="M 120 1000 Q 320 950 520 1020 T 700 980" stroke="#d1d5db" stroke-width="1" fill="none" opacity="0.5"/>
                        <path d="M 90 1200 Q 290 1150 490 1220 T 710 1180" stroke="#d1d5db" stroke-width="1" fill="none" opacity="0.5"/>
                        <path d="M 110 1400 Q 310 1350 510 1420 T 690 1380" stroke="#d1d5db" stroke-width="1" fill="none" opacity="0.5"/>

                        <!-- Background Path (Abu-abu) - Jalur Utama -->
                        <path class="path-bg" d="M 400 100 L 330 180 L 400 280 L 325 380 L 415 500 L 330 620 L 400 750 L 325 890 L 415 1030 L 330 1180 L 400 1330 L 370 1500" />
                        
                        <!-- Active Path (Merah - akan terisi sesuai scroll) -->
                        <path id="route-path" class="path-active" d="M 400 100 L 330 180 L 400 280 L 325 380 L 415 500 L 330 620 L 400 750 L 325 890 L 415 1030 L 330 1180 L 400 1330 L 370 1500" />
                        
                        <!-- Marker Basecamp -->
                        <circle cx="370" cy="1500" r="12" fill="#3b82f6" stroke="white" stroke-width="3"/>
                        <text x="370" y="1550" fill="#1e293b" font-size="14" font-weight="700" text-anchor="middle">🏕️ Basecamp Deroduwur</text>
                        
                        <!-- Marker Puncak -->
                        <circle cx="400" cy="100" r="12" fill="#ef4444" stroke="white" stroke-width="3"/>
                        <text x="400" y="70" fill="#ef4444" font-size="14" font-weight="700" text-anchor="middle">🏔️ Puncak Indraprasta (2365 MDPL)</text>
                        
                        <!-- Legenda -->
                        <rect x="20" y="20" width="180" height="90" rx="10" fill="white" stroke="#e2e8f0" stroke-width="1" opacity="0.9"/>
                        <text x="35" y="45" fill="#1e293b" font-size="12" font-weight="700">📌 Legenda</text>
                        <circle cx="35" cy="65" r="6" fill="#22c55e"/>
                        <text x="50" y="69" fill="#5C5C50" font-size="11">Pos Peristirahatan</text>
                        <circle cx="35" cy="85" r="6" fill="#ef4444"/>
                        <text x="50" y="89" fill="#5C5C50" font-size="11">Puncak</text>
                        <circle cx="35" cy="105" r="6" fill="#3b82f6"/>
                        <text x="50" y="109" fill="#5C5C50" font-size="11">Basecamp</text>
                    </svg>

                    <!-- Mapping Spot Koordinat -->
                    <?php 
                    $coordsPeta = [
                        ['x' => 370, 'y' => 1500, 'side' => 'left', 'label' => 'Basecamp'],
                        ['x' => 400, 'y' => 1330, 'side' => 'right', 'label' => 'Pos Ojek'],
                        ['x' => 330, 'y' => 1180, 'side' => 'left', 'label' => 'Gerbang'],
                        ['x' => 415, 'y' => 1030, 'side' => 'right', 'label' => 'Pos I'],
                        ['x' => 325, 'y' => 890,  'side' => 'left', 'label' => 'Hutan Pakis'],
                        ['x' => 400, 'y' => 750,  'side' => 'right', 'label' => 'Pos II'],
                        ['x' => 330, 'y' => 620,  'side' => 'left', 'label' => 'Tanjakan Jalak'],
                        ['x' => 415, 'y' => 500,  'side' => 'right', 'label' => 'Pos III'],
                        ['x' => 325, 'y' => 380,  'side' => 'left', 'label' => 'Pos IV'],
                        ['x' => 400, 'y' => 280,  'side' => 'right', 'label' => 'Sunrise Camp'],
                        ['x' => 330, 'y' => 180,  'side' => 'left', 'label' => 'Puncak Hastinapura'],
                        ['x' => 400, 'y' => 100,  'side' => 'right', 'label' => 'Puncak Indraprasta'],
                    ];

                    foreach ($spots as $index => $spot): 
                        $coord = $coordsPeta[$index] ?? ['x' => 400, 'y' => 1500 - ($index * 120), 'side' => ($index % 2 == 0 ? 'right' : 'left'), 'label' => $spot['nama']];
                        $posX = ($coord['x'] / 800) * 100;
                        $posY = ($coord['y'] / 1600) * 100;
                        
                        $spotProgress = $coord['y'] / 1600;
                        $zIndex = 50 - $index;

                        $isPuncak = strpos(strtolower($spot['nama']), 'puncak') !== false || strpos(strtolower($spot['nama']), 'indraprasta') !== false;
                        $isBasecamp = strpos(strtolower($spot['nama']), 'basecamp') !== false;

                        $fotoUrl = !empty($spot['foto']) && file_exists(__DIR__ . '/assets/images/telusurjalur/' . $spot['foto']) 
                            ? 'assets/images/telusurjalur/' . $spot['foto'] 
                            : 'assets/images/default-spot.jpg';
                    ?>
                    <div class="spot-pin pos-<?= $coord['side'] ?>" 
                         style="left: <?= $posX ?>%; top: <?= $posY ?>%; z-index: <?= $zIndex ?>;" 
                         data-progress="<?= $spotProgress ?>">
                         
                        <div class="pin-dot <?= $isPuncak ? 'puncak' : ($isBasecamp ? 'basecamp' : '') ?>"></div>
                        
                        <div class="pin-label"><?= htmlspecialchars($coord['label']) ?></div>

                        <div class="spot-card">
                            <div class="flex items-center justify-between gap-1">
                                <h4 class="font-bold text-[#2F5233] text-xs md:text-sm leading-tight truncate"><?= htmlspecialchars($spot['nama']) ?></h4>
                                <span class="text-sm flex-shrink-0"><?= $isPuncak ? '🏔️' : ($isBasecamp ? '⛰️' : '📍') ?></span>
                            </div>
                            
                            <?php if (!empty($spot['ketinggian'])): ?>
                                <p class="text-[10px] text-[#A9784B] font-medium leading-tight mt-0.5">📍 <?= htmlspecialchars($spot['ketinggian']) ?></p>
                            <?php endif; ?>

                            <?php if (!empty($spot['estimasi_waktu'])): ?>
                                <p class="text-[10px] text-gray-500 font-medium">⏱️ <?= htmlspecialchars($spot['estimasi_waktu']) ?></p>
                            <?php endif; ?>

                            <div class="card-details">
                                <div class="spot-img-container" onclick="event.stopPropagation(); openLightbox('<?= htmlspecialchars($fotoUrl) ?>')">
                                    <img src="<?= htmlspecialchars($fotoUrl) ?>" alt="<?= htmlspecialchars($spot['nama']) ?>" loading="lazy">
                                </div>
                                
                                <?php if (!empty($spot['deskripsi'])): ?>
                                    <p class="text-[11px] text-gray-600 mt-1 leading-snug line-clamp-3"><?= htmlspecialchars($spot['deskripsi']) ?></p>
                                <?php endif; ?>
                                
                                <?php if (!empty($spot['lokasi'])): ?>
                                    <p class="text-[10px] text-gray-400 mt-1">📍 <?= htmlspecialchars($spot['lokasi']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="text-center py-4 text-sm font-medium text-[#2F5233] border-t border-gray-200 mt-4">
                    🏕️ Basecamp Deroduwur — 🏔️ Puncak Indraprasta (2365 MDPL)
                </div>
            </div>
        </div>

        <!-- View Poster -->
        <div id="viewPoster" class="view-container">
            <div class="bg-[#FAF7F2] rounded-2xl shadow-xl p-4 md:p-6 border border-gray-200">
                <div class="poster-container">
                    <img src="<?= BASE_URL ?>assets/images/peta-jalur-bismo.jpg" 
                         alt="Peta Jalur Pendakian Gunung Bismo" 
                         onclick="openLightbox('<?= BASE_URL ?>assets/images/peta-jalur-bismo.jpg')"
                         loading="lazy">
                    
                    <div class="poster-overlay" onclick="openLightbox('<?= BASE_URL ?>assets/images/peta-jalur-bismo.jpg')">
                     Klik untuk memperbesar
                    </div>
                </div>
                
                <div class="flex flex-wrap justify-center gap-4 mt-6">
                    <button onclick="openLightbox('<?= BASE_URL ?>assets/images/peta-jalur-bismo.jpg')" 
                            class="inline-flex items-center gap-2 bg-[#2F5233] hover:bg-[#4A7A4E] text-white px-6 py-3 rounded-xl font-semibold transition duration-300 shadow-md hover:shadow-lg">
                       Lihat Peta Full
                    </button>
                    <a href="<?= BASE_URL ?>assets/images/peta-jalur-bismo.jpg" download="Peta-Jalur-Gunung-Bismo-Deroduwur.jpg" 
                       class="inline-flex items-center gap-2 bg-[#E0BE45] hover:bg-[#C46F2A] text-white px-6 py-3 rounded-xl font-semibold transition duration-300 shadow-md hover:shadow-lg">
                    Download Peta
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Lightbox untuk Full Gambar -->
<div id="lightboxModal" class="lightbox-modal" onclick="closeLightbox()">
    <button class="lightbox-close" onclick="closeLightbox()">&times;</button>
    <img id="lightboxImage" src="" alt="Full Size Image">
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle functionality
    const toggleInteraktif = document.getElementById('toggleInteraktif');
    const togglePoster = document.getElementById('togglePoster');
    const viewInteraktif = document.getElementById('viewInteraktif');
    const viewPoster = document.getElementById('viewPoster');

    toggleInteraktif.addEventListener('click', function() {
        // Update toggle buttons
        toggleInteraktif.classList.add('active');
        togglePoster.classList.remove('active');
        
        // Update views
        viewInteraktif.classList.add('active');
        viewPoster.classList.remove('active');
        
        // Re-trigger trail animation
        setTimeout(updateTrailAnimation, 300);
    });

    togglePoster.addEventListener('click', function() {
        // Update toggle buttons
        togglePoster.classList.add('active');
        toggleInteraktif.classList.remove('active');
        
        // Update views
        viewPoster.classList.add('active');
        viewInteraktif.classList.remove('active');
    });

    // Trail animation
    const trailContainer = document.getElementById('trailContainer');
    const path = document.getElementById('route-path');
    const pins = document.querySelectorAll('.spot-pin');

    if (!path || !trailContainer) return;

    const pathLength = path.getTotalLength();
    path.style.strokeDasharray = pathLength;
    path.style.strokeDashoffset = pathLength;

    function updateTrailAnimation() {
        if (!viewInteraktif.classList.contains('active')) return;
        
        const containerRect = trailContainer.getBoundingClientRect();
        const containerHeight = containerRect.height;
        const scrollY = window.scrollY;
        const offsetTop = containerRect.top + window.scrollY;
        
        const viewportHeight = window.innerHeight;
        const visibleStart = Math.max(0, offsetTop - scrollY);
        const visibleEnd = Math.min(viewportHeight, offsetTop + containerHeight - scrollY);
        const visibleHeight = Math.max(0, visibleEnd - visibleStart);
        
        let progress = visibleHeight / containerHeight;
        progress = Math.max(0, Math.min(1, progress));

        const drawLength = pathLength * progress;
        path.style.strokeDashoffset = pathLength - drawLength;

        pins.forEach(pin => {
            const pinProgress = parseFloat(pin.getAttribute('data-progress'));
            if (progress >= pinProgress - 0.05) {
                pin.classList.add('active');
            } else {
                pin.classList.remove('active');
                pin.classList.remove('expanded');
            }
        });
    }

    // Fitur Klik Pin / Kartu untuk Toggle Expand
    pins.forEach(pin => {
        pin.addEventListener('click', function(e) {
            e.stopPropagation();
            pins.forEach(p => { if (p !== pin) p.classList.remove('expanded'); });
            pin.classList.toggle('expanded');
        });
    });

    // Update on scroll
    window.addEventListener('scroll', updateTrailAnimation);
    window.addEventListener('resize', updateTrailAnimation);
    
    // Initial update
    setTimeout(updateTrailAnimation, 500);
});

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

// Close lightbox with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeLightbox();
    }
});
</script>

</body>
</html>