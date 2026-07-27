<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

if (!isLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $stmt = $pdo->prepare("SELECT foto FROM berita WHERE id = ?");
    $stmt->execute([$id]);
    $berita = $stmt->fetch();

    if ($berita && $berita['foto']) {
        $foto_path = BERITA_UPLOAD_PATH . $berita['foto'];
        if (file_exists($foto_path)) {
            unlink($foto_path);
        }
    }

    $stmt = $pdo->prepare("DELETE FROM berita WHERE id = ?");
    $stmt->execute([$id]);
}

redirect(BASE_URL . 'admin/berita/index.php');
?>