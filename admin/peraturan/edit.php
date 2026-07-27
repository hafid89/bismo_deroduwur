<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

if (!isLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$peraturan = getPeraturanById($id);

if (!$peraturan) {
    $_SESSION['message'] = 'Data peraturan tidak ditemukan';
    $_SESSION['message_type'] = 'danger';
    redirect(BASE_URL . 'admin/peraturan/index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kategori = $_POST['kategori'];
    $teks = trim($_POST['teks']);
    $denda = trim($_POST['denda']);

    $errors = [];

    if (empty($kategori)) $errors[] = 'Kategori wajib dipilih';
    if (empty($teks)) $errors[] = 'Teks peraturan wajib diisi';
    if (strlen($teks) < 5) $errors[] = 'Teks peraturan minimal 5 karakter';
    if (strlen($teks) > 255) $errors[] = 'Teks peraturan maksimal 255 karakter';
    if ($kategori == 'kewajiban' && !empty($teks) && preg_match('/[0-9]/', $teks)) {
        $errors[] = 'Teks peraturan untuk kategori kewajiban tidak boleh mengandung angka';
    }
    if (!empty($denda) && !preg_match('/^Rp\.?\s?[\d\.]+$/', $denda)) {
        $errors[] = 'Format denda tidak valid. Gunakan format: Rp. 1.025.000';
    }
    if ($kategori == 'fasilitas' && !empty($denda)) {
        $errors[] = 'Kategori fasilitas tidak boleh memiliki denda';
    }

    // Cek duplikat
    if (!empty($teks)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM peraturan WHERE teks = ? AND id != ?");
        $stmt->execute([$teks, $id]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = 'Teks peraturan "' . htmlspecialchars($teks) . '" sudah ada. Silakan gunakan teks lain.';
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE peraturan SET kategori = ?, teks = ?, denda = ? WHERE id = ?");
        if ($stmt->execute([$kategori, $teks, $denda ?: null, $id])) {
            $_SESSION['message'] = 'Peraturan "' . htmlspecialchars($teks) . '" berhasil diupdate!';
            $_SESSION['message_type'] = 'success';
            redirect(BASE_URL . 'admin/peraturan/index.php');
        } else {
            $errors[] = 'Gagal mengupdate peraturan. Silakan coba lagi.';
        }
    }

    if (!empty($errors)) $error = implode('<br>', $errors);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Peraturan - Admin</title>
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
        .error-list li::before { content: '<i class="bi bi-exclamation-triangle"></i>'; flex-shrink: 0; }

        .card-form { background: #fff; border-radius: 16px; padding: 28px 32px; box-shadow: 0 2px 12px rgba(0,0,0,0.04); border: 1px solid rgba(0,0,0,0.03); }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f0ebe6; border-radius: 8px; }
        ::-webkit-scrollbar-thumb { background: #d5cdc4; border-radius: 8px; }
        ::-webkit-scrollbar-thumb:hover { background: #b8aaa0; }

        @keyframes fadeUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        .animate-in { animation: fadeUp 0.45s ease forwards; opacity: 0; }
        .delay-1 { animation-delay: 0.05s; } .delay-2 { animation-delay: 0.1s; } .delay-3 { animation-delay: 0.15s; } .delay-4 { animation-delay: 0.2s; }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
<div class="flex h-screen overflow-hidden">

    <aside class="sidebar w-[220px] flex-shrink-0 h-full flex flex-col p-4">
        <div class="flex items-center gap-3 px-2 py-4 mb-6">
            <div class="w-10 h-10 rounded-xl bg-[#E0BE45]/20 flex items-center justify-center text-xl"><i class="bi bi-mountain text-lg"></i></div>
            <div><p class="text-white font-bold text-sm leading-tight">Gunung Bismo</p><p class="text-[#b8c9b0] text-[10px] font-medium tracking-wider">PANEL ADMIN</p></div>
        </div>
        <nav class="flex-1 space-y-1">
            <a href="../dashboard.php" class="nav-link"><span class="icon"><i class="bi bi-bar-chart"></i></span> Dashboard</a>
            <a href="../berita/index.php" class="nav-link"><span class="icon"><i class="bi bi-newspaper"></i></span> Berita</a>
            <a href="../galeri/index.php" class="nav-link"><span class="icon"><i class="bi bi-image"></i></span> Galeri</a>
            <a href="../flora/index.php" class="nav-link"><span class="icon"><i class="bi bi-leaf"></i></span> Flora</a>
            <a href="../fauna/index.php" class="nav-link"><span class="icon"><i class="bi bi-paw"></i></span> Fauna</a>
            <a href="index.php" class="nav-link active"><span class="icon"><i class="bi bi-list-check"></i></span> Peraturan</a>
            <a href="../spot-jalur/index.php" class="nav-link"><span class="icon"><i class="bi bi-geo-alt"></i></span> Spot Jalur</a>
        </nav>
        <div class="pt-4 border-t border-white/10 mt-auto">
            <a href="../logout.php" class="nav-link text-red-300/70 hover:text-red-300"><span class="icon"><i class="bi bi-box-arrow-left"></i></span> Keluar</a>
            <p class="text-[10px] text-white/30 text-center mt-3 tracking-wider">v1.0 • KKN 84.384</p>
        </div>
    </aside>

    <main class="flex-1 overflow-y-auto p-6 md:p-8">
        <div class="flex items-center justify-between mb-6 animate-in delay-1">
            <div>
                <p class="text-sm text-[#8a7e72] font-medium"><i class="bi bi-list-check"></i> Edit Regulasi</p>
                <h1 class="text-2xl font-bold text-[#1e3a2a]">Edit Peraturan</h1>
                <p class="text-sm text-[#8a7e72]">Perbarui data peraturan</p>
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
            <form method="POST" action="">

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Kategori <span class="text-red-500">*</span></label>
                    <select name="kategori" class="form-input w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none" required>
                        <option value="kewajiban" <?= $peraturan['kategori'] == 'kewajiban' ? 'selected' : '' ?>><i class="bi bi-pin"></i> Kewajiban</option>
                        <option value="larangan" <?= $peraturan['kategori'] == 'larangan' ? 'selected' : '' ?>><i class="bi bi-slash-circle"></i> Larangan</option>
                        <option value="fasilitas" <?= $peraturan['kategori'] == 'fasilitas' ? 'selected' : '' ?>><i class="bi bi-check-circle"></i> Fasilitas</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Teks Peraturan <span class="text-red-500">*</span> <span class="text-xs text-gray-400 font-normal">(Minimal 5 karakter)</span></label>
                    <input type="text" name="teks" value="<?= htmlspecialchars($peraturan['teks']) ?>" class="form-input w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none" required minlength="5" maxlength="255">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Denda <span class="text-xs text-gray-400 font-normal">(Opsional)</span></label>
                    <input type="text" name="denda" value="<?= htmlspecialchars($peraturan['denda']) ?>" class="form-input w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none" placeholder="Contoh: Rp. 1.025.000" maxlength="50">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ada denda • Gunakan format: Rp. 1.025.000</p>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="btn-primary-custom flex items-center gap-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Update Peraturan</button>
                    <a href="index.php" class="btn-secondary-custom">Batal</a>
                </div>
            </form>
        </div>

        <p class="text-center text-[10px] text-[#b8aaa0] mt-8 tracking-wider border-t border-[#f0ebe6] pt-4">© <?= date('Y') ?> Gunung Bismo via Deroduwur · KKN 84.384 UPNVYK</p>
    </main>
</div>
</body>
</html>
