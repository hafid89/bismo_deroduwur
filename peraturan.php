<?php require_once __DIR__ . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peraturan & Tiket - Gunung Bismo via Deroduwur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Outfit', sans-serif; }

        /* Hero Section - Sama seperti kisah dan telusur jalur */
        .hero-peraturan {
            height: 100vh;
            min-height: 600px;
            max-height: 800px;
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

        .hero-fade.delay-1 { animation-delay: 0.15s; }
        .hero-fade.delay-2 { animation-delay: 0.35s; }
        .hero-fade.delay-3 { animation-delay: 0.55s; }

        @keyframes heroFadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
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
            font-size: 1.25rem;
            font-weight: 700;
            color: #2F5233;
        }

        .timeline-desc {
            color: #5C5C50;
            margin-top: 4px;
            line-height: 1.8;
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
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
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
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
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
            border: 2px solid rgba(255,255,255,0.18);
            background: rgba(255,255,255,0.08);
            will-change: transform, box-shadow;
        }

        .schedule-card:hover {
            transform: translateY(-6px) scale(1.02);
            box-shadow: 0 15px 30px rgba(0,0,0,0.16);
            border-color: rgba(255,255,255,0.35);
            background: rgba(255,255,255,0.15);
        }

        .schedule-card.active {
            transform: scale(1.03);
            box-shadow: 0 22px 40px rgba(0,0,0,0.22);
            border-color: #E0BE45;
            background: rgba(224,190,69,0.16);
        }

        .schedule-card.active .day-title {
            color: #E0BE45;
        }

        .schedule-card.active .day-title {
            color: #E0BE45;
        }

        .fee-card {
            transition: transform 0.3s ease, border-color 0.3s ease, background-color 0.3s ease, box-shadow 0.3s ease;
            border: 2px solid rgba(255,255,255,0.18);
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(12px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
            will-change: transform, box-shadow;
        }

        .fee-card:hover {
            transform: translateY(-6px);
            border-color: rgba(255,255,255,0.35);
            background: rgba(255,255,255,0.16);
            box-shadow: 0 22px 40px rgba(0,0,0,0.2);
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
                font-size: 1rem;
            }
            .timeline-desc {
                font-size: 0.9rem;
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
            <h1 class="hero-title text-5xl md:text-6xl lg:text-7xl font-bold text-white hero-fade delay-1 mb-10">
                Peraturan & <span class="text-[#E0BE45]">Tiket Pendakian</span>
            </h1>
            <p class="hero-subtitle text-base md:text-lg lg:text-2xl text-white/90 leading-relaxed hero-fade delay-2 max-w-3xl mx-auto">
                Ketentuan, tata tertib, dan informasi penting sebelum melakukan pendakian 
                Gunung Bismo via Deroduwur untuk keamanan dan kenyamanan bersama.
            </p>
        </div>
    </div>
</section>

<!-- Alur Registrasi - Timeline -->
<section class="py-10 bg-[#FAF7F2]">
    <div class="container mx-auto px-4 max-w-4xl">
        <h2 class="text-3xl md:text-4xl font-bold text-[#2F5233] text-center mb-4">Alur Registrasi Pendakian</h2>
        <p class="text-center text-[#5C5C50] mb-12 max-w-4xl mx-auto">
            Ikuti langkah-langkah berikut untuk melakukan registrasi pendakian Gunung Bismo via Deroduwur
        </p>

        <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12">
            <div class="space-y-0">
                <!-- Timeline Item 1 -->
                <div class="timeline-item">
                    <div class="timeline-number">1. Pendaki Datang</div>
                    <p class="timeline-desc">
                        Pendaki datang dan langsung <strong>memarkirkan kendaraan</strong> di lokasi yang sudah disediakan 
                        di area Basecamp Deroduwur. Parkir tersedia untuk kendaraan roda 2 dan roda 4 dengan keamanan 24 jam.
                    </p>
                </div>

                <!-- Timeline Item 2 -->
                <div class="timeline-item">
                    <div class="timeline-number">2. Cek List Barang Bawaan</div>
                    <p class="timeline-desc">
                        <strong>Minta kertas cek list ke petugas basecamp.</strong> Pendaki mengisi sendiri barang-barang bawaan 
                        dan perlengkapan pendakian. Kemudian <strong>minta petugas basecamp untuk mengecek/ceklis</strong> 
                        barang bawaan yang sudah ditulis.
                    </p>
                </div>

                <!-- Timeline Item 3 -->
                <div class="timeline-item">
                    <div class="timeline-number">3. Registrasi & Briefing</div>
                    <p class="timeline-desc">
                        Silahkan menuju <strong>tempat registrasi</strong> untuk melakukan pembayaran tiket pendakian. 
                        Setelah itu akan di <strong>briefing</strong> untuk selanjutnya melakukan pendakian dengan aman dan nyaman.
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
            <h2 class="text-5xl font-bold mb-8">Jam Pelayanan Basecamp</h2>
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
                    <p class="text-5xl font-bold text-white">Rp 35.000</p>
                    <p class="text-white/80 mt-3">Sudah termasuk biaya administrasi dan tiket registrasi pendakian</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Peraturan - Kewajiban & Larangan -->
<section class="py-10 bg-[#FAF7F2]">
    <div class="container mx-auto px-4 max-w-5xl">
        <h2 class="text-3xl md:text-4xl font-bold text-[#2F5233] text-center mb-4">Peraturan Pendakian</h2>
        <p class="text-center text-[#5C5C50] mb-12 max-w-2xl mx-auto">
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
<section class="py-10 bg-[#FAF7F2]">
                  </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Informasi Porter, Rental, Ojek -->
<section class="py-20 bg-[#FAF7F2]">
            <!-- Porter -->
            <div class="service-card bg-white rounded-2xl p-8 text-center shadow-lg">
                <div class="icon-wrapper text-5xl mb-4">🎒</div>
                <h3 class="text-2xl font-bold text-[#2F5233] mb-2">Porter</h3>
                <p class="text-[#5C5C50] text-sm mb-3">
                    Jasa porter untuk membantu membawa perlengkapan pendakian
                </p>
                <p class="text-sm text-[#A9784B] font-medium mb-4">Kisaran: Rp 200.000 - 350.000</p>
                <a href="https://wa.me/6281390195488" target="_blank" 
                   class="inline-block bg-[#E0BE45] hover:bg-[#C46F2A] text-white px-6 py-3 rounded-xl font-semibold transition duration-300 w-full">
                    Pesan Porter via WA →
                </a>
            </div>

            <!-- Rental Alat -->
            <div class="service-card bg-white rounded-2xl p-8 text-center shadow-lg">
                <div class="icon-wrapper text-5xl mb-4">⛺</div>
                <h3 class="text-2xl font-bold text-[#2F5233] mb-2">Rental Alat</h3>
                <p class="text-[#5C5C50] text-sm mb-3">
                    Tenda, carrier, sleeping bag, dan perlengkapan outdoor lainnya
                </p>
                <p class="text-sm text-[#A9784B] font-medium mb-4">Tersedia berbagai jenis alat</p>
                <a href="https://wa.me/6281390195488" target="_blank" 
                   class="inline-block bg-[#E0BE45] hover:bg-[#C46F2A] text-white px-6 py-3 rounded-xl font-semibold transition duration-300 w-full">
                    Cek Ketersediaan →
                </a>
            </div>

            <!-- Ojek -->
            <div class="service-card bg-white rounded-2xl p-8 text-center shadow-lg">
                <div class="icon-wrapper text-5xl mb-4">🛵</div>
                <h3 class="text-2xl font-bold text-[#2F5233] mb-2">Ojek</h3>
                <p class="text-[#5C5C50] text-sm mb-3">
                    Transportasi menuju basecamp dan antar pos pendakian
                </p>
                <p class="text-sm text-[#A9784B] font-medium mb-4">Kisaran: Rp 50.000 - 100.000</p>
                <a href="https://wa.me/6281390195488" target="_blank" 
                   class="inline-block bg-[#E0BE45] hover:bg-[#C46F2A] text-white px-6 py-3 rounded-xl font-semibold transition duration-300 w-full">
                    Pesan Ojek →
                </a>
            </div>
        </div>

        <div class="mt-8 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-xl max-w-3xl mx-auto">
            <p class="text-[#5C5C50] text-sm flex items-start gap-2">
                <span><strong>Catatan:</strong> Semua pemesanan porter, rental alat, dan ojek dilakukan melalui WhatsApp. 
                Hubungi kontak di atas untuk informasi lebih lanjut.</span>
            </p>
        </div>
    </div>
</section>

<!-- CTA Denda & Penutup -->
<section class="py-16 bg-[#2F5233] text-white">
    <div class="container mx-auto px-4 text-center max-w-3xl">
        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/10">
            <div class="text-5xl mb-4">⚠️</div>
            <h2 class="text-3xl font-bold mb-4">Patuhi Peraturan untuk Keselamatan Bersama</h2>
            <p class="text-white/90 mb-6 text-lg">
                Setiap pelanggaran akan dikenakan <strong class="text-[#E0BE45]">denda maksimal Rp 1.025.000</strong> 
                sesuai dengan peraturan yang berlaku.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="https://wa.me/6281390195488" target="_blank" 
                   class="bg-[#E0BE45] hover:bg-[#C46F2A] text-white px-8 py-3 rounded-full font-semibold transition duration-300 inline-flex items-center gap-2">
                    Konsultasi via WhatsApp
                </a>
                <a href="<?= BASE_URL ?>persiapan.php" 
                   class="bg-white/20 hover:bg-white/30 text-white px-8 py-3 rounded-full font-semibold transition duration-300 border-2 border-white inline-flex items-center gap-2">
                    Persiapan Pendakian
                </a>
            </div>
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