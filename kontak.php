<?php require_once __DIR__ . '/includes/config.php'; ?>
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
    <title>Temui Kami - Gunung Bismo via Deroduwur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Outfit', sans-serif; }
        .contact-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .contact-card:hover {
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
        <h1 class="text-4xl md:text-5xl font-bold text-[#2F5233] text-center">Temui Kami</h1>
        <p class="text-center text-[#5C5C50] mt-4 max-w-2xl mx-auto">
            Hubungi kami untuk informasi dan konsultasi seputar pendakian
        </p>
    </div>
</section>

<!-- Kontak -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- WhatsApp -->
            <div class="contact-card bg-[#FAF7F2] rounded-2xl p-8 text-center">
                <div class="text-5xl mb-4">💬</div>
                <h3 class="text-xl font-bold text-[#2F5233] mb-2">WhatsApp</h3>
                <p class="text-[#5C5C50] mb-4">Respons cepat untuk pertanyaan</p>
                <a href="https://wa.me/6281234567890" target="_blank" class="bg-[#25D366] hover:bg-[#128C7E] text-white px-6 py-3 rounded-full font-semibold transition duration-300 inline-block">
                    Chat via WhatsApp
                </a>
            </div>

            <!-- Instagram -->
            <div class="contact-card bg-[#FAF7F2] rounded-2xl p-8 text-center">
                <div class="text-5xl mb-4">📸</div>
                <h3 class="text-xl font-bold text-[#2F5233] mb-2">Instagram</h3>
                <p class="text-[#5C5C50] mb-4">Ikuti aktivitas terkini</p>
                <a href="https://instagram.com/gunungbismo" target="_blank" class="bg-gradient-to-r from-[#405DE6] via-[#5851DB] to-[#E1306C] text-white px-6 py-3 rounded-full font-semibold transition duration-300 inline-block">
                    @gunungbismo
                </a>
            </div>
        </div>

        <!-- Alamat & Jam Operasional -->
        <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-[#2F5233] text-white rounded-2xl p-8">
                <h3 class="text-xl font-bold mb-4">📍 Alamat</h3>
                <p class="text-white/90">
                    Basecamp Deroduwur<br>
                    Desa Deroduwur, Kec. Pakem<br>
                    Kab. Sleman, Yogyakarta 55582
                </p>
            </div>
            <div class="bg-[#2F5233] text-white rounded-2xl p-8">
                <h3 class="text-xl font-bold mb-4">🕐 Jam Operasional</h3>
                <p class="text-white/90">
                    Senin - Minggu<br>
                    06.00 - 17.00 WIB
                </p>
            </div>
        </div>

        <!-- Google Maps -->
        <div class="mt-8 rounded-2xl overflow-hidden shadow-lg">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.5!2d110.4!3d-7.6!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zN8KwMzYnMDAuMCJTIDExMMKwMjQnMDAuMCJF!5e0!3m2!1sid!2sid!4v1234567890" 
                width="100%" 
                height="450" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy">
            </iframe>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-16 bg-[#FAF7F2]">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold text-[#2F5233] mb-4">Siap Mendaki?</h2>
        <p class="text-[#5C5C50] mb-8">Hubungi kami sekarang untuk informasi lebih lanjut</p>
        <a href="https://wa.me/6281234567890" target="_blank" class="bg-[#E0BE45] hover:bg-[#C46F2A] text-white px-8 py-4 rounded-full font-semibold transition duration-300 transform hover:scale-105 inline-block">
            Hubungi via WhatsApp
        </a>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
<script src="assets/js/main.js"></script>
</body>
</html>