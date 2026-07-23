<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$galeri = getGaleri();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jejak Visual - Gunung Bismo via Deroduwur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/lightbox.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }
        .gallery-item {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }
        .gallery-item:hover {
            transform: scale(1.03);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        .filter-btn {
            transition: all 0.3s ease;
        }
        .filter-btn.active {
            background-color: #2F5233;
            color: white;
        }
    </style>
</head>
<body>

<?php include __DIR__ . '/includes/navbar.php'; ?>

<!-- Header -->
<section class="pt-32 pb-16 bg-[#FAF7F2]">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl md:text-5xl font-bold text-[#2F5233] text-center">Jejak Visual</h1>
        <p class="text-center text-[#5C5C50] mt-4 max-w-2xl mx-auto">
            Dokumentasi keindahan Gunung Bismo dan Basecamp Deroduwur
        </p>
    </div>
</section>

<!-- Filter -->
<section class="py-8 bg-white">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap justify-center gap-3">
            <button class="filter-btn active px-6 py-2 rounded-full bg-[#2F5233] text-white font-medium" data-filter="all">Semua</button>
            <button class="filter-btn px-6 py-2 rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium" data-filter="jalur">Jalur</button>
            <button class="filter-btn px-6 py-2 rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium" data-filter="ekosistem">Ekosistem</button>
            <button class="filter-btn px-6 py-2 rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium" data-filter="kegiatan">Kegiatan</button>
        </div>
    </div>
</section>

<!-- Gallery Grid -->
<section class="py-12 bg-[#FAF7F2]">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="galleryGrid">
            <?php foreach ($galeri as $foto): ?>
            <div class="gallery-item rounded-2xl overflow-hidden shadow-lg" data-category="<?= htmlspecialchars($foto['kategori']) ?>">
                <a href="<?= BASE_URL ?>uploads/galeri/<?= htmlspecialchars($foto['foto']) ?>" data-lightbox="gallery" data-title="<?= htmlspecialchars($foto['judul']) ?>">
                    <img src="<?= BASE_URL ?>uploads/galeri/<?= htmlspecialchars($foto['foto']) ?>" 
                         alt="<?= htmlspecialchars($foto['judul']) ?>" 
                         class="w-full h-72 object-cover">
                    <?php if ($foto['judul']): ?>
                    <div class="p-4 bg-white">
                        <p class="text-[#2F5233] font-semibold"><?= htmlspecialchars($foto['judul']) ?></p>
                        <?php if ($foto['deskripsi']): ?>
                        <p class="text-sm text-[#5C5C50]"><?= htmlspecialchars($foto['deskripsi']) ?></p>
                        <?php endif; ?>
                        <p class="text-xs text-[#A9784B] mt-1">Kategori: <?= ucfirst(htmlspecialchars($foto['kategori'])) ?></p>
                    </div>
                    <?php endif; ?>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

<script src="assets/js/main.js"></script>
<script src="assets/js/lightbox.js"></script>
<script>
// Filter Gallery
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        const filter = this.dataset.filter;
        document.querySelectorAll('.gallery-item').forEach(item => {
            if (filter === 'all' || item.dataset.category === filter) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
});
</script>
</body>
</html>