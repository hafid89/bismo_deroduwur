<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

if (!isLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM berita WHERE id = ?");
$stmt->execute([$id]);
$berita = $stmt->fetch();

if (!$berita) {
    redirect(BASE_URL . 'admin/berita/index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul']);
    $isi = trim($_POST['isi']);
    $tanggal = $_POST['tanggal'];
    $foto = $berita['foto'];

    if (empty($judul) || empty($isi) || empty($tanggal)) {
        $error = 'Semua field wajib diisi';
    } else {
        $slug = createSlug($judul);

        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $upload = uploadFile($_FILES['foto'], BERITA_UPLOAD_PATH);
            if ($upload['success']) {
                if ($foto && file_exists(BERITA_UPLOAD_PATH . $foto)) {
                    unlink(BERITA_UPLOAD_PATH . $foto);
                }
                $foto = $upload['filename'];
            } else {
                $error = $upload['message'];
            }
        }

        if (empty($error)) {
            $stmt = $pdo->prepare("UPDATE berita SET judul = ?, slug = ?, isi = ?, foto = ?, tanggal = ? WHERE id = ?");
            if ($stmt->execute([$judul, $slug, $isi, $foto, $tanggal, $id])) {
                $success = 'Berita berhasil diupdate!';
                $stmt = $pdo->prepare("SELECT * FROM berita WHERE id = ?");
                $stmt->execute([$id]);
                $berita = $stmt->fetch();
            } else {
                $error = 'Gagal mengupdate berita';
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
    <title>Edit Berita - Admin</title>
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
            <a href="index.php" class="block py-2 px-4 bg-white/10 rounded-lg hover:bg-white/20 transition duration-300">
                Kelola Berita
            </a>
            <a href="../galeri/index.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300">
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
                <h1 class="text-3xl font-bold text-[#2F5233]">Edit Berita</h1>
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
                    <label class="block text-gray-700 font-semibold mb-2">Judul Berita</label>
                    <input type="text" name="judul" value="<?= htmlspecialchars($berita['judul']) ?>" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#2F5233] focus:outline-none focus:ring-2 focus:ring-[#2F5233] transition duration-300" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Tanggal</label>
                    <input type="date" name="tanggal" value="<?= $berita['tanggal'] ?>" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#2F5233] focus:outline-none focus:ring-2 focus:ring-[#2F5233] transition duration-300" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Foto</label>
                    <?php if ($berita['foto']): ?>
                    <div class="mb-2">
                        <img src="<?= BASE_URL ?>uploads/berita/<?= htmlspecialchars($berita['foto']) ?>" class="w-32 h-24 object-cover rounded">
                    </div>
                    <?php endif; ?>
                    <input type="file" name="foto" accept="image/*" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-[#2F5233] focus:outline-none transition duration-300">
                    <p class="text-sm text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah foto</p>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Isi Berita</label>
                    <textarea name="isi" rows="10" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#2F5233] focus:outline-none focus:ring-2 focus:ring-[#2F5233] transition duration-300" required><?= htmlspecialchars($berita['isi']) ?></textarea>
                </div>

                <button type="submit" class="bg-[#2F5233] hover:bg-[#4A7A4E] text-white px-8 py-3 rounded-full font-semibold transition duration-300 transform hover:scale-105">
                    Update Berita
                </button>
            </form>
        </div>
    </div>
</div>
</body>
</html>