<?php require_once __DIR__ . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kisah Kami - Gunung Bismo via Deroduwur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#FAF7F2]">

<?php include __DIR__ . '/includes/navbar.php'; ?>

<!-- Header -->
<section class="pt-32 pb-16 bg-[#FAF7F2]">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl md:text-5xl font-bold text-[#2F5233] text-center">Kisah Kami</h1>
        <p class="text-center text-[#5C5C50] mt-4 max-w-2xl mx-auto">
            Mengenal lebih dekat basecamp dan pengelolaan Gunung Bismo
        </p>
    </div>
</section>

<!-- Sejarah -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 max-w-4xl">
        <h2 class="text-3xl font-bold text-[#2F5233] mb-6">Sejarah Basecamp</h2>
        <div class="prose prose-lg text-[#5C5C50]">
            <p>
                Basecamp Deroduwur didirikan pada tahun 2010 sebagai pintu gerbang resmi untuk pendakian Gunung Bismo. 
                Berawal dari kepedulian masyarakat lokal terhadap potensi wisata alam di daerah mereka, basecamp ini 
                dikembangkan secara bertahap untuk memberikan pengalaman pendakian yang aman dan nyaman.
            </p>
            <p>
                Nama "Deroduwur" sendiri berasal dari bahasa Jawa yang memiliki makna mendalam tentang hubungan antara 
                manusia dan alam. Hingga kini, basecamp ini terus dikelola dengan semangat gotong royong dan komitmen 
                untuk menjaga kelestarian lingkungan.
            </p>
        </div>
    </div>
</section>

<!-- Profil Pengelola -->
<section class="py-16 bg-[#FAF7F2]">
    <div class="container mx-auto px-4 max-w-4xl">
        <h2 class="text-3xl font-bold text-[#2F5233] mb-6">Profil Pengelola</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white rounded-xl shadow-lg p-6 text-center border border-emerald-900/10">
                <div class="w-32 h-32 bg-gray-200 rounded-full mx-auto mb-4 flex items-center justify-center text-4xl text-gray-500">
                    👤
                </div>
                <h3 class="text-xl font-bold text-[#2F5233]">Mas Taufik</h3>
                <p class="text-[#5C5C50]">Ketua Pengelola</p>
                <p class="text-sm text-[#5C5C50] mt-2">Berpengalaman lebih dari 10 tahun dalam pengelolaan basecamp</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 text-center border border-emerald-900/10">
                <div class="w-32 h-32 bg-gray-200 rounded-full mx-auto mb-4 flex items-center justify-center text-4xl text-gray-500">
                    👤
                </div>
                <h3 class="text-xl font-bold text-[#2F5233]">Mas Wahyu</h3>
                <p class="text-[#5C5C50]">Koordinator Lapangan</p>
                <p class="text-sm text-[#5C5C50] mt-2">Ahli dalam navigasi dan keselamatan pendakian</p>
            </div>
        </div>
    </div>
</section>

<!-- Lokasi & Fasilitas -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-6 gap-2">
            <div>
                <h2 class="text-3xl font-bold text-[#2F5233]">Lokasi & Fasilitas</h2>
                <p class="text-sm text-gray-600 mt-1">
                    📍 Dusun Buntu, Desa Deroduwur, Kec. Mojotengah, Kab. Wonosobo, Jawa Tengah
                </p>
            </div>
            <!-- Tombol Direct Navigasi Google Maps Sesuai Koordinat Presisi Baru -->
            <a href="https://www.google.com/maps/search/?api=1&query=-7.2772452311451215,109.88243926075317" 
               target="_blank" 
               rel="noopener noreferrer" 
               class="inline-flex items-center gap-2 text-xs md:text-sm bg-[#2F5233] hover:bg-[#4A7A4E] text-white px-4 py-2.5 rounded-lg transition shadow">
                <span>📍 Buka Google Maps</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </a>
        </div>
        
       <!-- Google Maps Embed Akurat Mode Satelit (-7.2772452311451215, 109.88243926075317) -->
<div class="mb-10 rounded-2xl overflow-hidden shadow-xl border border-gray-200">
    <iframe 
        src="https://maps.google.com/maps?q=-7.2772452311451215,109.88243926075317&hl=id&z=17&t=k&output=embed" 
        width="100%" 
        height="420" 
        style="border:0;" 
        allowfullscreen="" 
        loading="lazy" 
        referrerpolicy="no-referrer-when-downgrade">
    </iframe>
</div>

        <h3 class="text-2xl font-bold text-[#2F5233] mb-4">Fasilitas yang Tersedia</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex items-start space-x-3 p-4 bg-[#FAF7F2] rounded-xl border border-gray-100">
                <span class="text-2xl">🅿️</span>
                <div>
                    <h4 class="font-semibold text-[#2F5233]">Parkir Luas & Aman</h4>
                    <p class="text-sm text-[#5C5C50]">Area parkir kendaraan roda 2 & roda 4 yang terjangkau dan dijaga 24 jam</p>
                </div>
            </div>
            <div class="flex items-start space-x-3 p-4 bg-[#FAF7F2] rounded-xl border border-gray-100">
                <span class="text-2xl">🚻</span>
                <div>
                    <h4 class="font-semibold text-[#2F5233]">Toilet & Kamar Mandi</h4>
                    <p class="text-sm text-[#5C5C50]">Fasilitas toilet yang bersih dan air pegunungan yang segar</p>
                </div>
            </div>
            <div class="flex items-start space-x-3 p-4 bg-[#FAF7F2] rounded-xl border border-gray-100">
                <span class="text-2xl">🍜</span>
                <div>
                    <h4 class="font-semibold text-[#2F5233]">Warung & Basecamp Rest</h4>
                    <p class="text-sm text-[#5C5C50]">Tersedia makanan hangat, kopi, dan tempat istirahat sebelum mendaki</p>
                </div>
            </div>
            <div class="flex items-start space-x-3 p-4 bg-[#FAF7F2] rounded-xl border border-gray-100">
                <span class="text-2xl">🏕️</span>
                <div>
                    <h4 class="font-semibold text-[#2F5233]">Area Resting & Persewaan</h4>
                    <p class="text-sm text-[#5C5C50]">Tempat persiapan, pengecekan peralatan, dan rental perlengkapan outdoor</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Nilai Plus -->
<section class="py-16 bg-[#2F5233] text-white">
    <div class="container mx-auto px-4 max-w-4xl">
        <h2 class="text-3xl font-bold text-center mb-8">Keunikan Deroduwur</h2>
        <div class="space-y-6">
            <div class="bg-white/10 backdrop-blur-sm p-6 rounded-xl border border-white/10">
                <h3 class="text-xl font-bold text-[#E0BE45] mb-2">🌿 Alam yang Masih Terjaga</h3>
                <p class="text-gray-200 leading-relaxed">Deroduwur menawarkan pengalaman mendaki di jalur yang masih alami dengan keanekaragaman hayati yang kaya. Flora dan fauna asli seperti Kantong Semar dan burung lokal masih dapat ditemukan dengan mudah.</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm p-6 rounded-xl border border-white/10">
                <h3 class="text-xl font-bold text-[#E0BE45] mb-2">🙏 Nilai Budaya & Religi</h3>
                <p class="text-gray-200 leading-relaxed">Basecamp terletak di Dusun Buntu yang kaya akan kearifan lokal, adat istiadat, serta sejarah religi pesarean kuno yang sangat dihormati masyarakat setempat.</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm p-6 rounded-xl border border-white/10">
                <h3 class="text-xl font-bold text-[#E0BE45] mb-2">🤝 Pengelolaan Berbasis Komunitas</h3>
                <p class="text-gray-200 leading-relaxed">Dikelola langsung oleh pemuda dan warga lokal Dusun Buntu dengan semangat gotong royong demi menjaga keselamatan pendaki sekaligus pelestarian alam.</p>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
<script src="assets/js/main.js"></script>
</body>
</html>