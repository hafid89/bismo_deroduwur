<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

if (!isLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM berita WHERE id = ?");
$stmt->execute([$id]);
$berita = $stmt->fetch();

if (!$berita) {
    $_SESSION['message'] = 'Berita tidak ditemukan';
    $_SESSION['message_type'] = 'danger';
    redirect(BASE_URL . 'admin/berita/index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul']);
    $isi = trim($_POST['isi']);
    $tanggal = $_POST['tanggal'];
    $foto = $berita['foto'];

    $errors = [];

    if (empty($judul)) $errors[] = 'Judul berita wajib diisi';
    if (empty($isi)) $errors[] = 'Isi berita wajib diisi';
    if (empty($tanggal)) $errors[] = 'Tanggal berita wajib diisi';
    if (strlen($judul) < 5) $errors[] = 'Judul berita minimal 5 karakter';
    if (strlen($isi) < 10) $errors[] = 'Isi berita minimal 10 karakter';
    if ($tanggal > date('Y-m-d')) $errors[] = 'Tanggal tidak boleh lebih dari hari ini';

    // Cek judul duplikat
    if (!empty($judul)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM berita WHERE judul = ? AND id != ?");
        $stmt->execute([$judul, $id]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = 'Judul berita "' . htmlspecialchars($judul) . '" sudah ada. Silakan gunakan judul lain.';
        }
    }

    if (empty($errors)) {
        $slug = createSlug($judul);

        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $upload = uploadFile($_FILES['foto'], BERITA_UPLOAD_PATH);
            if ($upload['success']) {
                if ($foto && file_exists(BERITA_UPLOAD_PATH . $foto)) {
                    unlink(BERITA_UPLOAD_PATH . $foto);
                }
                $foto = $upload['filename'];
            } else {
                $errors[] = $upload['message'];
            }
        }

        if (empty($errors)) {
            $stmt = $pdo->prepare("UPDATE berita SET judul = ?, slug = ?, isi = ?, foto = ?, tanggal = ? WHERE id = ?");
            if ($stmt->execute([$judul, $slug, $isi, $foto, $tanggal, $id])) {
                $_SESSION['message'] = 'Berita "' . htmlspecialchars($judul) . '" berhasil diupdate!';
                $_SESSION['message_type'] = 'success';
                redirect(BASE_URL . 'admin/berita/index.php');
            } else {
                $errors[] = 'Gagal mengupdate berita. Silakan coba lagi.';
            }
        }
    }

    if (!empty($errors)) {
        $error = implode('<br>', $errors);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Berita - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; transition: all 0.2s ease; }
        body { background: #f5f0eb; background-image: radial-gradient(circle at 10% 20%, rgba(74, 122, 78, 0.03) 0%, transparent 50%); }

        .sidebar { background: linear-gradient(180deg, #1e3a2a 0%, #2a4a35 100%); box-shadow: 4px 0 20px rgba(0,0,0,0.08); }
        .sidebar .nav-link { padding: 10px 16px; border-radius: 10px; color: rgba(255,255,255,0.65); font-weight: 500; font-size: 14px; transition: all 0.25s ease; display: block; }
        .sidebar .nav-link:hover { background: rgba(255,255,255,0.08); color: #fff; transform: translateX(4px); }
        .sidebar .nav-link.active { background: rgba(224, 190, 69, 0.15); color: #E0BE45; box-shadow: inset 3px 0 0 #E0BE45; }
        .sidebar .nav-link .icon { margin-right: 10px; }

        .form-input { transition: all 0.3s ease; }
        .form-input:focus { border-color: #2F5233; box-shadow: 0 0 0 3px rgba(47, 82, 51, 0.1); outline: none; }

        .btn-primary-custom { background: #2F5233; color: #fff; padding: 10px 28px; border-radius: 12px; font-weight: 600; font-size: 14px; border: none; cursor: pointer; transition: all 0.3s ease; text-decoration: none; display: inline-block; }
        .btn-primary-custom:hover { background: #1e3a2a; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(47, 82, 51, 0.2); }

        .btn-secondary-custom { background: #f0ebe6; color: #5c4e42; padding: 10px 28px; border-radius: 12px; font-weight: 500; font-size: 14px; border: none; cursor: pointer; transition: all 0.3s ease; text-decoration: none; display: inline-block; }
        .btn-secondary-custom:hover { background: #e0d8d0; }

        .alert-error { background: #fce4ec; border-left: 4px solid #ef5350; color: #5c1a1a; padding: 12px 16px; border-radius: 10px; }
        .error-list { list-style: none; padding: 0; margin: 0; }
        .error-list li { padding: 4px 0; display: flex; align-items: flex-start; gap: 8px; }
        .error-list li::before { content: '⚠️'; flex-shrink: 0; }

        .card-form { background: #fff; border-radius: 16px; padding: 28px 32px; box-shadow: 0 2px 12px rgba(0,0,0,0.04); border: 1px solid rgba(0,0,0,0.03); }
        .preview-image:hover { transform: scale(1.02); }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f0ebe6; border-radius: 8px; }
        ::-webkit-scrollbar-thumb { background: #d5cdc4; border-radius: 8px; }
        ::-webkit-scrollbar-thumb:hover { background: #b8aaa0; }

        @keyframes fadeUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        .animate-in { animation: fadeUp 0.45s ease forwards; opacity: 0; }
        .delay-1 { animation-delay: 0.05s; } .delay-2 { animation-delay: 0.1s; } .delay-3 { animation-delay: 0.15s; } .delay-4 { animation-delay: 0.2s; }
    </style>
</head>
<body>
<div class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="sidebar w-[220px] flex-shrink-0 h-full flex flex-col p-4">
        <div class="flex items-center gap-3 px-2 py-4 mb-6">
            <div class="w-10 h-10 rounded-xl bg-[#E0BE45]/20 flex items-center justify-center text-xl">🏔️</div>
            <div>
                <p class="text-white font-bold text-sm leading-tight">Gunung Bismo</p>
                <p class="text-[#b8c9b0] text-[10px] font-medium tracking-wider">PANEL ADMIN</p>
            </div>
        </div>
        <nav class="flex-1 space-y-1">
            <a href="../dashboard.php" class="nav-link"><span class="icon">📊</span> Dashboard</a>
            <a href="index.php" class="nav-link active"><span class="icon">📰</span> Berita</a>
            <a href="../galeri/index.php" class="nav-link"><span class="icon">🖼️</span> Galeri</a>
            <a href="../flora/index.php" class="nav-link"><span class="icon">🌿</span> Flora</a>
            <a href="../fauna/index.php" class="nav-link"><span class="icon">🐾</span> Fauna</a>
            <a href="../peraturan/index.php" class="nav-link"><span class="icon">📋</span> Peraturan</a>
            <a href="../spot-jalur/index.php" class="nav-link"><span class="icon">📍</span> Spot Jalur</a>
        </nav>
        <div class="pt-4 border-t border-white/10 mt-auto">
            <a href="../logout.php" class="nav-link text-red-300/70 hover:text-red-300"><span class="icon">🚪</span> Keluar</a>
            <p class="text-[10px] text-white/30 text-center mt-3 tracking-wider">v1.0 • KKN 84.384</p>
        </div>
    </aside>

    <!-- Main -->
    <main class="flex-1 overflow-y-auto p-6 md:p-8">
        <div class="flex items-center justify-between mb-6 animate-in delay-1">
            <div>
                <p class="text-sm text-[#8a7e72] font-medium">📰 Edit Konten</p>
                <h1 class="text-2xl font-bold text-[#1e3a2a]">Edit Berita</h1>
                <p class="text-sm text-[#8a7e72]">Perbarui informasi berita</p>
            </div>
            <a href="index.php" class="text-[#4a7a4e] hover:text-[#2a4a35] font-medium flex items-center gap-1 transition duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
        </div>

        <?php if ($error): ?>
        <div class="alert-error mb-5 animate-in delay-2">
            <div class="font-semibold mb-1">Terdapat kesalahan:</div>
            <ul class="error-list text-sm"><?php foreach (explode('<br>', $error) as $err): ?><li><?= htmlspecialchars($err) ?></li><?php endforeach; ?></ul>
        </div>
        <?php endif; ?>

        <div class="card-form animate-in delay-2">
            <form method="POST" action="" enctype="multipart/form-data">

                <!-- Preview Foto -->
                <div class="mb-6 flex items-center gap-4 p-4 bg-[#faf7f2] rounded-xl border border-[#f0ebe6]">
                    <div class="w-20 h-20 rounded-lg overflow-hidden border border-[#e0d8d0] flex-shrink-0">
                        <?php if (!empty($berita['foto']) && file_exists(BERITA_UPLOAD_PATH . $berita['foto'])): ?>
                        <img src="<?= BASE_URL ?>uploads/berita/<?= htmlspecialchars($berita['foto']) ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-2xl text-[#b8aaa0] bg-[#f0ebe6]">📰</div>
                        <?php endif; ?>
                    </div>
                    <div><p class="text-sm font-medium text-[#1e3a2a]">Foto saat ini</p><p class="text-xs text-[#8a7e72]">Upload foto baru untuk mengganti</p></div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Judul Berita <span class="text-red-500">*</span> <span class="text-xs text-gray-400 font-normal">(Minimal 5 karakter)</span></label>
                    <input type="text" name="judul" value="<?= htmlspecialchars($berita['judul']) ?>" class="form-input w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none" required minlength="5" maxlength="255">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal" value="<?= $berita['tanggal'] ?>" class="form-input w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none" required max="<?= date('Y-m-d') ?>">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Ganti Foto <span class="text-xs text-gray-400 font-normal">(Opsional)</span></label>
                    <input type="file" name="foto" accept="image/*" class="form-input w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah foto • Maks 5MB, JPG/PNG/GIF</p>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Isi Berita <span class="text-red-500">*</span> <span class="text-xs text-gray-400 font-normal">(Minimal 10 karakter)</span></label>
                    <textarea name="isi" rows="8" class="form-input w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none" required minlength="10"><?= htmlspecialchars($berita['isi']) ?></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="btn-primary-custom flex items-center gap-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Update Berita</button>
                    <a href="index.php" class="btn-secondary-custom">Batal</a>
                </div>
            </form>
        </div>

        <p class="text-center text-[10px] text-[#b8aaa0] mt-8 tracking-wider border-t border-[#f0ebe6] pt-4">© <?= date('Y') ?> Gunung Bismo via Deroduwur · KKN 84.384 UPNVYK</p>
    </main>
</div>
</body>
</html>