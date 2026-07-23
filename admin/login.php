<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

if (isLoggedIn()) {
    redirect(BASE_URL . 'admin/dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = 'Username dan password wajib diisi';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_full_name'] = $admin['full_name'];
            redirect(BASE_URL . 'admin/dashboard.php');
        } else {
            $error = 'Username atau password salah';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Gunung Bismo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }
        body { background: #FAF7F2; }
    </style>
</head>
<body>
<div class="min-h-screen flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-[#2F5233]">Panel Admin</h2>
            <p class="text-[#5C5C50] mt-2">Gunung Bismo via Deroduwur</p>
        </div>

        <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                    Username
                </label>
                <input type="text" id="username" name="username" 
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#2F5233] focus:outline-none focus:ring-2 focus:ring-[#2F5233] transition duration-300"
                       required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                    Password
                </label>
                <input type="password" id="password" name="password" 
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#2F5233] focus:outline-none focus:ring-2 focus:ring-[#2F5233] transition duration-300"
                       required>
            </div>
            <button type="submit" 
                    class="w-full bg-[#2F5233] hover:bg-[#4A7A4E] text-white font-bold py-3 px-4 rounded-lg transition duration-300 transform hover:scale-105">
                Login
            </button>
        </form>
        <!-- Tambahkan di bagian bawah form login, sebelum link kembali ke website -->
<div class="text-center mt-4">
    <p class="text-[#5C5C50] text-sm">
        Belum punya akun? 
        <a href="register.php" class="text-[#2F5233] font-semibold hover:underline transition duration-300">
            Daftar di sini
        </a>
    </p>
</div>
        <div class="text-center mt-4">
            <a href="<?= BASE_URL ?>" class="text-[#2F5233] hover:text-[#4A7A4E] transition duration-300">
                ← Kembali ke Website
            </a>
        </div>
    </div>
</div>
</body>
</html>