<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$berita_terbaru = getBeritaTerbaru(3);
$galeri_preview = getGaleri(6);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gunung Bismo via Deroduwur - Basecamp Pendakian</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }
        .hero-gradient {
            background: linear-gradient(135deg, rgba(47,82,51,0.9) 0%, rgba(74,122,78,0.7) 100%);
        }
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
    </style>
</head>
<body>

<?php include __DIR__ . '/includes/navbar.php'; ?>

<!-- Hero Section -->
<section class="relative min-h-screen flex items-center bg-cover bg-center" style="background-image: url('assets/images/hero-bg.jpg');">
    <div class="hero-gradient absolute inset-0"></div>
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-3xl">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 animate-float">
                Jalur Asri, Belum Banyak Terjamah
            </h1>
            <p class="text-xl text-white/90 mb-8">
                Temukan pengalaman mendaki yang autentik di Gunung Bismo melalui Basecamp Deroduwur.
                Nikmati keindahan alam yang masih terjaga dan ekosistem yang lestari.
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="<?= BASE_URL ?>jalur.php" class="bg-[#E0BE45] hover:bg-[#C46F2A] text-white px-8 py-4 rounded-full font-semibold transition duration-300 transform hover:scale-105">
                    Telusur Jalur Pendakian
                </a>
                <a href="https://wa.me/6281234567890" target="_blank" class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-8 py-4 rounded-full font-semibold transition duration-300 border-2 border-white">
                    Hubungi Kami
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Sekilas Basecamp -->
<section class="py-20 bg-[#FAF7F2]">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-[#2F5233] mb-6">Sekilas Basecamp Deroduwur</h2>
            <p class="text-lg text-[#5C5C50] mb-8 leading-relaxed">
                Basecamp Deroduwur merupakan pintu gerbang menuju keindahan Gunung Bismo. 
                Berada di ketinggian yang strategis, basecamp ini menawarkan akses langsung 
                ke jalur pendakian yang masih alami dan jarang terjamah. Dikelola oleh masyarakat 
                setempat, kami berkomitmen menjaga kelestarian alam sambil memberikan pengalaman 
                pendakian yang tak terlupakan.
            </p>
            <a href="<?= BASE_URL ?>kisah.php" class="inline-block bg-[#2F5233] hover:bg-[#4A7A4E] text-white px-8 py-3 rounded-full font-semibold transition duration-300">
                Selengkapnya →
            </a>
        </div>
    </div>
</section>

<!-- Highlight Nilai Plus -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl md:text-4xl font-bold text-center text-[#2F5233] mb-12">Keunggulan Basecamp Deroduwur</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="bg-[#FAF7F2] p-8 rounded-2xl card-hover text-center">
                <div class="w-20 h-20 bg-[#2F5233] rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-[#2F5233] mb-2">Jalur Rimbun</h3>
                <p class="text-[#5C5C50]">Jalur pendakian yang asri dengan pepohonan rindang dan udara segar</p>
            </div>
            <div class="bg-[#FAF7F2] p-8 rounded-2xl card-hover text-center">
                <div class="w-20 h-20 bg-[#2F5233] rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-[#2F5233] mb-2">Ekosistem Terjaga</h3>
                <p class="text-[#5C5C50]">Keanekaragaman hayati yang masih terjaga dengan baik</p>
            </div>
            <div class="bg-[#FAF7F2] p-8 rounded-2xl card-hover text-center">
                <div class="w-20 h-20 bg-[#2F5233] rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-[#2F5233] mb-2">Flora & Fauna</h3>
                <p class="text-[#5C5C50]">Beragam jenis tumbuhan dan satwa liar yang unik</p>
            </div>
            <div class="bg-[#FAF7F2] p-8 rounded-2xl card-hover text-center">
                <div class="w-20 h-20 bg-[#2F5233] rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-[#2F5233] mb-2">Budaya & Religi</h3>
                <p class="text-[#5C5C50]">Nilai budaya dan religi yang kental di sekitar basecamp</p>
            </div>
        </div>
    </div>
</section>

<!-- Preview Galeri -->
<section class="py-20 bg-[#FAF7F2]">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl md:text-4xl font-bold text-center text-[#2F5233] mb-12">Jejak Visual</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($galeri_preview as $foto): ?>
            <div class="overflow-hidden rounded-2xl shadow-lg card-hover">
                <a href="<?= BASE_URL ?>uploads/galeri/<?= htmlspecialchars($foto['foto']) ?>" data-lightbox="gallery">
                    <img src="<?= BASE_URL ?>uploads/galeri/<?= htmlspecialchars($foto['foto']) ?>" 
                         alt="<?= htmlspecialchars($foto['judul']) ?>" 
                         class="w-full h-64 object-cover">
                    <?php if ($foto['judul']): ?>
                    <div class="p-4 bg-white">
                        <p class="text-[#2F5233] font-semibold"><?= htmlspecialchars($foto['judul']) ?></p>
                        <p class="text-xs text-[#A9784B]">#<?= htmlspecialchars($foto['kategori']) ?></p>
                    </div>
                    <?php endif; ?>
                </a>
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
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl md:text-4xl font-bold text-center text-[#2F5233] mb-12">Kabar Bismo</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php foreach ($berita_terbaru as $berita): ?>
            <div class="bg-[#FAF7F2] rounded-2xl overflow-hidden shadow-lg card-hover">
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
            <a href="https://wa.me/6281234567890" target="_blank" class="bg-[#E0BE45] hover:bg-[#C46F2A] text-white px-8 py-4 rounded-full font-semibold transition duration-300 transform hover:scale-105">
                Hubungi via WhatsApp
            </a>
            <a href="<?= BASE_URL ?>kontak.php" class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-8 py-4 rounded-full font-semibold transition duration-300 border-2 border-white">
                Temui Kami
            </a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

<script src="assets/js/main.js"></script>
<script src="assets/js/lightbox.js"></script>
</body>
</html>