<?php
// Konfigurasi database
define('DB_HOST', 'localhost');
define('DB_NAME', 'gunung_bismo_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Konfigurasi URL
define('BASE_URL', 'http://localhost/gunung-bismo/');
define('BASE_PATH', dirname(__DIR__));

// Konfigurasi upload
define('UPLOAD_PATH', BASE_PATH . '/uploads/');
define('BERITA_UPLOAD_PATH', UPLOAD_PATH . 'berita/');
define('GALERI_UPLOAD_PATH', UPLOAD_PATH . 'galeri/');

// Session start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Koneksi database
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>