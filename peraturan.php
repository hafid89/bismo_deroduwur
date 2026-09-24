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
    <title>Panduan Berkelana & Peraturan - Gunung Bismo via Deroduwur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

        * {
            font-family: 'Outfit', sans-serif;
        }

        /* Hero Section - Sama seperti kisah dan telusur jalur */
        .hero-peraturan {
            height: 100vh;
            min-height: 600px;
            max-height: 1000px;
            background-size: cover;
            background-position: center;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-peraturan::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(47, 82, 51, 0.6);
            z-index: 1;
        }

        .hero-peraturan .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .hero-title {
            font-size: clamp(2.5rem, 6vw, 5rem);
            line-height: 1.1;
            margin-bottom: 0.5rem;
        }

        .hero-subtitle {
            font-size: clamp(1rem, 1.8vw, 1.5rem);
            line-height: 1.6;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-fade {
            opacity: 0;
            transform: translateY(30px);
            animation: heroFadeUp 0.9s ease forwards;
        }

        .hero-fade.delay-1 {
            animation-delay: 0.15s;
        }

        .hero-fade.delay-2 {
            animation-delay: 0.35s;
        }

        .hero-fade.delay-3 {
            animation-delay: 0.55s;
        }

        @keyframes heroFadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Timeline Style - Sama seperti kisah */
        .timeline-item {
            position: relative;
            padding-left: 30px;
            padding-bottom: 30px;
            border-left: 3px solid #2F5233;
        }

        .timeline-item:last-child {
            border-left: 3px solid transparent;
            padding-bottom: 0;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -7px;
            top: 5px;
            width: 14px;
            height: 14px;
            background: #E0BE45;
            border-radius: 50%;
            border: 3px solid #2F5233;
        }

        .timeline-number {
            font-size: 1.125rem;
            font-weight: 700;
            color: #2F5233;
        }

        .timeline-desc {
            font-size: 0.875rem;
            color: #5C5C50;
            margin-top: 4px;
            line-height: 1.8;
        }

        @media (min-width: 768px) {
            .timeline-number {
                font-size: 1.25rem;
            }

            .timeline-desc {
                font-size: 1rem;
            }
        }

        .timeline-desc strong {
            color: #2F5233;
        }

        /* Rule Cards */
        .rule-card {
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .rule-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            border-color: #2F5233;
        }

        .rule-card.danger:hover {
            border-color: #B3452F;
            background: #fef2f2;
        }

        .rule-card.success:hover {
            border-color: #3F7D4F;
            background: #f0fdf4;
        }

        .denda-badge {
            background: #B3452F;
            color: white;
            padding: 2px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        /* Service Cards */
        .service-card {
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .service-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            border-color: #E0BE45;
        }

        .service-card .icon-wrapper {
            transition: transform 0.3s ease;
        }

        .service-card:hover .icon-wrapper {
            transform: scale(1.1) rotate(-5deg);
        }

        .schedule-card {
            transition: transform 0.3s ease, border-color 0.3s ease, background-color 0.3s ease, box-shadow 0.3s ease;
            border: 2px solid rgba(255, 255, 255, 0.18);
            background: rgba(255, 255, 255, 0.08);
            will-change: transform, box-shadow;
        }

        .schedule-card:hover {
            transform: translateY(-6px) scale(1.02);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.16);
            border-color: rgba(255, 255, 255, 0.35);
            background: rgba(255, 255, 255, 0.15);
        }

        .schedule-card.active {
            transform: scale(1.03);
            box-shadow: 0 22px 40px rgba(0, 0, 0, 0.22);
            border-color: #E0BE45;
            background: rgba(224, 190, 69, 0.16);
        }

        .schedule-card.active .day-title {
            color: #E0BE45;
        }

        .schedule-card.active .day-title {
            color: #E0BE45;
        }

        .fee-card {
            transition: transform 0.3s ease, border-color 0.3s ease, background-color 0.3s ease, box-shadow 0.3s ease;
            border: 2px solid rgba(255, 255, 255, 0.18);
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
            will-change: transform, box-shadow;
        }

        .fee-card:hover {
            transform: translateY(-6px);
            border-color: rgba(255, 255, 255, 0.35);
            background: rgba(255, 255, 255, 0.16);
            box-shadow: 0 22px 40px rgba(0, 0, 0, 0.2);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-peraturan {
                height: 100vh;
                min-height: 450px;
            }

            .timeline-item {
                padding-left: 20px;
            }
        }

        @media (max-width: 480px) {
            .hero-peraturan {
                height: 100vh;
                min-height: 400px;
            }

            .hero-title {
                font-size: clamp(1.8rem, 7vw, 2.2rem);
            }

            .hero-subtitle {
                font-size: clamp(0.8rem, 2.5vw, 0.95rem);
                padding: 0 15px;
            }

            .timeline-number {
                font-size: 1.125rem;
            }

            .timeline-desc {
                font-size: 0.875rem;
            }
        }
    </style>
</head>

<body class="bg-[#FAF7F2]">

    <?php include __DIR__ . '/includes/navbar.php'; ?>

    <!-- Hero Section -->
    <section class="hero-peraturan" style="background-image: url('<?= BASE_URL ?>assets/images/persiapan/hero-peraturan.png');">
        <div class="hero-content container mx-auto px-6 md:px-12 lg:px-24">
            <div class="max-w-7xl mx-auto">
                <h1 class="hero-title text-4xl md:text-5xl lg:text-6xl font-bold text-white hero-fade delay-1 mb-10">
                    Panduan Berkelana <span class="text-[#E0BE45]"> & Tata Etika</span>
                </h1>
                <p class="hero-subtitle text-lg md:text-lg lg:text-2xl text-white/90 mb-8 leading-relaxed hero-fade delay-2 max-w-4xl mx-auto">
                    Perjalanan menarik memang sangat mendebarkan hati.
                    Menjadi langkah yang lebih baik untuk mempersiapkan jasmani dan rohani agar perjalanan semakin siap dilalui.
                    Resapi sejenak berbagai informasi penting dari kami untuk kenyamanan hati.
                </p>
            </div>
        </div>
    </section>

    <!-- Alur Registrasi - Timeline -->
    <section class="py-10 bg-[#FAF7F2]">
        <div class="container mx-auto px-4 max-w-4xl">
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-[#2F5233] text-center mb-6">Tahapan Registrasi Pendakian</h2>
            <p class="text-base md:text-xl lg:text-lg text-[#5C5C50] mb-8 text-center leading-relaxed">
                Ikuti langkah-langkah berikut sebelum melakukan pendakian Gunung Bismo via Deroduwur
            </p>

            <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12">
                <div class="space-y-0">
                    <!-- Timeline Item 1 -->
                    <div class="timeline-item">
                        <div class="timeline-number">1. Pendaki Datang</div>
                        <p class="timeline-desc">
                            Bagi para pendaki yang membawa kendaraan pribadi, baik roda 2 maupun roda 4, mohon dapat memarkirkan kendaraan dengan rapi di area parkir Basecamp Deroduwur.
                            Kawasan basecamp memiliki penjagaan 24 jam untuk menjamin keamanan kendaraan.
                        </p>
                    </div>

                    <!-- Timeline Item 2 -->
                    <div class="timeline-item">
                        <div class="timeline-number">2. Pemeriksaan Perlengkapan</div>
                        <p class="timeline-desc">
                            Pendaki diharuskan melakukan pengecekan barang bawaan dengan meminta kertas checklist ke petugas.
                            Pastikan pengisian dilakukan dengan benar agar persediaan logistik selama pendakian tidak mengganggu perjalanan.
                            Berikan kertas checklist ke petugas agar dilakukan double cross check untuk memastikan barang bawaan sesuai kebutuhan pendaki.
                        </p>
                    </div>

                    <!-- Timeline Item 3 -->
                    <div class="timeline-item">
                        <div class="timeline-number">3. Registrasi & Briefing</div>
                        <p class="timeline-desc">
                            Silahkan menuju tempat registrasi untuk melakukan pembayaran SIMAKSI pendakian. 
                            Pastikan pendaki mengikuti arahan briefing yang disampaikan oleh petugas agar pendakian berjalan dengan aman dan selamat sampai kembali pulang.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Jam Pelayanan -->
    <section class="py-16 bg-[#2F5233] text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold mb-6">Jam Pelayanan Basecamp</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="schedule-card rounded-xl p-6 bg-white/10 backdrop-blur-sm border border-white/10 hover:bg-white/15 transition duration-300" data-period="Senin - Kamis">
                        <p class="font-bold text-[#E0BE45] text-lg">Senin - Kamis</p>
                        <p class="text-white/90 text-lg">01:00 – 22:00</p>
                    </div>
                    <div class="schedule-card rounded-xl p-6 bg-white/10 backdrop-blur-sm border border-white/10 hover:bg-white/15 transition duration-300" data-period="Jum'at">
                        <p class="font-bold text-[#E0BE45] text-lg">Jum'at</p>
                        <p class="text-white/90 text-lg">01:00 – 10:00</p>
                        <p class="text-white/90 text-lg">13:00 – 22:00</p>
                    </div>
                    <div class="schedule-card rounded-xl p-6 bg-white/10 backdrop-blur-sm border border-white/10 hover:bg-white/15 transition duration-300" data-period="Sabtu - Minggu">
                        <p class="font-bold text-[#E0BE45] text-lg">Sabtu - Minggu</p>
                        <p class="text-white/90 text-lg">01:00 – 22:00</p>
                    </div>
                </div>

                <div class="mt-8 max-w-md mx-auto">
                    <div class="fee-card rounded-3xl p-8 text-center border border-white/15">
                        <p class="text-sm uppercase tracking-[0.35em] text-white/70 mb-4">Biaya Registrasi</p>
                        <p class="text-2xl md:text-3xl lg:text-4xl font-bold text-white">Rp 20.000</p>
                        <p class="text-white/80 mt-3">Sudah termasuk biaya administrasi dan tiket registrasi pendakian</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Peraturan - Kewajiban & Larangan -->
    <section class="py-10 bg-[#FAF7F2]">
        <div class="container mx-auto px-4 max-w-5xl">
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-[#2F5233] text-center mb-6">Peraturan Pendakian</h2>
            <p class="text-base md:text-xl lg:text-lg text-[#5C5C50] mb-8 text-center leading-relaxed">
                Kewajiban dan larangan yang harus dipatuhi selama berada di kawasan Gunung Bismo via Deroduwur
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Kewajiban -->
                <div>
                    <h3 class="text-2xl font-bold text-[#3F7D4F] mb-6 flex items-center">
                        Kewajiban
                    </h3>
                    <div class="space-y-3">
                        <?php
                        $stmt = $pdo->query("SELECT * FROM peraturan WHERE kategori = 'kewajiban' ORDER BY id");
                        $kewajiban = $stmt->fetchAll();
                        foreach ($kewajiban as $item):
                        ?>
                            <div class="rule-card success bg-white p-4 rounded-xl flex items-start border-l-4 border-[#3F7D4F]">
                                <span class="text-[#3F7D4F] text-xl mr-3 flex-shrink-0">✓</span>
                                <span class="text-[#5C5C50]"><?= htmlspecialchars($item['teks']) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Larangan -->
                <div>
                    <h3 class="text-2xl font-bold text-[#B3452F] mb-6 flex items-center">
                        Larangan & Denda
                    </h3>
                    <div class="space-y-3">
                        <?php
                        $stmt = $pdo->query("SELECT * FROM peraturan WHERE kategori = 'larangan' ORDER BY id");
                        $larangan = $stmt->fetchAll();
                        foreach ($larangan as $item):
                        ?>
                            <div class="rule-card danger bg-white p-4 rounded-xl border-l-4 border-[#B3452F]">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex items-start">
                                        <span class="text-[#B3452F] text-xl mr-3 flex-shrink-0">✕</span>
                                        <span class="text-[#5C5C50]"><?= htmlspecialchars($item['teks']) ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <!-- Total Denda -->
                        <div class="mt-4 bg-red-50 border-2 border-red-300 rounded-xl p-4 text-center">
                            <p class="text-sm text-red-600 font-semibold">
                                Melanggar aturan dikenakan denda senilai
                            </p>
                            <p class="text-4xl font-bold text-[#B3452F]">Rp 1.025.000</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Informasi Porter, Rental, Ojek -->
    <section class="py-20 bg-[#FAF7F2]">
        <div class="container mx-auto px-4 max-w-6xl">
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-[#2F5233] text-center mb-6">Layanan Pendukung</h2>
            <div class="mb-8 flex justify-center">
                <img src="<?= BASE_URL ?>assets/images/persiapan/rental-alat.jpg" alt="Informasi rental alat" class="w-full max-w-3xl h-auto rounded-2xl shadow-lg object-cover">
            </div>
            <div class="grid grid-cols-3 gap-2 md:gap-4 lg:gap-6">
                <!-- Porter -->
                <div class="service-card bg-white rounded-2xl p-3 md:p-4 lg:p-8 text-center shadow-lg">
                    <div class="icon-wrapper text-3xl md:text-4xl lg:text-5xl mb-3 md:mb-4">🎒</div>
                    <h3 class="text-base md:text-lg lg:text-2xl font-bold text-[#2F5233] mb-2">Porter</h3>
                    <p class="text-[#5C5C50] text-[10px] md:text-xs lg:text-sm mb-3">
                        Melayani kebutuhan untuk membawa perlengkapan logistik selama pendakian.
                    </p>
                    <p class="text-[10px] md:text-xs lg:text-sm text-[#A9784B] font-medium mb-3 md:mb-4">Start from Rp200.000,-</p>
                    <a href="https://wa.me/6285701005336" target="_blank"
                        class="inline-block bg-[#E0BE45] hover:bg-[#C46F2A] text-white px-2 md:px-4 lg:px-6 py-2 md:py-2.5 lg:py-3 rounded-xl font-semibold text-[10px] md:text-xs lg:text-base transition duration-300 w-full">
                        Pesan Porter
                    </a>
                </div>

                <!-- Rental Alat -->
                <div class="service-card bg-white rounded-2xl p-3 md:p-4 lg:p-8 text-center shadow-lg">
                    <div class="icon-wrapper text-3xl md:text-4xl lg:text-5xl mb-3 md:mb-4">⛺</div>
                    <h3 class="text-base md:text-lg lg:text-2xl font-bold text-[#2F5233] mb-2">Persewaan Alat</h3>
                    <p class="text-[#5C5C50] text-[10px] md:text-xs lg:text-sm mb-3">
                        Menyediakan berbagai jenis perlengkapan outdoor
                    </p>
                    <p class="text-[10px] md:text-xs lg:text-sm text-[#A9784B] font-medium mb-3 md:mb-4">Start from Rp5.000,</p>
                    <a href="https://wa.me/6285701005336" target="_blank"
                        class="inline-block bg-[#E0BE45] hover:bg-[#C46F2A] text-white px-2 md:px-4 lg:px-6 py-2 md:py-2.5 lg:py-3 rounded-xl font-semibold text-[10px] md:text-xs lg:text-base transition duration-300 w-full">
                        Cek Ketersediaan
                    </a>
                </div>

                <!-- Ojek -->
                <div class="service-card bg-white rounded-2xl p-3 md:p-4 lg:p-8 text-center shadow-lg">
                    <div class="icon-wrapper text-3xl md:text-4xl lg:text-5xl mb-3 md:mb-4">🛵</div>
                    <h3 class="text-base md:text-lg lg:text-2xl font-bold text-[#2F5233] mb-2">Ojek Gunung</h3>
                    <p class="text-[#5C5C50] text-[10px] md:text-xs lg:text-sm mb-3">
                        Melayani perjalanan dari basecamp hingga antar pos untuk kenyamanan pendakian.
                    </p>
                    <p class="text-[10px] md:text-xs lg:text-sm text-[#A9784B] font-medium mb-3 md:mb-4">Start from Rp50.000,-</p>
                    <a href="https://wa.me/6282137188300" target="_blank"
                        class="inline-block bg-[#E0BE45] hover:bg-[#C46F2A] text-white px-2 md:px-4 lg:px-6 py-2 md:py-2.5 lg:py-3 rounded-xl font-semibold text-[10px] md:text-xs lg:text-base transition duration-300 w-full">
                        Pesan Ojek
                    </a>
                </div>
            </div>

            <div class="mt-8 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-xl max-w-3xl mx-auto">
                <p class="text-[#5C5C50] text-sm flex items-start gap-2">
                    <span><strong>Catatan:</strong> Seluruh pemesanan tiap layanan pendukung dilakukan melalui WhatsApp.
                        Klik fitur untuk melanjutkan pemesanan dan dapatkan informasi lebih lanjut.</span>
                </p>
            </div>
        </div>
    </section>

    <!-- CTA Denda & Penutup -->
    <section class="py-2 pt-16 bg-[#2F5233] text-white">
        <div class="container mx-auto px-4 text-center max-w-3xl">
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/10">
                <div class="text-5xl mb-4">⚠️</div>
                <h2 class="text-xl md:text-2xl lg:text-3xl font-bold mb-6">Patuhi Aturan Demi Keselamatan</h2>
                <p class="text-base md:text-lg lg:text-xl text-white/90 leading-relaxed mb-8 max-w-2xl mx-auto text-center">
                    Setiap pelanggaran akan dikenakan <strong class="text-[#E0BE45]">denda maksimal Rp 1.025.000</strong>
                    sesuai dengan peraturan yang berlaku.
                </p>
            </div>
        </div>
    </section>

    <?php include __DIR__ . '/includes/footer.php'; ?>
    <script src="assets/js/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dayMap = {
                0: 'Sabtu - Minggu',
                1: 'Senin - Kamis',
                2: 'Senin - Kamis',
                3: 'Senin - Kamis',
                4: 'Senin - Kamis',
                5: "Jum'at",
                6: 'Sabtu - Minggu'
            };
            const dayNames = {
                0: 'Minggu',
                1: 'Senin',
                2: 'Selasa',
                3: 'Rabu',
                4: 'Kamis',
                5: "Jum'at",
                6: 'Sabtu'
            };
            const today = new Date().getDay();
            const currentPeriod = dayMap[today];
            const cards = document.querySelectorAll('.schedule-card');

            cards.forEach(card => {
                if (card.dataset.period === currentPeriod) {
                    card.classList.add('active');
                    const badge = card.querySelector('.schedule-badge');
                    if (badge) {
                        badge.textContent = 'Hari ini';
                    }
                    card.style.order = -1;
                }
            });

            // Current day effect only; no extra label text needed.
        });
    </script>
</body>

</html>