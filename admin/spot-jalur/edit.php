<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

if (!isLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ambil data spot
$stmt = $pdo->prepare("SELECT * FROM spot_jalur WHERE id = ?");
$stmt->execute([$id]);
$spot = $stmt->fetch();

if (!$spot) {
    $_SESSION['message'] = 'Spot tidak ditemukan';
    $_SESSION['message_type'] = 'danger';
    redirect(BASE_URL . 'admin/spot-jalur/index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $posisi = trim($_POST['posisi']);
    $ketinggian = trim($_POST['ketinggian']);
    $estimasi_waktu = trim($_POST['estimasi_waktu']);
    $deskripsi = trim($_POST['deskripsi']);
    $jenis = $_POST['jenis'];
    $urutan = (int)$_POST['urutan'];
    $foto = $spot['foto'];

    // ========== VALIDASI ==========
    $errors = [];

    if (empty($nama)) $errors[] = 'Nama spot wajib diisi';
    if (!empty($nama) && !preg_match('/^[a-zA-Z\s\-\.\']+$/', $nama)) {
        $errors[] = 'Nama spot hanya boleh terdiri dari huruf, spasi, tanda pisah (-), titik (.), dan apostrof (\')';
    }
    if (!empty($nama) && strlen($nama) > 100) $errors[] = 'Nama spot maksimal 100 karakter';
    if (empty($posisi)) $errors[] = 'Posisi spot wajib diisi';
    if (!empty($posisi) && strlen($posisi) > 50) $errors[] = 'Posisi maksimal 50 karakter';
    if (!empty($ketinggian) && strlen($ketinggian) > 50) $errors[] = 'Ketinggian maksimal 50 karakter';
    if (!empty($estimasi_waktu) && strlen($estimasi_waktu) > 50) $errors[] = 'Estimasi waktu maksimal 50 karakter';
    if (empty($jenis)) $errors[] = 'Jenis spot wajib dipilih';
    
    $allowed_jenis = ['spot', 'flora', 'fauna', 'wilayah'];
    if (!empty($jenis) && !in_array($jenis, $allowed_jenis)) {
        $errors[] = 'Jenis tidak valid. Pilih: spot, flora, fauna, atau wilayah';
    }
    if ($urutan < 0) $errors[] = 'Urutan harus berupa angka positif';

    // Cek duplikat nama (kecuali dirinya sendiri)
    if (!empty($nama)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM spot_jalur WHERE nama = ? AND id != ?");
        $stmt->execute([$nama, $id]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = 'Nama spot "' . htmlspecialchars($nama) . '" sudah ada. Silakan gunakan nama lain.';
        }
    }

    // Cek duplikat urutan (kecuali dirinya sendiri)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM spot_jalur WHERE urutan = ? AND id != ?");
    $stmt->execute([$urutan, $id]);
    if ($stmt->fetchColumn() > 0) {
        $errors[] = 'Urutan ' . $urutan . ' sudah digunakan. Silakan gunakan urutan lain.';
    }

    // Upload foto baru
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadFile($_FILES['foto'], UPLOAD_PATH . 'spot/');
        if ($upload['success']) {
            if ($foto && file_exists(UPLOAD_PATH . 'spot/' . $foto)) {
                unlink(UPLOAD_PATH . 'spot/' . $foto);
            }
            $foto = $upload['filename'];
        } else {
            $errors[] = $upload['message'];
        }
    }

    if (!empty($errors)) {
        $error = implode('<br>', $errors);
    } else {
        $stmt = $pdo->prepare("UPDATE spot_jalur SET nama = ?, posisi = ?, ketinggian = ?, estimasi_waktu = ?, deskripsi = ?, foto = ?, jenis = ?, urutan = ? WHERE id = ?");
        if ($stmt->execute([$nama, $posisi, $ketinggian, $estimasi_waktu, $deskripsi, $foto, $jenis, $urutan, $id])) {
            $_SESSION['message'] = 'Spot "' . htmlspecialchars($nama) . '" berhasil diupdate!';
            $_SESSION['message_type'] = 'success';
            redirect(BASE_URL . 'admin/spot-jalur/index.php');
        } else {
            $errors[] = 'Gagal mengupdate spot. Silakan coba lagi.';
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
    <title>Edit Spot Jalur - Admin</title>
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
            <a href="../galeri/index.php" class="nav-link"><span class="icon">🖼️</span> Galeri</a>
            <a href="../flora/index.php" class="nav-link"><span class="icon">🌿</span> Flora</a>
            <a href="../fauna/index.php" class="nav-link"><span class="icon">🐾</span> Fauna</a>
            <a href="../peraturan/index.php" class="nav-link"><span class="icon">📋</span> Peraturan</a>
            <a href="index.php" class="nav-link active"><span class="icon">📍</span> Spot Jalur</a>
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
                <p class="text-sm text-[#8a7e72] font-medium">📍 Edit Rute</p>
                <h1 class="text-2xl font-bold text-[#1e3a2a]">Edit Spot Jalur</h1>
                <p class="text-sm text-[#8a7e72]">Perbarui data spot di sepanjang jalur pendakian</p>
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
                        <?php if (!empty($spot['foto']) && file_exists(UPLOAD_PATH . 'spot/' . $spot['foto'])): ?>
                        <img src="<?= BASE_URL ?>uploads/spot/<?= htmlspecialchars($spot['foto']) ?>" 
                             alt="<?= htmlspecialchars($spot['nama']) ?>" 
                             class="w-full h-full object-cover">
                        <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-2xl text-[#b8aaa0] bg-[#f0ebe6]">
                            📍
                        </div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-[#1e3a2a]">Foto saat ini</p>
                        <p class="text-xs text-[#8a7e72]">Upload foto baru untuk mengganti</p>
                    </div>
                </div>

                <!-- Nama Spot -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Nama Spot <span class="text-red-500">*</span>
                        <span class="text-xs text-gray-400 font-normal">(Hanya huruf dan spasi)</span>
                    </label>
                    <input type="text" name="nama" value="<?= htmlspecialchars($spot['nama']) ?>" 
                           class="form-input w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none" 
                           required maxlength="100"
                           oninput="this.value = this.value.replace(/[^a-zA-Z\s\-\.\']/g, '')">
                    <p class="text-xs text-gray-400 mt-1">Maksimal 100 karakter</p>
                </div>

                <!-- Posisi -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Posisi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="posisi" value="<?= htmlspecialchars($spot['posisi']) ?>" 
                           class="form-input w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none" 
                           required maxlength="50">
                </div>

                <!-- Ketinggian -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Ketinggian <span class="text-xs text-gray-400 font-normal">(Opsional)</span>
                    </label>
                    <input type="text" name="ketinggian" value="<?= htmlspecialchars($spot['ketinggian']) ?>" 
                           class="form-input w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none" 
                           maxlength="50" placeholder="Contoh: 1.555 MDPL">
                </div>

                <!-- Estimasi Waktu -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Estimasi Waktu <span class="text-xs text-gray-400 font-normal">(Opsional)</span>
                    </label>
                    <input type="text" name="estimasi_waktu" value="<?= htmlspecialchars($spot['estimasi_waktu']) ?>" 
                           class="form-input w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none" 
                           maxlength="50" placeholder="Contoh: 75 menit">
                </div>

                <!-- Deskripsi -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Deskripsi <span class="text-xs text-gray-400 font-normal">(Opsional)</span>
                    </label>
                    <textarea name="deskripsi" rows="3" 
                              class="form-input w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none" 
                              placeholder="Deskripsikan spot ini..."><?= htmlspecialchars($spot['deskripsi']) ?></textarea>
                </div>

                <!-- Jenis -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Jenis <span class="text-red-500">*</span>
                    </label>
                    <select name="jenis" class="form-input w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none" required>
                        <option value="spot" <?= $spot['jenis'] == 'spot' ? 'selected' : '' ?>>📍 Spot</option>
                        <option value="flora" <?= $spot['jenis'] == 'flora' ? 'selected' : '' ?>>🌿 Flora</option>
                        <option value="fauna" <?= $spot['jenis'] == 'fauna' ? 'selected' : '' ?>>🐾 Fauna</option>
                        <option value="wilayah" <?= $spot['jenis'] == 'wilayah' ? 'selected' : '' ?>>🌄 Wilayah</option>
                    </select>
                </div>

                <!-- Urutan -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Urutan <span class="text-red-500">*</span>
                        <span class="text-xs text-gray-400 font-normal">(Angka positif)</span>
                    </label>
                    <input type="number" name="urutan" value="<?= $spot['urutan'] ?>" 
                           class="form-input w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none" 
                           required min="0">
                    <p class="text-xs text-gray-400 mt-1">Urutan posisi spot di jalur (0 = basecamp, semakin besar semakin atas)</p>
                </div>

                <!-- Ganti Foto -->
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
                        Update Spot
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

document.getElementById('spotForm').addEventListener('submit', function(e) {
    const nama = document.querySelector('input[name="nama"]').value.trim();
    const posisi = document.querySelector('input[name="posisi"]').value.trim();
    const urutan = parseInt(document.querySelector('input[name="urutan"]').value);
    
    if (!/^[a-zA-Z\s\-\.\']+$/.test(nama)) {
        e.preventDefault();
        alert('❌ Nama spot hanya boleh terdiri dari huruf, spasi, tanda pisah (-), titik (.), dan apostrof (\')!');
        return false;
    }
    
    if (posisi === '') {
        e.preventDefault();
        alert('❌ Posisi spot wajib diisi!');
        return false;
    }
    
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