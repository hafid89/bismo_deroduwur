<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$type = isset($_GET['type']) ? $_GET['type'] : '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Validasi tipe
if (!in_array($type, ['flora', 'fauna'])) {
    header('Location: ' . BASE_URL . 'alam.php');
    exit();
}

// Ambil data berdasarkan tipe
$table = $type === 'flora' ? 'flora' : 'fauna';
$stmt = $pdo->prepare("SELECT * FROM $table WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch();

if (!$item) {
    header('Location: ' . BASE_URL . 'alam.php');
    exit();
}

// Get related items (same type, different id)
$stmt = $pdo->prepare("SELECT * FROM $table WHERE id != ? ORDER BY created_at DESC LIMIT 3");
$stmt->execute([$id]);
$related = $stmt->fetchAll();

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
    <title><?= htmlspecialchars($item['nama']) ?> - Alam Bismo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Outfit', sans-serif; }

        /* Card Hover */
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        /* Content Styling */
        .content-text {
            line-height: 1.8;
            color: #5C5C50;
        }

        .content-text p {
            margin-bottom: 1rem;
            line-height: 1.8;
            color: #5C5C50;
        }

        .content-text h2, .content-text h3, .content-text h4 {
            color: #2F5233;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
            font-weight: 600;
        }

        .content-text ul, .content-text ol {
            margin-bottom: 1rem;
            padding-left: 1.5rem;
            color: #5C5C50;
        }

        .content-text li {
            margin-bottom: 0.5rem;
        }

        /* Badge */
        .badge-category {
            display: inline-block;
            padding: 6px 20px;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 600;
            background: #2F5233;
            color: white;
        }

        .badge-category.flora {
            background: #2F5233;
        }

        .badge-category.fauna {
            background: #A9784B;
        }

        /* Lightbox */
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

        .main-image {
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .main-image:hover {
            transform: scale(1.01);
        }

        @media (max-width: 768px) {
            .hero-detail {
                height: 50vh;
                min-height: 300px;
            }
            .hero-title {
                font-size: clamp(1.5rem, 5vw, 2.2rem);
            }
            .lightbox-close {
                top: 20px;
                right: 20px;
                font-size: 30px;
            }
        }

        @media (max-width: 480px) {
            .hero-detail {
                height: 40vh;
                min-height: 250px;
            }
        }
    </style>
</head>
<body>

<?php include __DIR__ . '/includes/navbar.php'; ?>

<!-- Detail Content -->
<section class="pt-12 pb-16 bg-[#FAF7F2]">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            
            <!-- Gambar Utama -->
            <?php 
            $imagePath = !empty($item['foto']) && file_exists(UPLOAD_PATH . $type . '/' . $item['foto']) 
                ? BASE_URL . 'uploads/' . $type . '/' . $item['foto'] 
                : BASE_URL . 'assets/images/default-' . $type . '.jpg';
            ?>
            <div class="relative">
                <img src="<?= $imagePath ?>" 
                     alt="<?= htmlspecialchars($item['nama']) ?>" 
                     class="w-full h-[400px] object-cover main-image"
                     onclick="openLightbox('<?= $imagePath ?>')">
                <div class="absolute bottom-4 right-4 bg-black/50 backdrop-blur-sm text-white text-xs px-3 py-1.5 rounded-full">
                    🔍 Klik gambar untuk memperbesar
                </div>
            </div>

            <div class="p-8 md:p-12">
                <!-- Title and Header Info -->
                <h1 class="text-4xl md:text-5xl font-bold text-[#2F5233] mb-3">
                    <?= htmlspecialchars($item['nama']) ?>
                </h1>

                <?php if (!empty($item['nama_ilmiah'])): ?>
                <p class="text-xl text-[#A9784B] italic mb-4">
                    <?= htmlspecialchars($item['nama_ilmiah']) ?>
                </p>
                <?php endif; ?>

                <div class="flex flex-wrap items-center gap-4 mb-4">
                    <?php if (!empty($item['lokasi'])): ?>
                    <span class="text-sm text-[#A9784B]">
                        📍 <?= htmlspecialchars($item['lokasi']) ?>
                    </span>
                    <?php endif; ?>
                </div>

                <!-- Deskripsi Lengkap -->
                <div class="content-text mt-6">
                    <?php if (!empty($item['deskripsi'])): ?>
                        <?= nl2br(htmlspecialchars($item['deskripsi'])) ?>
                    <?php else: ?>
                        <p class="text-[#5C5C50] italic">Deskripsi belum tersedia untuk <?= htmlspecialchars($item['nama']) ?>.</p>
                    <?php endif; ?>
                </div>

                <!-- Tombol Kembali -->
                <div class="mt-8 pt-8 border-t border-gray-200 flex flex-wrap gap-4">
                    <a href="<?= BASE_URL ?>alam.php#<?= $type ?>" class="inline-block bg-[#2F5233] hover:bg-[#4A7A4E] text-white px-6 py-2.5 rounded-full transition duration-300">
                        ← Kembali ke Alam Bismo
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Items -->
<?php if ($related): ?>
<section class="py-10 bg-[#FAF7F2]">
    <div class="container mx-auto px-4 max-w-6xl">
        <h2 class="text-2xl md:text-3xl font-bold text-[#2F5233] text-center mb-10">
        <?= ucfirst($type) ?> Lainnya
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php foreach ($related as $item): ?>
            <div class="bg-white rounded-2xl overflow-hidden shadow-lg card-hover">
                <?php 
                $relImagePath = !empty($item['foto']) && file_exists(UPLOAD_PATH . $type . '/' . $item['foto']) 
                    ? BASE_URL . 'uploads/' . $type . '/' . $item['foto'] 
                    : BASE_URL . 'assets/images/default-' . $type . '.jpg';
                ?>
                <img src="<?= $relImagePath ?>" 
                     alt="<?= htmlspecialchars($item['nama']) ?>" 
                     class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-[#2F5233] mb-1"><?= htmlspecialchars($item['nama']) ?></h3>
                    <?php if (!empty($item['nama_ilmiah'])): ?>
                    <p class="text-sm text-[#A9784B] italic mb-2"><?= htmlspecialchars($item['nama_ilmiah']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($item['lokasi'])): ?>
                    <p class="text-xs text-[#A9784B] mb-3">📍 <?= htmlspecialchars($item['lokasi']) ?></p>
                    <?php endif; ?>
                    <a href="<?= BASE_URL ?>alam-detail.php?type=<?= $type ?>&id=<?= $item['id'] ?>" class="text-[#2F5233] font-semibold hover:text-[#4A7A4E] transition duration-300">
                        Baca Selengkapnya →
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA Kelestarian -->
<section class="py-10 bg-[#2F5233] text-white">
    <div class="container mx-auto px-4 text-center max-w-3xl">
        <div class="text-4xl mb-4">🌳</div>
        <h2 class="text-2xl md:text-3xl font-bold mb-4">Jaga Kelestarian Alam</h2>
        <p class="text-white/90 text-lg mb-6">
            "Bawa Turun Kembali Sampah Anda" — Lindungi flora dan fauna Gunung Bismo untuk generasi mendatang.
        </p>
        <a href="<?= BASE_URL ?>alam.php" class="inline-block bg-[#E0BE45] hover:bg-[#C46F2A] text-white px-8 py-3 rounded-full font-semibold transition duration-300">
            Jelajahi Alam Bismo Lainnya →
        </a>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

<!-- Lightbox Modal -->
<div id="lightboxModal" class="lightbox-modal" onclick="closeLightbox()">
    <button class="lightbox-close" onclick="closeLightbox()">&times;</button>
    <img id="lightboxImage" src="" alt="Full Size Image">
</div>

<script>
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