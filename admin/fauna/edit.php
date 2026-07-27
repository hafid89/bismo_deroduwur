<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

if (!isLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$fauna = getFaunaById($id);

if (!$fauna) {
    $_SESSION['message'] = 'Data fauna tidak ditemukan';
    $_SESSION['message_type'] = 'danger';
    redirect(BASE_URL . 'admin/fauna/index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $nama_ilmiah = trim($_POST['nama_ilmiah']);
    $deskripsi = trim($_POST['deskripsi']);
    $lokasi = trim($_POST['lokasi']);
    $foto = $fauna['foto'];

    if (empty($nama)) {
        $error = 'Nama fauna wajib diisi';
    } else {
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $upload = uploadFile($_FILES['foto'], UPLOAD_PATH . 'fauna/');
            if ($upload['success']) {
                // Hapus foto lama
                if ($foto && file_exists(UPLOAD_PATH . 'fauna/' . $foto)) {
                    unlink(UPLOAD_PATH . 'fauna/' . $foto);
                }
                $foto = $upload['filename'];
            } else {
                $error = $upload['message'];
            }
        }

        if (empty($error)) {
            try {
                $stmt = $pdo->prepare("UPDATE fauna SET nama = ?, nama_ilmiah = ?, deskripsi = ?, foto = ?, lokasi = ? WHERE id = ?");
                if ($stmt->execute([$nama, $nama_ilmiah, $deskripsi, $foto, $lokasi, $id])) {
                    $_SESSION['message'] = 'Data fauna berhasil diupdate!';
                    $_SESSION['message_type'] = 'success';
                    redirect(BASE_URL . 'admin/fauna/index.php');
                } else {
                    $error = 'Gagal mengupdate data';
                }
            } catch (PDOException $e) {
                $error = 'Error: ' . $e->getMessage();
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
    <title>Edit Fauna - Admin</title>
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
                    <h1 class="text-3xl font-bold text-[#2F5233]">🐾 Edit Fauna</h1>
                    <p class="text-[#5C5C50] text-sm mt-1">Edit data fauna</p>
                </div>
                <a href="index.php" class="text-[#2F5233] hover:text-[#4A7A4E] transition duration-300 flex items-center gap-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
            </div>

            <?php if ($error): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-start">
                <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
            <?php endif; ?>

            <form method="POST" action="" enctype="multipart/form-data" class="bg-white rounded-xl shadow-lg p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">
                            Nama Fauna <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" value="<?= htmlspecialchars($fauna['nama']) ?>" 
                               class="form-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">
                            Nama Ilmiah
                        </label>
                        <input type="text" name="nama_ilmiah" value="<?= htmlspecialchars($fauna['nama_ilmiah']) ?>" 
                               class="form-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Deskripsi
                    </label>
                    <textarea name="deskripsi" rows="4" 
                              class="form-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none"><?= htmlspecialchars($fauna['deskripsi']) ?></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Lokasi Ditemukan
                    </label>
                    <input type="text" name="lokasi" value="<?= htmlspecialchars($fauna['lokasi']) ?>" 
                           class="form-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Foto Fauna
                    </label>
                    <?php if (!empty($fauna['foto']) && file_exists(UPLOAD_PATH . 'fauna/' . $fauna['foto'])): ?>
                    <div class="mb-3">
                        <p class="text-sm text-[#5C5C50] mb-2">Foto saat ini:</p>
                        <img src="<?= BASE_URL ?>uploads/fauna/<?= htmlspecialchars($fauna['foto']) ?>" 
                             class="preview-image w-40 h-32 object-cover rounded-lg shadow-md">
                    </div>
                    <?php endif; ?>
                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:bg-[#FAF7F2] transition duration-300">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span> atau drag & drop</p>
                                <p class="text-xs text-gray-500">JPG, PNG, GIF (Maks 5MB)</p>
                            </div>
                            <input type="file" name="foto" accept="image/*" class="hidden" onchange="previewImage(this)">
                        </label>
                    </div>
                    <div id="imagePreview" class="mt-3 hidden">
                        <p class="text-sm text-[#2F5233] font-medium mb-2">Preview:</p>
                        <img id="previewImg" src="#" alt="Preview" class="preview-image w-40 h-32 object-cover rounded-lg shadow-md">
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Kosongkan jika tidak ingin mengubah foto</p>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="bg-[#2F5233] hover:bg-[#4A7A4E] text-white px-8 py-3 rounded-full font-semibold transition duration-300 transform hover:scale-105 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Fauna
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
</script>
</body>
</html>