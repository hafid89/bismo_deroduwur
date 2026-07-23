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
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $full_name = trim($_POST['full_name']);

    // Validasi
    if (empty($username) || empty($email) || empty($password) || empty($full_name)) {
        $error = 'Semua field wajib diisi';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email tidak valid';
    } elseif ($password !== $confirm_password) {
        $error = 'Password dan konfirmasi password tidak sama';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter';
    } else {
        // Cek username atau email sudah terdaftar
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM admin WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetchColumn() > 0) {
            $error = 'Username atau email sudah terdaftar';
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert admin
            $stmt = $pdo->prepare("INSERT INTO admin (username, email, password, full_name) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$username, $email, $hashed_password, $full_name])) {
                $success = 'Registrasi berhasil! Silakan login.';
                // Kosongkan form
                $_POST = array();
            } else {
                $error = 'Gagal registrasi, silakan coba lagi';
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
    <title>Register Admin - Gunung Bismo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }
        body { background: #FAF7F2; }
        .register-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .register-card:hover {
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
        .password-strength {
            height: 4px;
            border-radius: 2px;
            margin-top: 4px;
            transition: all 0.3s ease;
        }
    </style>
</head>
<body>
<div class="min-h-screen flex items-center justify-center py-12 px-4 bg-gradient-to-br from-[#FAF7F2] to-[#E8E3D8]">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl p-8 register-card">
        <!-- Logo -->
        <div class="text-center mb-6">
            <div class="inline-block bg-[#2F5233] rounded-full p-3 mb-3">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-[#2F5233]">Daftar Admin</h2>
            <p class="text-[#5C5C50] text-sm mt-1">Buat akun untuk mengelola website</p>
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
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-start">
            <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <div>
                <span><?= htmlspecialchars($success) ?></span>
                <div class="mt-2">
                    <a href="login.php" class="inline-block bg-[#2F5233] hover:bg-[#4A7A4E] text-white px-4 py-2 rounded-lg text-sm font-semibold transition duration-300">
                        Login Sekarang →
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!$success): ?>
        <form method="POST" action="" class="space-y-4">
            <!-- Nama Lengkap -->
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="full_name">
                    Nama Lengkap
                </label>
                <div class="input-icon">
                    <span class="icon">👤</span>
                    <input type="text" id="full_name" name="full_name" 
                           value="<?= isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : '' ?>"
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#2F5233] focus:outline-none focus:ring-2 focus:ring-[#2F5233] transition duration-300"
                           placeholder="Masukkan nama lengkap"
                           required>
                </div>
            </div>

            <!-- Username -->
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                    Username
                </label>
                <div class="input-icon">
                    <span class="icon">🔑</span>
                    <input type="text" id="username" name="username" 
                           value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>"
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#2F5233] focus:outline-none focus:ring-2 focus:ring-[#2F5233] transition duration-300"
                           placeholder="Masukkan username"
                           required>
                </div>
            </div>

            <!-- Email -->
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    Email
                </label>
                <div class="input-icon">
                    <span class="icon">✉️</span>
                    <input type="email" id="email" name="email" 
                           value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#2F5233] focus:outline-none focus:ring-2 focus:ring-[#2F5233] transition duration-300"
                           placeholder="Masukkan email"
                           required>
                </div>
            </div>

            <!-- Password -->
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                    Password
                </label>
                <div class="input-icon">
                    <span class="icon">🔒</span>
                    <input type="password" id="password" name="password" 
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#2F5233] focus:outline-none focus:ring-2 focus:ring-[#2F5233] transition duration-300"
                           placeholder="Minimal 6 karakter"
                           required minlength="6"
                           onkeyup="checkPasswordStrength(this.value)">
                </div>
                <div id="passwordStrength" class="password-strength bg-gray-200"></div>
                <p id="passwordText" class="text-xs text-gray-500 mt-1">Password minimal 6 karakter</p>
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="confirm_password">
                    Konfirmasi Password
                </label>
                <div class="input-icon">
                    <span class="icon">✓</span>
                    <input type="password" id="confirm_password" name="confirm_password" 
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#2F5233] focus:outline-none focus:ring-2 focus:ring-[#2F5233] transition duration-300"
                           placeholder="Masukkan ulang password"
                           required minlength="6"
                           onkeyup="matchPassword(this.value)">
                </div>
                <p id="matchText" class="text-xs mt-1"></p>
            </div>

            <!-- Submit -->
            <button type="submit" 
                    class="w-full bg-[#2F5233] hover:bg-[#4A7A4E] text-white font-bold py-3 px-4 rounded-lg transition duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#2F5233] focus:ring-offset-2">
                Daftar Sekarang
            </button>
        </form>
        <?php endif; ?>

        <!-- Links -->
        <div class="mt-6 text-center space-y-2">
            <p class="text-[#5C5C50] text-sm">
                Sudah punya akun? 
                <a href="login.php" class="text-[#2F5233] font-semibold hover:text-[#4A7A4E] hover:underline transition duration-300">
                    Login di sini
                </a>
            </p>
            <p class="text-[#5C5C50] text-sm">
                <a href="<?= BASE_URL ?>" class="text-[#2F5233] hover:text-[#4A7A4E] transition duration-300">
                    ← Kembali ke Website
                </a>
            </p>
        </div>
    </div>
</div>

<script>
// Check password strength
function checkPasswordStrength(password) {
    const strengthBar = document.getElementById('passwordStrength');
    const strengthText = document.getElementById('passwordText');
    let strength = 0;

    if (password.length >= 6) strength++;
    if (password.length >= 10) strength++;
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
    if (/\d/.test(password)) strength++;
    if (/[^a-zA-Z0-9]/.test(password)) strength++;

    const colors = ['#ff4444', '#ff8800', '#ffcc00', '#44cc44', '#00aa00'];
    const labels = ['Sangat Lemah', 'Lemah', 'Sedang', 'Kuat', 'Sangat Kuat'];

    if (password.length === 0) {
        strengthBar.style.width = '0%';
        strengthBar.style.backgroundColor = '#e5e7eb';
        strengthText.textContent = 'Password minimal 6 karakter';
        strengthText.style.color = '#6b7280';
    } else {
        const index = Math.min(strength, 4);
        strengthBar.style.width = ((index + 1) * 20) + '%';
        strengthBar.style.backgroundColor = colors[index];
        strengthText.textContent = labels[index];
        strengthText.style.color = colors[index];
    }
}

// Match password
function matchPassword(value) {
    const password = document.getElementById('password').value;
    const matchText = document.getElementById('matchText');

    if (value.length === 0) {
        matchText.textContent = '';
        matchText.style.color = '#6b7280';
    } else if (value === password) {
        matchText.textContent = '✅ Password cocok';
        matchText.style.color = '#3F7D4F';
    } else {
        matchText.textContent = '❌ Password tidak cocok';
        matchText.style.color = '#B3452F';
    }
}
</script>
</body>
</html>