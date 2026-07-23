<?php require_once __DIR__ . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siap Mendaki - Gunung Bismo via Deroduwur</title>
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
        <h1 class="text-4xl md:text-5xl font-bold text-[#2F5233] text-center">Siap Mendaki</h1>
        <p class="text-center text-[#5C5C50] mt-4 max-w-2xl mx-auto">
            Informasi lengkap persiapan pendakian Gunung Bismo via Deroduwur
        </p>
    </div>
</section>

<!-- Perlengkapan Wajib -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 max-w-4xl">
        <h2 class="text-3xl font-bold text-[#2F5233] mb-6">Perlengkapan Wajib</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <ul class="space-y-3 text-[#5C5C50]">
                <li class="flex items-start space-x-3 p-3 bg-[#FAF7F2] rounded-lg">
                    <span class="text-[#2F5233] text-xl">✓</span>
                    <span><strong>Sepatu gunung</strong> - nyaman dan anti selip</span>
                </li>
                <li class="flex items-start space-x-3 p-3 bg-[#FAF7F2] rounded-lg">
                    <span class="text-[#2F5233] text-xl">✓</span>
                    <span><strong>Jaket & pakaian hangat</strong> - suhu bisa turun drastis</span>
                </li>
                <li class="flex items-start space-x-3 p-3 bg-[#FAF7F2] rounded-lg">
                    <span class="text-[#2F5233] text-xl">✓</span>
                    <span><strong>Air minum</strong> - minimal 2 liter</span>
                </li>
                <li class="flex items-start space-x-3 p-3 bg-[#FAF7F2] rounded-lg">
                    <span class="text-[#2F5233] text-xl">✓</span>
                    <span><strong>Makanan ringan/energi</strong> - untuk bekal di jalur</span>
                </li>
                <li class="flex items-start space-x-3 p-3 bg-[#FAF7F2] rounded-lg">
                    <span class="text-[#2F5233] text-xl">✓</span>
                    <span><strong>Senter/headlamp</strong> - untuk pendakian malam</span>
                </li>
            </ul>
            <ul class="space-y-3 text-[#5C5C50]">
                <li class="flex items-start space-x-3 p-3 bg-[#FAF7F2] rounded-lg">
                    <span class="text-[#2F5233] text-xl">✓</span>
                    <span><strong>P3K & obat pribadi</strong> - antisipasi kondisi darurat</span>
                </li>
                <li class="flex items-start space-x-3 p-3 bg-[#FAF7F2] rounded-lg">
                    <span class="text-[#2F5233] text-xl">✓</span>
                    <span><strong>Tas carrier</strong> - 40-60 liter sesuai kebutuhan</span>
                </li>
                <li class="flex items-start space-x-3 p-3 bg-[#FAF7F2] rounded-lg">
                    <span class="text-[#2F5233] text-xl">✓</span>
                    <span><strong>Trekking pole</strong> - membantu keseimbangan</span>
                </li>
                <li class="flex items-start space-x-3 p-3 bg-[#FAF7F2] rounded-lg">
                    <span class="text-[#2F5233] text-xl">✓</span>
                    <span><strong>Kantong sampah</strong> - bawa turun sampah pribadi</span>
                </li>
                <li class="flex items-start space-x-3 p-3 bg-[#FAF7F2] rounded-lg">
                    <span class="text-[#2F5233] text-xl">✓</span>
                    <span><strong>Dokumen identitas</strong> - KTP/SIM untuk registrasi</span>
                </li>
            </ul>
        </div>
    </div>
</section>

<!-- Tata Tertib -->
<section class="py-16 bg-[#FAF7F2]">
    <div class="container mx-auto px-4 max-w-4xl">
        <h2 class="text-3xl font-bold text-[#2F5233] mb-6">Tata Tertib Pendakian</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h3 class="text-xl font-bold text-red-600 mb-4">🚫 Dilarang</h3>
                <ul class="space-y-2 text-[#5C5C50]">
                    <li>• Membuang sampah sembarangan</li>
                    <li>• Merusak flora dan fauna</li>
                    <li>• Membawa senjata tajam</li>
                    <li>• Mendaki dalam kondisi sakit</li>
                    <li>• Membuat api unggun sembarangan</li>
                    <li>• Mengambil benda-benda dari alam</li>
                </ul>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h3 class="text-xl font-bold text-[#2F5233] mb-4">✅ Wajib</h3>
                <ul class="space-y-2 text-[#5C5C50]">
                    <li>• Membawa turun sampah pribadi</li>
                    <li>• Menjaga kebersihan area basecamp</li>
                    <li>• Menghormati pengunjung lain</li>
                    <li>• Melapor ke pos jika selesai</li>
                    <li>• Mengikuti arahan pengelola</li>
                    <li>• Menjaga ketenangan di area basecamp</li>
                </ul>
            </div>
        </div>
        <div class="mt-6 bg-[#E0BE45]/10 border-l-4 border-[#E0BE45] p-4 rounded">
            <p class="text-[#5C5C50] text-sm">
                <strong>💡 Pesan Penting:</strong> "Bawa Turun Kembali Sampah Anda" - Jaga kelestarian Gunung Bismo untuk generasi mendatang.
            </p>
        </div>
    </div>
