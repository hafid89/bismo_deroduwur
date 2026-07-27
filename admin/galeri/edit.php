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

    // 9. Cek upload foto baru
    $foto_baru = $foto['foto'];
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        // Cek ukuran file
        if ($_FILES['foto']['size'] > 5 * 1024 * 1024) {
            $errors[] = 'Ukuran foto terlalu besar. Maksimal 5MB';
        } else {
            // Cek tipe file
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $_FILES['foto']['tmp_name']);
            finfo_close($finfo);
            
            if (!in_array($mime_type, $allowed_types)) {
                $errors[] = 'Tipe file tidak diizinkan. Gunakan JPG, PNG, GIF, atau WEBP';
            } else {
                $upload = uploadFile($_FILES['foto'], GALERI_UPLOAD_PATH);
                if ($upload['success']) {
                    // Hapus foto lama
                    if ($foto_baru && file_exists(GALERI_UPLOAD_PATH . $foto_baru)) {
                        unlink(GALERI_UPLOAD_PATH . $foto_baru);
                    }
                    $foto_baru = $upload['filename'];
                } else {
                    $errors[] = $upload['message'];
                }
            }
        }
    }

    // Jika ada error, tampilkan
    if (!empty($errors)) {
        $error = implode('<br>', $errors);
    } else {
        $stmt = $pdo->prepare("UPDATE galeri SET judul = ?, kategori = ?, tag = ?, deskripsi = ?, foto = ? WHERE id = ?");
        if ($stmt->execute([$judul, $kategori, $tag, $deskripsi, $foto_baru, $id])) {
            $_SESSION['message'] = 'Foto "' . htmlspecialchars($judul) . '" berhasil diupdate!';
            $_SESSION['message_type'] = 'success';
            redirect(BASE_URL . 'admin/galeri/index.php');
        } else {
            $errors[] = 'Gagal mengupdate foto. Silakan coba lagi.';
            $error = implode('<br>', $errors);
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
            content: '⚠️';
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
</head>
<body>
<div class="flex h-screen overflow-hidden">

    <!-- ==================== SIDEBAR ==================== -->
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
            <a href="../berita/index.php" class="nav-link"><span class="icon">📰</span> Berita</a>
            <a href="index.php" class="nav-link active"><span class="icon">🖼️</span> Galeri</a>
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

    <!-- ==================== MAIN ==================== -->
    <main class="flex-1 overflow-y-auto p-6 md:p-8">

        <!-- Header -->
        <div class="flex items-center justify-between mb-6 animate-in delay-1">
            <div>
                <p class="text-sm text-[#8a7e72] font-medium">🖼️ Edit Galeri</p>
                <h1 class="text-2xl font-bold text-[#1e3a2a]">Edit Foto</h1>
                <p class="text-sm text-[#8a7e72]">Edit data foto di galeri</p>
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

                <!-- Preview Foto -->
                <div class="mb-6 flex items-center gap-4 p-4 bg-[#faf7f2] rounded-xl border border-[#f0ebe6]">
                    <div class="w-20 h-20 rounded-lg overflow-hidden border border-[#e0d8d0] flex-shrink-0">
                        <?php if (!empty($foto['foto']) && file_exists(GALERI_UPLOAD_PATH . $foto['foto'])): ?>
                        <img src="<?= BASE_URL ?>uploads/galeri/<?= htmlspecialchars($foto['foto']) ?>" 
                             alt="<?= htmlspecialchars($foto['judul']) ?>" 
                             class="w-full h-full object-cover">
                        <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-2xl text-[#b8aaa0] bg-[#f0ebe6]">
                            🖼️
                        </div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-[#1e3a2a]">Foto saat ini</p>
                        <p class="text-xs text-[#8a7e72]">Upload foto baru untuk mengganti</p>
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
                    <p class="text-xs text-gray-400 mt-1">⚠️ Judul tidak boleh mengandung angka</p>
                </div>

                <!-- Kategori -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select name="kategori" class="form-input w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none" required>
                        <option value="jalur" <?= $foto['kategori'] == 'jalur' ? 'selected' : '' ?>>📍 Jalur</option>
                        <option value="ekosistem" <?= $foto['kategori'] == 'ekosistem' ? 'selected' : '' ?>>🌿 Ekosistem</option>
                        <option value="kegiatan" <?= $foto['kategori'] == 'kegiatan' ? 'selected' : '' ?>>🎯 Kegiatan</option>
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

                <!-- Upload Foto Baru -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Ganti Foto <span class="text-xs text-gray-400 font-normal">(Opsional)</span>
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
                        <p class="text-sm text-[#2F5233] font-medium mb-2">Preview:</p>
                        <img id="previewImg" src="#" alt="Preview" class="preview-image w-40 h-32 object-cover rounded-lg shadow-md border border-gray-200">
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Kosongkan jika tidak ingin mengubah foto</p>
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
// Preview image sebelum upload
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

// Validasi form sebelum submit
document.querySelector('form').addEventListener('submit', function(e) {
    const judul = document.querySelector('input[name="judul"]').value.trim();
    const kategori = document.querySelector('select[name="kategori"]').value;
    
    // Cek judul minimal 3 karakter
    if (judul.length < 3) {
        e.preventDefault();
        alert('❌ Judul foto minimal 3 karakter!');
        return false;
    }
    
    // Cek apakah ada angka di judul
    if (/\d/.test(judul)) {
        e.preventDefault();
        alert('❌ Judul foto tidak boleh mengandung angka!');
        return false;
    }
    
    // Cek kategori dipilih
    if (kategori === '') {
        e.preventDefault();
        alert('❌ Silakan pilih kategori!');
        return false;
    }
    
    return true;
});
</script>
</body>
</html>