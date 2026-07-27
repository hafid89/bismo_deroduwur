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
        .rule-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .rule-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .denda-badge {
            background: #B3452F;
            color: white;
            padding: 2px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .fasilitas-icon {
            font-size: 2rem;
        }
    </style>
</head>
<body>

<?php include __DIR__ . '/includes/navbar.php'; ?>

<!-- Header -->
<section class="pt-32 pb-16 bg-[#FAF7F2]">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl md:text-5xl font-bold text-[#2F5233] text-center">Peraturan & Tiket</h1>
        <p class="text-center text-[#5C5C50] mt-4 max-w-2xl mx-auto">
            Ketentuan dan peraturan yang berlaku di kawasan Gunung Bismo via Deroduwur
        </p>
    </div>
</section>

<!-- Informasi Basecamp -->
<section class="py-8 bg-white border-b">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
            <div class="bg-[#FAF7F2] p-6 rounded-xl text-center">
                <div class="text-3xl mb-2">🏕️</div>
                <h3 class="font-bold text-[#2F5233]">Basecamp Pendakian</h3>
                <p class="text-sm text-[#5C5C50]">Gunung Bismo via Deroduwur</p>
                <p class="text-xs text-[#A9784B] mt-1">Dusun Buntu, Desa Deroduwur, Kec. Mojotengah, Kab. Wonosobo 56351</p>
            </div>
            <div class="bg-[#FAF7F2] p-6 rounded-xl text-center">
                <div class="text-3xl mb-2">📞</div>
                <h3 class="font-bold text-[#2F5233]">Kontak Basecamp</h3>
                <p class="text-sm text-[#5C5C50]">Telp/WA: 0813 9019 5488</p>
                <p class="text-sm text-[#5C5C50]">Email: mtbismoderoduwur13@gmail.com</p>
            </div>
        </div>
    </div>
</section>

<!-- Jam Pelayanan -->
<section class="py-8 bg-[#2F5233] text-white">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto text-center">
            <h2 class="text-2xl font-bold mb-4">🕐 Jam Pelayanan</h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white/10 rounded-lg p-4">
                    <p class="font-semibold">Senin-Kamis</p>
                    <p>01:00 – 22:00</p>
                </div>
                <div class="bg-white/10 rounded-lg p-4">
                    <p class="font-semibold">Jum'at</p>
                    <p>01:00 – 10:00</p>
                    <p>13:00 – 22:00</p>
                </div>
                <div class="bg-white/10 rounded-lg p-4 col-span-2">
                    <p class="font-semibold">Sabtu-Minggu</p>
                    <p>01:00 – 22:00</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Kewajiban -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 max-w-4xl">
        <h2 class="text-3xl font-bold text-[#2F5233] mb-8 flex items-center">
            <span class="mr-3">📋</span> Kewajiban Pendaki
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <?php
            $stmt = $pdo->query("SELECT * FROM peraturan WHERE kategori = 'kewajiban' ORDER BY id");
            $kewajiban = $stmt->fetchAll();
            foreach ($kewajiban as $item):
            ?>
            <div class="rule-card bg-[#FAF7F2] p-4 rounded-xl flex items-start">
                <span class="text-[#2F5233] text-xl mr-3">✓</span>
                <span class="text-[#5C5C50]"><?= htmlspecialchars($item['teks']) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Larangan & Denda -->
<section class="py-16 bg-[#FAF7F2]">
    <div class="container mx-auto px-4 max-w-4xl">
        <h2 class="text-3xl font-bold text-[#2F5233] mb-8 flex items-center">
            <span class="mr-3">🚫</span> Larangan & Denda
        </h2>
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-[#2F5233] text-white">
                            <th class="py-3 px-6 text-left">No</th>
                            <th class="py-3 px-6 text-left">Pelanggaran</th>
                            <th class="py-3 px-6 text-left">Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $pdo->query("SELECT * FROM peraturan WHERE kategori = 'larangan' ORDER BY id");
                        $larangan = $stmt->fetchAll();
                        $no = 1;
                        foreach ($larangan as $item):
                        ?>
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition duration-300">
                            <td class="py-3 px-6 text-[#5C5C50]"><?= $no++ ?></td>
                            <td class="py-3 px-6 text-[#5C5C50]"><?= htmlspecialchars($item['teks']) ?></td>
                            <td class="py-3 px-6">
                                <span class="denda-badge"><?= htmlspecialchars($item['denda']) ?></span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="bg-red-50">
                            <td colspan="3" class="py-3 px-6 text-center text-red-600 font-semibold">
                                ⚠️ Melanggar aturan dikenakan denda Rp. 1.025.000
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Fasilitas -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 max-w-4xl">
        <h2 class="text-3xl font-bold text-[#2F5233] mb-8 flex items-center">
            <span class="mr-3">🎯</span> Fasilitas Basecamp
        </h2>
        <p class="text-[#5C5C50] mb-6">Harga tiket pendakian sudah termasuk fasilitas Basecamp:</p>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <?php
            $stmt = $pdo->query("SELECT * FROM peraturan WHERE kategori = 'fasilitas' ORDER BY id");
            $fasilitas = $stmt->fetchAll();
            $icons = ['🎫', '🏥', '📶', '🚻', '🪑', '📦', '🕌'];
            $i = 0;
            foreach ($fasilitas as $item):
            ?>
            <div class="bg-[#FAF7F2] p-4 rounded-xl text-center rule-card">
                <div class="fasilitas-icon"><?= $icons[$i % count($icons)] ?></div>
                <p class="text-sm text-[#5C5C50] font-medium"><?= htmlspecialchars($item['teks']) ?></p>
            </div>
            <?php $i++; endforeach; ?>
        </div>
    </div>
</section>

<!-- Surat Pernyataan -->
<section class="py-16 bg-[#FAF7F2]">
    <div class="container mx-auto px-4 max-w-4xl">
        <h2 class="text-3xl font-bold text-[#2F5233] mb-8 text-center">📄 Surat Pernyataan</h2>
        <div class="bg-white rounded-xl shadow-lg p-8">
            <p class="text-[#5C5C50] text-center mb-6">
                Saya sebagai ketua kelompok pendakian Gunung Bismo via Deroduwur menyatakan sanggup mematuhi peraturan yang berlaku, 
                dan apabila melanggar, kami sanggup membayar denda sesuai yang ditetapkan.
            </p>
            <div class="text-center text-2xl font-bold text-[#B3452F]">
                Rp. 1.025.000
            </div>
            <div class="mt-6 text-center">
                <p class="text-sm text-[#5C5C50]">Penanggung Jawab</p>
                <div class="border-b-2 border-[#2F5233] w-48 mx-auto mt-2"></div>
                <p class="text-sm text-[#5C5C50] mt-1">(......................)</p>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
<script src="assets/js/main.js"></script>
</body>
</html>