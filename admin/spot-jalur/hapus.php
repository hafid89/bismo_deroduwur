<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

if (!isLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // Ambil data spot untuk hapus foto
    $stmt = $pdo->prepare("SELECT foto, nama FROM spot_jalur WHERE id = ?");
    $stmt->execute([$id]);
    $spot = $stmt->fetch();
    
    if ($spot) {
        // Hapus file foto jika ada
        if (!empty($spot['foto']) && file_exists(UPLOAD_PATH . 'spot/' . $spot['foto'])) {
            unlink(UPLOAD_PATH . 'spot/' . $spot['foto']);
        }
        
        // Hapus dari database
        $stmt = $pdo->prepare("DELETE FROM spot_jalur WHERE id = ?");
        if ($stmt->execute([$id])) {
            $_SESSION['message'] = 'Spot "' . htmlspecialchars($spot['nama']) . '" berhasil dihapus!';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Gagal menghapus spot';
            $_SESSION['message_type'] = 'danger';
        }
    } else {
        $_SESSION['message'] = 'Spot tidak ditemukan';
        $_SESSION['message_type'] = 'danger';
    }
}

redirect(BASE_URL . 'admin/spot-jalur/index.php');
?>