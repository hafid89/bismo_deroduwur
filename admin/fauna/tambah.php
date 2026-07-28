<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

if (!isLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

$error = '';
$success = '';
$old_nama = '';
$old_nama_ilmiah = '';
$old_deskripsi = '';
$old_lokasi = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $nama_ilmiah = trim($_POST['nama_ilmiah']);
    $deskripsi = trim($_POST['deskripsi']);
    $lokasi = trim($_POST['lokasi']);
    
    // Simpan nilai lama
    $old_nama = $nama;
    $old_nama_ilmiah = $nama_ilmiah;
    $old_deskripsi = $deskripsi;
    $old_lokasi = $lokasi;

    // ========== VALIDASI ==========
    $errors = [];

    // 1. Cek nama kosong
    if (empty($nama)) {
        $errors[] = 'Nama fauna wajib diisi';
    }

    // 2. Cek nama mengandung angka
    if (!empty($nama) && preg_match('/[0-9]/', $nama)) {
        $errors[] = 'Nama fauna tidak boleh mengandung angka';
    }

    // 3. Cek nama hanya huruf dan spasi
    if (!empty($nama) && !preg_match('/^[a-zA-Z\s\-\.]+$/', $nama)) {
        $errors[] = 'Nama fauna hanya boleh terdiri dari huruf, spasi, tanda pisah (-), dan titik (.)';
    }

    // 4. Cek duplikat nama
    if (!empty($nama)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM fauna WHERE nama = ?");
        $stmt->execute([$nama]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = 'Nama fauna "' . htmlspecialchars($nama) . '" sudah ada di database. Silakan gunakan nama lain.';
        }
    }

    // ========== VALIDASI NAMA ILMIAH ==========
    if (!empty($nama_ilmiah)) {
        // Cek apakah mengandung angka
        if (preg_match('/[0-9]/', $nama_ilmiah)) {
            $errors[] = 'Nama ilmiah tidak boleh mengandung angka';
        }
        
        // Cek format: minimal 2 kata (Genus + Spesies)
        $parts = explode(' ', $nama_ilmiah);
        $filtered = array_filter($parts, function($p) { return trim($p) !== ''; });
        if (count($filtered) < 2) {
            $errors[] = 'Nama ilmiah harus terdiri dari minimal 2 kata (Genus + Spesies)';
        }
        
        // Cek apakah huruf pertama genus kapital
        if (!empty($filtered[0]) && !ctype_upper(substr($filtered[0], 0, 1))) {
            $errors[] = 'Genus (kata pertama) pada nama ilmiah harus dimulai dengan huruf kapital';
        }
        
        // Cek apakah spesies (kata kedua dan seterusnya) lowercase semua
        for ($i = 1; $i < count($filtered); $i++) {
            if ($filtered[$i] !== strtolower($filtered[$i])) {
                $errors[] = 'Spesies (kata setelah genus) harus menggunakan huruf kecil semua';
                break;
            }
        }
    }

    // 5. Cek panjang nama (maksimal 100 karakter)
    if (!empty($nama) && strlen($nama) > 100) {
        $errors[] = 'Nama fauna maksimal 100 karakter';
    }

    // 6. Cek panjang nama ilmiah (maksimal 100 karakter)
    if (!empty($nama_ilmiah) && strlen($nama_ilmiah) > 100) {
        $errors[] = 'Nama ilmiah maksimal 100 karakter';
    }

    // 7. Cek panjang lokasi (maksimal 100 karakter)
    if (!empty($lokasi) && strlen($lokasi) > 100) {
        $errors[] = 'Lokasi maksimal 100 karakter';
    }

    // 8. Cek file upload
    $foto = '';
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadFile($_FILES['foto'], UPLOAD_PATH . 'fauna/');
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
        // Simpan ke database
        try {
            $stmt = $pdo->prepare("INSERT INTO fauna (nama, nama_ilmiah, deskripsi, foto, lokasi) VALUES (?, ?, ?, ?, ?)");
            if ($stmt->execute([$nama, $nama_ilmiah, $deskripsi, $foto, $lokasi])) {
                $_SESSION['message'] = 'Data fauna "' . htmlspecialchars($nama) . '" berhasil ditambahkan!';
                $_SESSION['message_type'] = 'success';
                redirect(BASE_URL . 'admin/fauna/index.php');
            } else {
                $errors[] = 'Gagal menambahkan data. Silakan coba lagi.';
                $error = implode('<br>', $errors);
            }
        } catch (PDOException $e) {
            $errors[] = 'Error: ' . $e->getMessage();
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
    <title>Tambah Fauna - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }
        .form-input {
            transition: all 0.3s ease;
        }
        .form-input:focus {
            border-color: #2F5233;
            box-shadow: 0 0 0 3px rgba(47, 82, 51, 0.1);
            outline: none;
        }
        .preview-image {
            transition: all 0.3s ease;
        }
        .preview-image:hover {
            transform: scale(1.02);
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
        .format-hint {
            font-size: 12px;
            padding: 8px 12px;
            background: #f0fdf4;
            border-radius: 8px;
            border-left: 3px solid #22c55e;
            margin-top: 4px;
        }
        .format-hint code {
            background: #e2e8f0;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-family: monospace;
        }
        .format-hint .example {
            color: #16a34a;
            font-weight: 500;
        }
        .format-hint .wrong {
            color: #dc2626;
            font-weight: 500;
        }
    </style>
</head>
<body class="bg-[#FAF7F2]">
<div class="flex h-screen">
    <!-- Sidebar -->
    <div class="w-64 bg-[#2F5233] text-white p-6">
        <h2 class="text-2xl font-bold mb-8">Panel Admin</h2>
        <nav class="space-y-2">
            <a href="../dashboard.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300">Dashboard</a>
            <a href="../flora/index.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300">Kelola Flora</a>
            <a href="index.php" class="block py-2 px-4 bg-white/10 rounded-lg hover:bg-white/20 transition duration-300">Kelola Fauna</a>
            <a href="../peraturan/index.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300">Kelola Peraturan</a>
            <a href="../logout.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300 text-red-300">Logout</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto">
        <div class="p-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-[#2F5233]">🐾 Tambah Fauna</h1>
                    <p class="text-[#5C5C50] text-sm mt-1">Tambahkan data fauna baru ke database</p>
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

            <form method="POST" action="" enctype="multipart/form-data" class="bg-white rounded-xl shadow-lg p-8" id="faunaForm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Fauna -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">
                            Nama Fauna <span class="text-red-500">*</span>
                            <span class="text-xs text-gray-400 font-normal">(Hanya huruf, tanpa angka)</span>
                        </label>
                        <input type="text" name="nama" value="<?= htmlspecialchars($old_nama) ?>" 
                               class="form-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none" 
                               placeholder="Contoh: Elang Jawa" 
                               required
                               oninput="this.value = this.value.replace(/[0-9]/g, '')">
                        <p class="text-xs text-gray-400 mt-1">⚠️ Nama tidak boleh mengandung angka</p>
                    </div>

                    <!-- Nama Ilmiah -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">
                            Nama Ilmiah
                            <span class="text-xs text-gray-400 font-normal">(Opsional)</span>
                        </label>
                        <input type="text" name="nama_ilmiah" id="nama_ilmiah" 
                               value="<?= htmlspecialchars($old_nama_ilmiah) ?>" 
                               class="form-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none" 
                               placeholder="Contoh: Nisaetus bartelsi">
                        
                        <div class="format-hint">
                            <p class="text-sm text-gray-600">
                                <i class="bi bi-info-circle text-green-600"></i> 
                                Format: <span class="example">Genus huruf kapital</span> + <span class="example">spesies huruf kecil</span>
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                Contoh: <code>Nisaetus bartelsi</code> ✓ | <code>nisaetus Bartelsi</code> ✗
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Deskripsi
                        <span class="text-xs text-gray-400 font-normal">(Opsional)</span>
                    </label>
                    <textarea name="deskripsi" rows="4" 
                              class="form-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none" 
                              placeholder="Deskripsikan fauna ini..."><?= htmlspecialchars($old_deskripsi) ?></textarea>
                    <p class="text-xs text-gray-400 mt-1">Maksimal 65.535 karakter</p>
                </div>

                <!-- Lokasi -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Lokasi Ditemukan
                        <span class="text-xs text-gray-400 font-normal">(Opsional)</span>
                    </label>
                    <input type="text" name="lokasi" value="<?= htmlspecialchars($old_lokasi) ?>" 
                           class="form-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none" 
                           placeholder="Contoh: Hutan Pos 2 hingga Pos 3">
                    <p class="text-xs text-gray-400 mt-1">Maksimal 100 karakter</p>
                </div>

                <!-- Upload Foto -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Foto Fauna
                        <span class="text-xs text-gray-400 font-normal">(Opsional)</span>
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

                <!-- Tombol Aksi -->
                <div class="flex gap-4">
                    <button type="submit" class="bg-[#2F5233] hover:bg-[#4A7A4E] text-white px-8 py-3 rounded-full font-semibold transition duration-300 transform hover:scale-105 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Fauna
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
document.getElementById('faunaForm').addEventListener('submit', function(e) {
    const nama = document.querySelector('input[name="nama"]').value.trim();
    const namaIlmiah = document.querySelector('input[name="nama_ilmiah"]').value.trim();
    
    // Cek nama ada angka
    if (/\d/.test(nama)) {
        e.preventDefault();
        alert('❌ Nama fauna tidak boleh mengandung angka!');
        return false;
    }
    
    // Cek nama hanya huruf dan spasi
    if (!/^[a-zA-Z\s\-\.]+$/.test(nama)) {
        e.preventDefault();
        alert('❌ Nama fauna hanya boleh terdiri dari huruf, spasi, tanda pisah (-), dan titik (.)!');
        return false;
    }
    
    // Validasi nama ilmiah jika diisi
    if (namaIlmiah !== '') {
        // Cek apakah mengandung angka
        if (/\d/.test(namaIlmiah)) {
            e.preventDefault();
            alert('❌ Nama ilmiah tidak boleh mengandung angka!');
            return false;
        }
        
        const parts = namaIlmiah.split(' ');
        const filtered = parts.filter(p => p !== '');
        
        // Minimal 2 kata
        if (filtered.length < 2) {
            e.preventDefault();
            alert('❌ Nama ilmiah harus terdiri dari minimal 2 kata (Genus + Spesies)!');
            return false;
        }
        
        // Cek huruf pertama genus kapital
        if (filtered[0].charAt(0) !== filtered[0].charAt(0).toUpperCase()) {
            e.preventDefault();
            alert('❌ Genus (kata pertama) pada nama ilmiah harus dimulai dengan huruf kapital!');
            return false;
        }
        
        // Cek spesies lowercase semua
        for (let i = 1; i < filtered.length; i++) {
            if (filtered[i] !== filtered[i].toLowerCase()) {
                e.preventDefault();
                alert('❌ Spesies (kata setelah genus) harus menggunakan huruf kecil semua!');
                return false;
            }
        }
    }
    
    return true;
});

// Mencegah input angka di field nama secara real-time
document.querySelector('input[name="nama"]').addEventListener('input', function() {
    this.value = this.value.replace(/[0-9]/g, '');
});
</script>
</body>
</html>