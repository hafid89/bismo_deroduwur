<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

if (!isLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$peraturan = getPeraturanById($id);

if (!$peraturan) {
    redirect(BASE_URL . 'admin/peraturan/index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kategori = $_POST['kategori'];
    $teks = trim($_POST['teks']);
    $denda = trim($_POST['denda']);

    if (empty($kategori) || empty($teks)) {
        $error = 'Kategori dan teks wajib diisi';
    } else {
        $stmt = $pdo->prepare("UPDATE peraturan SET kategori = ?, teks = ?, denda = ? WHERE id = ?");
        if ($stmt->execute([$kategori, $teks, $denda ?: null, $id])) {
            $success = 'Peraturan berhasil diupdate!';
            $peraturan = getPeraturanById($id);
        } else {
            $error = 'Gagal mengupdate peraturan';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Peraturan - Admin</title>
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
            <a href="index.php" class="block py-2 px-4 bg-white/10 rounded-lg hover:bg-white/20 transition duration-300">Kelola Peraturan</a>
            <a href="../logout.php" class="block py-2 px-4 hover:bg-white/20 rounded-lg transition duration-300 text-red-300">Logout</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto">
        <div class="p-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-[#2F5233]">📋 Edit Peraturan</h1>
                <a href="index.php" class="text-[#2F5233] hover:text-[#4A7A4E] transition duration-300">← Kembali</a>
            </div>

            <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST" action="" class="bg-white rounded-xl shadow-lg p-8">
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Kategori <span class="text-red-500">*</span></label>
                    <select name="kategori" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#2F5233] focus:outline-none focus:ring-2 focus:ring-[#2F5233]" required>
                        <option value="kewajiban" <?= $peraturan['kategori'] == 'kewajiban' ? 'selected' : '' ?>>Kewajiban</option>
                        <option value="larangan" <?= $peraturan['kategori'] == 'larangan' ? 'selected' : '' ?>>Larangan</option>
                        <option value="fasilitas" <?= $peraturan['kategori'] == 'fasilitas' ? 'selected' : '' ?>>Fasilitas</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Teks Peraturan <span class="text-red-500">*</span></label>
                    <input type="text" name="teks" value="<?= htmlspecialchars($peraturan['teks']) ?>" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#2F5233] focus:outline-none focus:ring-2 focus:ring-[#2F5233]" required>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Denda (Opsional)</label>
                    <input type="text" name="denda" value="<?= htmlspecialchars($peraturan['denda']) ?>" placeholder="Contoh: Rp. 1.025.000" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#2F5233] focus:outline-none focus:ring-2 focus:ring-[#2F5233]">
                    <p class="text-sm text-gray-500 mt-1">Kosongkan jika tidak ada denda</p>
                </div>
                <button type="submit" class="bg-[#2F5233] hover:bg-[#4A7A4E] text-white px-8 py-3 rounded-full font-semibold transition duration-300 transform hover:scale-105">Update</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>