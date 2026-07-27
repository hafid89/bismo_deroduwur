<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

if (!isLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

$error = '';
$success = '';
$old_judul = '';
$old_isi = '';
$old_tanggal = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul']);
    $isi = trim($_POST['isi']);
    $tanggal = $_POST['tanggal'];
    
    // Simpan nilai lama untuk ditampilkan kembali
    $old_judul = $judul;
    $old_isi = $isi;
    $old_tanggal = $tanggal;

    // ========== VALIDASI ==========
    $errors = [];

    // 1. Cek apakah judul kosong
    if (empty($judul)) {
        $errors[] = 'Judul berita wajib diisi';
    }

    // 2. Cek apakah isi kosong
    if (empty($isi)) {
        $errors[] = 'Isi berita wajib diisi';
    }

    // 3. Cek apakah tanggal kosong
    if (empty($tanggal)) {
        $errors[] = 'Tanggal berita wajib diisi';
    }

    // 4. Cek apakah judul mengandung angka di awal (opsional)
    if (!empty($judul) && preg_match('/^[0-9]/', $judul)) {
        $errors[] = 'Judul berita tidak boleh diawali dengan angka';
    }

    // 5. Cek panjang judul (minimal 5, maksimal 255)
    if (!empty($judul) && strlen($judul) < 5) {
        $errors[] = 'Judul berita minimal 5 karakter';
    }
    if (!empty($judul) && strlen($judul) > 255) {
        $errors[] = 'Judul berita maksimal 255 karakter';
    }

    // 6. Cek panjang isi (minimal 10 karakter)
    if (!empty($isi) && strlen($isi) < 10) {
        $errors[] = 'Isi berita minimal 10 karakter';
    }

    // 7. Cek apakah judul sudah ada di database (duplikat)
    if (!empty($judul)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM berita WHERE judul = ?");
        $stmt->execute([$judul]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = 'Judul berita "' . htmlspecialchars($judul) . '" sudah ada. Silakan gunakan judul lain.';
        }
    }

    // 8. Cek apakah tanggal valid
    if (!empty($tanggal)) {
        $dateParts = explode('-', $tanggal);
        if (count($dateParts) !== 3 || !checkdate($dateParts[1], $dateParts[2], $dateParts[0])) {
            $errors[] = 'Format tanggal tidak valid';
        }
    }

    // 9. Cek apakah tanggal tidak lebih dari hari ini
    if (!empty($tanggal) && $tanggal > date('Y-m-d')) {
        $errors[] = 'Tanggal tidak boleh lebih dari hari ini';
    }

    // 10. Cek file upload
    $foto = '';
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadFile($_FILES['foto'], BERITA_UPLOAD_PATH);
        if ($upload['success']) {
            $foto = $upload['filename'];
        } else {
            $errors[] = $upload['message'];
        }
    }

    // Jika ada error, tampilkan
    if (!empty($errors)) {
        $error = implode('<br>', $errors);
    } else {
        // Buat slug
        $slug = createSlug($judul);
        
        // Cek slug unik
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM berita WHERE slug = ?");
        $stmt->execute([$slug]);
        if ($stmt->fetchColumn() > 0) {
            $slug .= '-' . uniqid();
        }

        // Simpan ke database
        $stmt = $pdo->prepare("INSERT INTO berita (judul, slug, isi, foto, tanggal) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$judul, $slug, $isi, $foto, $tanggal])) {
            $_SESSION['message'] = 'Berita "' . htmlspecialchars($judul) . '" berhasil ditambahkan!';
            $_SESSION['message_type'] = 'success';
            redirect(BASE_URL . 'admin/berita/index.php');
        } else {
            $errors[] = 'Gagal menambahkan berita. Silakan coba lagi.';
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
    <title>Tambah Berita - Admin</title>
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
            content: '<i class="bi bi-exclamation-triangle"></i>';
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
            <a href="index.php" class="block py-2 px-4 bg-white/10 rounded-lg hover:bg-white/20 transition duration-300">Kelola Berita</a>
            <a href="../galeri/index.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300">Kelola Galeri</a>
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
                    <h1 class="text-3xl font-bold text-[#2F5233]"><i class="bi bi-newspaper"></i> Tambah Berita</h1>
                    <p class="text-[#5C5C50] text-sm mt-1">Tambahkan berita atau informasi terbaru</p>
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

            <form method="POST" action="" enctype="multipart/form-data" class="bg-white rounded-xl shadow-lg p-8" id="beritaForm">
                <!-- Judul Berita -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Judul Berita <span class="text-red-500">*</span>
                        <span class="text-xs text-gray-400 font-normal">(Minimal 5 karakter)</span>
                    </label>
                    <input type="text" name="judul" value="<?= htmlspecialchars($old_judul) ?>" 
                           class="form-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none" 
                           placeholder="Masukkan judul berita..." 
                           required
                           minlength="5"
                           maxlength="255">
                    <p class="text-xs text-gray-400 mt-1">Maksimal 255 karakter</p>
                </div>

                <!-- Tanggal -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Tanggal <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal" value="<?= htmlspecialchars($old_tanggal ?: date('Y-m-d')) ?>" 
                           class="form-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none" 
                           required
                           max="<?= date('Y-m-d') ?>">
                    <p class="text-xs text-gray-400 mt-1">Tanggal tidak boleh lebih dari hari ini</p>
                </div>

                <!-- Foto -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Foto <span class="text-xs text-gray-400 font-normal">(Opsional)</span>
                    </label>
                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:bg-[#FAF7F2] transition duration-300">
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
                        <img id="previewImg" src="#" alt="Preview" class="preview-image w-40 h-32 object-cover rounded-lg shadow-md">
                    </div>
                </div>

                <!-- Isi Berita -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Isi Berita <span class="text-red-500">*</span>
                        <span class="text-xs text-gray-400 font-normal">(Minimal 10 karakter)</span>
                    </label>
                    <textarea name="isi" rows="10" 
                              class="form-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none" 
                              placeholder="Tulis isi berita di sini..." 
                              required
                              minlength="10"><?= htmlspecialchars($old_isi) ?></textarea>
                    <p class="text-xs text-gray-400 mt-1">Gunakan HTML untuk formatting (paragraf, list, dll)</p>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex gap-4">
                    <button type="submit" class="bg-[#2F5233] hover:bg-[#4A7A4E] text-white px-8 py-3 rounded-full font-semibold transition duration-300 transform hover:scale-105 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Berita
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
document.getElementById('beritaForm').addEventListener('submit', function(e) {
    const judul = document.querySelector('input[name="judul"]').value.trim();
    const isi = document.querySelector('textarea[name="isi"]').value.trim();
    const tanggal = document.querySelector('input[name="tanggal"]').value;
    
    // Cek judul minimal 5 karakter
    if (judul.length < 5) {
        e.preventDefault();
        alert('❌ Judul berita minimal 5 karakter!');
        return false;
    }
    
    // Cek isi minimal 10 karakter
    if (isi.length < 10) {
        e.preventDefault();
        alert('❌ Isi berita minimal 10 karakter!');
        return false;
    }
    
    // Cek tanggal tidak lebih dari hari ini
    const today = new Date().toISOString().split('T')[0];
    if (tanggal > today) {
        e.preventDefault();
        alert('❌ Tanggal tidak boleh lebih dari hari ini!');
        return false;
    }
    
    return true;
});
</script>
</body>
</html>
