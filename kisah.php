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
<body>

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
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="w-32 h-32 bg-gray-300 rounded-full mx-auto mb-4 flex items-center justify-center text-4xl text-gray-500">
                    👤
                </div>
                <h3 class="text-xl font-bold text-[#2F5233]">Mas Taufik</h3>
                <p class="text-[#5C5C50]">Ketua Pengelola</p>
                <p class="text-sm text-[#5C5C50] mt-2">Berpengalaman lebih dari 10 tahun dalam pengelolaan basecamp</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="w-32 h-32 bg-gray-300 rounded-full mx-auto mb-4 flex items-center justify-center text-4xl text-gray-500">
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
        <h2 class="text-3xl font-bold text-[#2F5233] mb-6">Lokasi & Fasilitas</h2>
        
        <!-- Google Maps Embed -->
        <div class="mb-8 rounded-xl overflow-hidden shadow-lg">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.5!2d110.4!3d-7.6!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zN8KwMzYnMDAuMCJTIDExMMKwMjQnMDAuMCJF!5e0!3m2!1sid!2sid!4v1234567890" 
                width="100%" 
                height="400" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy">
            </iframe>
        </div>

        <h3 class="text-2xl font-bold text-[#2F5233] mb-4">Fasilitas yang Tersedia</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex items-start space-x-3 p-4 bg-[#FAF7F2] rounded-lg">
                <span class="text-2xl">🅿️</span>
                <div>
                    <h4 class="font-semibold text-[#2F5233]">Parkir Luas</h4>
                    <p class="text-sm text-[#5C5C50]">Area parkir yang aman untuk kendaraan</p>
                </div>
            </div>
            <div class="flex items-start space-x-3 p-4 bg-[#FAF7F2] rounded-lg">
                <span class="text-2xl">🚻</span>
                <div>
                    <h4 class="font-semibold text-[#2F5233]">Toilet</h4>
                    <p class="text-sm text-[#5C5C50]">Fasilitas toilet yang bersih dan terawat</p>
                </div>
            </div>
            <div class="flex items-start space-x-3 p-4 bg-[#FAF7F2] rounded-lg">
                <span class="text-2xl">🍜</span>
                <div>
                    <h4 class="font-semibold text-[#2F5233]">Warung Makan</h4>
                    <p class="text-sm text-[#5C5C50]">Tersedia warung dengan makanan lokal</p>
                </div>
            </div>
            <div class="flex items-start space-x-3 p-4 bg-[#FAF7F2] rounded-lg">
                <span class="text-2xl">🏕️</span>
                <div>
                    <h4 class="font-semibold text-[#2F5233]">Area Camping</h4>
                    <p class="text-sm text-[#5C5C50]">Tempat berkemah yang nyaman</p>
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
            <div class="bg-white/10 backdrop-blur-sm p-6 rounded-xl">
                <h3 class="text-xl font-bold text-[#E0BE45] mb-2">🌿 Alam yang Masih Terjaga</h3>
                <p>Deroduwur menawarkan pengalaman mendaki di jalur yang masih alami dengan keanekaragaman hayati yang kaya. Flora dan fauna asli masih dapat ditemukan dengan mudah di sepanjang jalur pendakian.</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm p-6 rounded-xl">
                <h3 class="text-xl font-bold text-[#E0BE45] mb-2">🙏 Nilai Budaya & Religi</h3>
                <p>Basecamp ini terletak di area yang memiliki nilai budaya dan religi yang kuat, memberikan pengalaman spiritual tersendiri bagi para pendaki yang menghargai kearifan lokal.</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm p-6 rounded-xl">
                <h3 class="text-xl font-bold text-[#E0BE45] mb-2">🤝 Pengelolaan Berbasis Komunitas</h3>
                <p>Dikelola oleh masyarakat setempat dengan semangat gotong royong, setiap kunjungan ke Deroduwur turut mendukung perekonomian lokal dan pelestarian budaya.</p>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
<script src="assets/js/main.js"></script>
</body>
</html>