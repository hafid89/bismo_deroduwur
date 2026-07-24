<?php 
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

// Ambil data dari database
$flora_list = getFlora();
$fauna_list = getFauna();
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
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .card-hover img {
            transition: transform 0.3s ease;
        }
        .card-hover:hover img {
            transform: scale(1.05);
        }
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #5C5C50;
        }
    </style>
</head>
<body>

<?php include __DIR__ . '/includes/navbar.php'; ?>

<!-- Header -->
<section class="pt-32 pb-16 bg-[#FAF7F2]">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl md:text-5xl font-bold text-[#2F5233] text-center">Alam Bismo</h1>
        <p class="text-center text-[#5C5C50] mt-4 max-w-2xl mx-auto">
            Keanekaragaman flora dan fauna di jalur pendakian Gunung Bismo
        </p>
    </div>
</section>

<!-- Intro -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="bg-[#FAF7F2] p-8 rounded-xl text-center">
            <h2 class="text-2xl font-bold text-[#2F5233] mb-4">Ekosistem Istimewa Deroduwur</h2>
            <p class="text-[#5C5C50] leading-relaxed">
                Jalur pendakian Gunung Bismo via Deroduwur memiliki keanekaragaman hayati yang luar biasa. 
                Dari flora endemik hingga fauna langka, setiap langkah di jalur ini menawarkan kesempatan 
                untuk menyaksikan keindahan alam yang masih terjaga. Keistimewaan ekosistem ini menjadi 
                salah satu daya tarik utama bagi para pendaki dan pecinta alam.
            </p>
        </div>
    </div>
</section>

<!-- Flora Dinamis -->
<section class="py-16 bg-[#FAF7F2]">
    <div class="container mx-auto px-4 max-w-4xl">
        <h2 class="text-3xl font-bold text-[#2F5233] mb-6">🌿 Flora</h2>
        
        <?php if (empty($flora_list)): ?>
        <div class="empty-state bg-white rounded-xl shadow-lg">
            <p class="text-lg">Belum ada data flora</p>
            <p class="text-sm text-gray-400 mt-1">Silakan tambahkan melalui admin panel</p>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php foreach ($flora_list as $flora): ?>
            <div class="bg-white rounded-xl overflow-hidden shadow-lg card-hover">
                <?php if (!empty($flora['foto']) && file_exists(UPLOAD_PATH . 'flora/' . $flora['foto'])): ?>
                <img src="<?= BASE_URL ?>uploads/flora/<?= htmlspecialchars($flora['foto']) ?>" 
                     alt="<?= htmlspecialchars($flora['nama']) ?>" 
                     class="w-full h-48 object-cover">
                <?php else: ?>
                <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-400">
                    <span>🌿</span>
                </div>
                <?php endif; ?>
                <div class="p-4">
                    <h4 class="font-bold text-[#2F5233]"><?= htmlspecialchars($flora['nama']) ?></h4>
                    <?php if (!empty($flora['nama_ilmiah'])): ?>
                    <p class="text-sm text-[#A9784B]"><?= htmlspecialchars($flora['nama_ilmiah']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($flora['deskripsi'])): ?>
                    <p class="text-sm text-[#5C5C50] mt-1"><?= htmlspecialchars($flora['deskripsi']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($flora['lokasi'])): ?>
                    <p class="text-xs text-[#A9784B] mt-2">📍 <?= htmlspecialchars($flora['lokasi']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Fauna Dinamis -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 max-w-4xl">
        <h2 class="text-3xl font-bold text-[#2F5233] mb-6">🐾 Fauna</h2>
        
        <?php if (empty($fauna_list)): ?>
        <div class="empty-state bg-[#FAF7F2] rounded-xl shadow-lg">
            <p class="text-lg">Belum ada data fauna</p>
            <p class="text-sm text-gray-400 mt-1">Silakan tambahkan melalui admin panel</p>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php foreach ($fauna_list as $fauna): ?>
            <div class="bg-[#FAF7F2] rounded-xl overflow-hidden shadow-lg card-hover">
                <?php if (!empty($fauna['foto']) && file_exists(UPLOAD_PATH . 'fauna/' . $fauna['foto'])): ?>
                <img src="<?= BASE_URL ?>uploads/fauna/<?= htmlspecialchars($fauna['foto']) ?>" 
                     alt="<?= htmlspecialchars($fauna['nama']) ?>" 
                     class="w-full h-48 object-cover">
                <?php else: ?>
                <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-400">
                    <span>🐾</span>
                </div>
                <?php endif; ?>
                <div class="p-4">
                    <h4 class="font-bold text-[#2F5233]"><?= htmlspecialchars($fauna['nama']) ?></h4>
                    <?php if (!empty($fauna['nama_ilmiah'])): ?>
                    <p class="text-sm text-[#A9784B]"><?= htmlspecialchars($fauna['nama_ilmiah']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($fauna['deskripsi'])): ?>
                    <p class="text-sm text-[#5C5C50] mt-1"><?= htmlspecialchars($fauna['deskripsi']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($fauna['lokasi'])): ?>
                    <p class="text-xs text-[#A9784B] mt-2">📍 <?= htmlspecialchars($fauna['lokasi']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Pesan Kelestarian -->
<section class="py-16 bg-[#2F5233] text-white">
    <div class="container mx-auto px-4 max-w-4xl text-center">
        <h2 class="text-3xl font-bold mb-6">Jaga Kelestarian Alam</h2>
        <p class="text-xl mb-6 text-white/90">
            "Bawa Turun Kembali Sampah Anda"
        </p>
        <p class="text-white/80 max-w-2xl mx-auto">
            Setiap langkah kita di Gunung Bismo adalah tanggung jawab untuk menjaga keindahan alam ini 
            tetap lestari. Mari kita bersama-sama melestarikan flora dan fauna untuk generasi mendatang.
        </p>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
<script src="assets/js/main.js"></script>
</body>
</html>