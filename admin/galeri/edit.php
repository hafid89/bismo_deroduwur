<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

if (!isLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ambil data galeri
$stmt = $pdo->prepare("SELECT * FROM galeri WHERE id = ?");
$stmt->execute([$id]);
$foto = $stmt->fetch();

if (!$foto) {
    $_SESSION['message'] = 'Data foto tidak ditemukan';
    $_SESSION['message_type'] = 'danger';
    redirect(BASE_URL . 'admin/galeri/index.php');
}

// Ambil foto tambahan (slide)
$fotoTambahan = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM galeri_foto WHERE galeri_id = ? ORDER BY urutan, id");
    $stmt->execute([$id]);
    $fotoTambahan = $stmt->fetchAll();
} catch (PDOException $e) {
    // Tabel galeri_foto belum dibuat
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul']);
    $kategori = $_POST['kategori'];
    $tag = trim($_POST['tag']);
    $deskripsi = trim($_POST['deskripsi']);

    // ========== VALIDASI ==========
    $errors = [];

    // 1. Cek judul kosong
    if (empty($judul)) {
        $errors[] = 'Judul foto wajib diisi';
    }

    // 2. Cek judul mengandung angka
    if (!empty($judul) && preg_match('/[0-9]/', $judul)) {
        $errors[] = 'Judul foto tidak boleh mengandung angka';
    }

    // 3. Cek panjang judul
    if (!empty($judul) && strlen($judul) < 3) {
        $errors[] = 'Judul foto minimal 3 karakter';
    }
    if (!empty($judul) && strlen($judul) > 255) {
        $errors[] = 'Judul foto maksimal 255 karakter';
    }

    // 4. Cek kategori dipilih
    if (empty($kategori)) {
        $errors[] = 'Kategori wajib dipilih';
    }

    // 5. Validasi kategori
    $allowed_kategori = ['jalur', 'ekosistem', 'kegiatan'];
    if (!empty($kategori) && !in_array($kategori, $allowed_kategori)) {
        $errors[] = 'Kategori tidak valid. Pilih: jalur, ekosistem, atau kegiatan';
    }

    // 6. Cek panjang tag
    if (!empty($tag) && strlen($tag) > 50) {
        $errors[] = 'Tag maksimal 50 karakter';
    }

    // 7. Cek tag tidak boleh spasi
    if (!empty($tag) && strpos($tag, ' ') !== false) {
        $errors[] = 'Tag tidak boleh mengandung spasi. Gunakan tanda koma (,) untuk memisahkan';
    }

    // 8. Cek duplikat judul (kecuali dirinya sendiri)
    if (!empty($judul)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM galeri WHERE judul = ? AND id != ?");
        $stmt->execute([$judul, $id]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = 'Judul foto "' . htmlspecialchars($judul) . '" sudah ada. Silakan gunakan judul lain.';
        }
    }

    // 9. Validasi foto sampul baru (opsional)
    $coverFile = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
        $coverFile = $_FILES['foto'];
        $msg = validateImageFile($coverFile);
        if ($msg !== '') {
            $errors[] = $msg;
        }
    }

    // 10. Validasi foto tambahan (slide)
    $extraFiles = normalizeUploadedFiles($_FILES['foto_tambahan'] ?? null);
    $hapusIds = array_map('intval', (array)($_POST['hapus_foto'] ?? []));
    $jumlahDihapus = count(array_intersect($hapusIds, array_map('intval', array_column($fotoTambahan, 'id'))));
    if (count($fotoTambahan) - $jumlahDihapus + count($extraFiles) > 9) {
        $errors[] = 'Maksimal 10 foto per galeri (1 sampul + 9 tambahan)';
    }
    foreach ($extraFiles as $f) {
        $msg = validateImageFile($f);
        if ($msg !== '') {
            $errors[] = $msg;
        }
    }

    // Jika ada error, tampilkan
    if (!empty($errors)) {
        $error = implode('<br>', $errors);
    } else {
        $uploadedNew = [];          // file baru yang diupload (untuk dibersihkan bila gagal)
        $foto_baru = $foto['foto'];
        $foto_lama_dihapus = null;
        $extraUploaded = [];

        // Upload sampul baru
        if ($coverFile) {
            $up = uploadFile($coverFile, GALERI_UPLOAD_PATH);
            if ($up['success']) {
                $foto_baru = $up['filename'];
                $uploadedNew[] = $up['filename'];
                $foto_lama_dihapus = $foto['foto'];
            } else {
                $errors[] = $up['message'];
            }
        }

        // Upload foto tambahan baru
        if (empty($errors)) {
            foreach ($extraFiles as $f) {
                $up = uploadFile($f, GALERI_UPLOAD_PATH);
                if (!$up['success']) {
                    $errors[] = $up['message'];
                    break;
                }
                $extraUploaded[] = $up['filename'];
                $uploadedNew[] = $up['filename'];
            }
        }

        if (!empty($errors)) {
            foreach ($uploadedNew as $u) {
                @unlink(GALERI_UPLOAD_PATH . $u);
            }
            $error = implode('<br>', $errors);
        } else {
            try {
                $pdo->beginTransaction();

                $stmt = $pdo->prepare("UPDATE galeri SET judul = ?, kategori = ?, tag = ?, deskripsi = ?, foto = ? WHERE id = ?");
                $stmt->execute([$judul, $kategori, $tag, $deskripsi, $foto_baru, $id]);

                // Hapus foto tambahan yang dicentang
                $fileUntukDihapus = [];
                $stmtCari = $pdo->prepare("SELECT foto FROM galeri_foto WHERE id = ? AND galeri_id = ?");
                $stmtHapus = $pdo->prepare("DELETE FROM galeri_foto WHERE id = ? AND galeri_id = ?");
                foreach ($hapusIds as $fid) {
                    $stmtCari->execute([$fid, $id]);
                    if ($row = $stmtCari->fetch()) {
                        $stmtHapus->execute([$fid, $id]);
                        $fileUntukDihapus[] = basename($row['foto']);
                    }
                }

                // Simpan foto tambahan baru
                if ($extraUploaded) {
                    $stmtMax = $pdo->prepare("SELECT COALESCE(MAX(urutan), 0) FROM galeri_foto WHERE galeri_id = ?");
                    $stmtMax->execute([$id]);
                    $urutan = (int)$stmtMax->fetchColumn();
                    $stmtIns = $pdo->prepare("INSERT INTO galeri_foto (galeri_id, foto, urutan) VALUES (?, ?, ?)");
                    foreach ($extraUploaded as $namaFile) {
                        $stmtIns->execute([$id, $namaFile, ++$urutan]);
                    }
                }

                $pdo->commit();

                // Hapus file fisik setelah database berhasil
                foreach ($fileUntukDihapus as $f) {
                    @unlink(GALERI_UPLOAD_PATH . $f);
                }
                if ($foto_lama_dihapus) {
                    @unlink(GALERI_UPLOAD_PATH . basename($foto_lama_dihapus));
                }

                $_SESSION['message'] = 'Foto "' . htmlspecialchars($judul) . '" berhasil diupdate!';
                $_SESSION['message_type'] = 'success';
                redirect(BASE_URL . 'admin/galeri/index.php');
            } catch (Throwable $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                foreach ($uploadedNew as $u) {
                    @unlink(GALERI_UPLOAD_PATH . $u);
                }
                $error = 'Gagal mengupdate galeri. Pastikan tabel galeri_foto sudah dibuat.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Galeri - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; transition: all 0.2s ease; }

        body {
            background: #f5f0eb;
            background-image: radial-gradient(circle at 10% 20%, rgba(74, 122, 78, 0.03) 0%, transparent 50%);
        }

        .sidebar {
            background: linear-gradient(180deg, #1e3a2a 0%, #2a4a35 100%);
            box-shadow: 4px 0 20px rgba(0,0,0,0.08);
        }
        .sidebar .nav-link {
            padding: 10px 16px;
            border-radius: 10px;
            color: rgba(255,255,255,0.65);
            font-weight: 500;
            font-size: 14px;
            transition: all 0.25s ease;
            display: block;
        }
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.08);
            color: #fff;
            transform: translateX(4px);
        }
        .sidebar .nav-link.active {
            background: rgba(224, 190, 69, 0.15);
            color: #E0BE45;
            box-shadow: inset 3px 0 0 #E0BE45;
        }
        .sidebar .nav-link .icon { margin-right: 10px; }

        .form-input {
            transition: all 0.3s ease;
        }
        .form-input:focus {
            border-color: #2F5233;
            box-shadow: 0 0 0 3px rgba(47, 82, 51, 0.1);
        }

        .btn-primary-custom {
            background: #2F5233;
            color: #fff;
            padding: 10px 28px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary-custom:hover {
            background: #1e3a2a;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(47, 82, 51, 0.2);
        }

        .btn-secondary-custom {
            background: #f0ebe6;
            color: #5c4e42;
            padding: 10px 28px;
            border-radius: 12px;
            font-weight: 500;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        .btn-secondary-custom:hover {
            background: #e0d8d0;
        }

        .error-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .error-list li {
            padding: 4px 0;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }
        .error-list li::before {
            content: '⚠';
            flex-shrink: 0;
        }
        .alert-error {
            background: #fce4ec;
            border-left: 4px solid #ef5350;
            color: #5c1a1a;
            padding: 12px 16px;
            border-radius: 10px;
        }

        .preview-image {
            transition: all 0.3s ease;
        }
        .preview-image:hover {
            transform: scale(1.02);
        }

        .card-form {
            background: #fff;
            border-radius: 16px;
            padding: 28px 32px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
            border: 1px solid rgba(0,0,0,0.03);
        }

        .badge-kategori {
            font-size: 10px;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
            display: inline-block;
        }
        .badge-jalur { background: #e8f5e9; color: #2e7d32; }
        .badge-ekosistem { background: #e3f2fd; color: #1565c0; }
        .badge-kegiatan { background: #fff3e0; color: #e65100; }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f0ebe6; border-radius: 8px; }
        ::-webkit-scrollbar-thumb { background: #d5cdc4; border-radius: 8px; }
        ::-webkit-scrollbar-thumb:hover { background: #b8aaa0; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in {
            animation: fadeUp 0.45s ease forwards;
            opacity: 0;
        }
        .delay-1 { animation-delay: 0.05s; }
        .delay-2 { animation-delay: 0.1s; }
        .delay-3 { animation-delay: 0.15s; }
        .delay-4 { animation-delay: 0.2s; }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
<div class="flex h-screen overflow-hidden">

    <!-- ==================== SIDEBAR ==================== -->
    <aside class="sidebar w-[220px] flex-shrink-0 h-full flex flex-col p-4">
        <div class="flex items-center gap-3 px-2 py-4 mb-6">
            <div class="w-10 h-10 rounded-xl bg-[#E0BE45]/20 flex items-center justify-center text-xl"><i class="bi bi-person-circle text-lg"></i></div>
            <div>
                <p class="text-white font-bold text-sm leading-tight">Gunung Bismo</p>
                <p class="text-[#b8c9b0] text-[10px] font-medium tracking-wider">PANEL ADMIN</p>
            </div>
        </div>

        <nav class="flex-1 space-y-1">
            <a href="../dashboard.php" class="nav-link"><span class="icon"><i class="bi bi-bar-chart"></i></span> Dashboard</a>
            <a href="../berita/index.php" class="nav-link"><span class="icon"><i class="bi bi-newspaper"></i></span> Berita</a>
            <a href="index.php" class="nav-link active"><span class="icon"><i class="bi bi-image"></i></span> Galeri</a>
            <a href="../flora/index.php" class="nav-link"><span class="icon"><i class="bi bi-tree"></i></span> Flora</a>
            <a href="../fauna/index.php" class="nav-link"><span class="icon"><i class="bi bi-feather"></i></span> Fauna</a>
            <a href="../peraturan/index.php" class="nav-link"><span class="icon"><i class="bi bi-list-check"></i></span> Peraturan</a>
            <a href="../spot-jalur/index.php" class="nav-link"><span class="icon"><i class="bi bi-geo-alt"></i></span> Spot Jalur</a>
        </nav>

        <div class="pt-4 border-t border-white/10 mt-auto">
            <a href="../logout.php" class="nav-link text-red-300/70 hover:text-red-300"><span class="icon"><i class="bi bi-box-arrow-left"></i></span> Keluar</a>
            <p class="text-[10px] text-white/30 text-center mt-3 tracking-wider">v1.0 • KKN 84.384</p>
        </div>
    </aside>

    <!-- ==================== MAIN ==================== -->
    <main class="flex-1 overflow-y-auto p-6 md:p-8">

        <!-- Header -->
        <div class="flex items-center justify-between mb-6 animate-in delay-1">
            <div>
                <p class="text-sm text-[#8a7e72] font-medium"><i class="bi bi-image"></i> Edit Galeri</p>
                <h1 class="text-2xl font-bold text-[#1e3a2a]">Edit Foto</h1>
                <p class="text-sm text-[#8a7e72]">Edit data dan foto di galeri</p>
            </div>
            <a href="index.php" class="text-[#4a7a4e] hover:text-[#2a4a35] font-medium flex items-center gap-1 transition duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>

        <!-- Alert Error -->
        <?php if ($error): ?>
        <div class="alert-error mb-5 animate-in delay-2">
            <div class="font-semibold mb-1">Terdapat kesalahan:</div>
            <ul class="error-list text-sm">
                <?php foreach (explode('<br>', $error) as $err): ?>
                <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <!-- Form -->
        <div class="card-form animate-in delay-2">
            <form method="POST" action="" enctype="multipart/form-data">

                <!-- Preview Foto Saat Ini -->
                <div class="mb-6 flex items-center gap-4 p-4 bg-[#faf7f2] rounded-xl border border-[#f0ebe6]">
                    <div class="w-20 h-20 rounded-lg overflow-hidden border border-[#e0d8d0] flex-shrink-0">
                        <?php if (!empty($foto['foto']) && file_exists(GALERI_UPLOAD_PATH . $foto['foto'])): ?>
                        <img src="<?= BASE_URL ?>uploads/galeri/<?= htmlspecialchars($foto['foto']) ?>"
                             alt="<?= htmlspecialchars($foto['judul']) ?>"
                             class="w-full h-full object-cover">
                        <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-2xl text-[#b8aaa0] bg-[#f0ebe6]">
                            <i class="bi bi-image"></i>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-[#1e3a2a]">Foto sampul saat ini</p>
                        <p class="text-xs text-[#8a7e72]">Total <?= 1 + count($fotoTambahan) ?> foto di galeri ini</p>
                    </div>
                </div>

                <!-- Judul -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Judul Foto <span class="text-red-500">*</span>
                        <span class="text-xs text-gray-400 font-normal">(Minimal 3 karakter, tanpa angka)</span>
                    </label>
                    <input type="text" name="judul" value="<?= htmlspecialchars($foto['judul']) ?>"
                           class="form-input w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none"
                           placeholder="Contoh: Pemandangan Puncak Indraprasta"
                           required
                           minlength="3"
                           maxlength="255"
                           oninput="this.value = this.value.replace(/[0-9]/g, '')">
                    <p class="text-xs text-gray-400 mt-1"><i class="bi bi-exclamation-triangle"></i> Judul tidak boleh mengandung angka</p>
                </div>

                <!-- Kategori -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select name="kategori" class="form-input w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none" required>
                        <option value="jalur" <?= $foto['kategori'] == 'jalur' ? 'selected' : '' ?>>Jalur</option>
                        <option value="ekosistem" <?= $foto['kategori'] == 'ekosistem' ? 'selected' : '' ?>>Ekosistem</option>
                        <option value="kegiatan" <?= $foto['kategori'] == 'kegiatan' ? 'selected' : '' ?>>Kegiatan</option>
                    </select>
                    <span class="badge-kategori <?= $foto['kategori'] == 'jalur' ? 'badge-jalur' : ($foto['kategori'] == 'ekosistem' ? 'badge-ekosistem' : 'badge-kegiatan') ?> mt-2 inline-block">
                        Kategori saat ini: <?= ucfirst($foto['kategori']) ?>
                    </span>
                </div>

                <!-- Tag -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Tag <span class="text-xs text-gray-400 font-normal">(Opsional)</span>
                    </label>
                    <input type="text" name="tag" value="<?= htmlspecialchars($foto['tag']) ?>"
                           class="form-input w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none"
                           placeholder="Contoh: puncak,spot,flora"
                           maxlength="50">
                    <p class="text-xs text-gray-400 mt-1">Maksimal 50 karakter, gunakan koma (,) untuk memisahkan tag</p>
                </div>

                <!-- Deskripsi -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Deskripsi <span class="text-xs text-gray-400 font-normal">(Opsional)</span>
                    </label>
                    <textarea name="deskripsi" rows="3"
                              class="form-input w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none"
                              placeholder="Tambahkan deskripsi foto..."><?= htmlspecialchars($foto['deskripsi']) ?></textarea>
                </div>

                <!-- Ganti Foto Sampul -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Ganti Foto Sampul <span class="text-xs text-gray-400 font-normal">(Opsional)</span>
                    </label>
                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:bg-[#faf7f2] transition duration-300">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span> atau drag & drop</p>
                                <p class="text-xs text-gray-500">JPG, PNG, GIF, WEBP (Maks 5MB)</p>
                            </div>
                            <input type="file" name="foto" accept="image/*" class="hidden" onchange="previewImage(this)">
                        </label>
                    </div>
                    <div id="imagePreview" class="mt-3 hidden">
                        <p class="text-sm text-[#2F5233] font-medium mb-2">Preview sampul baru:</p>
                        <img id="previewImg" src="#" alt="Preview" class="preview-image w-40 h-32 object-cover rounded-lg shadow-md border border-gray-200">
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Kosongkan jika tidak ingin mengubah foto sampul</p>
                </div>

                <!-- Foto Tambahan (Slide) -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Foto Tambahan (Slide) <span class="text-xs text-gray-400 font-normal">(Opsional, maks 9 foto)</span>
                    </label>

                    <?php if (!empty($fotoTambahan)): ?>
                    <p class="text-xs text-gray-500 mb-2">Foto tambahan saat ini. Centang "Hapus" lalu klik Update untuk menghapus.</p>
                    <div class="flex flex-wrap gap-3 mb-4">
                        <?php foreach ($fotoTambahan as $ft): ?>
                        <div>
                            <img src="<?= BASE_URL ?>uploads/galeri/<?= htmlspecialchars($ft['foto']) ?>" alt=""
                                 class="w-28 h-24 object-cover rounded-lg border border-gray-200">
                            <label class="mt-1 flex items-center gap-1 text-xs text-red-600 cursor-pointer">
                                <input type="checkbox" name="hapus_foto[]" value="<?= (int)$ft['id'] ?>"> Hapus
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <input type="file" name="foto_tambahan[]" accept="image/*" multiple
                           class="block w-full rounded-xl border border-gray-300 bg-white p-2 text-sm"
                           onchange="previewExtra(this)">
                    <p class="text-xs text-gray-500 mt-2">Pilih beberapa foto sekaligus. Foto ini tampil sebagai slide setelah foto sampul.</p>
                    <div id="extraPreview" class="mt-3 flex flex-wrap gap-2"></div>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex gap-3">
                    <button type="submit" class="btn-primary-custom flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Foto
                    </button>
                    <a href="index.php" class="btn-secondary-custom">Batal</a>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <p class="text-center text-[10px] text-[#b8aaa0] mt-8 tracking-wider border-t border-[#f0ebe6] pt-4">
            © <?= date('Y') ?> Gunung Bismo via Deroduwur · KKN 84.384 UPNVYK
        </p>
    </main>
</div>

<script>
// Preview sampul baru
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Preview foto tambahan baru
function previewExtra(input) {
    const box = document.getElementById('extraPreview');
    box.innerHTML = '';
    Array.from(input.files || []).forEach(function(file) {
        const img = document.createElement('img');
        img.className = 'preview-image w-28 h-24 object-cover rounded-lg shadow-md border border-gray-200';
        img.alt = file.name;
        img.src = URL.createObjectURL(file);
        box.appendChild(img);
    });
}

// Validasi form sebelum submit
document.querySelector('form').addEventListener('submit', function(e) {
    const judul = document.querySelector('input[name="judul"]').value.trim();
    const kategori = document.querySelector('select[name="kategori"]').value;

    if (judul.length < 3) {
        e.preventDefault();
        alert('❌ Judul foto minimal 3 karakter!');
        return false;
    }

    if (/\d/.test(judul)) {
        e.preventDefault();
        alert('❌ Judul foto tidak boleh mengandung angka!');
        return false;
    }

    if (kategori === '') {
        e.preventDefault();
        alert('❌ Silakan pilih kategori!');
        return false;
    }

    const semuaFile = []
        .concat(Array.from(document.querySelector('input[name="foto"]').files || []))
        .concat(Array.from(document.querySelector('input[name="foto_tambahan[]"]').files || []));

    for (const f of semuaFile) {
        if (/\.(heic|heif)$/i.test(f.name)) {
            e.preventDefault();
            alert('❌ ' + f.name + ': format HEIC/HEIF belum didukung. Konversi ke JPG, PNG, atau WEBP.');
            return false;
        }
        if (f.size > 5 * 1024 * 1024) {
            e.preventDefault();
            alert('❌ ' + f.name + ' lebih dari 5MB!');
            return false;
        }
    }

    return true;
});
</script>
</body>
</html>