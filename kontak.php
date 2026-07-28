<?php require_once __DIR__ . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hubungi Kami — Basecamp Gunung Bismo via Deroduwur</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="<?= BASE_URL ?>assets/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= BASE_URL ?>assets/images/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= BASE_URL ?>assets/images/apple-touch-icon.png">
    <link rel="shortcut icon" href="<?= BASE_URL ?>assets/images/favicon.ico">
    <meta name="theme-color" content="#1b3d2f">

    <!-- Tailwind CSS & Lucide Icons -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&family=Playfair+Display:ital,wght@0,600;1,600&display=swap');
        
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            color: #2d3748;
            background-color: #fcfbf9;
        }
        .font-serif-custom {
            font-family: 'Playfair Display', Georgia, serif;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(229, 231, 235, 0.7);
        }
    </style>
</head>
<body class="antialiased selection:bg-[#2F5233] selection:text-white">

<?php include __DIR__ . '/includes/navbar.php'; ?>

<!-- Hero Section -->
<section class="relative pt-36 pb-20 overflow-hidden bg-[#1b3d2f] text-white">
    <!-- Accent Background Pattern -->
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-3xl mx-auto text-center">
            <span class="inline-block text-xs uppercase tracking-widest text-[#E0BE45] font-semibold mb-3 px-3 py-1 rounded-full bg-white/10 backdrop-blur-sm border border-white/10">
                Pusat Informasi & Registrasi
            </span>
            <h1 class="text-4xl md:text-6xl font-serif-custom font-semibold leading-tight tracking-tight">
                Mari Bicara dengan Pengelola Basecamp
            </h1>
            <p class="mt-6 text-base md:text-lg text-emerald-100/80 font-light leading-relaxed">
                Punya pertanyaan seputar jalur Deroduwur, cuaca terkini, atau butuh bantuan reservasi rombongan? Tim pengelola kami siap membantu pendakian Anda.
            </p>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-16 md:py-24 -mt-8">
    <div class="container mx-auto px-4 md:px-6 max-w-6xl">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            
            <!-- Kolom Kiri: Informasi Kontak & Lokasi -->
            <div class="lg:col-span-5 space-y-8">
                <div>
                    <h2 class="text-2xl font-serif-custom font-bold text-[#1b3d2f]">Informasi Basecamp</h2>
                    <p class="text-gray-600 mt-2 text-sm">Silakan kunjungi atau hubungi kami melalui saluran resmi berikut.</p>
                </div>

                <!-- List Detail Kontak -->
                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="p-3 bg-[#1b3d2f]/5 text-[#1b3d2f] rounded-xl shrink-0 mt-1">
                            <i data-lucide="map-pin" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 text-sm">Lokasi Basecamp</h3>
                            <p class="text-gray-600 text-sm mt-1 leading-relaxed">
                                Basecamp Pendakian Bismo via Deroduwur<br>
                                Desa Deroduwur, Kec. Mojotengah,<br>
                                Kab. Wonosobo, Jawa Tengah 56351
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="p-3 bg-[#1b3d2f]/5 text-[#1b3d2f] rounded-xl shrink-0 mt-1">
                            <i data-lucide="clock" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 text-sm">Jam Layanan Simaksi</h3>
                            <p class="text-gray-600 text-sm mt-1">
                                Layanan Tiket & Registrasi: <span class="font-medium text-gray-800">24 Jam</span><br>
                                <span class="text-xs text-gray-500">*Disarankan tiba sebelum pukul 21.00 WIB untuk briefing safety.</span>
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="p-3 bg-[#1b3d2f]/5 text-[#1b3d2f] rounded-xl shrink-0 mt-1">
                            <i data-lucide="phone-call" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 text-sm">Kontak Cepat & Evakuasi</h3>
                            <p class="text-gray-600 text-sm mt-1">
                                WhatsApp Admin: <a href="https://wa.me/6281234567890" class="text-[#1b3d2f] font-semibold underline underline-offset-4 hover:text-emerald-700">+62 812-3456-7890</a><br>
                                Tim SAR / Emergency: <span class="text-gray-800 font-medium">+62 821-9876-5432</span>
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="p-3 bg-[#1b3d2f]/5 text-[#1b3d2f] rounded-xl shrink-0 mt-1">
                            <i data-lucide="mail" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 text-sm">Email Resmi</h3>
                            <p class="text-gray-600 text-sm mt-1">
                                info@gunungbismoderoduwur.com
                            </p>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200 my-6">

                <!-- Media Sosial -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-4">Media Sosial Resmi</h4>
                    <div class="flex gap-3">
                        <a href="https://instagram.com/gunungbismo" target="_blank" class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 text-gray-700 text-sm font-medium hover:bg-gray-50 transition">
                            <i data-lucide="instagram" class="w-4 h-4 text-pink-600"></i>
                            @gunungbismo_deroduwur
                        </a>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Form Pesan -->
            <div class="lg:col-span-7">
                <div class="glass-card rounded-3xl p-8 md:p-10 shadow-xl shadow-gray-200/50">
                    <h2 class="text-2xl font-serif-custom font-bold text-[#1b3d2f] mb-2">Kirim Pesan</h2>
                    <p class="text-gray-500 text-sm mb-8">
                        Silakan isi formulir di bawah ini. Tim pengelola akan membalas pertanyaan Anda melalui email atau WhatsApp.
                    </p>

                    <form action="#" method="POST" class="space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Nama Lengkap</label>
                                <input type="text" required placeholder="Contoh: Budi Santoso" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#1b3d2f]/20 focus:border-[#1b3d2f] text-sm transition">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">No. WhatsApp</label>
                                <input type="tel" required placeholder="0812345678xx" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#1b3d2f]/20 focus:border-[#1b3d2f] text-sm transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Topik Pertanyaan</label>
                            <select class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#1b3d2f]/20 focus:border-[#1b3d2f] text-sm transition text-gray-700 bg-white">
                                <option value="simaksi">Informasi Simaksi & Perizinan</option>
                                <option value="cuaca">Kondisi Cuaca & Jalur</option>
                                <option value="porter">Sewa Porter / Tour Guide</option>
                                <option value="rombongan">Reservasi Kelompok / Event</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Pesan Anda</label>
                            <textarea rows="4" required placeholder="Tuliskan detail pertanyaan atau kebutuhan Anda..." class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#1b3d2f]/20 focus:border-[#1b3d2f] text-sm transition"></textarea>
                        </div>

                        <button type="submit" class="w-full bg-[#1b3d2f] hover:bg-[#25523f] text-white py-3.5 px-6 rounded-xl font-medium text-sm transition duration-200 shadow-lg shadow-[#1b3d2f]/10 flex items-center justify-center gap-2">
                            <i data-lucide="send" class="w-4 h-4"></i>
                            Kirim Pesan Sekarang
                        </button>
                    </form>
                </div>
            </div>

        </div>

        <!-- Section Google Maps (Pembaruan Koordinat) -->
        <div class="mt-20">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
                <div>
                    <h3 class="text-2xl font-serif-custom font-bold text-[#1b3d2f]">Peta Menuju Basecamp</h3>
                    <p class="text-gray-500 text-sm mt-1">Dapat diakses dengan mudah menggunakan kendaraan roda dua maupun roda empat.</p>
                </div>
                <a href="https://www.google.com/maps/search/?api=1&query=-7.277098481653131,109.88252316527377" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-xs font-semibold text-[#1b3d2f] border border-[#1b3d2f]/20 px-4 py-2 rounded-lg hover:bg-[#1b3d2f] hover:text-white transition w-fit">
                    <i data-lucide="navigation" class="w-3.5 h-3.5"></i>
                    Buka di Google Maps
                </a>
            </div>

            <div class="rounded-3xl overflow-hidden shadow-lg border border-gray-200/80 h-[400px]">
                <iframe 
                    src="https://maps.google.com/maps?q=-7.277098481653131,109.88252316527377&hl=id&z=16&output=embed" 
                    width="100%" 
                    height="100%" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>

    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

<!-- Script untuk Inisialisasi Icon -->
<script>
    lucide.createIcons();
</script>
<script src="assets/js/main.js"></script>
</body>
</html>