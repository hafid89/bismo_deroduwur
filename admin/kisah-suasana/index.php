<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

if (!isLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

if (empty($_SESSION['kisah_suasana_csrf'])) {
    $_SESSION['kisah_suasana_csrf'] = bin2hex(random_bytes(32));
}
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = is_string($_POST['csrf_token'] ?? null) ? $_POST['csrf_token'] : '';
    if (!hash_equals($_SESSION['kisah_suasana_csrf'], $token)) {
        $error = 'Sesi formulir berakhir. Muat ulang halaman lalu coba kembali.';
    } elseif (($_POST['action'] ?? '') === 'upload') {
        $files = $_FILES['foto'] ?? null;
        $uploaded = 0;
        $errors = [];

        if (!$files || empty($files['name'][0])) {
            $errors[] = 'Pilih minimal satu foto.';
        } else {
            foreach ($files['name'] as $index => $name) {
                $file = [
                    'name' => $name,
                    'type' => $files['type'][$index],
                    'tmp_name' => $files['tmp_name'][$index],
                    'error' => $files['error'][$index],
                    'size' => $files['size'][$index],
                ];
                $result = uploadFile($file, UPLOAD_PATH . 'kisah-suasana/');
                if (!$result['success']) {
                    $errors[] = htmlspecialchars($name) . ': ' . $result['message'];
                    continue;
                }

                $stmt = $pdo->prepare('INSERT INTO kisah_suasana (foto) VALUES (?)');
                $stmt->execute([$result['filename']]);
                $uploaded++;
            }
        }

        $_SESSION['message'] = $uploaded ? $uploaded . ' foto berhasil ditambahkan.' : '';
        $_SESSION['error'] = $errors ? implode('<br>', $errors) : '';
        redirect(BASE_URL . 'admin/kisah-suasana/index.php');
    } elseif (($_POST['action'] ?? '') === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $stmt = $pdo->prepare('SELECT foto FROM kisah_suasana WHERE id = ?');
        $stmt->execute([$id]);
        $photo = $stmt->fetch();
        if ($photo) {
            $stmt = $pdo->prepare('DELETE FROM kisah_suasana WHERE id = ?');
            $stmt->execute([$id]);
            $path = UPLOAD_PATH . 'kisah-suasana/' . basename($photo['foto']);
            if (is_file($path)) {
                unlink($path);
            }
            $_SESSION['message'] = 'Foto berhasil dihapus.';
        }
        redirect(BASE_URL . 'admin/kisah-suasana/index.php');
    }
}

$message = $_SESSION['message'] ?? '';
$error = $error ?: ($_SESSION['error'] ?? '');
unset($_SESSION['message'], $_SESSION['error']);
$stmt = $pdo->query('SELECT * FROM kisah_suasana ORDER BY created_at DESC, id DESC');
$photos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suasana Basecamp - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="min-h-screen bg-[#FAF7F2] text-[#26382A]">
<main class="mx-auto max-w-6xl px-4 py-8 md:px-8">
    <header class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="text-sm text-[#A9784B]">Panel admin</p>
            <h1 class="text-3xl font-bold text-[#2F5233]">Suasana Basecamp</h1>
            <p class="mt-1 text-sm text-gray-600">Foto yang diunggah akan tampil di halaman Kisah Kami.</p>
        </div>
        <a href="../dashboard.php" class="inline-flex items-center gap-2 font-semibold text-[#2F5233]"><i class="bi bi-arrow-left"></i> Dashboard</a>
    </header>

    <?php if ($message): ?><p class="mb-4 rounded bg-green-50 p-3 text-green-800" role="status"><?= htmlspecialchars($message) ?></p><?php endif; ?>
    <?php if ($error): ?><div class="mb-4 rounded bg-red-50 p-3 text-red-800" role="alert"><?= $error ?></div><?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="mb-8 border-y border-[#DDD8CE] py-6">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['kisah_suasana_csrf']) ?>">
        <input type="hidden" name="action" value="upload">
        <label for="photos" class="mb-2 block font-semibold">Tambah foto</label>
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <input id="photos" type="file" name="foto[]" accept="image/jpeg,image/png,image/gif,image/webp,.heic,.heif" multiple required class="block w-full rounded border border-gray-300 bg-white p-2">
            <button type="submit" class="rounded bg-[#2F5233] px-5 py-2.5 font-semibold text-white hover:bg-[#4A7A4E]">Unggah foto</button>
        </div>
        <p class="mt-2 text-xs text-gray-500">JPG, PNG, GIF, WEBP; maksimal 5 MB per foto. HEIC/HEIF perlu dikonversi terlebih dahulu.</p>
    </form>

    <section aria-label="Daftar foto suasana">
        <h2 class="mb-4 text-xl font-bold">Foto saat ini (<?= count($photos) ?>)</h2>
        <?php if (!$photos): ?><p class="border-y border-[#DDD8CE] py-6 text-gray-600">Belum ada foto yang diunggah. Foto bawaan tetap tampil sampai foto baru ditambahkan.</p><?php endif; ?>
        <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4">
            <?php foreach ($photos as $photo): ?>
            <article class="overflow-hidden rounded border border-[#DDD8CE] bg-white">
                <img src="<?= BASE_URL ?>uploads/kisah-suasana/<?= rawurlencode($photo['foto']) ?>" alt="Foto suasana basecamp" class="aspect-[4/3] w-full object-cover" loading="lazy">
                <div class="flex items-center justify-between gap-2 p-3">
                    <time class="text-xs text-gray-500"><?= formatTanggal($photo['created_at']) ?></time>
                    <form method="POST" onsubmit="return confirm('Hapus foto ini?')">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['kisah_suasana_csrf']) ?>">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= (int)$photo['id'] ?>">
                        <button type="submit" class="rounded p-2 text-red-700 hover:bg-red-50" aria-label="Hapus foto"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </section>
</main>
</body>
</html>