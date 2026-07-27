<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

if (!isLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $fauna = getFaunaById($id);
    
    if ($fauna) {
        // Hapus file foto jika ada
        if (!empty($fauna['foto']) && file_exists(UPLOAD_PATH . 'fauna/' . $fauna['foto'])) {
            unlink(UPLOAD_PATH . 'fauna/' . $fauna['foto']);
        }
        
        // Hapus dari database
        $stmt = $pdo->prepare("DELETE FROM fauna WHERE id = ?");
        if ($stmt->execute([$id])) {
            $_SESSION['message'] = 'Data fauna berhasil dihapus!';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Gagal menghapus data fauna';
            $_SESSION['message_type'] = 'danger';
        }
    } else {
        $_SESSION['message'] = 'Data fauna tidak ditemukan';
        $_SESSION['message_type'] = 'danger';
    }
}

redirect(BASE_URL . 'admin/fauna/index.php');
?>