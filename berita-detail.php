<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM berita WHERE id = ?");
$stmt->execute([$id]);
$berita = $stmt->fetch();

if (!$berita) {
    header('Location: ' . BASE_URL . 'berita.php');
    exit();
}

// Get related berita
$stmt = $pdo->prepare("SELECT * FROM berita WHERE id != ? ORDER BY tanggal DESC LIMIT 3");
$stmt->execute([$id]);
$related = $stmt->fetchAll();

if (empty($_SESSION['komentar_csrf'])) {
    $_SESSION['komentar_csrf'] = bin2hex(random_bytes(32));
}
$commentError = '';
$commentName = '';
$commentText = ''; // HTML mentah dari editor (untuk re-populate kalau error)

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment'])) {
    $commentName = is_string($_POST['nama'] ?? null) ? trim($_POST['nama']) : '';
    // Terima teks komentar biasa
    $commentText = is_string($_POST['isi_komentar'] ?? null) ? $_POST['isi_komentar'] : '';
    $token = is_string($_POST['csrf_token'] ?? null) ? $_POST['csrf_token'] : '';

    // Sanitasi HTML komentar
    $commentHtml = sanitizeCommentHtml($commentText);
    $commentPlain = htmlToPlainText($commentHtml);
    $plainLen = mb_strlen($commentPlain, 'UTF-8');

    if (!hash_equals($_SESSION['komentar_csrf'], $token)) {
        $commentError = 'Sesi formulir berakhir. Muat ulang halaman lalu coba kembali.';
    } elseif (mb_strlen($commentName, 'UTF-8') < 2 || mb_strlen($commentName, 'UTF-8') > 80) {
        $commentError = 'Nama harus berisi 2 sampai 80 karakter.';
    } elseif ($plainLen < 2) {
        $commentError = 'Komentar minimal 2 karakter.';
    } elseif ($plainLen > 2000) {
        $commentError = 'Komentar maksimal 2.000 karakter (tanpa tag HTML).';
    } else {
        // Cek apakah kolom isi_plain ada; kalau tidak, fallback ke insert biasa
        try {
            $stmt = $pdo->prepare('INSERT INTO komentar_berita (berita_id, nama, isi, isi_plain) VALUES (?, ?, ?, ?)');
            $stmt->execute([$id, $commentName, $commentHtml, $commentPlain]);
        } catch (PDOException $e) {
            // Fallback kalau kolom isi_plain belum ada
            $stmt = $pdo->prepare('INSERT INTO komentar_berita (berita_id, nama, isi) VALUES (?, ?, ?)');
            $stmt->execute([$id, $commentName, $commentHtml]);
        }

        $_SESSION['komentar_csrf'] = bin2hex(random_bytes(32));
        redirect(BASE_URL . 'berita-detail.php?id=' . $id . '#komentar');
    }
}

