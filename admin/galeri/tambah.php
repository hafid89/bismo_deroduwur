<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

if (!isLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

$error = '';
$success = '';
$old_judul = '';
$old_kategori = '';
$old_tag = '';
$old_deskripsi = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul']);
    $kategori = $_POST['kategori'];
    $tag = trim($_POST['tag']);
    $deskripsi = trim($_POST['deskripsi']);

    // Simpan nilai lama untuk ditampilkan kembali
    $old_judul = $judul;
    $old_kategori = $kategori;
    $old_tag = $tag;
    $old_deskripsi = $deskripsi;

    // ========== VALIDASI ==========
    $errors = [];

    // 1. Cek apakah judul kosong
    if (empty($judul)) {
        $errors[] = 'Judul foto wajib diisi';
    }

    // 2. Cek apakah judul mengandung angka
    if (!empty($judul) && preg_match('/[0-9]/', $judul)) {
        $errors[] = 'Judul foto tidak boleh mengandung angka';
    }

    // 3. Cek panjang judul (minimal 3, maksimal 255)
    if (!empty($judul) && strlen($judul) < 3) {
        $errors[] = 'Judul foto minimal 3 karakter';
    }
    if (!empty($judul) && strlen($judul) > 255) {
        $errors[] = 'Judul foto maksimal 255 karakter';
    }

    // 4. Cek apakah kategori dipilih
    if (empty($kategori)) {
        $errors[] = 'Kategori wajib dipilih';
    }

    // 5. Validasi kategori
    $allowed_kategori = ['jalur', 'ekosistem', 'kegiatan'];
    if (!empty($kategori) && !in_array($kategori, $allowed_kategori)) {
        $errors[] = 'Kategori tidak valid. Pilih: jalur, ekosistem, atau kegiatan';
    }

    // 6-8. Validasi foto (boleh lebih dari satu, foto pertama jadi sampul)
    $files = normalizeUploadedFiles($_FILES['foto'] ?? null);
    if (count($files) === 0) {
        $errors[] = 'Foto wajib diupload (minimal 1)';
    } elseif (count($files) > 10) {
        $errors[] = 'Maksimal 10 foto per galeri';
    } else {
        foreach ($files as $f) {
            $msg = validateImageFile($f);
            if ($msg !== '') {
                $errors[] = $msg;
            }
        }
    }

    // 9. Cek apakah judul sudah ada di database (duplikat)
    if (!empty($judul)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM galeri WHERE judul = ?");
        $stmt->execute([$judul]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = 'Judul foto "' . htmlspecialchars($judul) . '" sudah ada. Silakan gunakan judul lain.';
        }
    }

    // 10. Cek panjang tag (maksimal 50 karakter)
    if (!empty($tag) && strlen($tag) > 50) {
        $errors[] = 'Tag maksimal 50 karakter';
    }

    // 11. Cek apakah tag mengandung spasi
    if (!empty($tag) && strpos($tag, ' ') !== false) {
        $errors[] = 'Tag tidak boleh mengandung spasi. Gunakan tanda koma (,) untuk memisahkan';
    }

    // Jika ada error, tampilkan
    if (!empty($errors)) {
        $error = implode('<br>', $errors);
    } else {
        // Upload semua file
        $uploaded = [];
        foreach ($files as $f) {
            $up = uploadFile($f, GALERI_UPLOAD_PATH);
            if (!$up['success']) {
                $errors[] = $up['message'];
                break;
            }
            $uploaded[] = $up['filename'];
        }

        if (!empty($errors)) {
            foreach ($uploaded as $u) {
                @unlink(GALERI_UPLOAD_PATH . $u);
            }
            $error = implode('<br>', $errors);
        } else {
            try {
                $pdo->beginTransaction();

                // Foto pertama = sampul (disimpan di tabel galeri)
                $stmt = $pdo->prepare("INSERT INTO galeri (foto, judul, kategori, tag, deskripsi) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$uploaded[0], $judul, $kategori, $tag, $deskripsi]);
                $galeriId = (int)$pdo->lastInsertId();

                // Foto berikutnya = slide tambahan
                $stmtFoto = $pdo->prepare("INSERT INTO galeri_foto (galeri_id, foto, urutan) VALUES (?, ?, ?)");
                foreach (array_slice($uploaded, 1) as $i => $namaFile) {
                    $stmtFoto->execute([$galeriId, $namaFile, $i + 1]);
                }

                $pdo->commit();

                $_SESSION['message'] = 'Galeri "' . htmlspecialchars($judul) . '" berhasil ditambahkan (' . count($uploaded) . ' foto)!';
                $_SESSION['message_type'] = 'success';
                redirect(BASE_URL . 'admin/galeri/index.php');
            } catch (Throwable $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                foreach ($uploaded as $u) {
                    @unlink(GALERI_UPLOAD_PATH . $u);
                }
                $error = 'Gagal menyimpan galeri. Pastikan tabel galeri_foto sudah dibuat.';
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
    <title>Tambah Galeri - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }
        .form-input {
            transition: all 0.3s ease;
        }
        .form-input:focus {
            border-color: #2F5233;
            box-shadow: 0 0 0 3px rgba(47, 82, 51, 0.1);
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
        .preview-image {
            transition: all 0.3s ease;
        }
        .preview-image:hover {
            transform: scale(1.02);
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-[#FAF7F2]">
<div class="flex h-screen">
    <!-- Sidebar -->
    <div class="w-64 bg-[#2F5233] text-white p-6">
        <h2 class="text-2xl font-bold mb-8">Panel Admin</h2>
        <nav class="space-y-2">
            <a href="../dashboard.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300">Dashboard</a>
            <a href="../berita/index.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300">Kelola Berita</a>
            <a href="index.php" class="block py-2 px-4 bg-white/10 rounded-lg hover:bg-white/20 transition duration-300">Kelola Galeri</a>
            <a href="../flora/index.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300">Kelola Flora</a>
            <a href="../fauna/index.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300">Kelola Fauna</a>
            <a href="../peraturan/index.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300">Kelola Peraturan</a>
            <a href="../logout.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300 text-red-300">Logout</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto">
        <div class="p-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-[#2F5233]"><i class="bi bi-image"></i> Tambah Galeri</h1>
                    <p class="text-[#5C5C50] text-sm mt-1">Tambahkan satu atau beberapa foto ke galeri</p>
                </div>
                <a href="index.php" class="text-[#2F5233] hover:text-[#4A7A4E] transition duration-300 flex items-center gap-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
            </div>

            <?php if ($error): ?>
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg mb-4">
                <div class="font-semibold mb-1">Terdapat kesalahan:</div>
                <ul class="error-list text-sm">
                    <?php foreach (explode('<br>', $error) as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <form method="POST" action="" enctype="multipart/form-data" class="bg-white rounded-xl shadow-lg p-8" id="galeriForm">
                <!-- Judul Foto -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Judul Foto <span class="text-red-500">*</span>
                        <span class="text-xs text-gray-400 font-normal">(Minimal 3 karakter, tanpa angka)</span>
                    </label>
                    <input type="text" name="judul" value="<?= htmlspecialchars($old_judul) ?>"
                           class="form-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none"
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
                    <select name="kategori" class="form-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="jalur" <?= $old_kategori == 'jalur' ? 'selected' : '' ?>>Jalur</option>
                        <option value="ekosistem" <?= $old_kategori == 'ekosistem' ? 'selected' : '' ?>>Ekosistem</option>
                        <option value="kegiatan" <?= $old_kategori == 'kegiatan' ? 'selected' : '' ?>>Kegiatan</option>
                    </select>
                </div>

                <!-- Tag -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Tag <span class="text-xs text-gray-400 font-normal">(Opsional)</span>
                    </label>
                    <input type="text" name="tag" value="<?= htmlspecialchars($old_tag) ?>"
                           class="form-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none"
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
                              class="form-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none"
                              placeholder="Tambahkan deskripsi foto..."><?= htmlspecialchars($old_deskripsi) ?></textarea>
                </div>

                <!-- Upload Foto (banyak) -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Foto <span class="text-red-500">*</span>
                        <span class="text-xs text-gray-400 font-normal">(Bisa pilih lebih dari satu, maks 10. Foto pertama jadi sampul)</span>
                    </label>
                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:bg-[#FAF7F2] transition duration-300">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span> atau drag & drop</p>
                                <p class="text-xs text-gray-500">JPG, PNG, GIF, WEBP (Maks 5MB per foto)</p>
                            </div>
                            <input type="file" name="foto[]" accept="image/*" multiple class="hidden" onchange="previewImages(this)" required>
                        </label>
                    </div>
                    <div id="imagePreview" class="mt-3 hidden">
                        <p class="text-sm text-[#2F5233] font-medium mb-2">
                            Preview (<span id="previewCount">0</span> foto, yang pertama jadi sampul):
                        </p>
                        <div id="previewGrid" class="flex flex-wrap gap-2"></div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex gap-4">
                    <button type="submit" class="bg-[#2F5233] hover:bg-[#4A7A4E] text-white px-8 py-3 rounded-full font-semibold transition duration-300 transform hover:scale-105 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Foto
                    </button>
                    <a href="index.php" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-8 py-3 rounded-full font-semibold transition duration-300">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Preview semua foto yang dipilih
function previewImages(input) {
    const grid = document.getElementById('previewGrid');
    const files = Array.from(input.files || []);
    grid.innerHTML = '';
    document.getElementById('previewCount').textContent = files.length;
    document.getElementById('imagePreview').classList.toggle('hidden', files.length === 0);

    files.forEach(function(file, i) {
        const wrap = document.createElement('div');
        wrap.className = 'relative';

        const img = document.createElement('img');
        img.className = 'preview-image w-28 h-24 object-cover rounded-lg shadow-md';
        img.alt = file.name;
        img.src = URL.createObjectURL(file);
        wrap.appendChild(img);

        if (i === 0) {
            const badge = document.createElement('span');
            badge.className = 'absolute left-1 top-1 rounded bg-[#2F5233] px-2 py-0.5 text-[10px] font-semibold text-white';
            badge.textContent = 'Sampul';
            wrap.appendChild(badge);
        }
        grid.appendChild(wrap);
    });
}

// Validasi form sebelum submit
document.getElementById('galeriForm').addEventListener('submit', function(e) {
    const judul = document.querySelector('input[name="judul"]').value.trim();
    const kategori = document.querySelector('select[name="kategori"]').value;
    const foto = document.querySelector('input[name="foto[]"]');

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

    if (!foto.files || foto.files.length === 0) {
        e.preventDefault();
        alert('❌ Foto wajib diupload!');
        return false;
    }

    if (foto.files.length > 10) {
        e.preventDefault();
        alert('❌ Maksimal 10 foto per galeri!');
        return false;
    }

    for (const f of foto.files) {
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