<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

if (!isLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

$error = '';
$success = '';
$old_nama = '';
$old_posisi = '';
$old_ketinggian = '';
$old_estimasi_waktu = '';
$old_deskripsi = '';
$old_jenis = 'spot';
$old_urutan = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $posisi = trim($_POST['posisi']);
    $ketinggian = trim($_POST['ketinggian']);
    $estimasi_waktu = trim($_POST['estimasi_waktu']);
    $deskripsi = trim($_POST['deskripsi']);
    $jenis = $_POST['jenis'];
    $urutan = (int)$_POST['urutan'];
    
    // Simpan nilai lama
    $old_nama = $nama;
    $old_posisi = $posisi;
    $old_ketinggian = $ketinggian;
    $old_estimasi_waktu = $estimasi_waktu;
    $old_deskripsi = $deskripsi;
    $old_jenis = $jenis;
    $old_urutan = $urutan;

    // ========== VALIDASI ==========
    $errors = [];

    // 1. Cek nama wajib diisi
    if (empty($nama)) {
        $errors[] = 'Nama spot wajib diisi';
    }

    // 2. Cek nama hanya huruf dan spasi
    if (!empty($nama) && !preg_match('/^[a-zA-Z\s\-\.\']+$/', $nama)) {
        $errors[] = 'Nama spot hanya boleh terdiri dari huruf, spasi, tanda pisah (-), titik (.), dan apostrof (\')';
    }

    // 3. Cek panjang nama (maksimal 100)
    if (!empty($nama) && strlen($nama) > 100) {
        $errors[] = 'Nama spot maksimal 100 karakter';
    }

    // 4. Cek posisi wajib diisi
    if (empty($posisi)) {
        $errors[] = 'Posisi spot wajib diisi';
    }

    // 5. Cek panjang posisi (maksimal 50)
    if (!empty($posisi) && strlen($posisi) > 50) {
        $errors[] = 'Posisi maksimal 50 karakter';
    }

    // 6. Cek ketinggian (opsional)
    if (!empty($ketinggian) && strlen($ketinggian) > 50) {
        $errors[] = 'Ketinggian maksimal 50 karakter';
    }

    // 7. Cek estimasi waktu (opsional)
    if (!empty($estimasi_waktu) && strlen($estimasi_waktu) > 50) {
        $errors[] = 'Estimasi waktu maksimal 50 karakter';
    }

    // 8. Cek jenis wajib dipilih
    if (empty($jenis)) {
        $errors[] = 'Jenis spot wajib dipilih';
    }

    // 9. Validasi jenis
    $allowed_jenis = ['spot', 'flora', 'fauna', 'wilayah'];
    if (!empty($jenis) && !in_array($jenis, $allowed_jenis)) {
        $errors[] = 'Jenis tidak valid. Pilih: spot, flora, fauna, atau wilayah';
    }

    // 10. Cek urutan (harus angka positif)
    if ($urutan < 0) {
        $errors[] = 'Urutan harus berupa angka positif';
    }

    // 11. Cek apakah nama sudah ada di database (duplikat)
    if (!empty($nama)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM spot_jalur WHERE nama = ?");
        $stmt->execute([$nama]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = 'Nama spot "' . htmlspecialchars($nama) . '" sudah ada. Silakan gunakan nama lain.';
        }
    }

    // 12. Cek apakah urutan sudah ada (opsional)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM spot_jalur WHERE urutan = ?");
    $stmt->execute([$urutan]);
    if ($stmt->fetchColumn() > 0) {
        $errors[] = 'Urutan ' . $urutan . ' sudah digunakan. Silakan gunakan urutan lain.';
    }

    // 13. Cek file upload (opsional)
    $foto = '';
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadFile($_FILES['foto'], UPLOAD_PATH . 'spot/');
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
        $stmt = $pdo->prepare("INSERT INTO spot_jalur (nama, posisi, ketinggian, estimasi_waktu, deskripsi, foto, jenis, urutan) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$nama, $posisi, $ketinggian, $estimasi_waktu, $deskripsi, $foto, $jenis, $urutan])) {
            $_SESSION['message'] = 'Spot "' . htmlspecialchars($nama) . '" berhasil ditambahkan!';
            $_SESSION['message_type'] = 'success';
            redirect(BASE_URL . 'admin/spot-jalur/index.php');
        } else {
            $errors[] = 'Gagal menambahkan spot. Silakan coba lagi.';
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
    <title>Tambah Spot Jalur - Admin</title>
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
            content: '⚠️';
            flex-shrink: 0;
        }
        .preview-image {
            transition: all 0.3s ease;
        }
        .preview-image:hover {
            transform: scale(1.02);
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
            <a href="index.php" class="block py-2 px-4 bg-white/10 rounded-lg hover:bg-white/20 transition duration-300">Kelola Spot Jalur</a>
            <a href="../logout.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300 text-red-300">Logout</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto">
        <div class="p-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-[#2F5233]">📍 Tambah Spot Jalur</h1>
                    <p class="text-[#5C5C50] text-sm mt-1">Tambahkan spot baru di sepanjang jalur pendakian</p>
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

            <form method="POST" action="" enctype="multipart/form-data" class="bg-white rounded-xl shadow-lg p-8" id="spotForm">
                <!-- Nama Spot -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Nama Spot <span class="text-red-500">*</span>
                        <span class="text-xs text-gray-400 font-normal">(Hanya huruf dan spasi)</span>
                    </label>
                    <input type="text" name="nama" value="<?= htmlspecialchars($old_nama) ?>" 
                           class="form-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none" 
                           placeholder="Contoh: Basecamp Deroduwur" 
                           required
                           maxlength="100"
                           oninput="this.value = this.value.replace(/[^a-zA-Z\s\-\.\']/g, '')">
                    <p class="text-xs text-gray-400 mt-1">Maksimal 100 karakter, hanya huruf, spasi, -, . , dan '</p>
                </div>

                <!-- Posisi -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Posisi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="posisi" value="<?= htmlspecialchars($old_posisi) ?>" 
                           class="form-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none" 
                           placeholder="Contoh: pos1, puncak, basecamp" 
                           required
                           maxlength="50">
                    <p class="text-xs text-gray-400 mt-1">Maksimal 50 karakter</p>
                </div>

                <!-- Ketinggian -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Ketinggian <span class="text-xs text-gray-400 font-normal">(Opsional)</span>
                    </label>
                    <input type="text" name="ketinggian" value="<?= htmlspecialchars($old_ketinggian) ?>" 
                           class="form-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none" 
                           placeholder="Contoh: 1.555 MDPL"
                           maxlength="50">
                    <p class="text-xs text-gray-400 mt-1">Maksimal 50 karakter</p>
                </div>

                <!-- Estimasi Waktu -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Estimasi Waktu <span class="text-xs text-gray-400 font-normal">(Opsional)</span>
                    </label>
                    <input type="text" name="estimasi_waktu" value="<?= htmlspecialchars($old_estimasi_waktu) ?>" 
                           class="form-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none" 
                           placeholder="Contoh: 75 menit"
                           maxlength="50">
                    <p class="text-xs text-gray-400 mt-1">Maksimal 50 karakter</p>
                </div>

                <!-- Deskripsi -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Deskripsi <span class="text-xs text-gray-400 font-normal">(Opsional)</span>
                    </label>
                    <textarea name="deskripsi" rows="3" 
                              class="form-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none" 
                              placeholder="Deskripsikan spot ini..."><?= htmlspecialchars($old_deskripsi) ?></textarea>
                </div>

                <!-- Jenis -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Jenis <span class="text-red-500">*</span>
                    </label>
                    <select name="jenis" class="form-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none" required>
                        <option value="spot" <?= $old_jenis == 'spot' ? 'selected' : '' ?>>📍 Spot</option>
                        <option value="flora" <?= $old_jenis == 'flora' ? 'selected' : '' ?>>🌿 Flora</option>
                        <option value="fauna" <?= $old_jenis == 'fauna' ? 'selected' : '' ?>>🐾 Fauna</option>
                        <option value="wilayah" <?= $old_jenis == 'wilayah' ? 'selected' : '' ?>>🌄 Wilayah</option>
                    </select>
                </div>

                <!-- Urutan -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Urutan <span class="text-red-500">*</span>
                        <span class="text-xs text-gray-400 font-normal">(Angka positif)</span>
                    </label>
                    <input type="number" name="urutan" value="<?= $old_urutan ?>" 
                           class="form-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none" 
                           required
                           min="0">
                    <p class="text-xs text-gray-400 mt-1">Urutan posisi spot di jalur (0 = basecamp, semakin besar semakin atas)</p>
                </div>

                <!-- Foto -->
                <div class="mb-6">
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

                <!-- Tombol Aksi -->
                <div class="flex gap-4">
                    <button type="submit" class="bg-[#2F5233] hover:bg-[#4A7A4E] text-white px-8 py-3 rounded-full font-semibold transition duration-300 transform hover:scale-105 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Spot
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
// Preview image
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

// Validasi form
document.getElementById('spotForm').addEventListener('submit', function(e) {
    const nama = document.querySelector('input[name="nama"]').value.trim();
    const posisi = document.querySelector('input[name="posisi"]').value.trim();
    const urutan = parseInt(document.querySelector('input[name="urutan"]').value);
    
    // Cek nama hanya huruf dan spasi
    if (!/^[a-zA-Z\s\-\.\']+$/.test(nama)) {
        e.preventDefault();
        alert('❌ Nama spot hanya boleh terdiri dari huruf, spasi, tanda pisah (-), titik (.), dan apostrof (\')!');
        return false;
    }
    
    // Cek posisi
    if (posisi === '') {
        e.preventDefault();
        alert('❌ Posisi spot wajib diisi!');
        return false;
    }
    
    // Cek urutan
    if (isNaN(urutan) || urutan < 0) {
        e.preventDefault();
        alert('❌ Urutan harus berupa angka positif!');
        return false;
    }
    
    return true;
});
</script>
</body>
</html>