// Ambil komentar (handle kalau isi_plain belum ada)
try {
    $stmt = $pdo->prepare('SELECT nama, isi, isi_plain, created_at FROM komentar_berita WHERE berita_id = ? ORDER BY created_at DESC');
    $stmt->execute([$id]);
    $comments = $stmt->fetchAll();
} catch (PDOException $e) {
    $stmt = $pdo->prepare('SELECT nama, isi, created_at FROM komentar_berita WHERE berita_id = ? ORDER BY created_at DESC');
    $stmt->execute([$id]);
    $comments = $stmt->fetchAll();
    foreach ($comments as &$c) { $c['isi_plain'] = null; }
    unset($c);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- ===== FAVICON ===== -->
    <link rel="icon" type="image/png" sizes="32x32" href="<?= BASE_URL ?>assets/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= BASE_URL ?>assets/images/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= BASE_URL ?>assets/images/apple-touch-icon.png">
    <link rel="shortcut icon" href="<?= BASE_URL ?>assets/images/favicon.ico">
    <meta name="theme-color" content="#2F5233">
    <title><?= htmlspecialchars($berita['judul']) ?> - Gunung Bismo via Deroduwur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Outfit', sans-serif; }

        /* Card Styling - Konsisten dengan berita.php */
        .card-berita {
            background: white;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            display: block;
        }
        .card-berita:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.12);
        }
        .card-berita .img-wrapper { overflow: hidden; position: relative; }
        .card-berita .img-wrapper img {
            width: 100%; height: 200px; object-fit: cover;
            transition: transform 0.5s ease;
        }
        .card-berita:hover .img-wrapper img { transform: scale(1.06); }
        .card-berita .card-body { padding: 14px 16px; }
        .card-berita .card-body .tanggal {
            font-size: 0.8rem; color: #A9784B; margin-bottom: 6px;
        }
        .card-berita .card-body h3 {
            font-size: 1rem; font-weight: 700; color: #2F5233;
            margin-bottom: 8px;
            display: -webkit-box; -webkit-line-clamp: 2;
            -webkit-box-orient: vertical; overflow: hidden;
        }
        .card-berita .card-body .deskripsi {
            font-size: 0.8rem; color: #5C5C50; line-height: 1.7;
            display: -webkit-box; -webkit-line-clamp: 3;
            -webkit-box-orient: vertical; overflow: hidden;
            margin-bottom: 12px;
        }
        .card-berita .card-body .btn-read {
            color: #E0BE45; font-weight: 700; font-size: 0.9rem;
            transition: color 0.3s ease;
            display: inline-flex; align-items: center; gap: 4px;
        }
        .card-berita .card-body .btn-read:hover { color: #A9784B; }
        .card-berita .card-body .btn-read::after {
            content: ' →'; transition: transform 0.3s ease; display: inline-block;
        }
        .card-berita .card-body .btn-read:hover::after { transform: translateX(4px); }

        @media (max-width: 768px) {
            .card-berita .img-wrapper img { height: 160px; }
            .card-berita .card-body { padding: 12px 14px; }
        }
        @media (max-width: 480px) {
            .card-berita .img-wrapper img { height: 130px; }
            .card-berita .card-body { padding: 10px 12px; }
            .card-berita .card-body h3 { font-size: 0.85rem; }
            .card-berita .card-body .deskripsi { font-size: 0.7rem; }
        }

        .content img { max-width: 100%; height: auto; border-radius: 0.75rem; margin: 1.5rem 0; }
        .content p { margin-bottom: 1rem; line-height: 1.8; color: #5C5C50; }
        .content h2, .content h3, .content h4 {
            color: #2F5233; margin-top: 1.5rem; margin-bottom: 0.75rem; font-weight: 600;
        }
        .content ul, .content ol { margin-bottom: 1rem; padding-left: 1.5rem; color: #5C5C50; }
        .content li { margin-bottom: 0.5rem; }

        /* ============ TAMPILAN KOMENTAR TERSIMPAN ============ */
        .comment-content {
            color: #5C5C50;
            line-height: 1.75;
            word-break: break-word;
            font-size: 0.95rem;
        }
        .comment-content p { margin: 0 0 0.5rem; }
        .comment-content p:last-child { margin-bottom: 0; }
        .comment-content a { color: #2F5233; text-decoration: underline; }
        .comment-content a:hover { color: #A9784B; }
        .comment-content ul, .comment-content ol { padding-left: 1.5rem; margin: 0.5rem 0; }
        .comment-content li { margin-bottom: 0.25rem; }
        .comment-content blockquote {
            border-left: 4px solid #E0BE45;
            padding: 6px 0 6px 14px;
            margin: 8px 0;
            color: #6b5c4d;
            background: #faf7f2;
            border-radius: 0 8px 8px 0;
            font-style: italic;
        }
        .comment-content code {
            background: #f0ebe6;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 0.88em;
            color: #b8341c;
        }
        .comment-content pre {
            background: #1e3a2a;
            color: #e8f5e9;
            padding: 12px 16px;
            border-radius: 8px;
            overflow-x: auto;
            margin: 8px 0;
            font-size: 0.85rem;
        }
        .comment-content pre code { background: none; color: inherit; padding: 0; }
        .comment-content h1, .comment-content h2, .comment-content h3 {
            color: #2F5233; margin: 12px 0 6px; font-weight: 700;
        }
        .comment-content h1 { font-size: 1.4rem; }
        .comment-content h2 { font-size: 1.2rem; }
        .comment-content h3 { font-size: 1.05rem; }
        .comment-content table {
            border-collapse: collapse; margin: 8px 0; width: 100%;
            font-size: 0.9rem;
        }
        .comment-content table th,
        .comment-content table td {
            border: 1px solid #e0d8d0; padding: 6px 10px;
        }
        .comment-content table th { background: #faf7f2; font-weight: 600; }

    </style>
</head>
<body>

<?php include __DIR__ . '/includes/navbar.php'; ?>

<!-- Detail Berita -->
<section class="py-12 bg-[#FAF7F2]" style="padding-top: 6rem;">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <?php if ($berita['foto']): ?>
            <img src="<?= BASE_URL ?>uploads/berita/<?= htmlspecialchars($berita['foto']) ?>" 
                 alt="<?= htmlspecialchars($berita['judul']) ?>" 
                 class="w-full h-72 md:h-96 object-cover">
            <?php endif; ?>
            <div class="p-6 md:p-10">
                <p class="text-sm text-[#A9784B] mb-3"><?= formatTanggal($berita['tanggal']) ?></p>
                <h1 class="text-2xl md:text-4xl font-bold text-[#2F5233] mb-6"><?= htmlspecialchars($berita['judul']) ?></h1>
                <div class="content">
                    <?= sanitizeRichText($berita['isi']) ?>
                </div>
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <a href="<?= BASE_URL ?>berita.php" class="inline-flex items-center gap-2 bg-[#2F5233] hover:bg-[#4A7A4E] text-white px-6 py-3 rounded-full font-semibold transition duration-300">
                        ← Kembali ke Kabar Bismo
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============ SECTION KOMENTAR ============ -->
<section class="bg-[#FAF7F2] pb-12" id="komentar">
    <div class="container mx-auto max-w-4xl px-4">
        <div class="space-y-6">
            <div class="rounded-2xl bg-white p-6 shadow-md md:p-8">
            <h2 class="mb-5 text-2xl font-bold text-[#2F5233]">
                Komentar (<?= count($comments) ?>)
            </h2>

            <?php if ($commentError): ?>
            <p class="mb-4 rounded border-l-4 border-red-500 bg-red-50 p-3 text-sm text-red-700" role="alert">
                <?= htmlspecialchars($commentError) ?>
            </p>
            <?php endif; ?>

                        <!-- ======== DAFTAR KOMENTAR ======== -->
            <div class="space-y-5">
                <?php if (!$comments): ?>
                <p class="text-sm text-gray-500">Belum ada komentar. Jadilah yang pertama berbagi.</p>
                <?php endif; ?>

                <?php foreach ($comments as $comment): ?>
                <article class="border-t border-gray-100 pt-4">
                    <div class="flex flex-wrap items-baseline justify-between gap-2">
                        <h3 class="font-semibold text-[#2F5233]">
                            <?= htmlspecialchars($comment['nama']) ?>
                        </h3>
                        <time class="text-xs text-gray-500" datetime="<?= htmlspecialchars($comment['created_at']) ?>">
                            <?= formatTanggal($comment['created_at']) ?>
                        </time>
                    </div>
                    <div class="comment-content mt-2">
                        <?php
                        // Deteksi: kalau isi komentar mengandung tag HTML → sanitize saat render (double safety).
                        // Kalau plain text (komentar lama) → escape + nl2br.
                        $rawIsi = $comment['isi'];
                        if (preg_match('/<[a-z][\s\S]*>/i', $rawIsi)) {
                            // Sudah HTML (komentar baru) — sanitize ulang untuk safety
                            echo sanitizeCommentHtml($rawIsi);
                        } else {
                            // Plain text lama
                            echo nl2br(htmlspecialchars($rawIsi, ENT_QUOTES, 'UTF-8'));
                        }
                        ?>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
                        </div>
            
            <!-- ======== FORM KOMENTAR ======== -->
            <form method="POST" action="<?= BASE_URL ?>berita-detail.php?id=<?= (int)$id ?>#komentar"
                                    class="rounded-2xl bg-white p-6 shadow-md space-y-4 md:p-8" id="commentForm">
                                <h2 class="text-2xl font-bold text-[#2F5233]">Tulis Komentar</h2>
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['komentar_csrf']) ?>">

                <div>
                    <label for="commentName" class="mb-1 block text-sm font-semibold text-[#2F5233]">
                        Nama <span class="text-red-500">*</span>
                    </label>
                    <input id="commentName" name="nama" value="<?= htmlspecialchars($commentName) ?>"
                           required minlength="2" maxlength="80"
                           class="w-full rounded-xl border border-gray-300 px-4 py-2.5 focus:border-[#2F5233] focus:outline-none focus:ring-2 focus:ring-[#2F5233]/10"
                           autocomplete="name" placeholder="Nama Anda">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold text-[#2F5233]">
                        Komentar <span class="text-red-500">*</span>
                    </label>
                    <textarea id="commentText" name="isi_komentar" rows="6" maxlength="2000" required
                              class="w-full resize-y rounded-xl border border-gray-300 px-4 py-3 focus:border-[#2F5233] focus:outline-none focus:ring-2 focus:ring-[#2F5233]/10"
                              placeholder="Tulis komentar Anda..." aria-describedby="commentLimit"><?= htmlspecialchars($commentText, ENT_QUOTES, 'UTF-8') ?></textarea>
                    <p id="commentLimit" class="mt-1 text-xs text-gray-400">Maksimal 2.000 karakter</p>
                </div>

                <button type="submit" name="submit_comment" value="1"
                        class="inline-flex items-center gap-2 rounded-full bg-[#2F5233] px-6 py-2.5 font-semibold text-white transition hover:bg-[#4A7A4E]">
                    Kirim Komentar
                </button>
            </form>
        </div>
    </div>
</section>

<!-- Related Berita -->
<?php if ($related): ?>
<section class="py-12 bg-[#FAF7F2]">
    <div class="container mx-auto px-4 max-w-6xl">
        <h2 class="text-2xl md:text-3xl font-bold text-[#2F5233] text-center mb-10">Kabar Bismo Lainnya</h2>
        <div class="grid grid-cols-2 gap-3 md:gap-4 lg:grid-cols-3 lg:gap-6">
            <?php foreach ($related as $item): ?>
            <a href="<?= BASE_URL ?>berita-detail.php?id=<?= $item['id'] ?>" class="card-berita">
                <div class="img-wrapper">
                    <?php if ($item['foto']): ?>
                    <img src="<?= BASE_URL ?>uploads/berita/<?= htmlspecialchars($item['foto']) ?>" 
                         alt="<?= htmlspecialchars($item['judul']) ?>"
                         loading="lazy">
                    <?php else: ?>
                    <img src="<?= BASE_URL ?>assets/images/default-news.jpg" 
                         alt="Default Berita"
                         loading="lazy">
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <p class="tanggal"><?= formatTanggal($item['tanggal']) ?></p>
                    <h3><?= htmlspecialchars($item['judul']) ?></h3>
                    <p class="deskripsi"><?= truncateText(strip_tags($item['isi']), 120) ?></p>
                    <span class="btn-read">Baca Selengkapnya</span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>

<script src="assets/js/main.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.getElementById('navbar');
    if (navbar) {
        navbar.classList.remove('bg-transparent');
        navbar.classList.add('bg-forest');
    }
});
</script>
</body>
</html>