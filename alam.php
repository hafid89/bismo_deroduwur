<?php require_once __DIR__ . '/includes/config.php'; ?>
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

<!-- Flora -->
<section class="py-16 bg-[#FAF7F2]">
    <div class="container mx-auto px-4 max-w-4xl">
        <h2 class="text-3xl font-bold text-[#2F5233] mb-6">🌿 Flora</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl overflow-hidden shadow-lg card-hover">
                <img src="assets/images/flora-kantong-semar.jpg" alt="Kantong Semar" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h4 class="font-bold text-[#2F5233]">Kantong Semar</h4>
                    <p class="text-sm text-[#A9784B]">Nepenthes sp.</p>
                    <p class="text-sm text-[#5C5C50] mt-1">Tanaman karnivora endemik, ditemukan di dekat Pos I</p>
                </div>
            </div>
            <div class="bg-white rounded-xl overflow-hidden shadow-lg card-hover">
                <img src="assets/images/flora-pakis.jpg" alt="Pakis" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h4 class="font-bold text-[#2F5233]">Hutan Pakis</h4>
                    <p class="text-sm text-[#A9784B]">Various species</p>
                    <p class="text-sm text-[#5C5C50] mt-1">Kawasan hutan pakis yang rimbun sebelum Pos I</p>
                </div>
            </div>
            <div class="bg-white rounded-xl overflow-hidden shadow-lg card-hover">
                <img src="assets/images/flora-edelweiss.jpg" alt="Edelweiss" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h4 class="font-bold text-[#2F5233]">Edelweiss</h4>
                    <p class="text-sm text-[#A9784B]">Anaphalis javanica</p>
                    <p class="text-sm text-[#5C5C50] mt-1">Bunga abadi yang ditemukan di area puncak</p>
                </div>
            </div>
            <div class="bg-white rounded-xl overflow-hidden shadow-lg card-hover">
                <img src="assets/images/flora-anggrek.jpg" alt="Anggrek Hutan" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h4 class="font-bold text-[#2F5233]">Anggrek Hutan</h4>
                    <p class="text-sm text-[#A9784B]">Orchidaceae</p>
                    <p class="text-sm text-[#5C5C50] mt-1">Berbagai jenis anggrek liar di sepanjang jalur</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Fauna -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 max-w-4xl">
        <h2 class="text-3xl font-bold text-[#2F5233] mb-6">🐾 Fauna</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-[#FAF7F2] rounded-xl overflow-hidden shadow-lg card-hover">
                <img src="assets/images/fauna-elang.jpg" alt="Elang" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h4 class="font-bold text-[#2F5233]">Elang Jawa</h4>
                    <p class="text-sm text-[#A9784B]">Nisaetus bartelsi</p>
                    <p class="text-sm text-[#5C5C50] mt-1">Burung pemangsa endemik yang sering terlihat di sekitar puncak</p>
                </div>
            </div>
            <div class="bg-[#FAF7F2] rounded-xl overflow-hidden shadow-lg card-hover">
                <img src="assets/images/fauna-kera.jpg" alt="Kera" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h4 class="font-bold text-[#2F5233]">Kera Ekor Panjang</h4>
                    <p class="text-sm text-[#A9784B]">Macaca fascicularis</p>
                    <p class="text-sm text-[#5C5C50] mt-1">Ditemukan di sekitar basecamp dan hutan pinus</p>
                </div>
            </div>
            <div class="bg-[#FAF7F2] rounded-xl overflow-hidden shadow-lg card-hover">
                <img src="assets/images/fauna-kupu.jpg" alt="Kupu-kupu" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h4 class="font-bold text-[#2F5233]">Kupu-kupu Tropis</h4>
                    <p class="text-sm text-[#A9784B]">Various species</p>
                    <p class="text-sm text-[#5C5C50] mt-1">Beragam jenis kupu-kupu di area berbunga</p>
                </div>
            </div>
            <div class="bg-[#FAF7F2] rounded-xl overflow-hidden shadow-lg card-hover">
                <img src="assets/images/fauna-burung.jpg" alt="Burung" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h4 class="font-bold text-[#2F5233]">Burung Endemik</h4>
                    <p class="text-sm text-[#A9784B]">Various species</p>
                    <p class="text-sm text-[#5C5C50] mt-1">Beragam burung khas Gunung Bismo</p>
                </div>
            </div>
        </div>
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