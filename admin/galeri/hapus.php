<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

if (!isLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $stmt = $pdo->prepare("SELECT foto FROM galeri WHERE id = ?");
    $stmt->execute([$id]);
    $galeri = $stmt->fetch();

    if ($galeri && $galeri['foto']) {
        $foto_path = GALERI_UPLOAD_PATH . $galeri['foto'];
        if (file_exists($foto_path)) {
            unlink($foto_path);
        }
    }

    $stmt = $pdo->prepare("DELETE FROM galeri WHERE id = ?");
    $stmt->execute([$id]);
}

redirect(BASE_URL . 'admin/galeri/index.php');
?>