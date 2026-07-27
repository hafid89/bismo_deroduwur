<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Cek jika sudah login, redirect ke dashboard
if (isLoggedIn()) {
    redirect(BASE_URL . 'admin/dashboard.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if (empty($email)) {
        $error = 'Email wajib diisi';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid';
    } else {
        // Cek apakah email terdaftar
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        if (!$admin) {
            $error = 'Email tidak terdaftar. Silakan cek kembali.';
        } else {
            // Hapus token lama yang expired
            $stmt = $pdo->prepare("DELETE FROM reset_password WHERE admin_id = ? AND expires_at < NOW()");
            $stmt->execute([$admin['id']]);

            // Generate token unik
            $token = bin2hex(random_bytes(32));
            $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Simpan token ke database
            $stmt = $pdo->prepare("INSERT INTO reset_password (admin_id, token, expires_at) VALUES (?, ?, ?)");
            if ($stmt->execute([$admin['id'], $token, $expires_at])) {
                // Simpan juga di tabel admin untuk backup
                $stmt = $pdo->prepare("UPDATE admin SET reset_token = ?, reset_token_expires = ? WHERE id = ?");
                $stmt->execute([$token, $expires_at, $admin['id']]);

                $success = 'Link reset password telah dikirim ke email Anda.';
                
                // Dalam produksi, kirim email berisi link reset
                // Untuk development, tampilkan link di halaman
                $reset_link = BASE_URL . 'admin/reset-password.php?token=' . $token;
                
                // Log untuk development
                error_log("Reset link: " . $reset_link);
            } else {
                $error = 'Gagal memproses permintaan. Silakan coba lagi.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }
        body { background: linear-gradient(135deg, #FAF7F2 0%, #E8E3D8 100%); }
        .forgot-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .forgot-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .input-icon {
            position: relative;
        }
        .input-icon input {
            padding-left: 2.5rem;
        }
        .input-icon .icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #A9784B;
        }
        .animated-bg {
            background: linear-gradient(-45deg, #2F5233, #4A7A4E, #E0BE45, #A9784B);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    </style>
</head>
<body>
<div class="min-h-screen flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl p-8 forgot-card">
        <!-- Header -->
        <div class="text-center mb-6">
            <div class="inline-block bg-[#2F5233] rounded-full p-3 mb-3">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-[#2F5233]">Lupa Password</h2>
            <p class="text-[#5C5C50] text-sm mt-1">Kami akan kirim link reset ke email Anda</p>
        </div>

        <?php if ($error): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-start">
            <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
        <?php endif; ?>

        <?php if ($success): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg mb-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p><?= htmlspecialchars($success) ?></p>
                    <?php if (isset($reset_link)): ?>
                    <div class="mt-3 p-3 bg-white rounded-lg border border-gray-200">
                        <p class="text-xs text-gray-500 mb-1">Link reset (untuk development):</p>
                        <a href="<?= htmlspecialchars($reset_link) ?>" target="_blank" 
                           class="text-sm text-[#2F5233] font-semibold hover:underline break-all">
                            <?= htmlspecialchars($reset_link) ?>
                        </a>
                    </div>
                    <?php endif; ?>
                    <div class="mt-4">
                        <a href="login.php" class="inline-block bg-[#2F5233] hover:bg-[#4A7A4E] text-white px-6 py-2 rounded-lg text-sm font-semibold transition duration-300">
                            Kembali ke Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!$success): ?>
        <form method="POST" action="" class="space-y-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    Email Terdaftar
                </label>
                <div class="input-icon">
                    <span class="icon"><i class="bi bi-envelope"></i></span>
                    <input type="email" id="email" name="email" 
                           value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#2F5233] focus:outline-none focus:ring-2 focus:ring-[#2F5233] transition duration-300"
                           placeholder="Masukkan email Anda"
                           required>
                </div>
                <p class="text-xs text-gray-500 mt-1">
                    Masukkan email yang terdaftar untuk menerima link reset password
                </p>
            </div>

            <button type="submit" 
                    class="w-full bg-[#2F5233] hover:bg-[#4A7A4E] text-white font-bold py-3 px-4 rounded-lg transition duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#2F5233] focus:ring-offset-2">
                Kirim Link Reset
            </button>
        </form>
        <?php endif; ?>

        <!-- Links -->
        <div class="mt-6 text-center space-y-2">
            <p class="text-[#5C5C50] text-sm">
                <a href="login.php" class="text-[#2F5233] font-semibold hover:text-[#4A7A4E] hover:underline transition duration-300">
                    ← Kembali ke Login
                </a>
            </p>
            <p class="text-[#5C5C50] text-sm">
                Belum punya akun? 
                <a href="register.php" class="text-[#2F5233] font-semibold hover:text-[#4A7A4E] hover:underline transition duration-300">
                    Daftar di sini
                </a>
            </p>
        </div>
    </div>
</div>
</body>
</html>