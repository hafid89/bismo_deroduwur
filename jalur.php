<?php require_once __DIR__ . '/includes/config.php'; 
// Get spot data (urutan dari Database: Basecamp -> Pos 1 -> ... -> Puncak)
$stmt = $pdo->query("SELECT * FROM spot_jalur ORDER BY urutan ASC");
$spots = $stmt->fetchAll();

// Koordinat spot yang sudah ditentukan
$spotCoordinates = [
    'Basecamp Deroduwur' => ['lat' => -7.277109360392517, 'lng' => 109.88252197062681],
    'Pos Ojek' => ['lat' => -7.2767872, 'lng' => 109.8826357],
    'Hutan Pakis' => ['lat' => -7.2757148, 'lng' => 109.8825928],
    'Pos I' => ['lat' => -7.261790953381199, 'lng' => 109.88517987714762],
    'Banyu Bismo' => ['lat' => -7.261247285357853, 'lng' => 109.88578710849409],
    'Kantong Semar' => ['lat' => -7.2608867, 'lng' => 109.8852702],
    'Pos II' => ['lat' => -7.257795841701307, 'lng' => 109.88617187577394],
    'Pos III' => ['lat' => -7.252985478281804, 'lng' => 109.88824678002217],
    'Tanjakan Jalak Wangi' => ['lat' => -7.2524526, 'lng' => 109.8885454],
    'Pos IV' => ['lat' => -7.2500403947923155, 'lng' => 109.88889473470591],
    'Sunrise Camp' => ['lat' => -7.24736831503517, 'lng' => 109.88807604023916],
    'Puncak Hastinapura' => ['lat' => -7.2472013510496085, 'lng' => 109.88833085009672],
    'Puncak Indraprasta' => ['lat' => -7.239103523689495, 'lng' => 109.88885808274736],
];
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
    <title>Telusur Jalur - Gunung Bismo via Deroduwur</title>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-gesture-handling/dist/leaflet-gesture-handling.min.css" />
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Outfit', sans-serif; }


        #map-container {
            position: relative;
            width: 100%;
            height: 800px;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            z-index: 1; /* Pastikan peta di bawah navbar */
        }

        /* Atau tambahkan ini untuk memastikan */
        .leaflet-control-container {
            z-index: 1;
        }
        .hero-jalur {
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

        #map-container {
            position: relative;
            width: 100%;
            height: 800px;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        #map {
            width: 100%;
            height: 100%;
            background: #f0f4f8;
        }

        .custom-marker {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .custom-marker:hover {
            transform: scale(1.3);
        }

        .marker-basecamp {
            background: #3b82f6;
            width: 28px;
            height: 28px;
        }

        .marker-puncak {
            background: #ef4444;
            width: 28px;
            height: 28px;
        }

        .marker-pos {
            background: #22c55e;
        }

        .marker-spot {
            background: #eab308;
        }

        .custom-popup .leaflet-popup-content-wrapper {
            border-radius: 12px;
            padding: 0;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .custom-popup .leaflet-popup-content {
            margin: 0;
            min-width: 200px;
            max-width: 300px;
        }

        .popup-content {
            padding: 12px 16px;
        }

        .popup-content h3 {
            font-size: 14px;
            font-weight: 700;
            color: #2F5233;
            margin-bottom: 4px;
        }

        .popup-content p {
            font-size: 12px;
            color: #5C5C50;
            margin: 2px 0;
        }

        .popup-content .popup-image {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            margin-top: 8px;
            cursor: pointer;
        }

        .popup-content .popup-image:hover {
            opacity: 0.9;
        }

        .map-controls {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .map-controls button {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(8px);
            border: none;
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
            color: #2F5233;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .map-controls button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
            background: #2F5233;
            color: white;
        }

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

        .map-legend {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(8px);
            padding: 12px 16px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            font-size: 12px;
            min-width: 140px;
        }

        .map-legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 4px 0;
        }

        .map-legend-item .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid white;
            flex-shrink: 0;
        }

        .dot-blue { background: #3b82f6; }
        .dot-red { background: #ef4444; }
        .dot-green { background: #22c55e; }
        .dot-yellow { background: #eab308; }
        .dot-route { 
            width: 20px;
            height: 4px;
            background: #ef4444;
            border-radius: 2px;
            flex-shrink: 0;
        }

        /* Poster View */
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

        @media (max-width: 768px) {
            #map-container {
                height: 500px;
            }
            .hero-jalur {
                height: 40vh;
                min-height: 300px;
            }
            .map-controls {
                bottom: 10px;
                gap: 6px;
            }
            .map-controls button {
                font-size: 10px;
                padding: 6px 12px;
            }
            .map-legend {
                top: 10px;
                right: 10px;
                padding: 8px 12px;
                font-size: 10px;
                min-width: 100px;
            }
        }

        @media (max-width: 480px) {
            #map-container {
                height: 400px;
            }
            .hero-title {
                font-size: clamp(1.8rem, 7vw, 2.2rem);
            }
            .hero-subtitle {
                font-size: clamp(0.8rem, 2.5vw, 0.95rem);
                padding: 0 15px;
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
            <h1 class="hero-title text-4xl md:text-5xl lg:text-6xl font-bold text-white hero-fade delay-1 mb-10">
                Telusur <span class="text-[#E0BE45]">Jalur Pendakian</span>
            </h1>
            <p class="hero-subtitle text-lg md:text-lx lg:text-2xl text-white/90 mb-8 leading-relaxed hero-fade delay-2 max-w-4xl mx-auto">
                Jelajahi setiap pos dan spot menarik di sepanjang jalur pendakian Gunung Bismo via Deroduwur.
            </p>
        </div>
    </div>
</section>

<!-- Visualisasi Jalur -->
<section class="py-12 bg-white">
    <div class="container mx-auto px-4 max-w-6xl">
        <div class="text-center mb-8">
            <h2 class="text-3xl md:text-4xl font-bold text-[#2F5233] text-center mb-6">Peta Interaktif Jalur Pendakian</h2>
            <p class="text-base md:text-xl lg:text-lg text-[#5C5C50] mb-8 text-center leading-relaxed">Zoom, geser, dan klik marker untuk melihat detail spot</p>
        </div>

        <!-- Toggle Button -->
        <div class="flex justify-center mb-8">
            <div class="inline-flex bg-gray-100 rounded-2xl p-1.5 shadow-md">
                <button id="toggleInteraktif" class="toggle-btn active px-6 py-3 rounded-xl font-semibold text-sm transition duration-300">
                    Peta Interaktif
                </button>
                <button id="togglePoster" class="toggle-btn px-6 py-3 rounded-xl font-semibold text-sm transition duration-300">
                    Poster
                </button>
            </div>
        </div>

        <!-- View Interaktif -->
        <div id="viewInteraktif" class="view-container active">
            <div class="bg-[#FAF7F2] rounded-2xl shadow-xl p-4 md:p-6 border border-gray-200">
                
                <div id="map-container">
                    <div id="map"></div>
                    
                    <div class="map-legend">
                        <div class="font-bold text-[#2F5233] text-xs mb-2">📌 Legenda</div>
                        <div class="map-legend-item">
                            <span class="dot dot-blue"></span>
                            <span>Basecamp</span>
                        </div>
                        <div class="map-legend-item">
                            <span class="dot dot-red"></span>
                            <span>Puncak</span>
                        </div>
                        <div class="map-legend-item">
                            <span class="dot dot-green"></span>
                            <span>Pos</span>
                        </div>
                        <div class="map-legend-item">
                            <span class="dot dot-yellow"></span>
                            <span>Spot</span>
                        </div>
                        <div class="map-legend-item">
                            <span class="dot-route"></span>
                            <span>Jalur Pendakian</span>
                        </div>
                    </div>

                    <div class="map-controls">
                        <button onclick="zoomToRoute()">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Zoom ke Jalur
                        </button>
                        <button onclick="resetMap()">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Reset Peta
                        </button>
                        <button onclick="toggleSatellite()">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Ganti Layer
                        </button>
                    </div>
                </div>
                
                <div class="text-center py-4 text-sm font-medium text-[#2F5233] border-t border-gray-200 mt-4">
                    Basecamp Deroduwur — Puncak Indraprasta (2.365 MDPL) — Total <?= count($spots) ?> Spot
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
                    <a href="<?= BASE_URL ?>assets/images/peta-jalur-bismo.jpg" download="Peta-Jalur-Gunung-Bismo-Deroduwur.jpg" 
                       class="inline-flex items-center gap-2 bg-[#E0BE45] hover:bg-[#C46F2A] text-white px-6 py-3 rounded-xl font-semibold transition duration-300 shadow-md hover:shadow-lg">
                    Download Peta
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Lightbox -->
<div id="lightboxModal" class="lightbox-modal" onclick="closeLightbox()">
    <button class="lightbox-close" onclick="closeLightbox()">&times;</button>
    <img id="lightboxImage" src="" alt="Full Size Image">
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-gesture-handling/dist/leaflet-gesture-handling.min.js"></script>

<script>



// Data koordinat spot dari PHP
const spotData = <?php 
    $data = [];
    foreach ($spots as $spot) {
        $nama = $spot['nama'];
        $coord = $spotCoordinates[$nama] ?? null;
        if ($coord) {
            $data[] = [
                'nama' => $nama,
                'lat' => $coord['lat'],
                'lng' => $coord['lng'],
                'ketinggian' => $spot['ketinggian'] ?? '',
                'estimasi_waktu' => $spot['estimasi_waktu'] ?? '',
                'deskripsi' => $spot['deskripsi'] ?? '',
                'foto' => $spot['foto'] ?? '',
                'jenis' => $spot['jenis'] ?? 'spot',
                'urutan' => $spot['urutan'] ?? 0,
            ];
        }
    }
    echo json_encode($data);
?>;

// JALUR DARI FILE CSV TERBARU (Jalur pendakian via deroduwur (3).csv)
const routeCoordinates = [
    [-7.2770562, 109.8825237],
[-7.2767872, 109.8826357],
[-7.2757148, 109.8825928],
[-7.2752581, 109.8828156],
[-7.2744509, 109.8829181],
[-7.2739613, 109.8830189],
[-7.273284, 109.883333],
[-7.2721874, 109.8837758],
[-7.2717826, 109.8840347],
[-7.2712559, 109.8842518],
[-7.2708504, 109.8842335],
[-7.2699529, 109.8842136],
[-7.2694747, 109.8843735],
[-7.2689949, 109.8846133],
[-7.2683798, 109.8848259],
[-7.2679233, 109.8850609],
[-7.2677351, 109.8851619],
[-7.2668234, 109.8852363],
[-7.266371, 109.8853584],
[-7.2661602, 109.8854075],
[-7.2659321, 109.8854507],
[-7.265703, 109.885534],
[-7.2654802, 109.8856544],
[-7.2651703, 109.8856417],
[-7.2642031, 109.8858515],
[-7.26395, 109.8858919],
[-7.2635154, 109.8858486],
[-7.2629557, 109.8856502],
[-7.2623517, 109.8858317],
[-7.2620439, 109.8857649],
[-7.2617883, 109.8858372],
[-7.2617184, 109.8858288],
[-7.2615554, 109.885934],
[-7.2614385, 109.8859219],
[-7.2613856, 109.8858831],
[-7.2613144, 109.8858947],
[-7.2612479, 109.8857937],
[-7.2611551, 109.8857844],
[-7.2612099, 109.8856485],
[-7.2614374, 109.8855811],
[-7.2618648, 109.8851879],
[-7.2615012, 109.8851486],
[-7.2611918, 109.8851911],
[-7.2608867, 109.8852702],
[-7.2605857, 109.8854572],
[-7.2602817, 109.8855851],
[-7.260116, 109.8856089],
[-7.259958, 109.8856971],
[-7.259878, 109.885847],
[-7.2595429, 109.8860754],
[-7.259127, 109.8863032],
[-7.25874, 109.8864016],
[-7.2585246, 109.8865246],
[-7.2582659, 109.8866141],
[-7.2580547, 109.8865778],
[-7.2577571, 109.8861499],
[-7.2575421, 109.8864271],
[-7.2572557, 109.8866805],
[-7.2569692, 109.8868196],
[-7.2568389, 109.8869962],
[-7.2566717, 109.8871798],
[-7.2562234, 109.8874987],
[-7.2557446, 109.8875623],
[-7.2552754, 109.8877037],
[-7.2548183, 109.8878519],
[-7.2543733, 109.8880464],
[-7.2539504, 109.8881584],
[-7.2536777, 109.8882124],
[-7.2532612, 109.8882048],
[-7.2529703, 109.8880439],
[-7.2528128, 109.8882316],
[-7.2524526, 109.8885454],
[-7.2520991, 109.8887334],
[-7.2517645, 109.8888568],
[-7.2515336, 109.8889894],
[-7.251025, 109.8889203],
[-7.2505995, 109.8889878],
[-7.2503344, 109.8890463],
[-7.2499948, 109.8887045],
[-7.2494714, 109.8883852],
[-7.2492835, 109.8883583],
[-7.2487973, 109.8885673],
[-7.2485979, 109.8886466],
[-7.2483352, 109.8885268],
[-7.2479739, 109.8882753],
[-7.2476824, 109.8880519],
[-7.2475293, 109.8880062],
[-7.2473624, 109.8880529],
[-7.2472151, 109.8882211],
[-7.2471969, 109.888359],
[-7.2471704, 109.8883005],
[-7.2471139, 109.8882996],
[-7.2470512, 109.8882994],
[-7.2469993, 109.8882954],
[-7.2469436, 109.8882712],
[-7.246891, 109.8882652],
[-7.2468618, 109.8882398],
[-7.2468333, 109.8882301],
[-7.2467554, 109.888168],
[-7.2466826, 109.8881221],
[-7.246582, 109.8880745],
[-7.2464867, 109.8880424],
[-7.2464161, 109.8880456],
[-7.2462742, 109.8880265],
[-7.2462187, 109.8880276],
[-7.2461563, 109.8880082],
[-7.2461136, 109.8880225],
[-7.2460625, 109.8879968],
[-7.245986, 109.8879923],
[-7.2459438, 109.8879654],
[-7.2458887, 109.8879664],
[-7.2458493, 109.8879389],
[-7.2458101, 109.8879458],
[-7.2457873, 109.8879275],
[-7.2457335, 109.8879189],
[-7.2457127, 109.8879351],
[-7.2456753, 109.8879154],
[-7.2456557, 109.8879197],
[-7.2456341, 109.8879195],
[-7.2455946, 109.8878918],
[-7.2455427, 109.8878852],
[-7.2454994, 109.8878618],
[-7.2454789, 109.887859],
[-7.2454024, 109.8878203],
[-7.2453638, 109.8877937],
[-7.2453179, 109.8877773],
[-7.245281, 109.8877929],
[-7.2452499, 109.8877831],
[-7.2452247, 109.8877786],
[-7.2451697, 109.8877636],
[-7.2451476, 109.8877519],
[-7.2451135, 109.8877545],
[-7.2450871, 109.8877417],
[-7.2450534, 109.887723],
[-7.2449978, 109.88774],
[-7.2449062, 109.8877304],
[-7.244841, 109.8876945],
[-7.2447944, 109.8876832],
[-7.2447388, 109.8876885],
[-7.2446649, 109.8876668],
[-7.2445776, 109.8876172],
[-7.2445174, 109.8876022],
[-7.2445032, 109.8876088],
[-7.2444561, 109.8875968],
[-7.2443882, 109.8876066],
[-7.244347, 109.8876024],
[-7.2442291, 109.8875683],
[-7.2441993, 109.8875586],
[-7.2441817, 109.887568],
[-7.2441061, 109.8875615],
[-7.2440741, 109.8875446],
[-7.2439401, 109.8875309],
[-7.2438888, 109.8875235],
[-7.2438436, 109.8875051],
[-7.2437306, 109.8875058],
[-7.243675, 109.8874991],
[-7.2436204, 109.8874982],
[-7.2434649, 109.8875127],
[-7.2434381, 109.8874935],
[-7.243384, 109.8875043],
[-7.2430779, 109.8873254],
[-7.2430176, 109.887248],
[-7.2429774, 109.8872445],
[-7.2429396, 109.8872683],
[-7.2429243, 109.8872563],
[-7.2428785, 109.8872815],
[-7.2428007, 109.887311],
[-7.2427571, 109.8873356],
[-7.242717, 109.887328],
[-7.2426746, 109.8873292],
[-7.2426404, 109.8873237],
[-7.2424686, 109.8873573],
[-7.2423698, 109.8873504],
[-7.2423131, 109.8873552],
[-7.2422286, 109.8873674],
[-7.2421666, 109.8873614],
[-7.2421216, 109.8873332],
[-7.2420679, 109.8873343],
[-7.2420566, 109.887318],
[-7.2420072, 109.887316],
[-7.2419928, 109.8872925],
[-7.2419502, 109.8873043],
[-7.2418389, 109.8873097],
[-7.2417015, 109.8873282],
[-7.2416234, 109.8873732],
[-7.2415916, 109.8873831],
[-7.2415582, 109.8873792],
[-7.2415378, 109.8873955],
[-7.2415045, 109.8874051],
[-7.2414872, 109.8874434],
[-7.2414684, 109.8874522],
[-7.2414496, 109.8874502],
[-7.2414232, 109.8874603],
[-7.2413513, 109.8874414],
[-7.2413091, 109.8874495],
[-7.2412876, 109.8874764],
[-7.2411843, 109.8874936],
[-7.2411723, 109.8875138],
[-7.2411527, 109.8875226],
[-7.2411397, 109.8875364],
[-7.2410776, 109.8875525],
[-7.2410251, 109.8875827],
[-7.2410122, 109.8876137],
[-7.2409698, 109.88764],
[-7.2409501, 109.8876785],
[-7.2408966, 109.8877124],
[-7.2408376, 109.8877484],
[-7.2407896, 109.8877802],
[-7.2407529, 109.8877834],
[-7.2407193, 109.8878141],
[-7.2406718, 109.8878339],
[-7.2406376, 109.8878378],
[-7.2406189, 109.8878543],
[-7.2405495, 109.8878723],
[-7.24052, 109.8878752],
[-7.2404922, 109.8878963],
[-7.2404543, 109.8879111],
[-7.2404039, 109.8879129],
[-7.2403596, 109.8879279],
[-7.2403195, 109.8879545],
[-7.2402658, 109.8879688],
[-7.2401991, 109.8880065],
[-7.2401253, 109.8880538],
[-7.2400351, 109.8880738],
[-7.2399793, 109.888141],
[-7.2399217, 109.8881816],
[-7.2398678, 109.8882087],
[-7.2398366, 109.8882388],
[-7.2398198, 109.888238],
[-7.23977, 109.888281],
[-7.2397512, 109.8883706],
[-7.2396966, 109.8884584],
[-7.2396493, 109.8884909],
[-7.2396226, 109.8885478],
[-7.239575, 109.8885494],
[-7.239498, 109.8885878],
[-7.2394432, 109.8885721],
[-7.2393841, 109.8886292],
[-7.2392889, 109.8886548],
[-7.2391185, 109.8888444],
];

// Inisialisasi Peta
let map;
let currentLayer = 'satellite';
let markerLayer;
let routeLayer;
let routePolyline;

const BASE_URL = '<?= BASE_URL ?>';

function initMap() {
    const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: '&copy; <a href="https://www.esri.com/">Esri</a>',
        maxZoom: 20,
        minZoom: 10,
    });

    const streetLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19,
        minZoom: 10,
    });

    map = L.map('map', {
        center: [-7.26, 109.885],
        zoom: 14,
        zoomControl: false,
        gestureHandling: true,
        layers: [satelliteLayer],
    });

    L.control.zoom({
        position: 'bottomright'
    }).addTo(map);

    map._satelliteLayer = satelliteLayer;
    map._streetLayer = streetLayer;

    addMarkers();
    addRoute();

    setTimeout(() => {
        zoomToRoute();
    }, 500);
}

function getMarkerIcon(spot) {
    const isBasecamp = spot.nama.toLowerCase().includes('basecamp');
    const isPuncak = spot.nama.toLowerCase().includes('puncak');
    const isPos = spot.nama.toLowerCase().includes('pos');
    
    let className = 'custom-marker';
    if (isBasecamp) className += ' marker-basecamp';
    else if (isPuncak) className += ' marker-puncak';
    else if (isPos) className += ' marker-pos';
    else className += ' marker-spot';
    
    return L.divIcon({
        className: className,
        iconSize: [isBasecamp || isPuncak ? 28 : 20, isBasecamp || isPuncak ? 28 : 20],
        iconAnchor: [isBasecamp || isPuncak ? 14 : 10, isBasecamp || isPuncak ? 14 : 10],
    });
}

function addMarkers() {
    markerLayer = L.layerGroup().addTo(map);

    spotData.forEach((spot, index) => {
        const icon = getMarkerIcon(spot);
        const marker = L.marker([spot.lat, spot.lng], { icon: icon });
        
        const fotoUrl = spot.foto ? `${BASE_URL}uploads/spot/${spot.foto}` : `${BASE_URL}assets/images/default-spot.jpg`;
        const fotoHtml = spot.foto ? `<img src="${fotoUrl}" alt="${spot.nama}" class="popup-image" onclick="event.stopPropagation(); openLightbox('${fotoUrl}')">` : '';
        
        const popupContent = `
            <div class="popup-content">
                <h3>${spot.nama}</h3>
                ${spot.ketinggian ? `<p>📏 ${spot.ketinggian}</p>` : ''}
                ${spot.estimasi_waktu ? `<p>⏱️ ${spot.estimasi_waktu}</p>` : ''}
                ${spot.deskripsi ? `<p class="text-gray-500 text-xs">${spot.deskripsi}</p>` : ''}
                ${fotoHtml}
            </div>
        `;
        
        marker.bindPopup(popupContent, {
            className: 'custom-popup',
            maxWidth: 300,
            minWidth: 200,
        });
        
        markerLayer.addLayer(marker);
    });
}

function addRoute() {
    routePolyline = L.polyline(routeCoordinates, {
        color: '#ef4444',
        weight: 4,
        opacity: 0.9,
        smoothFactor: 1,
        lineJoin: 'round',
    });

    const glowPolyline = L.polyline(routeCoordinates, {
        color: '#ef4444',
        weight: 10,
        opacity: 0.2,
        smoothFactor: 1,
        lineJoin: 'round',
    });

    routeLayer = L.layerGroup([glowPolyline, routePolyline]).addTo(map);
}

function zoomToRoute() {
    const bounds = L.latLngBounds(routeCoordinates);
    map.fitBounds(bounds, { 
        padding: [40, 40],
        maxZoom: 15,
    });
}

function resetMap() {
    map.setView([-7.26, 109.885], 14);
}

function toggleSatellite() {
    if (currentLayer === 'satellite') {
        map.removeLayer(map._satelliteLayer);
        map.addLayer(map._streetLayer);
        currentLayer = 'street';
    } else {
        map.removeLayer(map._streetLayer);
        map.addLayer(map._satelliteLayer);
        currentLayer = 'satellite';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    setTimeout(initMap, 300);
});

document.addEventListener('DOMContentLoaded', function() {
    const toggleInteraktif = document.getElementById('toggleInteraktif');
    const togglePoster = document.getElementById('togglePoster');
    const viewInteraktif = document.getElementById('viewInteraktif');
    const viewPoster = document.getElementById('viewPoster');

    toggleInteraktif.addEventListener('click', function() {
        toggleInteraktif.classList.add('active');
        togglePoster.classList.remove('active');
        viewInteraktif.classList.add('active');
        viewPoster.classList.remove('active');
        setTimeout(() => {
            if (map) map.invalidateSize();
        }, 300);
    });

    togglePoster.addEventListener('click', function() {
        togglePoster.classList.add('active');
        toggleInteraktif.classList.remove('active');
        viewPoster.classList.add('active');
        viewInteraktif.classList.remove('active');
    });
});

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