</section>

<!-- Biaya Registrasi -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 max-w-4xl">
        <h2 class="text-3xl font-bold text-[#2F5233] mb-6">Biaya Registrasi</h2>
        <div class="bg-[#FAF7F2] p-8 rounded-xl text-center">
            <div class="text-4xl font-bold text-[#2F5233]">Rp 50.000</div>
            <p class="text-[#5C5C50] mt-2">/ orang (termasuk biaya registrasi dan asuransi dasar)</p>
            <p class="text-sm text-[#5C5C50] mt-4">* Biaya dapat berubah sewaktu-waktu. Hubungi kami untuk informasi terbaru.</p>
        </div>
    </div>
</section>

<!-- Porter & Rental -->
<section class="py-16 bg-[#FAF7F2]">
    <div class="container mx-auto px-4 max-w-4xl">
        <h2 class="text-3xl font-bold text-[#2F5233] mb-6">Porter & Rental Alat</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-lg card-hover text-center">
                <div class="text-4xl mb-3">🎒</div>
                <h3 class="text-xl font-bold text-[#2F5233] mb-2">Porter</h3>
                <p class="text-[#5C5C50] text-sm">Jasa porter untuk membantu membawa perlengkapan</p>
                <p class="text-sm text-[#A9784B] mt-2">Kisaran: Rp 200.000 - 350.000</p>
                <a href="https://wa.me/6281234567890" target="_blank" class="inline-block mt-3 text-[#2F5233] font-semibold hover:text-[#4A7A4E] transition duration-300">
                    Hubungi untuk info →
                </a>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-lg card-hover text-center">
                <div class="text-4xl mb-3">⛺</div>
                <h3 class="text-xl font-bold text-[#2F5233] mb-2">Rental Alat</h3>
                <p class="text-[#5C5C50] text-sm">Tenda, carrier, sleeping bag, dan perlengkapan lainnya</p>
                <p class="text-sm text-[#A9784B] mt-2">Tersedia berbagai jenis alat</p>
                <a href="https://wa.me/6281234567890" target="_blank" class="inline-block mt-3 text-[#2F5233] font-semibold hover:text-[#4A7A4E] transition duration-300">
                    Cek Ketersediaan →
                </a>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-lg card-hover text-center">
                <div class="text-4xl mb-3">🛵</div>
                <h3 class="text-xl font-bold text-[#2F5233] mb-2">Ojek</h3>
                <p class="text-[#5C5C50] text-sm">Transportasi menuju basecamp dan pos-pos</p>
                <p class="text-sm text-[#A9784B] mt-2">Kisaran: Rp 50.000 - 100.000</p>
                <a href="https://wa.me/6281234567890" target="_blank" class="inline-block mt-3 text-[#2F5233] font-semibold hover:text-[#4A7A4E] transition duration-300">
                    Pesan Ojek →
                </a>
            </div>
        </div>
        <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
            <p class="text-[#5C5C50] text-sm">
                <strong>📌 Catatan:</strong> Semua pemesanan porter, rental alat, dan ojek dilakukan melalui WhatsApp. 
                Kami tidak menyediakan sistem booking online untuk saat ini.
            </p>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-16 bg-[#2F5233] text-white">
    <div class="container mx-auto px-4 text-center max-w-4xl">
        <h2 class="text-3xl font-bold mb-4">Butuh Informasi Lebih Lanjut?</h2>
        <p class="text-xl mb-8 text-white/90">Hubungi kami untuk konsultasi persiapan pendakian</p>
        <a href="https://wa.me/6281234567890" target="_blank" class="bg-[#E0BE45] hover:bg-[#C46F2A] text-white px-8 py-4 rounded-full font-semibold transition duration-300 transform hover:scale-105 inline-block">
            Hubungi via WhatsApp
        </a>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
<script src="assets/js/main.js"></script>
</body>
</html>