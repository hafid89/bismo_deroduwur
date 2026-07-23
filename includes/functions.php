<?php
require_once __DIR__ . '/config.php';

// Fungsi untuk membuat slug
function createSlug($string) {
    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9-]/', '-', $string);
    $string = preg_replace('/-+/', '-', $string);
    return trim($string, '-');
}

// Fungsi untuk upload file
function uploadFile($file, $targetDir, $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp']) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Error uploading file'];
    }

    $fileName = $file['name'];
    $fileSize = $file['size'];
    $fileTmp = $file['tmp_name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($fileExt, $allowedTypes)) {
        return ['success' => false, 'message' => 'Tipe file tidak diizinkan'];
    }

    if ($fileSize > 5 * 1024 * 1024) {
        return ['success' => false, 'message' => 'Ukuran file terlalu besar (maks 5MB)'];
    }

    $newFileName = uniqid() . '.' . $fileExt;
    $targetPath = $targetDir . $newFileName;

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    if (move_uploaded_file($fileTmp, $targetPath)) {
        return ['success' => true, 'filename' => $newFileName];
    }

    return ['success' => false, 'message' => 'Gagal upload file'];
}

// Fungsi untuk format tanggal Indonesia
function formatTanggal($date) {
    $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
               'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $timestamp = strtotime($date);
    return date('d', $timestamp) . ' ' . $months[date('n', $timestamp) - 1] . ' ' . date('Y', $timestamp);
}

// Fungsi untuk truncate text
function truncateText($text, $length = 150) {
    $text = strip_tags($text);
    if (strlen($text) <= $length) {
        return $text;
    }
    $text = substr($text, 0, $length);
    $text = substr($text, 0, strrpos($text, ' '));
    return $text . '...';
}

// Fungsi untuk cek login
function isLoggedIn() {
    return isset($_SESSION['admin_id']) && isset($_SESSION['admin_username']);
}

// Fungsi untuk redirect
function redirect($url) {
    header('Location: ' . $url);
    exit();
}

// Fungsi untuk get berita terbaru
function getBeritaTerbaru($limit = 3) {
    global $pdo;
    $limit = intval($limit);
    $stmt = $pdo->query("SELECT * FROM berita ORDER BY tanggal DESC LIMIT $limit");
    return $stmt->fetchAll();
}

// Fungsi untuk get galeri
function getGaleri($limit = null) {
    global $pdo;
    $sql = "SELECT * FROM galeri ORDER BY created_at DESC";
    if ($limit) {
        $limit = intval($limit);
        $sql .= " LIMIT $limit";
    }
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

// Fungsi untuk get galeri by tag
function getGaleriByTag($tag, $limit = null) {
    global $pdo;
    $sql = "SELECT * FROM galeri WHERE tag = ? ORDER BY created_at DESC";
    if ($limit) {
        $limit = intval($limit);
        $sql .= " LIMIT $limit";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$tag]);
    return $stmt->fetchAll();
}

// Fungsi untuk get galeri by kategori
function getGaleriByKategori($kategori, $limit = null) {
    global $pdo;
    $sql = "SELECT * FROM galeri WHERE kategori = ? ORDER BY created_at DESC";
    if ($limit) {
        $limit = intval($limit);
        $sql .= " LIMIT $limit";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$kategori]);
    return $stmt->fetchAll();
}

// Fungsi untuk pagination
function getPaginatedData($table, $page = 1, $limit = 10, $orderBy = 'created_at DESC') {
    global $pdo;
    $page = intval($page);
    $limit = intval($limit);
    $offset = ($page - 1) * $limit;
    
    $sql = "SELECT * FROM $table ORDER BY $orderBy LIMIT $limit OFFSET $offset";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

// Fungsi untuk get total data
function getTotalData($table) {
    global $pdo;
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM $table");
    $result = $stmt->fetch();
    return $result ? (int)$result['total'] : 0;
}

// Fungsi untuk get berita by id
function getBeritaById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM berita WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Fungsi untuk get related berita
function getRelatedBerita($id, $limit = 3) {
    global $pdo;
    $limit = intval($limit);
    $stmt = $pdo->prepare("SELECT * FROM berita WHERE id != ? ORDER BY tanggal DESC LIMIT $limit");
    $stmt->execute([$id]);
    return $stmt->fetchAll();
}

// Fungsi untuk sanitasi input
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}
?>