<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

if (!isLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$fauna = getFaunaById($id);

if (!$fauna) {
    $_SESSION['message'] = 'Data fauna tidak ditemukan';
    $_SESSION['message_type'] = 'danger';
    redirect(BASE_URL . 'admin/fauna/index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $nama_ilmiah = trim($_POST['nama_ilmiah']);
    $deskripsi = trim($_POST['deskripsi']);
    $lokasi = trim($_POST['lokasi']);
    $foto = $fauna['foto'];

    $errors = [];

    if (empty($nama)) $errors[] = 'Nama fauna wajib diisi';
    if (!empty($nama) && preg_match('/[0-9]/', $nama)) $errors[] = 'Nama fauna tidak boleh mengandung angka';
    if (!empty($nama) && !preg_match('/^[a-zA-Z\s\-\.]+$/', $nama)) $errors[] = 'Nama fauna hanya boleh terdiri dari huruf dan spasi';
    if (!empty($nama) && strlen($nama) > 100) $errors[] = 'Nama fauna maksimal 100 karakter';
    if (!empty($nama_ilmiah) && strlen($nama_ilmiah) > 100) $errors[] = 'Nama ilmiah maksimal 100 karakter';
    if (!empty($lokasi) && strlen($lokasi) > 100) $errors[] = 'Lokasi maksimal 100 karakter';

    if (!empty($nama)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM fauna WHERE nama = ? AND id != ?");
        $stmt->execute([$nama, $id]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = 'Nama fauna "' . htmlspecialchars($nama) . '" sudah ada. Silakan gunakan nama lain.';
        }
    }

    if (empty($errors)) {
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $upload = uploadFile($_FILES['foto'], UPLOAD_PATH . 'fauna/');
            if ($upload['success']) {
                if ($foto && file_exists(UPLOAD_PATH . 'fauna/' . $foto)) unlink(UPLOAD_PATH . 'fauna/' . $foto);
                $foto = $upload['filename'];
            } else {
                $errors[] = $upload['message'];
            }
        }

        if (empty($errors)) {
            try {
                $stmt = $pdo->prepare("UPDATE fauna SET nama = ?, nama_ilmiah = ?, deskripsi = ?, foto = ?, lokasi = ? WHERE id = ?");
                if ($stmt->execute([$nama, $nama_ilmiah, $deskripsi, $foto, $lokasi, $id])) {
                    $_SESSION['message'] = 'Data fauna "' . htmlspecialchars($nama) . '" berhasil diupdate!';
                    $_SESSION['message_type'] = 'success';
                    redirect(BASE_URL . 'admin/fauna/index.php');
                } else {
                    $errors[] = 'Gagal mengupdate data. Silakan coba lagi.';
                }
            } catch (PDOException $e) {
                $errors[] = 'Error: ' . $e->getMessage();
            }
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
    <title>Edit Fauna - Admin</title>
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

    <aside class="sidebar w-[220px] flex-shrink-0 h-full flex flex-col p-4">
        <div class="flex items-center gap-3 px-2 py-4 mb-6">
            <div class="w-10 h-10 rounded-xl bg-[#E0BE45]/20 flex items-center justify-center text-xl">🏔️</div>
            <div><p class="text-white font-bold text-sm leading-tight">Gunung Bismo</p><p class="text-[#b8c9b0] text-[10px] font-medium tracking-wider">PANEL ADMIN</p></div>
        </div>
        <nav class="flex-1 space-y-1">
            <a href="../dashboard.php" class="nav-link"><span class="icon">📊</span> Dashboard</a>
            <a href="../berita/index.php" class="nav-link"><span class="icon">📰</span> Berita</a>
            <a href="../galeri/index.php" class="nav-link"><span class="icon">🖼️</span> Galeri</a>
            <a href="../flora/index.php" class="nav-link"><span class="icon">🌿</span> Flora</a>
            <a href="index.php" class="nav-link active"><span class="icon">🐾</span> Fauna</a>
            <a href="../peraturan/index.php" class="nav-link"><span class="icon">📋</span> Peraturan</a>
            <a href="../spot-jalur/index.php" class="nav-link"><span class="icon">📍</span> Spot Jalur</a>
        </nav>
        <div class="pt-4 border-t border-white/10 mt-auto">
            <a href="../logout.php" class="nav-link text-red-300/70 hover:text-red-300"><span class="icon">🚪</span> Keluar</a>
            <p class="text-[10px] text-white/30 text-center mt-3 tracking-wider">v1.0 • KKN 84.384</p>
        </div>
    </aside>

    <main class="flex-1 overflow-y-auto p-6 md:p-8">
        <div class="flex items-center justify-between mb-6 animate-in delay-1">
            <div>
                <p class="text-sm text-[#8a7e72] font-medium">🐾 Edit Satwa</p>
                <h1 class="text-2xl font-bold text-[#1e3a2a]">Edit Fauna</h1>
                <p class="text-sm text-[#8a7e72]">Perbarui data fauna</p>
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

                <div class="mb-6 flex items-center gap-4 p-4 bg-[#faf7f2] rounded-xl border border-[#f0ebe6]">
                    <div class="w-20 h-20 rounded-lg overflow-hidden border border-[#e0d8d0] flex-shrink-0">
                        <?php if (!empty($fauna['foto']) && file_exists(UPLOAD_PATH . 'fauna/' . $fauna['foto'])): ?>
                        <img src="<?= BASE_URL ?>uploads/fauna/<?= htmlspecialchars($fauna['foto']) ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-2xl text-[#b8aaa0] bg-[#f0ebe6]">🐾</div>
                        <?php endif; ?>
                    </div>
                    <div><p class="text-sm font-medium text-[#1e3a2a]">Foto saat ini</p><p class="text-xs text-[#8a7e72]">Upload foto baru untuk mengganti</p></div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Nama Fauna <span class="text-red-500">*</span> <span class="text-xs text-gray-400 font-normal">(Tanpa angka)</span></label>
                    <input type="text" name="nama" value="<?= htmlspecialchars($fauna['nama']) ?>" class="form-input w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none" required maxlength="100" oninput="this.value = this.value.replace(/[0-9]/g, '')">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Nama Ilmiah <span class="text-xs text-gray-400 font-normal">(Opsional)</span></label>
                    <input type="text" name="nama_ilmiah" value="<?= htmlspecialchars($fauna['nama_ilmiah']) ?>" class="form-input w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none" maxlength="100">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Deskripsi <span class="text-xs text-gray-400 font-normal">(Opsional)</span></label>
                    <textarea name="deskripsi" rows="4" class="form-input w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none"><?= htmlspecialchars($fauna['deskripsi']) ?></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Lokasi <span class="text-xs text-gray-400 font-normal">(Opsional)</span></label>
                    <input type="text" name="lokasi" value="<?= htmlspecialchars($fauna['lokasi']) ?>" class="form-input w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none" maxlength="100" placeholder="Contoh: Hutan Pos 2">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Ganti Foto <span class="text-xs text-gray-400 font-normal">(Opsional)</span></label>
                    <input type="file" name="foto" accept="image/*" class="form-input w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah foto • Maks 5MB, JPG/PNG/GIF/WEBP</p>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="btn-primary-custom flex items-center gap-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Update Fauna</button>
                    <a href="index.php" class="btn-secondary-custom">Batal</a>
                </div>
            </form>
        </div>

        <p class="text-center text-[10px] text-[#b8aaa0] mt-8 tracking-wider border-t border-[#f0ebe6] pt-4">© <?= date('Y') ?> Gunung Bismo via Deroduwur · KKN 84.384 UPNVYK</p>
    </main>
</div>
</body>
</html>