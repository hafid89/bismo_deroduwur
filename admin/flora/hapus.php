<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

if (!isLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $flora = getFloraById($id);
    if ($flora && !empty($flora['foto']) && file_exists(UPLOAD_PATH . 'flora/' . $flora['foto'])) {
        unlink(UPLOAD_PATH . 'flora/' . $flora['foto']);
    }
    $stmt = $pdo->prepare("DELETE FROM flora WHERE id = ?");
    $stmt->execute([$id]);
}

redirect(BASE_URL . 'admin/flora/index.php');
?>