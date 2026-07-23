<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

if (!isLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul']);
    $kategori = $_POST['kategori'];
    $tag = trim($_POST['tag']);
    $deskripsi = trim($_POST['deskripsi']);

    if (empty($judul) || empty($kategori)) {
        $error = 'Judul dan kategori wajib diisi';
    } elseif (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Foto wajib diupload';
    } else {
        $upload = uploadFile($_FILES['foto'], GALERI_UPLOAD_PATH);
        if ($upload['success']) {
            $stmt = $pdo->prepare("INSERT INTO galeri (foto, judul, kategori, tag, deskripsi) VALUES (?, ?, ?, ?, ?)");
            if ($stmt->execute([$upload['filename'], $judul, $kategori, $tag, $deskripsi])) {
                $success = 'Foto berhasil ditambahkan!';
            } else {
                $error = 'Gagal menambahkan foto';
            }
        } else {
            $error = $upload['message'];
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
    </style>
</head>
<body class="bg-[#FAF7F2]">
<div class="flex h-screen">
    <!-- Sidebar -->
    <div class="w-64 bg-[#2F5233] text-white p-6">
        <h2 class="text-2xl font-bold mb-8">Panel Admin</h2>
        <nav class="space-y-2">
            <a href="../dashboard.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300">
                Dashboard
            </a>
            <a href="../berita/index.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300">
                Kelola Berita
            </a>
            <a href="index.php" class="block py-2 px-4 bg-white/10 rounded-lg hover:bg-white/20 transition duration-300">
                Kelola Galeri
            </a>
            <a href="../logout.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300 text-red-300">
                Logout
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto">
        <div class="p-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-[#2F5233]">Tambah Galeri</h1>
                <a href="index.php" class="text-[#2F5233] hover:text-[#4A7A4E] transition duration-300">
                    ← Kembali
                </a>
            </div>

            <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
                <?= htmlspecialchars($success) ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="" enctype="multipart/form-data" class="bg-white rounded-xl shadow-lg p-8">
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Judul Foto</label>
                    <input type="text" name="judul" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#2F5233] focus:outline-none focus:ring-2 focus:ring-[#2F5233] transition duration-300" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Kategori</label>
                    <select name="kategori" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#2F5233] focus:outline-none focus:ring-2 focus:ring-[#2F5233] transition duration-300" required>
                        <option value="jalur">Jalur</option>
                        <option value="ekosistem">Ekosistem</option>
                        <option value="kegiatan">Kegiatan</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Tag (Opsional)</label>
                    <input type="text" name="tag" placeholder="Contoh: puncak, spot, flora, fauna" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#2F5233] focus:outline-none focus:ring-2 focus:ring-[#2F5233] transition duration-300">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Deskripsi (Opsional)</label>
                    <textarea name="deskripsi" rows="3" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#2F5233] focus:outline-none focus:ring-2 focus:ring-[#2F5233] transition duration-300"></textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Foto</label>
                    <input type="file" name="foto" accept="image/*" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-[#2F5233] focus:outline-none transition duration-300" required>
                    <p class="text-sm text-gray-500 mt-1">Maksimal 5MB, format: JPG, PNG, GIF, WEBP</p>
                </div>

                <button type="submit" class="bg-[#2F5233] hover:bg-[#4A7A4E] text-white px-8 py-3 rounded-full font-semibold transition duration-300 transform hover:scale-105">
                    Simpan Foto
                </button>
            </form>
        </div>
    </div>
</div>
</body>
</html>