<?php require_once __DIR__ . '/includes/config.php'; ?>
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
        .timeline-item {
            border-left: 3px solid #2F5233;
            padding-left: 20px;
            position: relative;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -7px;
            top: 5px;
            width: 12px;
            height: 12px;
            background: #2F5233;
            border-radius: 50%;
        }
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<?php include __DIR__ . '/includes/navbar.php'; ?>

<!-- Header -->
<section class="pt-32 pb-16 bg-[#FAF7F2]">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl md:text-5xl font-bold text-[#2F5233] text-center">Telusur Jalur</h1>
        <p class="text-center text-[#5C5C50] mt-4 max-w-2xl mx-auto">
            Informasi lengkap tentang jalur pendakian Gunung Bismo via Deroduwur
        </p>
    </div>
</section>

<!-- Overview -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 max-w-4xl">
        <h2 class="text-3xl font-bold text-[#2F5233] mb-6">Overview Jalur</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-[#FAF7F2] p-6 rounded-xl text-center">
                <div class="text-3xl mb-2">⛰️</div>
                <h3 class="font-bold text-[#2F5233]">Tingkat Kesulitan</h3>
                <p class="text-[#5C5C50]">Sedang - Berat</p>
            </div>
            <div class="bg-[#FAF7F2] p-6 rounded-xl text-center">
                <div class="text-3xl mb-2">⏱️</div>
                <h3 class="font-bold text-[#2F5233]">Estimasi Waktu</h3>
                <p class="text-[#5C5C50]">6-8 Jam (PP)</p>
            </div>
            <div class="bg-[#FAF7F2] p-6 rounded-xl text-center">
                <div class="text-3xl mb-2">📏</div>
                <h3 class="font-bold text-[#2F5233]">Ketinggian Puncak</h3>
                <p class="text-[#5C5C50]">2.365 MDPL (Indraprasta)</p>
            </div>
        </div>
        <p class="text-[#5C5C50]">
            Jalur pendakian Gunung Bismo via Deroduwur menawarkan pengalaman mendaki yang menantang namun memuaskan. 
            Dengan pemandangan alam yang masih asri dan udara yang segar, jalur ini cocok untuk pendaki dengan tingkat 
            kebugaran sedang hingga berat.
        </p>
    </div>
</section>

<!-- Rincian Pos -->
<section class="py-16 bg-[#FAF7F2]">
    <div class="container mx-auto px-4 max-w-4xl">
        <h2 class="text-3xl font-bold text-[#2F5233] mb-6">Rincian Pos-pos Pendakian</h2>
        <div class="space-y-4">
            <div class="timeline-item bg-white p-6 rounded-xl shadow-lg">
                <h3 class="font-bold text-[#2F5233]">Base Camp</h3>
                <p class="text-sm text-[#A9784B]">Titik awal pendakian</p>
                <p class="text-[#5C5C50]">Area parkir dan registrasi</p>
            </div>
            <div class="timeline-item bg-white p-6 rounded-xl shadow-lg">
                <h3 class="font-bold text-[#2F5233]">Pos Ojek / Gerbang Batas Hutan</h3>
                <p class="text-sm text-[#A9784B]">Akses transportasi ojek</p>
                <p class="text-[#5C5C50]">Titik akhir kendaraan bermotor</p>
            </div>
            <div class="timeline-item bg-white p-6 rounded-xl shadow-lg">
                <h3 class="font-bold text-[#2F5233]">Pos I</h3>
                <p class="text-sm text-[#A9784B]">Ketinggian: 1.555 MDPL | 75 menit</p>
                <p class="text-[#5C5C50]">Area peristirahatan pertama</p>
            </div>
            <div class="timeline-item bg-white p-6 rounded-xl shadow-lg">
                <h3 class="font-bold text-[#2F5233]">Pos II — Kumbang Alang-alang</h3>
                <p class="text-sm text-[#A9784B]">Ketinggian: 1.765 MDPL | 45 menit</p>
                <p class="text-[#5C5C50]">Melewati area alang-alang yang luas</p>
            </div>
            <div class="timeline-item bg-white p-6 rounded-xl shadow-lg">
                <h3 class="font-bold text-[#2F5233]">Pos III (Camp Area)</h3>
                <p class="text-sm text-[#A9784B]">Ketinggian: 1.991 MDPL | 60 menit</p>
                <p class="text-[#5C5C50]">Area perkemahan dengan pemandangan indah</p>
            </div>
            <div class="timeline-item bg-white p-6 rounded-xl shadow-lg">
                <h3 class="font-bold text-[#2F5233]">Pos IV (Camp Area)</h3>
                <p class="text-sm text-[#A9784B]">Ketinggian: 2.204 MDPL | 60 menit</p>
                <p class="text-[#5C5C50]">Area perkemahan terakhir sebelum puncak</p>
            </div>
            <div class="timeline-item bg-white p-6 rounded-xl shadow-lg">
                <h3 class="font-bold text-[#2F5233]">Sunrise Camp</h3>
                <p class="text-sm text-[#A9784B]">Spot utama menikmati matahari terbit</p>
                <p class="text-[#5C5C50]">Tempat terbaik untuk melihat sunrise</p>
            </div>
            <div class="timeline-item bg-white p-6 rounded-xl shadow-lg border-l-4 border-[#E0BE45]">
                <h3 class="font-bold text-[#2F5233]">🏔️ Puncak Indraprasta / Hastinapura</h3>
                <p class="text-sm text-[#A9784B]">Ketinggian: 2.338 - 2.365 MDPL | 20 menit</p>
                <p class="text-[#5C5C50]">Puncak dengan panorama 360 derajat</p>
            </div>
        </div>
    </div>
</section>

<!-- Spot Menarik -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 max-w-4xl">
        <h2 class="text-3xl font-bold text-[#2F5233] mb-6">Spot Menarik di Sepanjang Jalur</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-[#FAF7F2] rounded-xl overflow-hidden shadow-lg card-hover">
                <img src="assets/images/spot-banyu-bismo.jpg" alt="Banyu Bismo" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h4 class="font-bold text-[#2F5233]">💧 Banyu Bismo</h4>
                    <p class="text-sm text-[#5C5C50]">Sumber air di jalur (dekat Pos I & antara Pos II-III)</p>
                    <a href="<?= BASE_URL ?>galeri.php?tag=banyu-bismo" class="text-xs text-[#2F5233] font-semibold hover:text-[#4A7A4E] transition duration-300">Lihat foto lainnya →</a>
                </div>
            </div>
            <div class="bg-[#FAF7F2] rounded-xl overflow-hidden shadow-lg card-hover">
                <img src="assets/images/spot-kantong-semar.jpg" alt="Kantong Semar" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h4 class="font-bold text-[#2F5233]">🌱 Kantong Semar</h4>
                    <p class="text-sm text-[#5C5C50]">Spot tanaman kantong semar liar (dekat Pos I)</p>
                    <a href="<?= BASE_URL ?>alam.php" class="text-xs text-[#2F5233] font-semibold hover:text-[#4A7A4E] transition duration-300">Lihat di Alam Bismo →</a>
                </div>
            </div>
            <div class="bg-[#FAF7F2] rounded-xl overflow-hidden shadow-lg card-hover">
                <img src="assets/images/spot-hutan-pakis.jpg" alt="Hutan Pakis" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h4 class="font-bold text-[#2F5233]">🌿 Hutan Pakis</h4>
                    <p class="text-sm text-[#5C5C50]">Kawasan hutan pakis sebelum Pos I</p>
                    <a href="<?= BASE_URL ?>galeri.php?tag=hutan-pakis" class="text-xs text-[#2F5233] font-semibold hover:text-[#4A7A4E] transition duration-300">Lihat foto lainnya →</a>
                </div>
            </div>
            <div class="bg-[#FAF7F2] rounded-xl overflow-hidden shadow-lg card-hover">
                <img src="assets/images/spot-tanjakan.jpg" alt="Tanjakan Jalak Wangi" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h4 class="font-bold text-[#2F5233]">⛰️ Tanjakan Jalak Wangi</h4>
                    <p class="text-sm text-[#5C5C50]">Titik tanjakan menantang sebelum Pos III</p>
                    <a href="<?= BASE_URL ?>galeri.php?tag=tanjakan" class="text-xs text-[#2F5233] font-semibold hover:text-[#4A7A4E] transition duration-300">Lihat foto lainnya →</a>
                </div>
            </div>
            <div class="bg-[#FAF7F2] rounded-xl overflow-hidden shadow-lg card-hover md:col-span-2">
                <img src="assets/images/spot-sunrise.jpg" alt="Sunrise Camp" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h4 class="font-bold text-[#2F5233]">🌅 Sunrise Camp</h4>
                    <p class="text-sm text-[#5C5C50]">Spot utama menikmati matahari terbit sebelum puncak</p>
                    <a href="<?= BASE_URL ?>galeri.php?tag=sunrise" class="text-xs text-[#2F5233] font-semibold hover:text-[#4A7A4E] transition duration-300">Lihat foto lainnya →</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Peta Jalur -->
<section class="py-16 bg-[#FAF7F2]">
    <div class="container mx-auto px-4 max-w-4xl">
        <h2 class="text-3xl font-bold text-[#2F5233] mb-6">Peta Jalur Pendakian</h2>
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <img src="assets/images/peta-jalur.jpg" alt="Peta Jalur Pendakian Gunung Bismo" class="w-full">
            <div class="p-4 text-center text-[#5C5C50] text-sm">
                Peta jalur pendakian Gunung Bismo via Deroduwur
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-16 bg-[#2F5233] text-white">
    <div class="container mx-auto px-4 text-center max-w-4xl">
        <h2 class="text-3xl font-bold mb-4">Butuh Informasi Lebih Lanjut?</h2>
        <p class="text-xl mb-8 text-white/90">Hubungi kami untuk informasi detail tentang jalur pendakian</p>
        <a href="https://wa.me/6281234567890" target="_blank" class="bg-[#E0BE45] hover:bg-[#C46F2A] text-white px-8 py-4 rounded-full font-semibold transition duration-300 transform hover:scale-105 inline-block">
            Hubungi via WhatsApp
        </a>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
<script src="assets/js/main.js"></script>
</body>
</html>