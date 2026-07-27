<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

if (!isLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

$error = '';
$success = '';
$old_kategori = '';
$old_teks = '';
$old_denda = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kategori = $_POST['kategori'];
    $teks = trim($_POST['teks']);
    $denda = trim($_POST['denda']);
    
    // Simpan nilai lama untuk ditampilkan kembali
    $old_kategori = $kategori;
    $old_teks = $teks;
    $old_denda = $denda;

    // ========== VALIDASI ==========
    $errors = [];

    // 1. Cek apakah kategori dipilih
    if (empty($kategori)) {
        $errors[] = 'Kategori wajib dipilih';
    }

    // 2. Validasi kategori (hanya boleh kewajiban, larangan, fasilitas)
    $allowed_kategori = ['kewajiban', 'larangan', 'fasilitas'];
    if (!empty($kategori) && !in_array($kategori, $allowed_kategori)) {
        $errors[] = 'Kategori tidak valid. Pilih: kewajiban, larangan, atau fasilitas';
    }

    // 3. Cek apakah teks kosong
    if (empty($teks)) {
        $errors[] = 'Teks peraturan wajib diisi';
    }

    // 4. Cek panjang teks (minimal 5, maksimal 255)
    if (!empty($teks) && strlen($teks) < 5) {
        $errors[] = 'Teks peraturan minimal 5 karakter';
    }
    if (!empty($teks) && strlen($teks) > 255) {
        $errors[] = 'Teks peraturan maksimal 255 karakter';
    }

    // 5. Cek apakah teks mengandung angka (opsional - untuk larangan mungkin ada angka)
    if (!empty($teks) && preg_match('/[0-9]/', $teks) && $kategori == 'kewajiban') {
        $errors[] = 'Teks peraturan untuk kategori kewajiban tidak boleh mengandung angka';
    }

    // 6. Cek apakah teks sudah ada di database (duplikat)
    if (!empty($teks)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM peraturan WHERE teks = ?");
        $stmt->execute([$teks]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = 'Teks peraturan "' . htmlspecialchars($teks) . '" sudah ada. Silakan gunakan teks lain.';
        }
    }

    // 7. Cek format denda (jika diisi)
    if (!empty($denda)) {
        // Cek apakah format denda valid (contoh: Rp. 1.025.000 atau Rp 1.025.000)
        if (!preg_match('/^Rp\.?\s?[\d\.]+$/', $denda)) {
            $errors[] = 'Format denda tidak valid. Gunakan format: Rp. 1.025.000 atau Rp 1.025.000';
        }
        
        // Cek panjang denda
        if (strlen($denda) > 50) {
            $errors[] = 'Denda maksimal 50 karakter';
        }
    }

    // 8. Cek untuk kategori fasilitas tidak boleh memiliki denda
    if ($kategori == 'fasilitas' && !empty($denda)) {
        $errors[] = 'Kategori fasilitas tidak boleh memiliki denda';
    }

    // Jika ada error, tampilkan
    if (!empty($errors)) {
        $error = implode('<br>', $errors);
    } else {
        // Simpan ke database
        $stmt = $pdo->prepare("INSERT INTO peraturan (kategori, teks, denda) VALUES (?, ?, ?)");
        if ($stmt->execute([$kategori, $teks, $denda ?: null])) {
            $_SESSION['message'] = 'Peraturan "' . htmlspecialchars($teks) . '" berhasil ditambahkan!';
            $_SESSION['message_type'] = 'success';
            redirect(BASE_URL . 'admin/peraturan/index.php');
        } else {
            $errors[] = 'Gagal menambahkan peraturan. Silakan coba lagi.';
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
    <title>Tambah Peraturan - Admin</title>
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
            <a href="../galeri/index.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300">Kelola Galeri</a>
            <a href="../flora/index.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300">Kelola Flora</a>
            <a href="../fauna/index.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300">Kelola Fauna</a>
            <a href="index.php" class="block py-2 px-4 bg-white/10 rounded-lg hover:bg-white/20 transition duration-300">Kelola Peraturan</a>
            <a href="../logout.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300 text-red-300">Logout</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto">
        <div class="p-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-[#2F5233]"><i class="bi bi-list-check"></i> Tambah Peraturan</h1>
                    <p class="text-[#5C5C50] text-sm mt-1">Tambahkan peraturan baru ke database</p>
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

            <form method="POST" action="" class="bg-white rounded-xl shadow-lg p-8" id="peraturanForm">
                <!-- Kategori -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select name="kategori" id="kategori" class="form-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="kewajiban" <?= $old_kategori == 'kewajiban' ? 'selected' : '' ?>>Kewajiban</option>
                        <option value="larangan" <?= $old_kategori == 'larangan' ? 'selected' : '' ?>>Larangan</option>
                        <option value="fasilitas" <?= $old_kategori == 'fasilitas' ? 'selected' : '' ?>>Fasilitas</option>
                    </select>
                    <p class="text-xs text-gray-400 mt-1">Pilih jenis peraturan</p>
                </div>

                <!-- Teks Peraturan -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Teks Peraturan <span class="text-red-500">*</span>
                        <span class="text-xs text-gray-400 font-normal">(Minimal 5 karakter)</span>
                    </label>
                    <input type="text" name="teks" value="<?= htmlspecialchars($old_teks) ?>" 
                           class="form-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none" 
                           placeholder="Contoh: Setiap Pendaki Harus Dalam Kondisi Sehat" 
                           required
                           minlength="5"
                           maxlength="255">
                    <p class="text-xs text-gray-400 mt-1">Maksimal 255 karakter</p>
                </div>

                <!-- Denda -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Denda <span class="text-xs text-gray-400 font-normal">(Opsional)</span>
                    </label>
                    <input type="text" name="denda" value="<?= htmlspecialchars($old_denda) ?>" 
                           class="form-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none" 
                           id="dendaInput"
                           placeholder="Contoh: Rp. 1.025.000">
                    <div id="dendaHint" class="text-xs text-gray-400 mt-1">
                        Gunakan format: Rp. 1.025.000 atau Rp 1.025.000
                    </div>
                    <div id="dendaWarning" class="text-xs text-red-500 mt-1 hidden">
                        <i class="bi bi-exclamation-triangle"></i> Kategori fasilitas tidak boleh memiliki denda
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex gap-4">
                    <button type="submit" class="bg-[#2F5233] hover:bg-[#4A7A4E] text-white px-8 py-3 rounded-full font-semibold transition duration-300 transform hover:scale-105 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Peraturan
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
// Validasi form sebelum submit
document.getElementById('peraturanForm').addEventListener('submit', function(e) {
    const kategori = document.querySelector('select[name="kategori"]').value;
    const teks = document.querySelector('input[name="teks"]').value.trim();
    const denda = document.querySelector('input[name="denda"]').value.trim();
    
    // Cek kategori dipilih
    if (kategori === '') {
        e.preventDefault();
        alert('❌ Silakan pilih kategori!');
        return false;
    }
    
    // Cek teks minimal 5 karakter
    if (teks.length < 5) {
        e.preventDefault();
        alert('❌ Teks peraturan minimal 5 karakter!');
        return false;
    }
    
    // Cek untuk kategori fasilitas tidak boleh memiliki denda
    if (kategori === 'fasilitas' && denda !== '') {
        e.preventDefault();
        alert('❌ Kategori fasilitas tidak boleh memiliki denda!');
        return false;
    }
    
    // Cek format denda jika diisi
    if (denda !== '' && !/^Rp\.?\s?[\d\.]+$/.test(denda)) {
        e.preventDefault();
        alert('❌ Format denda tidak valid! Gunakan format: Rp. 1.025.000 atau Rp 1.025.000');
        return false;
    }
    
    return true;
});

// Validasi real-time untuk denda berdasarkan kategori
document.getElementById('kategori').addEventListener('change', function() {
    const kategori = this.value;
    const dendaInput = document.getElementById('dendaInput');
    const dendaWarning = document.getElementById('dendaWarning');
    const dendaHint = document.getElementById('dendaHint');
    
    if (kategori === 'fasilitas') {
        dendaWarning.classList.remove('hidden');
        dendaHint.classList.add('hidden');
        dendaInput.value = '';
        dendaInput.disabled = true;
        dendaInput.placeholder = 'Tidak dapat diisi untuk kategori fasilitas';
    } else {
        dendaWarning.classList.add('hidden');
        dendaHint.classList.remove('hidden');
        dendaInput.disabled = false;
        dendaInput.placeholder = 'Contoh: Rp. 1.025.000';
    }
});

// Format otomatis untuk input denda
document.getElementById('dendaInput').addEventListener('input', function() {
    let value = this.value;
    // Hanya izinkan Rp, angka, titik, dan spasi
    value = value.replace(/[^Rp\d\s\.]/g, '');
    this.value = value;
});
</script>
</body>
</html>
