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
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }

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

        /* SVG Trail Container */
        .trail-svg-container {
            position: relative;
            width: 100%;
            max-width: 650px;
            margin: 0 auto;
            padding: 40px 0 80px 0;
        }

        #trail-svg {
            width: 100%;
            height: auto;
            overflow: visible;
        }

        .path-bg {
            stroke: #CBD5E1;
            stroke-width: 6;
            stroke-linecap: round;
            stroke-linejoin: round;
            fill: none;
        }

        .path-active {
            stroke: #ef4444; /* Warna Merah Rute */
            stroke-width: 6;
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

        /* Pin Dot */
        .pin-dot {
            width: 20px;
            height: 20px;
            background: #22c55e;
            border: 3px solid #ffffff;
            border-radius: 50%;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        .spot-pin:hover .pin-dot, .spot-pin.expanded .pin-dot {
            transform: scale(1.3);
            background: #eab308;
        }

        .pin-dot.puncak {
            background: #ef4444;
            width: 26px;
            height: 26px;
            border-color: #fef08a;
        }

        /* Kartu Info Ringkas & Fleksibel (Anti-Tabrakan) */
        .spot-card {
            position: absolute;
            width: 210px;
            max-width: 65vw;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(8px);
            border-radius: 12px;
            padding: 8px 10px;
            box-shadow: 0 8px 20px -4px rgba(0, 0, 0, 0.15);
            border-left: 4px solid #2F5233;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }

        /* Posisi Kiri / Kanan */
        .spot-pin.pos-left .spot-card { 
            right: 30px; 
            top: 50%;
            transform: translateY(-50%) translateX(-10px);
        }

        .spot-pin.pos-right .spot-card { 
            left: 30px; 
            top: 50%;
            transform: translateY(-50%) translateX(10px);
        }

        .spot-pin.active .spot-card {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(-50%) translateX(0);
        }

        /* Bagian Detail Foto & Deskripsi (Hidden by default, Expand on hover/click) */
        .card-details {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: all 0.3s ease-in-out;
        }

        /* Saat di-hover atau di-klik (Expanded), Tampilkan Detail Lengkap */
        .spot-pin:hover .spot-card, 
        .spot-pin.expanded .spot-card {
            width: 240px;
            box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.25);
            z-index: 100 !important;
        }

        .spot-pin:hover .card-details,
        .spot-pin.expanded .card-details {
            max-height: 250px;
            opacity: 1;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px dashed #e2e8f0;
        }

        /* Container Foto */
        .spot-img-container {
            width: 100%;
            height: 100px;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 6px;
            background-color: #e2e8f0;
        }

        .spot-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Custom Scrollbar */
        #modalScrollBody::-webkit-scrollbar { width: 8px; }
        #modalScrollBody::-webkit-scrollbar-track { background: #F1F5F9; }
        #modalScrollBody::-webkit-scrollbar-thumb { background: #2F5233; border-radius: 4px; }
    </style>
</head>
<body class="bg-[#FAF7F2]">

<?php include __DIR__ . '/includes/navbar.php'; ?>

<!-- Header Sampul Utama -->
<section class="pt-32 pb-16">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-emerald-900/10">
            
            <!-- Kontainer Foto Sampul (Menyesuaikan Ukuran Asli Gambar) -->
            <div class="relative w-full bg-slate-900 flex items-center justify-center overflow-hidden">
                <!-- Tag Img Asli tanpa Crop -->
                <img 
                    src="assets/images/peta-jalur-bismo.jpg" 
                    alt="Peta Jalur Pendakian Gunung Bismo" 
                    class="w-full h-auto object-contain max-h-[70vh] block"
                >
                
                <!-- Badge Overlay di Pojok Kiri Atas -->
                <div class="absolute top-4 left-4">
                    <span class="bg-[#E0BE45] text-[#2F5233] text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider shadow-md">
                        Peta Interaktif
                    </span>
                </div>
            </div>
            
            <!-- Informasi & Tombol Aksi -->
            <div class="p-6 md:p-8">
                <h1 class="text-2xl md:text-4xl font-bold text-[#2F5233] mb-3">Eksplorasi Jalur Pendakian Gunung Bismo</h1>
                <p class="text-[#5C5C50] text-sm md:text-base leading-relaxed mb-6">
                    Lihat simulasi rute pendakian dari puncak hingga basecamp lengkap dengan foto dan informasi pos perhentian.
                </p>
                
                <button id="openModalBtn" class="w-full md:w-auto bg-[#2F5233] hover:bg-[#4A7A4E] text-white font-semibold px-8 py-4 rounded-xl shadow-lg transition duration-300 flex items-center justify-center gap-3">
                    <span>🗺️ Buka Visualisasi Jalur</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </button>
            </div>

        </div>
    </div>
</section>

<!-- MODAL POP-UP -->
<div id="trailModal" class="modal-overlay fixed inset-0 z-50 bg-black/70 flex items-center justify-center p-2 md:p-6">
    <div class="modal-content bg-[#FAF7F2] w-full max-w-3xl h-[90vh] rounded-2xl shadow-2xl flex flex-col overflow-hidden relative">
        
        <!-- Header Modal -->
        <div class="p-4 md:p-5 bg-white border-b border-gray-200 flex justify-between items-center z-30">
            <div>
                <h3 class="text-lg md:text-xl font-bold text-[#2F5233]">Visualisasi Jalur Pendakian</h3>
                <p class="text-xs text-gray-500">Scroll ke bawah untuk melihat jalur. Klik/Hover kartu untuk detail.</p>
            </div>
            <button id="closeModalBtn" class="w-10 h-10 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-full flex items-center justify-center font-bold text-xl transition">
                ✕
            </button>
        </div>

        <!-- Body Scrollable Modal -->
        <div id="modalScrollBody" class="flex-1 overflow-y-auto relative p-4">
            <div class="text-center py-2.5 text-xs font-semibold text-emerald-800 bg-emerald-50 rounded-lg mb-4 border border-emerald-200">
                👇 Scroll ke bawah untuk simulasi. Ketuk/Arahkan kursor ke spot untuk membuka foto & penjelasan.
            </div>

            <div class="trail-svg-container" id="trailContainer">
                
                <!-- SVG Canvas: Tinggi Ditingkatkan ke 1600 ViewBox agar Jalur Sangat Lega -->
                <svg id="trail-svg" viewBox="0 0 500 1600" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path class="path-bg" d="M 210 100 L 290 180 L 210 280 L 295 380 L 205 500 L 290 620 L 210 750 L 290 890 L 205 1030 L 290 1180 L 210 1330 L 250 1500" />
                    <path id="route-path" class="path-active" d="M 210 100 L 290 180 L 210 280 L 295 380 L 205 500 L 290 620 L 210 750 L 290 890 L 205 1030 L 290 1180 L 210 1330 L 250 1500" />
                </svg>

                <!-- Mapping Spot Koordinat Sesuai Urutan Database (Jarak Y Diregangkan Sangat Aman) -->
                <?php 
                $coordsPeta = [
                    ['x' => 250, 'y' => 1500, 'side' => 'right'], // Basecamp (y=1500)
                    ['x' => 210, 'y' => 1330, 'side' => 'left'],  // Pos Ojek
                    ['x' => 290, 'y' => 1180, 'side' => 'right'], // Batas Hutan
                    ['x' => 205, 'y' => 1030, 'side' => 'left'],  // POS I
                    ['x' => 290, 'y' => 890,  'side' => 'right'], // Hutan Pakis / Kantong Semar
                    ['x' => 210, 'y' => 750,  'side' => 'left'],  // POS II
                    ['x' => 290, 'y' => 620,  'side' => 'right'], // Tanjakan Jalak Wangi
                    ['x' => 205, 'y' => 500,  'side' => 'left'],  // POS III
                    ['x' => 295, 'y' => 380,  'side' => 'right'], // POS IV
                    ['x' => 210, 'y' => 280,  'side' => 'left'],  // Sunrise Camp
                    ['x' => 290, 'y' => 180,  'side' => 'right'], // Puncak Hastinapura
                    ['x' => 210, 'y' => 100,  'side' => 'left'],  // Puncak Indraprasta (y=100)
                ];

                foreach ($spots as $index => $spot): 
                    $coord = $coordsPeta[$index] ?? ['x' => 250, 'y' => 1500 - ($index * 120), 'side' => ($index % 2 == 0 ? 'right' : 'left')];
                    $posX = ($coord['x'] / 500) * 100;
                    $posY = ($coord['y'] / 1600) * 100;
                    
                    $spotProgress = $coord['y'] / 1600;
                    $zIndex = 30 - $index;

                    $isPuncak = strpos(strtolower($spot['nama']), 'puncak') !== false;
                    $isFlora = isset($spot['jenis']) && $spot['jenis'] == 'flora';
                    $isFauna = isset($spot['jenis']) && $spot['jenis'] == 'fauna';

                    $fotoUrl = !empty($spot['foto']) && file_exists(__DIR__ . '/assets/images/spots/' . $spot['foto']) 
                        ? 'assets/images/spots/' . $spot['foto'] 
                        : 'assets/images/default-spot.jpg';
                ?>
                <div class="spot-pin pos-<?= $coord['side'] ?>" 
                     style="left: <?= $posX ?>%; top: <?= $posY ?>%; z-index: <?= $zIndex ?>;" 
                     data-progress="<?= $spotProgress ?>">
                     
                    <div class="pin-dot <?= $isPuncak ? 'puncak' : '' ?>"></div>

                    <div class="spot-card">
                        <!-- HEADER RINGKAS (Selalu Terlihat saat Active) -->
                        <div class="flex items-center justify-between gap-1">
                            <h4 class="font-bold text-[#2F5233] text-xs md:text-sm leading-tight truncate"><?= htmlspecialchars($spot['nama']) ?></h4>
                            <span class="text-xs flex-shrink-0"><?= $isPuncak ? '🏔️' : ($isFlora ? '🌿' : ($isFauna ? '🐾' : '📍')) ?></span>
                        </div>
                        
                        <?php if (!empty($spot['ketinggian'])): ?>
                            <p class="text-[10px] text-[#A9784B] font-medium leading-tight mt-0.5">📍 <?= htmlspecialchars($spot['ketinggian']) ?></p>
                        <?php endif; ?>

                        <!-- DETAIL EXPANDABLE (Foto & Penjelasan Muncul Saat Di-hover / Di-klik) -->
                        <div class="card-details">
                            <div class="spot-img-container">
                                <img src="<?= htmlspecialchars($fotoUrl) ?>" alt="<?= htmlspecialchars($spot['nama']) ?>" loading="lazy">
                            </div>

                            <?php if (!empty($spot['estimasi_waktu'])): ?>
                                <p class="text-[10px] text-gray-500 font-medium">⏱️ <?= htmlspecialchars($spot['estimasi_waktu']) ?></p>
                            <?php endif; ?>
                            
                            <?php if (!empty($spot['deskripsi'])): ?>
                                <p class="text-[11px] text-gray-600 mt-1 leading-snug line-clamp-3"><?= htmlspecialchars($spot['deskripsi']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>
            
            <div class="text-center py-6 text-sm font-bold text-[#2F5233]">
                🏕️ Basecamp Pendakian Gunung Bismo
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

<!-- Script Animasi Scroll & Fitur Klik Expand -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('trailModal');
    const openBtn = document.getElementById('openModalBtn');
    const closeBtn = document.getElementById('closeModalBtn');
    const modalScrollBody = document.getElementById('modalScrollBody');
    
    const path = document.getElementById('route-path');
    const pins = document.querySelectorAll('.spot-pin');

    const pathLength = path.getTotalLength();
    
    path.style.strokeDasharray = pathLength;
    path.style.strokeDashoffset = pathLength;

    function updateTrailAnimation() {
        const scrollTop = modalScrollBody.scrollTop;
        const scrollHeight = modalScrollBody.scrollHeight - modalScrollBody.clientHeight;
        
        let progress = scrollHeight > 0 ? (scrollTop / scrollHeight) : 0;
        progress = Math.max(0, Math.min(1, progress));

        const drawLength = pathLength * progress;
        path.style.strokeDashoffset = pathLength - drawLength;

        pins.forEach(pin => {
            const pinProgress = parseFloat(pin.getAttribute('data-progress'));
            if (progress >= pinProgress - 0.03) {
                pin.classList.add('active');
            } else {
                pin.classList.remove('active');
                pin.classList.remove('expanded');
            }
        });
    }

    // Fitur Klik Pin / Kartu untuk Toggle Expand di Perangkat Mobile/Touchscreen
    pins.forEach(pin => {
        pin.addEventListener('click', function(e) {
            e.stopPropagation();
            pins.forEach(p => { if (p !== pin) p.classList.remove('expanded'); });
            pin.classList.toggle('expanded');
        });
    });

    // Buka Modal
    openBtn.addEventListener('click', function() {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        modalScrollBody.scrollTop = 0;
        setTimeout(updateTrailAnimation, 200);
    });

    // Tutup Modal
    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
        pins.forEach(p => p.classList.remove('expanded'));
    }

    closeBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
    });

    modalScrollBody.addEventListener('scroll', updateTrailAnimation);
});
</script>

</body>
</html>
