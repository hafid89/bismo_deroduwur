<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

if (!isLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$flora = getFloraById($id);

if (!$flora) {
    redirect(BASE_URL . 'admin/flora/index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $nama_ilmiah = trim($_POST['nama_ilmiah']);
    $deskripsi = trim($_POST['deskripsi']);
    $lokasi = trim($_POST['lokasi']);
    $foto = $flora['foto'];

    if (empty($nama)) {
        $error = 'Nama flora wajib diisi';
    } else {
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $upload = uploadFile($_FILES['foto'], UPLOAD_PATH . 'flora/');
            if ($upload['success']) {
                if ($foto && file_exists(UPLOAD_PATH . 'flora/' . $foto)) {
                    unlink(UPLOAD_PATH . 'flora/' . $foto);
                }
                $foto = $upload['filename'];
            } else {
                $error = $upload['message'];
            }
        }

        if (empty($error)) {
            $stmt = $pdo->prepare("UPDATE flora SET nama = ?, nama_ilmiah = ?, deskripsi = ?, foto = ?, lokasi = ? WHERE id = ?");
            if ($stmt->execute([$nama, $nama_ilmiah, $deskripsi, $foto, $lokasi, $id])) {
                $success = 'Data flora berhasil diupdate!';
                $flora = getFloraById($id);
            } else {
                $error = 'Gagal mengupdate data';
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
    <title>Edit Flora - Admin</title>
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
            <a href="../dashboard.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300">Dashboard</a>
            <a href="index.php" class="block py-2 px-4 bg-white/10 rounded-lg hover:bg-white/20 transition duration-300">Kelola Flora</a>
            <a href="../fauna/index.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300">Kelola Fauna</a>
            <a href="../peraturan/index.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300">Kelola Peraturan</a>
            <a href="../logout.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300 text-red-300">Logout</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto">
        <div class="p-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-[#2F5233]">🌿 Edit Flora</h1>
                <a href="index.php" class="text-[#2F5233] hover:text-[#4A7A4E] transition duration-300">← Kembali</a>
            </div>

            <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST" action="" enctype="multipart/form-data" class="bg-white rounded-xl shadow-lg p-8">
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Nama Flora <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="<?= htmlspecialchars($flora['nama']) ?>" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#2F5233] focus:outline-none focus:ring-2 focus:ring-[#2F5233]" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Nama Ilmiah</label>
                    <input type="text" name="nama_ilmiah" value="<?= htmlspecialchars($flora['nama_ilmiah']) ?>" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#2F5233] focus:outline-none focus:ring-2 focus:ring-[#2F5233]">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Deskripsi</label>
                    <textarea name="deskripsi" rows="4" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#2F5233] focus:outline-none focus:ring-2 focus:ring-[#2F5233]"><?= htmlspecialchars($flora['deskripsi']) ?></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Lokasi</label>
                    <input type="text" name="lokasi" value="<?= htmlspecialchars($flora['lokasi']) ?>" placeholder="Contoh: Dekat Pos I" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#2F5233] focus:outline-none focus:ring-2 focus:ring-[#2F5233]">
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Foto</label>
                    <?php if (!empty($flora['foto']) && file_exists(UPLOAD_PATH . 'flora/' . $flora['foto'])): ?>
                    <div class="mb-2">
                        <img src="<?= BASE_URL ?>uploads/flora/<?= htmlspecialchars($flora['foto']) ?>" class="w-32 h-24 object-cover rounded">
                    </div>
                    <?php endif; ?>
                    <input type="file" name="foto" accept="image/*" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-[#2F5233] focus:outline-none">
                    <p class="text-sm text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah foto</p>
                </div>
                <button type="submit" class="bg-[#2F5233] hover:bg-[#4A7A4E] text-white px-8 py-3 rounded-full font-semibold transition duration-300 transform hover:scale-105">Update</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>