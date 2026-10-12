<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

if (!isLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // Hapus file foto tambahan (barisnya ikut terhapus lewat ON DELETE CASCADE)
    try {
        $stmt = $pdo->prepare("SELECT foto FROM galeri_foto WHERE galeri_id = ?");
        $stmt->execute([$id]);
        foreach ($stmt->fetchAll() as $row) {
            $path = GALERI_UPLOAD_PATH . basename($row['foto']);
            if (is_file($path)) {
                unlink($path);
            }
        }
    } catch (PDOException $e) {
        // Tabel galeri_foto belum dibuat, lewati
    }

    // Hapus foto sampul
    $stmt = $pdo->prepare("SELECT foto FROM galeri WHERE id = ?");
    $stmt->execute([$id]);
    $galeri = $stmt->fetch();

    if ($galeri && $galeri['foto']) {
        $foto_path = GALERI_UPLOAD_PATH . basename($galeri['foto']);
        if (file_exists($foto_path)) {
            unlink($foto_path);
        }
    }

    $stmt = $pdo->prepare("DELETE FROM galeri WHERE id = ?");
    $stmt->execute([$id]);

    $_SESSION['message'] = 'Galeri berhasil dihapus';
    $_SESSION['message_type'] = 'success';
}

redirect(BASE_URL . 'admin/galeri/index.php');
?>