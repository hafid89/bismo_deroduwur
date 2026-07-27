<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Cek jika sudah login, redirect ke dashboard
if (isLoggedIn()) {
    redirect(BASE_URL . 'admin/dashboard.php');
}

$error = '';
$success = '';
$valid_token = false;
$admin_id = 0;

// Get token dari URL
$token = isset($_GET['token']) ? trim($_GET['token']) : '';

if (!empty($token)) {
    // Validasi token
    $stmt = $pdo->prepare("
        SELECT rp.*, a.id as admin_id, a.email, a.username 
        FROM reset_password rp 
        JOIN admin a ON rp.admin_id = a.id 
        WHERE rp.token = ? AND rp.used = FALSE AND rp.expires_at > NOW()
    ");
    $stmt->execute([$token]);
    $reset_data = $stmt->fetch();

    if ($reset_data) {
        $valid_token = true;
        $admin_id = $reset_data['admin_id'];
    } else {
        // Cek di tabel admin sebagai backup
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE reset_token = ? AND reset_token_expires > NOW()");
        $stmt->execute([$token]);
        $admin = $stmt->fetch();
        
        if ($admin) {
            $valid_token = true;
            $admin_id = $admin['id'];
        } else {
            $error = 'Token tidak valid atau sudah kadaluarsa. Silakan minta link reset baru.';
        }
    }
}

// Proses reset password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $valid_token) {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($password) || empty($confirm_password)) {
        $error = 'Password dan konfirmasi password wajib diisi';
    } elseif ($password !== $confirm_password) {
        $error = 'Password dan konfirmasi password tidak sama';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter';
    } else {
        try {
            $pdo->beginTransaction();

            // Hash password baru
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Update password admin
            $stmt = $pdo->prepare("UPDATE admin SET password = ?, reset_token = NULL, reset_token_expires = NULL WHERE id = ?");
            $stmt->execute([$hashed_password, $admin_id]);

            // Tandai token sebagai used
            $stmt = $pdo->prepare("UPDATE reset_password SET used = TRUE WHERE token = ?");
            $stmt->execute([$token]);

            // Hapus token lain yang sudah kadaluarsa untuk admin ini
            $stmt = $pdo->prepare("DELETE FROM reset_password WHERE admin_id = ? AND used = TRUE");
            $stmt->execute([$admin_id]);

            $pdo->commit();
            $success = 'Password berhasil direset! Silakan login dengan password baru Anda.';
            $valid_token = false; // Sembunyikan form
            
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = 'Gagal mereset password. Silakan coba lagi.';
            error_log("Reset password error: " . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }
        body { background: linear-gradient(135deg, #FAF7F2 0%, #E8E3D8 100%); }
        .reset-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .reset-card:hover {
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
<div class="min-h-screen flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl p-8 reset-card">
        <!-- Header -->
        <div class="text-center mb-6">
            <div class="inline-block bg-[#2F5233] rounded-full p-3 mb-3">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-[#2F5233]">Reset Password</h2>
            <p class="text-[#5C5C50] text-sm mt-1">
                <?= $valid_token ? 'Buat password baru untuk akun Anda' : 'Link reset tidak valid' ?>
            </p>
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
                    <div class="mt-4">
                        <a href="login.php" class="inline-block bg-[#2F5233] hover:bg-[#4A7A4E] text-white px-6 py-2 rounded-lg text-sm font-semibold transition duration-300">
                            Login Sekarang →
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($valid_token && !$success): ?>
        <form method="POST" action="" class="space-y-4">
            <!-- Password Baru -->
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                    Password Baru
                </label>
                <div class="input-icon">
                    <span class="icon"><i class="bi bi-lock"></i></span>
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
                    Konfirmasi Password Baru
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

            <!-- Informasi Token -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-3 rounded text-xs text-blue-700">
                <p><i class="bi bi-shield-lock"></i> Token valid. Silakan buat password baru.</p>
            </div>

            <button type="submit" 
                    class="w-full bg-[#2F5233] hover:bg-[#4A7A4E] text-white font-bold py-3 px-4 rounded-lg transition duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#2F5233] focus:ring-offset-2">
                Reset Password
            </button>
        </form>
        <?php endif; ?>

        <?php if (!$valid_token && !$success): ?>
        <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 px-4 py-3 rounded-lg mb-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="font-semibold">Token Tidak Valid</p>
                    <p class="text-sm">Link reset password Anda mungkin sudah kadaluarsa atau sudah digunakan.</p>
                    <div class="mt-3">
                        <a href="lupa-password.php" class="inline-block bg-[#2F5233] hover:bg-[#4A7A4E] text-white px-4 py-2 rounded-lg text-sm font-semibold transition duration-300">
                            Minta Link Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Links -->
        <div class="mt-6 text-center space-y-2">
            <p class="text-[#5C5C50] text-sm">
                <a href="login.php" class="text-[#2F5233] font-semibold hover:text-[#4A7A4E] hover:underline transition duration-300">
                    ← Kembali ke Login
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
        matchText.textContent = '<i class="bi bi-check-circle"></i> Password cocok';
        matchText.style.color = '#3F7D4F';
    } else {
        matchText.textContent = '❌ Password tidak cocok';
        matchText.style.color = '#B3452F';
    }
}
</script>
</body>
</html>