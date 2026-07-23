<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Cek apakah admin sudah login
if (!isLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}
?>